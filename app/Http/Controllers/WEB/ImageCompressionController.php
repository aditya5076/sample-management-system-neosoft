<?php

namespace App\Http\Controllers\WEB;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use GuzzleHttp\Client;
use URL;
use DB;
use Carbon\Carbon;
use App\Models\UserRoleMaster;
use App\Models\RequestsTable;
use App\Models\UploadedImages;
use App\Models\Inward;
use App\Models\Outward;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\Rule;
use App\Helpers\Helper;
use DataTables;
use CompressImage;
use File;
use Illuminate\Support\Facades\Mail;
use App\Mail\RemoteImportFailure;
use Illuminate\Support\Facades\Storage;
use Aws\S3\S3Client;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Filesystem;
use App\Models\ProductMaster;

class ImageCompressionController extends Controller
{
    /**
     * This controller is used for Image compression module.
     * @access Rights : Admin
     * @param Array : Validation Declaration.
     * @param Constant : Declaration.
     * @param Parameter : Declaration.
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */

    const max_allowed_image_size = 400;
    const AInocular_API_FAILURE = 44;
    protected $date_bracket = null;
    protected $status = null;
    protected $code = null;
    protected $role_master = [];
    protected $images_array = [];
    protected $check_images_size = [];
    protected $static_return = [
        'success_code' => 'success',
        'error_code' => 'error',
        'validation_code' => 'validation',
        'Image' => [
            'success' => 'The images were uploaded and compressed successfully.',
            'error' => 'Something went wrong,Try Again.',
            'validation' => [
                'mime' => 'Supported image types: JPG/JPEG',
                'max_images' => 'You can upload maximum 20 images at a time.',
                'max_size' => 'Maximum size of all images exceeded. 400 MB allowed!'
            ],
            'AInocular_predict_failure' => 'Images have been uploaded but AInocular Predict API integration failed,Try Again.',
            'AInocular_upload_new_image_failure' => 'Images have been uploaded but AInocular Upload-New-Image API integration failed,Try Again.'
        ]
    ];
    protected $uploading_rules = [
        'upload_file' => 'max:20',
        'upload_file.*' => 'mimes:jpg,jpeg',
    ];
    protected $ainocular_predict_url = 'http://206.189.130.62:5000/predict_ld';
    protected $ainocular_upload_new_image_url = 'http://206.189.130.62:5555/upload_new_image';
    protected $static_ainocular_colors = ["White","Beige","Yellow","Orange","Red","Pink","Purple","Blue","Green","Brown","Grey","Black","Turquoise","Wine","Golden","Magenta"];

    /**
    * @Initialization constructor
    */
    public function __construct()
    {
        $this->date_bracket = Carbon::now();
        $this->role_master = Helper::getRoleMaster();
    } // end : construct

    /**
    * This function is used for showing upload form as landing page for image compression.
    * @return View : Image compression view.
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    public function uploadForm()
    {
        return view('ImageCompression.show-upload-form');
    } // end : uploadForm

    /**
    * This function is used for compressing uploaded images & storing the images on cloud.
    * @param Request : $request object -> File Array
    * @return Response : Image compression view with Status message via Ajax response.
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    protected function compress_and_store(Request $request)
    {
        try {
            $return = '';
            $validator = Validator::make($request->all(), $this->uploading_rules);
            if ($validator->fails()) {
                if(!empty($validator->messages()->get('upload_file'))){
                    $return.= $this->static_return['Image']['validation']['max_images'].PHP_EOL;
                }
                if(!empty($validator->messages()->get('upload_file.*'))){
                    $return.= $this->static_return['Image']['validation']['mime'].PHP_EOL;
                }
                return [
                    $this->static_return['validation_code'] => $return
                ];
            }
            if($this->check_total_image_size($request) > self::max_allowed_image_size){
                $return.= $this->static_return['Image']['validation']['max_size'].PHP_EOL;
                return [
                    $this->static_return['validation_code'] => $return
                ];
            }
            ini_set('memory_limit','10240M');
            ini_set('max_execution_time', 0);
            $this->images_array = $request->file('upload_file');
            foreach ($this->images_array as $images) {
                $fPrefix = pathinfo($images->getClientOriginalName(), PATHINFO_FILENAME);
                $fExtension = pathinfo($images->getClientOriginalName(), PATHINFO_EXTENSION);
                $regeneratedFilename = strtoupper($fPrefix).'.'.$fExtension;

                $this->thumbnail_generation($images,$regeneratedFilename);
                $cloud = $images->storeAs('original_images',$regeneratedFilename,'do_spaces');

                $this->store_image_mysql($regeneratedFilename,'THUMBNAIL');
            }
            unset($this->images_array);
            $AInocularPredictions = $this->integrate_ainocular_predict_api($_FILES['upload_file']);
            $AInocularUploadNewImage = $this->integrate_ainocular_upload_new_image_api($_FILES['upload_file']);
            if($AInocularPredictions == self::AInocular_API_FAILURE)
            {
                return [
                    $this->static_return['error_code'] => $this->static_return['Image']['AInocular_predict_failure']
                ];
            }
            foreach ($AInocularPredictions as $data) {
                ProductMaster::where('unique_sku_id',$data['image_name'])
                ->update([
                    'color' => $data['color'],
                    'design' => $data['design']
                ]);
            }
            if($AInocularUploadNewImage == self::AInocular_API_FAILURE)
            {
                return [
                    $this->static_return['error_code'] => $this->static_return['Image']['AInocular_upload_new_image_failure']
                ];
            }
            return [
                $this->static_return['success_code'] => $this->static_return['Image']['success']
            ];
        } catch (\Exception $e) {
            return [
                $this->static_return['error_code'] => $this->static_return['Image']['error']
            ];
        }
    } // end : compress_and_store

    /**
    * This function is used for compression procedure, thumbnail generation & storing thumbnails on cloud.
    * @param Array : Image
    * @return Thumbnail : Compressed image.
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    private function thumbnail_generation($images,$getFileName)
    {
        $compression_object = CompressImage::make($images);
        $compression_object->resize(400, 400, function ($constraint) { $constraint->aspectRatio(); $constraint->upsize(); });
        $resource = $compression_object->encode('jpg', 100)->stream()->detach();
        $path = Storage::disk('do_spaces')->put(
            'thumbnail_images/'.$getFileName,
            $resource
        );
    } // end : thumbnail_generation

    /**
    * This function is used for checking max size of all uploaded images.
    * @param Request : $request object
    * @return SUM : All images size sum
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    private function check_total_image_size($request)
    {
        $this->check_images_size = $request->file('upload_file');
        foreach ($this->check_images_size as $images) {
            $image_size = $images->getSize();
            $checkSize[] = number_format($image_size / 1048576,2);
        }
        return array_sum($checkSize);
    } // end : check_total_image_size

    /**
    * This function is used for storing images in mysql db as a backup along with timestamp.
    * @param Image-Name
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    private function store_image_mysql($image_name,$type)
    {
        UploadedImages::insert([
            'type' => $type,
            'image_name' => $image_name,
            'created_at' => $this->date_bracket,
            'updated_at' => $this->date_bracket
        ]);
    } // end : store_image_mysql

    /**
        * This API function is used for integration of AInocular python API in-order to generate predictions of color & design of uploaded image.
        * @param Array : $IMAGE_ARRAY
		* @return Predictions : $returnArray [Color & Design & Image name]
		* @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
	private function integrate_ainocular_predict_api($IMAGE_ARRAY)
	{
        try {
            if(!empty($IMAGE_ARRAY)){
                foreach ($IMAGE_ARRAY['tmp_name'] as $counter=>$tmp_image_path) {    
                    if(function_exists('curl_file_create')) 
                    { 
                        $curlFile = curl_file_create($tmp_image_path);
                    } 
                    else 
                    {
                        $curlFile = '@' . realpath($tmp_image_path);
                    }
                    $curl_request = curl_init($this->ainocular_predict_url);
                    curl_setopt($curl_request, CURLOPT_POST, true);
                    curl_setopt($curl_request,CURLOPT_POSTFIELDS,array('file' => $curlFile));
                    curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, true);
                    $AInocularResponse = curl_exec($curl_request);
                    curl_close($curl_request);
                    $getAInocularResponse[] = json_decode($AInocularResponse, true);
                    array_push($getAInocularResponse[$counter],pathinfo($IMAGE_ARRAY['name'][$counter],PATHINFO_FILENAME));
                }
                $return['image_name'] = null;
                $return['color'] = null;
                $return['design'] = null;
                foreach ($getAInocularResponse as $predicted_value) {
                    if(!empty($predicted_value['predictions']))
                    {
                        if(count($predicted_value['predictions']) == 1)
                        {
                            if(in_array($predicted_value['predictions'][0],$this->static_ainocular_colors))
                            {
                                $return['color'] = $predicted_value['predictions'][0];
                            }
                            else
                            {
                                $return['design'] = $predicted_value['predictions'][0];
                            }
                        }
                        else
                        {
                            $return['color'] = $predicted_value['predictions'][0];
                            $return['design'] = $predicted_value['predictions'][1]; 
                        }
                    }
                    $returnArray[] = [
                        'color' => $return['color'],
                        'design' => $return['design'],
                        'image_name' => (!empty($predicted_value[0]) ? $predicted_value[0] : $return['image_name'])
                    ];
                }
                return $returnArray;
            }
        } catch (\Exception $e) {
            return self::AInocular_API_FAILURE;
        }
    } // end : integrate_ainocular_predict_api
    
    /**
        * This API function is used for integration of AInocular python API whenever a new image is uploaded in SMS.
        * @param Array : $IMAGE_ARRAY
		* @return ReturnArray
		* @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
	private function integrate_ainocular_upload_new_image_api($IMAGE_ARRAY)
	{
        try {
            if(!empty($IMAGE_ARRAY)){
                foreach ($IMAGE_ARRAY['tmp_name'] as $counter=>$tmp_image_path) {    
                    if(function_exists('curl_file_create')) 
                    { 
                        $curlFile = curl_file_create($tmp_image_path);
                    } 
                    else 
                    {
                        $curlFile = '@' . realpath($tmp_image_path);
                    }
                    $curl_request = curl_init($this->ainocular_upload_new_image_url);
                    curl_setopt($curl_request, CURLOPT_POST, true);
                    curl_setopt($curl_request,CURLOPT_POSTFIELDS,array('file' => $curlFile));
                    curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, true);
                    $AInocularResponse = curl_exec($curl_request);
                    curl_close($curl_request);
                    $getAInocularResponse[] = json_decode($AInocularResponse, true);
                    array_push($getAInocularResponse[$counter],pathinfo($IMAGE_ARRAY['name'][$counter],PATHINFO_FILENAME));
                }
                $return['image_name'] = null;
                $return['code'] = null;
                $return['message'] = null;
                foreach ($getAInocularResponse as $predicted_value) {
                    if(!empty($predicted_value['code']))
                    {
                        $return['code'] = $predicted_value['code'];
                        $return['message'] = $predicted_value['msg'];
                    }
                    $returnArray[] = [
                        'AInocular_resp_code' => $return['code'],
                        'AInocular_resp_message' => $return['message'],
                        'image_name' => (!empty($predicted_value[0]) ? $predicted_value[0] : $return['image_name'])
                    ];
                }
                return $returnArray;
            }
        } catch (\Exception $e) {
            return self::AInocular_API_FAILURE;
        }
	} // end : integrate_ainocular_upload_new_image_api
}

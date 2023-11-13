<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\GenericResponseFormat;
use App\Http\Traits\GenericRestfulData;
use App\Http\Traits\GenericCloudComputing;
use App\User; 
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Validator;
use GuzzleHttp\Client;
use URL;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\DOSnapshotSuccess;
use App\Models\UploadedImages;
use App\Models\ProductMaster;

class CloudComputingController extends Controller
{
    /**
     * This controller is used for maintaining functions related to cloud computing operations from Digital Ocean Spaces s3
     * @access Rights : IPAD:API
     * @param Array : Validation Declaration.
     * @trait Declaration : GenericResponseFormat,GenericRestfulData.
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */

    use GenericResponseFormat,GenericRestfulData,GenericCloudComputing;
    const product_exists = 1;
    const product_does_not_exists = 0;
    protected $date_bracket = null;
    protected $images_array = [];
    protected $thumbnail_array = [];
    protected $original_image = [];
    protected $cloud_space_folders = [
        'org' => 'original_images',
        'thumb' => 'thumbnail_images'
    ];
    protected $retrieval_rules = [
        'sku_id' => 'required' 
    ];
    protected $delimiters = [
        'start' => 'thumbnail_images/',
        'end' => '?X-Amz-Content-Sha256'
    ];

    /**
    * @Initialization constructor
    */
    public function __construct(){
        $this->date_bracket = Carbon::now();
    } // end : construct

    /**
    * This function is used for retrieval of compressed thumbnails from digital ocean spaces.
    * @return JSON : Response format ( Status code, Message, Data array which contains list of thumbnail URLs)
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    public function retrieve_thumbnails()
    {
        ini_set('memory_limit','10240M');
        ini_set('max_execution_time', 0);
        try {
            $this->images_array = Storage::disk('do_spaces')->allFiles($this->cloud_space_folders['thumb']);
            foreach ($this->images_array as $image_name) {
                $imageName = $this->custom_get_string_between($this->generic_image_retrieval(null,null,$image_name), $this->delimiters['start'] , $this->delimiters['end']);
                if($this->check_product_master(substr(urldecode($imageName), 0, strlen(urldecode($imageName)) - 4)) == self::product_exists)
                {
                    $getDate=UploadedImages::where('image_name',urldecode($imageName))->select('created_at')->orderBy('id','desc')->first();
                    $this->thumbnail_array[] = [
                        'Thumbnail' => $this->generic_image_retrieval(null,null,$image_name),
                        'Date' => (!empty($getDate->created_at) ? date_format($getDate->created_at,"Y-m-d H:i:s") : '')
                    ];
                }
            }
            $data = $this->thumbnail_array;
            return $this->response_format(true,$this->static_restful_data['success_code'],$this->static_restful_data['success_fetch_message'],$data);
        } catch (\Exception $e) {
            return $this->response_format(false,$this->static_restful_data['error_code'],$this->static_restful_data['server_error_message'],[]);
        }
    } // end : retrieve_thumbnails

    /**
    * This function is used for retrieval of original master image.
    * @param SKU-ID
    * @return JSON : Response format ( Status code, Message, Data array which contains master image URL)
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    public function retrieve_original_image(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), $this->retrieval_rules);
            if ($validator->fails()) {
                return response()->json(['message' => 'The given data was invalid.','error'=>$validator->errors()], 422);  
            }
            $this->original_image = [
                'Original' => $this->generic_image_retrieval($this->cloud_space_folders['org'],$request->sku_id,null),
            ];
            $data = $this->original_image;
            return $this->response_format(true,$this->static_restful_data['success_code'],$this->static_restful_data['success_fetch_message'],$data);
        } catch (\Exception $e) {
            return $this->response_format(false,$this->static_restful_data['error_code'],$this->static_restful_data['server_error_message'],[]);
        }
    } // end : retrieve_original_image

    /**
        * This function is used to send email after successful completion of snapshot procedure of Digital Ocean droplet -> API
        * @param Subject : Email subject
        * @param Body : Email body
        * @return JSON : Response format ( Status code, Message, Data array which contains message)
        * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    public function snapshot_success_mail(Request $request)
    {
        try {
            $toEmail = "maheshachari@sutlejtextiles.com";
            Mail::to($toEmail)->cc(['bhairav@ainocular.com','vaibhav@ainocular.com','sameer.jambhulkar@wwindia.com','bhushan.more@neosofttech.com'])->send(new DOSnapshotSuccess($request->subject,$request->body));
            return $this->response_format(true,$this->static_restful_data['success_code'],$this->static_restful_data['snapshot_mail_send'],'Email has been sent to '. $toEmail);
        } catch (\Exception $e) {
            return $this->response_format(false,$this->static_restful_data['error_code'],$this->static_restful_data['server_error_message'],'Mail Failed');
        }
    } // end : snapshot_success_mail

    /**
        * This function is a custom function developed to get a string between long text.
        * @param String : Entire original string
        * @param Start : Start delimiter 
        * @param End : End delimiter 
        * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    private function custom_get_string_between($string, $start, $end){
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    } // end : custom_get_string_between

    /**
        * This function is a developed to check whether thumbnail name exists in product master or not as a part of sync activity with IOS.
        * @param Thumbnail-name
        * @return constants
        * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    private function check_product_master($thumbnail_name)
    {
        if(!empty(ProductMaster::where('unique_sku_id',$thumbnail_name)->first()))
        {
            return self::product_exists;
        }else
        {
            return self::product_does_not_exists;
        }
    } // end : check_product_master
}

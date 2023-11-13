<?php

namespace App\Http\Controllers\CRON;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use GuzzleHttp\Client;
use URL;
use DB;
use Carbon\Carbon;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\Rule;
use App\Helpers\Helper;
use App\Models\ProductMaster;
use App\Library\Services\MsSQLGeneric;
use App\Models\RequestsTable;
use App\Models\CronLogs;
use Illuminate\Support\Facades\Mail;
use App\Mail\RemoteImportFailure;
use App\Models\ProductPricing;

class ImportController extends Controller
{
    /**
     * This controller is used for performing all cron related functions in importing data from Sutlej data connections.
     * @access Rights : Code Level / Task Schedulers
     * @param Array : Validation Declaration.
     * @param Constant : Declaration.
     * @param Parameter : Declaration.
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    const cron_success = 1;
    const is_latest = 1;
    const cron_failure = 0;
    const run_cron = 1;
    protected $date_bracket = null;
    protected $import_products_data = [];
    protected $product_array = [];
    protected $import_requests_data = [];
    protected $requests_array = [];
    protected $import_products_pricing_data = [];
    protected $products_pricing_array = [];
    protected $log_array = [];
    protected $new_products = [];
    private $static_returns = [
        'products' => [
            'job_name' => 'Products Import',
            'mssql_table' => 'Table_ProductMaster',
            'imported' => 'Products imported successfully.',
            'failure' => 'Something went wrong while importing products, Try again.'
        ],
        'requests' => [
            'job_name' => 'Requests Import',
            'mssql_table' => 'Table_SKU',
            'imported' => 'Requests imported successfully.',
            'failure' => 'Something went wrong while importing requests, Try again.'
        ],
        'products_pricing' => [
            'job_name' => 'Products Pricing Import',
            'mssql_table' => 'Table_Costing',
            'imported' => 'Products pricing imported successfully.',
            'failure' => 'Something went wrong while importing products pricing, Try again.'
        ]
    ];

    /**
    * @Initialization constructor
    */
    public function __construct()
    {
        $this->date_bracket = Carbon::now();
    } // end : construct

    /**
    * This function is used to perform all the import procedures. 
    * It will execute the procedures only if the mechanism is set as ON in custom configurations module.
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    public function import_procedures()
    {
        if(in_array(self::run_cron,Helper::custom_config_procedures('Remote Import Procedures')))
        {
            $this->import_products();
            $this->import_requests();

            // Commented to stop the products pricing import cron as per client(vaibhav) requirement - 05012023
            // $this->import_products_pricing();
        }
    } // end : import_procedures

    /**
    * This function is used for importing products master table from Sutlej MSSQL connection & store at our end. 
    * @return Logs
    * @return Failure-Mails : If failed.
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    public function import_products()
    {
        try {
            $serviceObject = new  MsSQLGeneric();
            $this->import_products_data = $serviceObject->generic_query($this->static_returns['products']['mssql_table']);
            foreach ($this->import_products_data as $key => $value) {
                $this->product_array[] = [
                    'mssql_products_id' => $value['ID'],
                    'unique_sku_id' => strtoupper($value['Unique_SKU_ID']),
                    'quality' => $value['QualityName'],
                    'design_name' => $value['DesignName'],
                    'shade' => $value['ShadeName'],
                    'epi_on_loom' => $value['EPIOnLoom'],
                    'ppi_on_loom' => $value['PPIOnLoom'],
                    'epi_finish' => $value['EPIFinish'],
                    'ppi_finish' => $value['PPIFinish'],
                    'gsm' => $value['GSM'],
                    'glm' => $value['GLM'],
                    'designer' => $value['DesignerName'],
                    'end_use' => $value['EndUse'],
                    'product_type' => $value['ProductType'],
                    'fabric_type' => $value['FabricType'],
                    'weave_type' => $value['WeaveType'],
                    'category' => $value['Category'],
                    'repeat_inch' => $value['RepeatInch'],
                    'repeat_cm' => $value['RepeatCM'],
                    'finish_width' => $value['FinishWidth'],
                    'repeats_horizontal' => $value['RepeatsHorizontal'],
                    'repeats_vertical' => $value['RepeatsVertical'],
                    'Composition' => $value['Composition'],
                    'print_design' => $value['Print_Design'],
                    'print_colorway' => $value['Print_Colorway'],
                    'print_repeat_inch' => $value['Print_Repeat_Inch'], 
                    'print_repeat_cm' => $value['Print_Repeat_CM'],
                    'print_category' => $value['Print_Category'],
                    'print_type' => $value['Print_Type'],
                    'print_cost' => $value['Print_Cost'],
                    'emb_design' => $value['Emb_Design'],
                    'emb_colorway' => $value['Emb_Colorway'],
                    'emb_repeat_inch' => $value['Emb_Repeat_Inch'],
                    'emb_repeat_cm' => $value['Emb_Repeat_CM'],
                    'emb_category' => $value['Emb_Category'], 
                    'emb_stitch_type' => $value['Emb_Stitch_Type'], 
                    'emb_vendor' => $value['Emb_Vendor'],
                    'emb_stitches' => $value['Emb_Stitches'],
                    'emb_applique_work' => $value['Emb_Applique_Work'], 
                    'emb_cost' => $value['Emb_Cost'],
                    'emb_gsm' => $value['Emb_GSM'], 
                    'created_at' => $this->date_bracket,
                    'updated_at' => $this->date_bracket
                ];
            }
            foreach ($this->product_array as $product_key => $product_value) {
                $unique_sku = $product_value['unique_sku_id'];
                unset($product_value['unique_sku_id']);
                ProductMaster::updateOrCreate(
                    ['unique_sku_id' => $unique_sku],
                    $product_value
                );
            }
            $this->generate_import_logs($this->static_returns['products']['job_name'],$this->static_returns['products']['mssql_table'],$this->static_returns['products']['imported'],self::cron_success);
            echo $this->static_returns['products']['imported'].'<br>';
        } catch (\Exception $e) {
            $this->generate_import_logs($this->static_returns['products']['job_name'],$this->static_returns['products']['mssql_table'],$this->static_returns['products']['failure'],self::cron_failure);
            $this->send_error_mail($this->static_returns['products']['mssql_table'],$this->static_returns['products']['failure'],'FAILED',$this->date_bracket);
            echo $this->static_returns['products']['failure'].'<br>';
        }
    } // end : import_products

    /**
    * This function is used for importing Requests table from Sutlej MSSQL connection & store at our end. 
    * @return Logs
    * @return Failure-Mails : If failed.
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    public function import_requests()
    {
        try {
            $this->date_bracket = Carbon::now();
            $service_Object = new  MsSQLGeneric();
            $this->import_requests_data = $service_Object->generic_query($this->static_returns['requests']['mssql_table']);
            foreach ($this->import_requests_data as $key => $value) {
                $this->requests_array[] = [
                    'mssql_requests_id' => $value['ID'],
                    'request_no' => $value['RequestNo'],
                    'unique_sku_id' => strtoupper($value['Unique_SKU_ID']),
                    'quality_name' => $value['QualityName'],
                    'design_name' => $value['DesignName'],
                    'shade_name' => $value['ShadeName'],
                    'requirement' => $value['Requirement'],
                    'designer_name' => $value['DesignerName'],
                    'sample_length' => $value['SampleLength'],
                    'request_date' => $value['RequestDate'],
                    'request_type' => $value['Request_Type'],
                    'print_design' => $value['Print_Design'],
                    'print_colorway' => $value['Print_Colorway'],
                    'emb_design' => $value['Emb_Design'],
                    'emb_colorway' => $value['Emb_Colorway'],
                    'emb_vendor' => $value['Emb_Vendor'],
                    'due_date' => $value['Due_Date'],
                    'created_at' => $this->date_bracket,
                    'updated_at' => $this->date_bracket
                ];
            }
            foreach ($this->requests_array as $request_key => $request_value) {
                $unique_mssql_requests_id = $request_value['mssql_requests_id'];
                unset($request_value['mssql_requests_id']);
                RequestsTable::updateOrCreate(
                    ['mssql_requests_id' => $unique_mssql_requests_id],
                    $request_value
                );
            }
            $this->generate_import_logs($this->static_returns['requests']['job_name'],$this->static_returns['requests']['mssql_table'],$this->static_returns['requests']['imported'],self::cron_success);
            echo $this->static_returns['requests']['imported'].'<br>';
        } catch (\Exception $e) {
            $this->generate_import_logs($this->static_returns['requests']['job_name'],$this->static_returns['requests']['mssql_table'],$this->static_returns['requests']['failure'],self::cron_failure);
            $this->send_error_mail($this->static_returns['requests']['mssql_table'],$this->static_returns['requests']['failure'],'FAILED',$this->date_bracket);
            echo $this->static_returns['requests']['failure'].'<br>';
        }
    } // end : import_requests

    /**
    * This function is used for importing products pricing from Sutlej MSSQL connection quality wise & store at our end Unique SKU ID wise.
    * Its based on Transactional statements,If process is successful then only it will be committed else rollbacked to previous secure state.
    * @return Logs
    * @return Failure-Mails : If failed.
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    public function import_products_pricing()
    {
        try {
            ini_set('memory_limit','10240M');
            ini_set('max_execution_time', 0);
            DB::beginTransaction();
                $this->date_bracket = Carbon::now();
                ProductPricing::query()->delete();
                $serviceObject = new MsSQLGeneric();
                $this->import_products_pricing_data= $serviceObject->generic_query($this->static_returns['products_pricing']['mssql_table']);
                foreach ($this->import_products_pricing_data as $key => $value) {
                    $quality_products = ProductMaster::where('quality',$value['QualityName'])->select('unique_sku_id')->get()->toArray();
                    foreach ($quality_products as $sku) {
                        $this->products_pricing_array[] = [
                            'unique_sku_id' => strtoupper($sku['unique_sku_id']),
                            'P1' => $value['EBIDTA15'],
                            'P2' => $value['EBIDTA20'],
                            'P3' => $value['EBIDTA25'],
                            'P4' => $value['EBIDTA30'],
                            'P5' => ($value['EBIDTA25'] * $value['USDPrice']),
                            'rupee_multiplier' => $value['USDPrice'],
                            'created_at' => $value['LastUpdated'],
                            'updated_at' => $this->date_bracket
                        ];
                    }
                }
                $this->products_pricing_array = collect($this->products_pricing_array); 
                $chunk_optimisation = $this->products_pricing_array->chunk(1000);
                foreach($chunk_optimisation as $chunk){
                    ProductPricing::insert($chunk->toArray());
                }
            DB::commit();
                $this->new_products = ProductMaster::Join('products_pricing', 'products_master.unique_sku_id', '=', 'products_pricing.unique_sku_id')->select('products_master.unique_sku_id')->get()->toArray();
                foreach ($this->new_products as $prods) {
                    ProductPricing::where('unique_sku_id',$prods['unique_sku_id'])->orderBy('created_at', 'desc')->limit(1)
                    ->update([
                        'is_latest' => self::is_latest
                    ]);
                }
                $this->generate_import_logs($this->static_returns['products_pricing']['job_name'],$this->static_returns['products_pricing']['mssql_table'],$this->static_returns['products_pricing']['imported'],self::cron_success);
                echo $this->static_returns['products_pricing']['imported'].'<br>';
        } catch (\Exception $e) {
            DB::rollback();
                $this->generate_import_logs($this->static_returns['products_pricing']['job_name'],$this->static_returns['products_pricing']['mssql_table'],$this->static_returns['products_pricing']['failure'],self::cron_failure);
                $this->send_error_mail($this->static_returns['products_pricing']['mssql_table'],$this->static_returns['products_pricing']['failure'],'FAILED',$this->date_bracket);
                echo $this->static_returns['products_pricing']['failure'].'<br>';
        }
    } // end : import_products_pricing
    
    /**
    * This function is used for generating logs related to cron job activities.
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    protected function generate_import_logs($job_name,$mssql_table,$description,$cron_status)
    {
        $this->log_array[] = [
            'job_name' => $job_name,
            'mssql_table' => $mssql_table,
            'description' => $description,
            'cron_status' => $cron_status,
            'started_at' => $this->date_bracket,
            'ended_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];
        CronLogs::insert($this->log_array);
        unset($this->log_array);
    } // end : generate_import_logs

    /**
    * This function is used for generating mails if cron jobs fail & send to Sutlej authorities.
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    private function send_error_mail($importTable,$importDescription,$status,$importDate)
    {
        try {
            $subject = "Remote data import failure.";
            $toEmail = "programmer.akashputhanekar@gmail.com";
            Mail::to($toEmail)->cc(['yadav.rohit@neosoftmail.com'])->send(new RemoteImportFailure($importTable,$importDescription,$status,$importDate,$subject));
            return 'Email has been sent to '. $toEmail;
        } catch (\Exception $e) {
            echo 'Mail failed';
        }
    } // end : send_error_mail
}

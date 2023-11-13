<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\GenericResponseFormat;
use App\Http\Traits\GenericRestfulData;
use App\User; 
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Validator;
use GuzzleHttp\Client;
use URL;
use DB;
use Carbon\Carbon;
use App\Models\CustomerManagement;

class CustomerManagementController extends Controller
{
     /**
     * This controller is used for maintaining functions related to customer management.
     * @access Rights : IPAD:API
     * @param Array : Validation Declaration.
     * @trait Declaration : GenericResponseFormat,GenericRestfulData.
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */

    use GenericResponseFormat,GenericRestfulData;
    const active_user = 1;
    const inactivate_user = 0;
    protected $date_bracket = null;
    protected $createArray = [];
    protected $customer_storing_rules = [
        'data.*.customer_name' => 'required',
        'data.*.country' => 'required',
        'data.*.contact_person' => 'required',
        'data.*.email' => 'required|email',
        'data.*.contact_number' => 'required',
        'data.*.created_by' => 'required',
        'data.*.payment_terms' => 'required',
        'data.*.last_modified_by' => 'required',
        'data.*.is_active' => 'required',
        'data.*.created_at' => 'required',
        'data.*.updated_at' => 'required'
    ];

    /**
    * @Initialization constructor
    */
    public function __construct(){
        $this->date_bracket = Carbon::now();
    } // end : construct

    /**
    * This function is used generically to get customer model data from MYSQL.
    * @return Array: Customer Data
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    protected function customers_model_data(){
        return CustomerManagement::get();
    } // end : customers_model_data

    /**
    * This function is used for listing of customers for customer data & offline ipad synchronization of customer data.
    * @return JSON : Response format ( Status code, Message, Data array if any)
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    public function getCustomers(){
        $getCustomers = $this->customers_model_data();
        switch ($getCustomers) {
            case (isset($getCustomers) && ($getCustomers->count() > 0)):
                $data = $getCustomers;
                return $this->response_format(true,$this->static_restful_data['success_code'],$this->static_restful_data['success_fetch_message'],$data);
            break;
            case ($getCustomers->count() == 0):
                $data = [];
                return $this->response_format(true,$this->static_restful_data['success_code'],$this->static_restful_data['error_fetch_message'],$data);
            break;
            default:
                $data = [];
                return $this->response_format(false,$this->static_restful_data['error_code'],$this->static_restful_data['server_error_message'],$data);
            break;
        }
    } // end : getCustomers

    /**
    * This function is used for storing customers in mysql from IPAD as a process of syncronization.
    * @param Request : $request object
    * @return JSON : Response format ( Status code, Message, Data array if any)
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    public function storeCustomers(Request $request){
        try {
            $data = ['data' => $request->all()];
            $validator = Validator::make($data, $this->customer_storing_rules);
            if ($validator->fails()) {
                return response()->json(['message' => 'The given data was invalid.','error'=>$validator->errors()], 422);  
            }
            foreach ($request->all() as $key => $value) {
                $this->createArray[] = [
                    'customer_name' => $value['customer_name'],
                    'country' => $value['country'],
                    'contact_person' => $value['contact_person'],
                    'email' => $value['email'],
                    'contact_number' => $value['contact_number'],
                    'payment_terms' => $value['payment_terms'],
                    'created_by' => $value['created_by'],
                    'last_modified_by' => $value['last_modified_by'],
                    'is_active' => $value['is_active'],
                    'created_at' => $value['created_at'],
                    'updated_at' => $value['updated_at'],
                ];
            }
            foreach ($this->createArray as $array_key => $array_value) {
                $unique_email = $array_value['email'];
                unset($array_value['email']);
                CustomerManagement::updateOrCreate(
                    ['email' => $unique_email],
                    $array_value
                );
            }
            $data = $this->customers_model_data();
            return $this->response_format(true,$this->static_restful_data['success_code'],$this->static_restful_data['success_store_message'],$data);
        } catch (\Exception $e) {
            $data = [];
            return $this->response_format(false,$this->static_restful_data['error_code'],$this->static_restful_data['server_error_message'],$data);
        }
    } // end : storeCustomers
}

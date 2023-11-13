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
use App\Models\Cart;

class CartController extends Controller
{
    /**
     * This controller is used for maintaining functions related to cart.
     * @access Rights : IPAD:API
     * @param Array : Validation Declaration.
     * @trait Declaration : GenericResponseFormat,GenericRestfulData.
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */

    use GenericResponseFormat,GenericRestfulData;
    const validation_success = 3;
    protected $date_bracket = null;
    protected $cart_storing_rules = [
        'data.*.email' => 'required|email',
        'data.*.productid' => 'required',
        'data.*.qty' => 'required',
        'data.*.productprice' => 'required',
        'data.*.commitment' => 'required',
        'data.*.action' => 'required',
        'data.*.action_date' => 'required'
    ];

    /**
    * @Initialization constructor
    */
    public function __construct(){
        $this->date_bracket = Carbon::now();
    } // end : construct

    /**
    * This function is used as validator for checking data before performing sync activities.
    * @param Request : $request object
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    private function process_validator($request)
    {
        $check_data = ['data' => $request];
        $validator = Validator::make($check_data, $this->cart_storing_rules);
        if ($validator->fails()) {
            return response()->json(['message' => 'The given data was invalid.','error'=>$validator->errors()], 422);  
        }else{
            return self::validation_success;
        }
    } // end : process_validator

    /**
    * This function is used for storing cart details in mysql from IPAD as a process of syncronization.
    * @param Request : $request object
    * @return JSON : Response format ( Status code, Message, Data array if any)
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    public function sync_cart(Request $request)
    {
        try {
            if(($this->process_validator($request->all())) !== self::validation_success){
                return $this->process_validator($request->all());
            }
            $this->common_insertion($request->all());
            return $this->response_format(true,$this->static_restful_data['success_code'],$this->static_restful_data['success_store_message'],Cart::get());
        } catch (\Exception $e) {
            return $this->response_format(false,$this->static_restful_data['error_code'],$this->static_restful_data['server_error_message'],[]);
        }
    } // end : sync_wishsync_cartlist

    /**
    * This function is used for common insertion of Cart records.
    * @param Request : $request object
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    private function common_insertion($request)
    {
        foreach ($request as $key => $value) {
            Cart::insert([$key => $value]);
        }
    } // end : common_insertion
}

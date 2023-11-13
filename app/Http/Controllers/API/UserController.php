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

class UserController extends Controller
{
    /**
     * This controller is used for maintaining functions related to user login functionalities.
     * @access Rights : IPAD:API
     * @param Array : Validation Declaration.
     * @trait Declaration : GenericResponseFormat,GenericRestfulData.
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */

    use GenericResponseFormat,GenericRestfulData;
    protected $date_bracket = null;
    protected $login_rules = [
        'email' => 'required|email', 
        'password' => 'required|min:8|max:16|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*#?&]/',
    ];

    /**
    * @Initialization constructor
    */
    public function __construct(){
        $this->date_bracket = Carbon::today()->toDateString();
    } // end : construct

    /**
    * This function is used for IPAD Login.
    * @param Request $request object
    * @return JSON : Response format ( Status code, Message, Data array if any)
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    public function ipad_Login(Request $request){
        $loginData = $this->validate($request, $this->login_rules);
        if(!auth()->attempt($loginData)){
            return $this->response_format(false,$this->static_restful_data['error_code'],$this->static_restful_data['invalid_credentials_message'],null);
        }
        $accessToken = $this->generate_access_token();
        if(isset($accessToken) && $accessToken != ''){
            $data['user'] = auth()->user();
            $data['access_token'] = $accessToken;
            return $this->response_format(true,$this->static_restful_data['success_code'],$this->static_restful_data['login_success_message'],$data);
        }else{
            return $this->response_format(true,$this->static_restful_data['error_code'],$this->static_restful_data['server_error_message'],null);
        }
    } // end : ipad_Login

    /**
    * This function is used to generate passport access token of laravel for API authentication.
    * @return Token : Encrypted using laravel passport
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    private function generate_access_token(){
        return auth()->user()->createToken('authToken')->accessToken;
    } // end : generate_access_token
}

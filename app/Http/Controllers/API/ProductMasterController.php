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
use App\Models\ProductMaster;
use App\Models\ProductPricing;

class ProductMasterController extends Controller
{
    /**
     * This controller is used for maintaining functions related to products.
     * @access Rights : IPAD:API
     * @param Array : Validation Declaration.
     * @trait Declaration : GenericResponseFormat,GenericRestfulData.
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */

    use GenericResponseFormat, GenericRestfulData;
    const active_user = 1;
    const inactivate_user = 0;
    protected $date_bracket = null;

    /**
     * @Initialization constructor
     */
    public function __construct()
    {
        $this->date_bracket = Carbon::today()->toDateString();
    } // end : construct

    /**
     * This function is used for listing of products from product master.
     * @return JSON : Response format ( Status code, Message, Data array if any)
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    public function getList()
    {
        ini_set('memory_limit','-1');
        $getProducts = ProductMaster::where('is_active', self::active_user)->get();
        switch ($getProducts) {
            case (isset($getProducts) && ($getProducts->count() > 0)):
                $data = $getProducts;
                return $this->response_format(true, $this->static_restful_data['success_code'], $this->static_restful_data['success_fetch_message'], $data);
                break;
            case ($getProducts->count() == 0):
                $data = [];
                return $this->response_format(true, $this->static_restful_data['success_code'], $this->static_restful_data['error_fetch_message'], $data);
                break;
            default:
                $data = [];
                return $this->response_format(false, $this->static_restful_data['error_code'], $this->static_restful_data['server_error_message'], $data);
                break;
        }
    } // end : getList

    /**
     * This function is used for listing of product pricing.
     * @return JSON : Response format ( Status code, Message, Data array if any)
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    public function getPricing()
    {
        ini_set('memory_limit', '-1');
        $getPricing = ProductPricing::where("is_latest", 1)->get();
        switch ($getPricing) {
            case (isset($getPricing) && ($getPricing->count() > 0)):
                $data = $getPricing;
                return $this->response_format(true, $this->static_restful_data['success_code'], $this->static_restful_data['success_fetch_message'], $data);
                break;
            case ($getPricing->count() == 0):
                $data = [];
                return $this->response_format(true, $this->static_restful_data['success_code'], $this->static_restful_data['error_fetch_message'], $data);
                break;
            default:
                $data = [];
                return $this->response_format(false, $this->static_restful_data['error_code'], $this->static_restful_data['server_error_message'], $data);
                break;
        }
    } // end : getPricing
}

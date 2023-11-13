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
use App\Models\ProductMaster;
use App\Models\Cart;
use App\Models\Wishlist;
use App\Models\Orders;
use App\Models\OrderDetails;
use App\Models\ProductPricing;

class MasterController extends Controller
{
    /**
     * This controller is used as master controller for all master models related information.
     * @access Rights : IPAD:API
     * @param Array : Validation Declaration.
     * @trait Declaration : GenericResponseFormat,GenericRestfulData.
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */

    use GenericResponseFormat, GenericRestfulData;
    protected $date_bracket = null;

    /**
     * @Initialization constructor
     */
    public function __construct()
    {
        $this->date_bracket = Carbon::now();
    } // end : construct

    /**
     * This function returns counts of all master models to IOS Developers which is used for syncronization process.
     * @return JSON : Response format ( Status code, Message, Data array if any)
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    public function getCount()
    {
        try {
            $data['user_count'] = User::count();
            $data['customer_count'] = CustomerManagement::count();
            $data['product_count'] = ProductMaster::count();
            $data['cart_count'] = Cart::count();
            $data['wishlist_count'] = Wishlist::count();
            $data['order_count'] = Orders::count();
            $data['orderDetails_count'] = OrderDetails::count();
            $data['productPricing_count'] = ProductPricing::where("is_latest", 1)->count();
            return $this->response_format(true, $this->static_restful_data['success_code'], $this->static_restful_data['success_fetch_message'], $data);
        } catch (\Exception $e) {
            $data = [];
            return $this->response_format(true, $this->static_restful_data['error_code'], $this->static_restful_data['server_error_message'], $data);
        }
    } // end : getCount
}

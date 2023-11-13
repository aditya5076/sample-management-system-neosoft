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
use App\Models\Orders;
use App\Models\OrderDetails;
use Illuminate\Support\Facades\Mail;
use App\Mail\IOSOrdersConfirmation;

class OrderController extends Controller
{
    /**
     * This controller is used for maintaining functions related to Orders syncronization.
     * @access Rights : IPAD:API
     * @param Array : Validation Declaration.
     * @trait Declaration : GenericResponseFormat,GenericRestfulData.
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */

    use GenericResponseFormat,GenericRestfulData;
    const validation_success = 3;
    const mail_not_sent = 0;
    const mail_sent = 1;
    protected $date_bracket = null;
    protected $orderIDs = [];
    protected $productPrice = [];
    protected $order_storing_rules = [
        'data.*.orderid' => 'required',
        'data.*.orderdate' => 'required',
        'data.*.orderemail' => 'required|email',
        'data.*.customeremail' => 'required|email',
        'data.*.userid' => 'required',
        'data.*.isactive' => 'required',
    ];
    protected $order_details_storing_rules = [
        'data.*.orderid' => 'required',
        'data.*.productid' => 'required',
        'data.*.qty' => 'required',
        'data.*.productprice' => 'required',
        'data.*.commitment' => 'required',
    ];
    protected $module_identities = [
        'orders' => 'ORDERS',
        'order_details' => 'ORDER_DETAILS'
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
    private function process_validator($request,$module_identity)
    {
        $check_data = ['data' => $request];
        switch ($module_identity) {
            case $this->module_identities['orders']:
                $validator = Validator::make($check_data, $this->order_storing_rules);
                if ($validator->fails()) {
                    return response()->json(['message' => 'The given data was invalid.','error'=>$validator->errors()], 422);  
                }else{
                    return self::validation_success;
                }
            break;
            case $this->module_identities['order_details']:
                $validator = Validator::make($check_data, $this->order_details_storing_rules);
                if ($validator->fails()) {
                    return response()->json(['message' => 'The given data was invalid.','error'=>$validator->errors()], 422);  
                }else{
                    return self::validation_success;
                }
            break;
        }
    } // end : process_validator

    /**
    * This function is used for storing orders in mysql from IPAD as a process of syncronization.
    * @param Request : $request object
    * @return JSON : Response format ( Status code, Message, Data array if any)
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    public function sync_orders(Request $request)
    {
        try {
            if(($this->process_validator($request->all(),$this->module_identities['orders'])) !== self::validation_success){
                return $this->process_validator($request->all(),$this->module_identities['orders']);
            }
            $this->orderIDs = $this->common_insertion($request->all(),$this->module_identities['orders']);
            $this->order_confirmation_mails($this->orderIDs);
            return $this->response_format(true,$this->static_restful_data['success_code'],$this->static_restful_data['success_store_message'],Orders::get());
        } catch (\Exception $e) {
            return $this->response_format(false,$this->static_restful_data['error_code'],$this->static_restful_data['server_error_message'],[]);
        }
    } // end : sync_orders

    /**
    * This function is used for storing orders in mysql from IPAD as a process of syncronization.
    * @param Request : $request object
    * @return JSON : Response format ( Status code, Message, Data array if any)
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    public function sync_orders_details(Request $request)
    {
        try {
            if(($this->process_validator($request->all(),$this->module_identities['order_details'])) !== self::validation_success){
                return $this->process_validator($request->all(),$this->module_identities['order_details']);
            }
            $this->common_insertion($request->all(),$this->module_identities['order_details']);
            return $this->response_format(true,$this->static_restful_data['success_code'],$this->static_restful_data['success_store_message'],OrderDetails::get());
        } catch (\Exception $e) {
            return $this->response_format(false,$this->static_restful_data['error_code'],$this->static_restful_data['server_error_message'],[]);
        }
    } // end : sync_orders_details

    /**
    * This function is used for common insertion of orders & order details records.
    * @param Request : $request object
    * @param Modal-Name
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    private function common_insertion($request,$modal_name)
    {
        switch ($modal_name) {
            case $this->module_identities['orders']:
                foreach ($request as $key => $value) {
                    $orderID = $value['orderid'];
                    $this->orderIDs[] = $value['orderid'];
                    unset($value['orderid']);
                    Orders::updateOrCreate(
                        ['orderid' => $orderID],
                        $value
                    );
                }
                return $this->orderIDs;
            break;
            case $this->module_identities['order_details']:
                foreach ($request as $key => $value) {
                    $orderID = $value['orderid'];
                    $productID = $value['productid'];
                    unset($value['priceDecimal']);
                    unset($value['orderid']);
                    unset($value['productid']);
                    OrderDetails::updateOrCreate(
                        ['orderid' => $orderID, 'productid' => $productID],
                        $value
                    );
                }
            break;
        }
    } // end : common_insertion

    /**
    * This function is used for sending order confirmation mails to customers.
    * @param Order-IDs
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    private function order_confirmation_mails($order_ids)
    {
        try {
            foreach ($order_ids as $orderid) {
                $mail_check = Orders::where('orderid',$orderid)->select('mail_is_sent')->first();
                if($mail_check->mail_is_sent == self::mail_not_sent){
                    $subject = "Order confirmation: #Order-ID: ".$orderid;
                    $ordersData = $this->retrieve_order_details($orderid);
                    foreach ($ordersData as $data) {
                        $this->productPrice[] = $data['total_calculated_price'];
                    }
                    $toEmail = $ordersData[0]['orderemail'];
                    Mail::to($toEmail)->cc([$ordersData[0]['loggedInUserEmail']])->send(new IOSOrdersConfirmation($ordersData,$subject,$ordersData[0]['customer_name'],array_sum($this->productPrice),$orderid,$ordersData[0]['orderdate'],$ordersData[0]['ordernote'],$ordersData[0]['ordercreatedby'],$ordersData[0]['customeremail'],$ordersData[0]['customerContactPerson']));
                    Orders::where('orderid', [$orderid])->where('mail_is_sent',self::mail_not_sent)
                    ->update([
                        'mail_is_sent' => self::mail_sent
                    ]);
                    unset($this->productPrice);
                }
            } 
        } catch (\Exception $e) {
            return $e;
        }
    } // end : order_confirmation_mails

    /**
    * This function is used for returning entire order details array while sending confirmation emails to customers after placing orders from IOS Tabs.
    * @param Order-ID
    * @return Array : Orders Details
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    private function retrieve_order_details($orderid)
    {
        // Commented to remove listing price and added design, quality and, shade as per the client(Vaibhav) statement.
        
        // return Orders::leftJoin('order_details', 'orders.orderid', '=', 'order_details.orderid')->leftJoin('products_master', 'order_details.productid', '=', 'products_master.id')->leftJoin('customer_management', 'orders.customeremail', '=', 'customer_management.email')->leftJoin('users', 'orders.userid', '=', 'users.id')->leftJoin('products_pricing', 'products_master.unique_sku_id', '=', 'products_pricing.unique_sku_id')->where('orders.orderid',[$orderid])->select('orders.orderid','orders.orderdate','orders.customeremail','products_master.unique_sku_id','order_details.qty','order_details.productprice','customer_management.customer_name','orders.orderemail','orders.ordernote','order_details.productnote','users.name as ordercreatedby','customer_management.email as custemail',DB::raw("MAX(products_pricing.p3) as p3_price"),DB::raw('COALESCE(productprice,0) * qty as total_calculated_price'),'customer_management.contact_person as customerContactPerson')->groupBy('unique_sku_id')->get()->toArray();

        return Orders::leftJoin('order_details', 'orders.orderid', '=', 'order_details.orderid')->leftJoin('products_master', 'order_details.productid', '=', 'products_master.id')->leftJoin('customer_management', 'orders.customeremail', '=', 'customer_management.email')->leftJoin('users', 'orders.userid', '=', 'users.id')->where('orders.orderid',[$orderid])->select('orders.orderid','orders.orderdate','orders.customeremail','products_master.unique_sku_id','products_master.quality','products_master.design_name','products_master.shade','order_details.qty','order_details.productprice','customer_management.customer_name','orders.orderemail','orders.ordernote','order_details.productnote','users.name as ordercreatedby','users.email as loggedInUserEmail','customer_management.email as custemail',DB::raw('COALESCE(productprice,0) * qty as total_calculated_price'),'customer_management.contact_person as customerContactPerson')->groupBy('unique_sku_id')->get()->toArray();
    } // end : retrieve_order_details
}

<?php

namespace App\Exports;

use App\Models\CustomerManagement;
use App\Models\ProductMaster;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomersQuantitySKU implements FromCollection, WithHeadings
{
    /**
        * * This export is created for dumping customer wise quantity of sku based on QDS data for IPAD.
        * @return \Illuminate\Support\Collection
        * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    protected $from_date = null;
    protected $to_date = null;
    protected $query_array = [];
    private $report_headings = ["Customer Name","Region","Unique SKU ID","Quality Name","Design Name","Shade Name","Order ID"," Product Quantity","Product Price"];
    
    /**
    * @Initialization constructor
    */
    public function __construct($fromdate,$todate){
        $this->from_date = $fromdate;
        $this->to_date = $todate;
    } // end : construct

    /**
     * This collection returns excel dump of quantity of sku based on QDS data for IPAD.
    * @return Excel
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    public function collection()
    {
        $this->query_array = CustomerManagement::Join('orders', 'customer_management.email', '=', 'orders.customeremail')->leftJoin('order_details', 'orders.orderid', '=', 'order_details.orderid')->leftJoin('products_master', 'order_details.productid', '=', 'products_master.id')->select('customer_management.customer_name','customer_management.country','products_master.unique_sku_id','products_master.quality','products_master.design_name','products_master.shade','order_details.orderid','order_details.qty','order_details.productprice');
        if(!empty($this->from_date) && !empty($this->to_date)){
            $this->query_array = $this->query_array->whereBetween('orders.orderdate', [$this->from_date. " 00:00:00", $this->to_date. " 23:59:59"]);
        }
        return $this->query_array = $this->query_array->groupBy(['customer_name', 'country','products_master.id'])->get();
    } // end : collection


    /**
     * This returns Customers headings only for this export.
    * @return Array
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    public function headings(): array
    {
        return $this->report_headings;
    } // end : headings
}

<?php

namespace App\Exports;

use App\Models\Orders;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersDump implements FromCollection, WithHeadings
{
    /**
        * * This export is created for dumping orders master data for IPAD.
        * @return \Illuminate\Support\Collection
        * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    protected $from_date = null;
    protected $to_date = null;
    protected $query_array = [];
    private $report_headings = ["Order ID","Order created by","Quality","Design","Shade","Order Date","Order Note","Order Email","Customer Email","Product","Product Quantity","Product Price","Product Note","Print Design","Print Colorway","Print Repeat Inch","Print Repeat Cm","Print Category","Print Type","Print Cost","Emb Design","Emb Colorway","Emb Repeat Inch","Emb Repeat Cm","Emb Stitch Type","Emb vendor","Emb Stitches","Emb Applique Work","Emb cost","Emb Gsm","Emb Category"];
    
    /**
    * @Initialization constructor
    */
    public function __construct($fromdate,$todate){
        $this->from_date = $fromdate;
        $this->to_date = $todate;
    } // end : construct

    public function collection()
    {
        $this->query_array = Orders::leftJoin('order_details', 'orders.orderid', '=', 'order_details.orderid')->Join('users', 'orders.userid', '=', 'users.id')->Join('products_master', 'order_details.productid', '=', 'products_master.id')->select('orders.orderid','users.name','products_master.quality','products_master.design_name','products_master.shade','orders.orderdate','orders.ordernote','orders.orderemail','orders.customeremail','products_master.unique_sku_id','order_details.qty','order_details.productprice','order_details.productnote','products_master.print_design','products_master.print_colorway','products_master.print_repeat_inch','products_master.print_repeat_cm','products_master.print_category','products_master.print_type','products_master.print_cost','products_master.emb_design','products_master.emb_colorway','products_master.emb_repeat_inch','products_master.emb_repeat_cm','products_master.emb_stitch_type','products_master.emb_vendor','products_master.emb_stitches','products_master.emb_applique_work','products_master.emb_cost','products_master.emb_gsm','products_master.emb_category');
        if(!empty($this->from_date) && !empty($this->to_date)){
            $this->query_array = $this->query_array->whereBetween('orders.orderdate', [$this->from_date. " 00:00:00", $this->to_date. " 23:59:59"]);
        }
        return $this->query_array = $this->query_array->get();
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

<?php

namespace App\Exports;

use App\Models\CustomerManagement;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomersWishlist implements FromCollection, WithHeadings
{
    /**
        * * This export is created for dumping customers wishlist data for IPAD.
        * @return \Illuminate\Support\Collection
        * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    protected $from_date = null;
    protected $to_date = null;
    protected $query_array = [];
    private $report_headings = ["Sku ID","Quality","Design","Shade","Customer Name","Email","Contact Number","Wishlist Product","Wishlist status","Date","Print Design","Print Colorway","Print Repeat Inch","Print Repeat Cm","Print Category","Print Type","Print Cost","Emb Design","Emb Colorway","Emb Repeat Inch","Emb Repeat Cm","Emb Stitch Type","Emb vendor","Emb Stitches","Emb Applique Work","Emb cost","Emb Gsm","Emb Category"];

    /**
    * @Initialization constructor
    */
    public function __construct($fromdate,$todate){
        $this->from_date = $fromdate;
        $this->to_date = $todate;
    } // end : construct

    /**
     * This collection returns excel dump of Customers wishlist data.
    * @return Excel
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    public function collection()
    {
        $this->query_array = CustomerManagement::rightJoin('wishlist', 'customer_management.email', '=', 'wishlist.email')
        ->join('products_master', 'wishlist.productid', '=', 'products_master.id')
        ->select('products_master.unique_sku_id','products_master.quality','products_master.design_name','products_master.shade','customer_management.customer_name','customer_management.email','customer_management.contact_number','products_master.unique_sku_id',DB::raw("CASE WHEN wishlist.action = 1 THEN 'ADDED' ELSE 'REMOVED' END as wishlist_status"),'wishlist.action_date','products_master.print_design','products_master.print_colorway','products_master.print_repeat_inch','products_master.print_repeat_cm','products_master.print_category','products_master.print_type','products_master.print_cost','products_master.emb_design','products_master.emb_colorway','products_master.emb_repeat_inch','products_master.emb_repeat_cm','products_master.emb_stitch_type','products_master.emb_vendor','products_master.emb_stitches','products_master.emb_applique_work','products_master.emb_cost','products_master.emb_gsm','products_master.emb_category');
        if(!empty($this->from_date) && !empty($this->to_date)){
            $this->query_array = $this->query_array->whereBetween('wishlist.action_date', [$this->from_date. " 00:00:00", $this->to_date. " 23:59:59"]);
        }
        return $this->query_array = $this->query_array->orderBy('customer_management.email')->get();
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

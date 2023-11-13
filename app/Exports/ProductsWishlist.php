<?php

namespace App\Exports;

use App\Models\ProductMaster;
use App\Models\Wishlist;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsWishlist implements FromCollection, WithHeadings
{
    /**
        * * This export is created for dumping products wishlist data for IPAD.
        * @return \Illuminate\Support\Collection
        * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    protected $from_date = null;
    protected $to_date = null;
    protected $query_array = [];
    private $report_headings = ["Unique SKU ID","Quality Name","Design Name","Shade Name","Customer Name","Region","Wishlist added count","Wishlist removed count","Print Design","Print Colorway","Print Repeat Inch","Print Repeat Cm","Print Category","Print Type","Print Cost","Emb Design","Emb Colorway","Emb Repeat Inch","Emb Repeat Cm","Emb Stitch Type","Emb vendor","Emb Stitches","Emb Applique Work","Emb cost","Emb Gsm","Emb Category"];

    /**
    * @Initialization constructor
    */
    public function __construct($fromdate,$todate){
        $this->from_date = $fromdate;
        $this->to_date = $todate;
    } // end : construct

    /**
     * This collection returns excel dump of Products wishlist data.
    * @return Excel
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    public function collection()
    {
        $this->query_array = Wishlist::leftJoin('products_master', 'wishlist.productid', '=', 'products_master.id')->leftJoin('customer_management', 'wishlist.email', '=', 'customer_management.email')->select('products_master.unique_sku_id','products_master.quality','products_master.design_name','products_master.shade','customer_management.customer_name','customer_management.country as region',DB::raw("SUM(if(wishlist.action = 1, 1, 0)) AS wishlist_added"),DB::raw("SUM(if(wishlist.action = 0, 1, 0)) AS wishlist_removed"),'products_master.print_design','products_master.print_colorway','products_master.print_repeat_inch','products_master.print_repeat_cm','products_master.print_category','products_master.print_type','products_master.print_cost','products_master.emb_design','products_master.emb_colorway','products_master.emb_repeat_inch','products_master.emb_repeat_cm','products_master.emb_stitch_type','products_master.emb_vendor','products_master.emb_stitches','products_master.emb_applique_work','products_master.emb_cost','products_master.emb_gsm','products_master.emb_category');
        if(!empty($this->from_date) && !empty($this->to_date)){
            $this->query_array = $this->query_array->whereBetween('wishlist.action_date', [$this->from_date. " 00:00:00", $this->to_date. " 23:59:59"]);
        }
        return $this->query_array = $this->query_array->groupBy(['unique_sku_id', 'customer_name','region'])->get();
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

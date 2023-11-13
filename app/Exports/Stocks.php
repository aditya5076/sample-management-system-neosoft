<?php

namespace App\Exports;

use App\Models\Inward;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Stocks implements FromCollection, WithHeadings
{
    /**
     * * This export is created for Stocks report dumping.
    * @return \Illuminate\Support\Collection
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */

    protected $quality = null;
    protected $design = null;
    protected $shade = null;
    protected $from_date = null;
    protected $to_date = null;
    protected $query_array = [];
    private $report_headings = ["Request Number","SKU ID","Quality Name","Design Name","Shade Name","Location Name","Quantity","Inward Date","Print Design","Print Colorway","Emb Design","Emb Colorway","Emb Vendor"];

    /**
    * @Initialization constructor
    */
    public function __construct($quality,$design,$shade){
        // $this->from_date = $fromdate. " 00:00:00";
        // $this->to_date = $todate. " 23:59:59";
        $this->quality = $quality;
        $this->design = $design;
        $this->shade = $shade;
    } // end : construct

    /**
     * This collection returns excel dump of Stocks report.
    * @return Excel
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    public function collection()
    {
        $this->query_array = Inward::leftJoin('requests', 'inward.unique_sku_id', '=', 'requests.unique_sku_id')
        ->leftJoin('location_master', 'inward.location_id', '=', 'location_master.id')
        ->leftJoin('outward',function($join){
            $join->on('inward.unique_sku_id', '=', 'outward.unique_sku_id');
            $join->on('outward.location_id', '=', 'inward.location_id');
            $join->on('outward.request_id','=','inward.request_id');
        })
        ->select('requests.request_no','inward.unique_sku_id','requests.quality_name','requests.design_name','requests.shade_name','location_master.location_name',DB::raw("( COALESCE(SUM(DISTINCT inward.quantity),0) - COALESCE(SUM(DISTINCT outward.issued_quantity),0) ) as quantity"),'inward.created_at','requests.print_design','requests.print_colorway','requests.emb_design','requests.emb_colorway','requests.emb_vendor');
        if(!empty($this->quality)){
            $this->query_array = $this->query_array->where('quality_name',$this->quality);
        }
        if(!empty($this->design)){
            $this->query_array = $this->query_array->where('design_name',$this->design);
        }
        if(!empty($this->shade)){
            $this->query_array = $this->query_array->where('shade_name',$this->shade);
        }
        return $this->query_array = $this->query_array->orderBy('created_at', 'DESC')->groupBy('unique_sku_id', 'inward.location_id')->get();
    } // end : collection

    /**
     * This returns report headings only for this export.
    * @return Array
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    public function headings(): array
    {
        return $this->report_headings;
    } // end : headings
}

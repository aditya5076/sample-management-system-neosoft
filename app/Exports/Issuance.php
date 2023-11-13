<?php

namespace App\Exports;

use App\Models\Outward;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Issuance implements FromCollection, WithHeadings
{
    /**
     * * This export is created for Issuance report generation.
    * @return \Illuminate\Support\Collection
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */

    protected $from_date = null;
    protected $to_date = null;
    protected $query_array = [];
    private $report_headings = ["Request Number","SKU ID","Quality Name","Design Name","Shade Name","Issued To","Issued Quantity","Issued Date","Location Name","Print Design","Print Colorway","Emb Design","Emb Colorway","Emb Vendor"];

    /**
    * @Initialization constructor
    */
    public function __construct($fromdate,$todate){
        $this->from_date = $fromdate;
        $this->to_date = $todate;
    } // end : construct

    public function collection()
    {
        $this->query_array = Outward::leftJoin('requests', 'outward.unique_sku_id', '=', 'requests.unique_sku_id')
        ->leftJoin('location_master', 'outward.location_id', '=', 'location_master.id')
        ->select('requests.request_no','outward.unique_sku_id','requests.quality_name','requests.design_name','requests.shade_name','outward.issued_to','outward.issued_quantity','outward.issued_date','location_master.location_name','requests.print_design','requests.print_colorway','requests.emb_design','requests.emb_colorway','requests.emb_vendor');
        if(!empty($this->from_date) && !empty($this->to_date)){
            $this->query_array = $this->query_array->whereBetween('outward.issued_date', [$this->from_date. " 00:00:00", $this->to_date. " 23:59:59"]);
        }
        $this->query_array = $this->query_array->groupBy(['unique_sku_id','outward.id'])->orderBy('outward.created_at', 'DESC')->get(); 
        return $this->query_array;
    }

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

<?php

namespace App\Exports;

use App\Models\RequestsTable;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RequestDetailsDump implements FromCollection, WithHeadings
{
    /**
     * * This export is created for Request details dumping.
    * @return \Illuminate\Support\Collection
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */

    protected $quality = null;
    protected $design = null;
    protected $shade = null;
    protected $from_date = null;
    protected $to_date = null;
    protected $query_array = [];
    private $report_headings = ["Request Number","SKU ID","Quality Name","Design Name","Shade Name","Print Design","Print Colorway","Emb Design","Emb Colorway","Emb Vendor"];

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
     * This collection returns excel dump of Requests details report.
    * @return Excel
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    public function collection()
    {
        $this->query_array = RequestsTable::select('requests.request_no','requests.unique_sku_id','requests.quality_name','requests.design_name','requests.shade_name','requests.print_design','requests.print_colorway','requests.emb_design','requests.emb_colorway','requests.emb_vendor');
        if(!empty($this->quality)){
            $this->query_array = $this->query_array->where('requests.quality_name',$this->quality);
        }
        if(!empty($this->design)){
            $this->query_array = $this->query_array->where('requests.design_name',$this->design);
        }
        if(!empty($this->shade)){
            $this->query_array = $this->query_array->where('requests.shade_name',$this->shade);
        }
        return $this->query_array = $this->query_array->orderBy('requests.created_at', 'DESC')->get();
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

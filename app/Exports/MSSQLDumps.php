<?php

namespace App\Exports;

use App\Models\ProductMaster;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MSSQLDumps implements FromCollection, WithHeadings
{
    /**
     * * This export is created for dumping tables from MSSQL based on unique-sku-ids.
     * @return \Illuminate\Support\Collection
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */

    protected $from_date = null;
    protected $to_date = null;
    protected $query_array = [];
    private $report_headings = ["Request Number", "SKU ID", "Quality Name", "Design Name", "Shade Name", "EPI On Loom", "PPI On Loom", "EPI Finish", "PPI Finish", "GSM", "GLM", "Product Price", "Product Designer Name", "End Use", "Product Type", "Category", "Repeat Inch", "Repeat CM", "Finish Width", "Repeats Horizontal", "Repeats Vertical", "Color", "Design", "Product Creation Date", "Request Requirement Type", "Request Barcode", "Request Delivery Date", "Request Designer Name", "Request Sample Length", "Request Creation Date", "Print Design", "Print Colorway", "Print Repeat Inch", "Print Repeat Cm", "Print Category", "Print Type", "Print Cost", "Emb Design", "Emb Colorway", "Emb Repeat Inch", "Emb Repeat Cm", "Emb Stitch Type", "Emb vendor", "Emb Stitches", "Emb Applique Work", "Emb cost", "Emb Gsm", "Emb Category"];

    /**
     * @Initialization constructor
     */
    public function __construct($fromdate, $todate)
    {
        $this->from_date = $fromdate;
        $this->to_date = $todate;
    } // end : construct

    public function collection()
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 0);
        $this->query_array = ProductMaster::leftJoin('requests', 'products_master.unique_sku_id', '=', 'requests.unique_sku_id')
            ->leftJoin('products_pricing', function ($join) {
                $join->on('products_master.unique_sku_id', '=', 'products_pricing.unique_sku_id');
                $join->where('products_pricing.is_latest', '=', 1);
            })
            ->select(
                'requests.request_no',
                'products_master.unique_sku_id',
                'products_master.quality',
                'products_master.design_name',
                'products_master.shade',
                'products_master.epi_on_loom',
                'products_master.ppi_on_loom',
                'products_master.epi_finish',
                'products_master.ppi_finish',
                'products_master.gsm',
                'products_master.glm',
                'products_pricing.P3',
                'products_master.designer',
                'products_master.end_use',
                'products_master.product_type',
                'products_master.category',
                'products_master.repeat_inch',
                'products_master.repeat_cm',
                'products_master.finish_width',
                'products_master.repeats_horizontal',
                'products_master.repeats_vertical',
                'products_master.color',
                'products_master.design',
                'products_master.created_at as product_create_date',
                'requests.requirement',
                'requests.barcode',
                'requests.delivery_date',
                'requests.designer_name',
                'requests.sample_length',
                'requests.created_at as request_create_date',
                'products_master.print_design',
                'products_master.print_colorway',
                'products_master.print_repeat_inch',
                'products_master.print_repeat_cm',
                'products_master.print_category',
                'products_master.print_type',
                'products_master.print_cost',
                'products_master.emb_design',
                'products_master.emb_colorway',
                'products_master.emb_repeat_inch',
                'products_master.emb_repeat_cm',
                'products_master.emb_stitch_type',
                'products_master.emb_vendor',
                'products_master.emb_stitches',
                'products_master.emb_applique_work',
                'products_master.emb_cost',
                'products_master.emb_gsm',
                'products_master.emb_category'
            )
            ->when(!empty($this->from_date) && !empty($this->to_date), function ($query) {
                $query->whereBetween('requests.request_date', [$this->from_date . " 00:00:00", $this->to_date . " 23:59:59"]);
            })->orderBy('requests.request_date', 'DESC')->get();

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

<?php

namespace App\Exports;

use App\Models\RequestsTable;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SampleEfficiency implements FromCollection, WithHeadings
{
    /**
     * * This export is created for Sample efficiency report dumping.
     * @return \Illuminate\Support\Collection
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    protected $from_date = null;
    protected $to_date = null;
    protected $query_array = [];
    private $report_headings = ["Request Number", "SKU ID", "Quality Name", "Design Name", "Shade Name", "Request Date", "Due Date", "Delivery Date", "Delay(In Days)"];

    /**
     * @Initialization constructor
     */
    public function __construct($fromdate, $todate)
    {
        $this->from_date = $fromdate;
        $this->to_date = $todate;
    } // end : construct

    /**
     * This collection returns excel dump of Sample efficiency report.
     * @return Excel
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    public function collection()
    {
        $this->query_array = RequestsTable::select('request_no', 'unique_sku_id', 'quality_name', 'design_name', 'shade_name', 'created_at', 'due_date', 'delivery_date')
            ->addSelect(['delay' => RequestsTable::raw('DATEDIFF(created_at, delivery_date)')])
            ->when(!empty($this->from_date) && !empty($this->to_date), function ($query) {
                return $query->whereBetween('created_at', [$this->from_date . " 00:00:00", $this->to_date . " 23:59:59"]);
            })
            ->orderBy('created_at', 'DESC')
            ->get();
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

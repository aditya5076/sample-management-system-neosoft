<?php

namespace App\Exports;

use App\Models\CustomerManagement;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomersDump implements FromCollection, WithHeadings
{
    /**
        * * This export is created for dumping customers master data for IPAD.
        * @return \Illuminate\Support\Collection
        * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    protected $from_date = null;
    protected $to_date = null;
    protected $query_array = [];
    private $report_headings = ["Customer Name","Country","Contact Person","Email","Contact Number","Payment Terms","Created By","Last Modified By","Active Status","Creation Date","Modification Date"];

    /**
    * @Initialization constructor
    */
    public function __construct($fromdate,$todate){
        $this->from_date = $fromdate;
        $this->to_date = $todate;
    } // end : construct

    /**
     * This collection returns excel dump of Customers data.
    * @return Excel
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    public function collection()
    {
        $this->query_array = CustomerManagement::leftJoin('users AS a', 'customer_management.created_by', '=', 'a.id')
        ->leftJoin('users AS b', 'customer_management.last_modified_by', '=', 'b.id')
        ->select('customer_management.customer_name','customer_management.country','customer_management.contact_person','customer_management.email','customer_management.contact_number','customer_management.payment_terms','a.name as created_by','b.name as last_modified_by',DB::raw("CASE WHEN customer_management.is_active = 1 THEN 'YES' ELSE 'NO' END as is_active"),'customer_management.created_at','customer_management.updated_at');
        if(!empty($this->from_date) && !empty($this->to_date)){
            $this->query_array = $this->query_array->whereBetween('customer_management.created_at', [$this->from_date. " 00:00:00", $this->to_date. " 23:59:59"]);
        }
        return $this->query_array = $this->query_array->orderBy('customer_management.created_at', 'DESC')->get();
    }

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

<?php

namespace App\Exports;

use App\Models\Orders;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrderConfirmationAttachment implements FromCollection, WithHeadings
{

    /**
        * This export is created for dumping order confirmation emails attachment.
        * @return \Illuminate\Support\Collection
        * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    protected $ordersData = [];
    protected $query_array = [];
    protected $productPrice;

    // removed "Listing Price" and added "Quality, Design & Shade"
    private $report_headings = ["Order ID","Order Date","Order created by","Order Note","Customer Name","Contact Person","Customer Email","Product","Quality","Design","Shade","Product Note","Product Quantity","Finalised Price","Total Price"];

    /**
    * @Initialization constructor
    */
    public function __construct($ordersData,$productPrice){
        $this->ordersData = $ordersData;
        $this->productPrice = $productPrice;
    } // end : construct

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        foreach ($this->ordersData as $data) {
            $this->query_array[] = [$data['orderid'],$data['orderdate'],$data['ordercreatedby'],$data['ordernote'],$data['customer_name'],$data['customerContactPerson'],$data['customeremail'],$data['unique_sku_id'],$data['quality'],$data['design_name'],$data['shade'],$data['productnote'],$data['qty'],$data['productprice'],$data['total_calculated_price']];
        }
        $this->query_array[] = ['','','','','','','','','','','','','','Total',$this->productPrice];
        return collect($this->query_array);
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

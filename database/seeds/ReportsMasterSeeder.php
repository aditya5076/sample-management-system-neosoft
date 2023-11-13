<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportsMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $status = DB::table('reports_master')->first();
        if($status == null){
            DB::table('reports_master')->insert(array(
                array(   
                    'report_name' => 'Sampling Efficiency Report',
                    'report_description' => 'This report is used for reviewing how frequently samples are being developed',
                    'report_type' => 'WEB',
                    'is_active' => '1',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ),
                array(   
                    'report_name' => 'Stock Report',
                    'report_description' => 'This report is used to know the stock of all SKUs',
                    'report_type' => 'WEB',
                    'is_active' => '1',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ),
                array(   
                    'report_name' => 'Issuance Report',
                    'report_description' => 'This report is used to know how much quantity of samples is being issued to whom',
                    'report_type' => 'WEB',
                    'is_active' => '1',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ),
                array(   
                    'report_name' => 'Products + Requests Report',
                    'report_description' => 'This report is the join of both the tables coming from SQL based on SKU ID',
                    'report_type' => 'WEB',
                    'is_active' => '1',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ),
                array(   
                    'report_name' => 'Customer Master Dump',
                    'report_description' => 'This report is used to get entire customer data dump.',
                    'report_type' => 'IPAD',
                    'is_active' => '1',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ),
                array(   
                    'report_name' => 'Order Master Dump',
                    'report_description' => 'This report is used to get entire orders data dump.',
                    'report_type' => 'IPAD',
                    'is_active' => '1',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ),
                array(   
                    'report_name' => 'Customer-wise Wishlist',
                    'report_description' => 'This report is used to get customer wise wishlist data.',
                    'report_type' => 'IPAD',
                    'is_active' => '1',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ),
                array(   
                    'report_name' => 'Product-wise Wishlist',
                    'report_description' => 'This report is used to get product wise wishlist data.',
                    'report_type' => 'IPAD',
                    'is_active' => '1',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ),
                array(   
                    'report_name' => 'Product-wise Orders',
                    'report_description' => 'This report is used to get product wise orders data.',
                    'report_type' => 'IPAD',
                    'is_active' => '1',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ),
                array(   
                    'report_name' => 'Customer-wise quantity of SKU by QDS',
                    'report_description' => 'This report is used to get customer wise quantity of SKU based on QDS data.',
                    'report_type' => 'IPAD',
                    'is_active' => '1',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ),
                array(   
                    'report_name' => 'Requests Details Report',
                    'report_description' => 'This report gives details about all requests & QDS.',
                    'report_type' => 'WEB',
                    'is_active' => '1',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ),
           ));
        }
    }
}

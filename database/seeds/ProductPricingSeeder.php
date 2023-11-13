<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductPricingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $status = DB::table('products_pricing')->first();
        if($status == null){
            DB::table('products_pricing')->insert(array(
                array(   
                    'unique_sku_id' => 'Q0644D000148S000',
                    'P1' => '100',
                    'P2' => '101',
                    'P3' => '103',
                    'P4' => '106',
                    'P5' => '108',
                    'P6' => '102',
                    'P7' => '112',
                    'rupee_multiplier' => 70,
                    'created_at' => '2020-01-21 17:58:28',
                    'updated_at' => '2020-01-21 17:58:28'
                ),
                array(   
                    'unique_sku_id' => 'Q0644D000342S009',
                    'P1' => '100',
                    'P2' => '101',
                    'P3' => '103',
                    'P4' => '106',
                    'P5' => '108',
                    'P6' => '102',
                    'P7' => '112',
                    'rupee_multiplier' => 70,
                    'created_at' => '2020-01-21 17:58:28',
                    'updated_at' => '2020-01-21 17:58:28'
                ),
                array(   
                    'unique_sku_id' => 'Q0644D000148S000',
                    'P1' => '100',
                    'P2' => '102',
                    'P3' => '103',
                    'P4' => '105',
                    'P5' => '108',
                    'P6' => '102',
                    'P7' => '112',
                    'rupee_multiplier' => 70,
                    'created_at' => '2020-01-20 17:58:28',
                    'updated_at' => '2020-01-20 17:58:28'
                ),
                array(   
                    'unique_sku_id' => 'Q0644D000343S008',
                    'P1' => '100',
                    'P2' => '101',
                    'P3' => '103',
                    'P4' => '106',
                    'P5' => '108',
                    'P6' => '102',
                    'P7' => '112',
                    'rupee_multiplier' => 70,
                    'created_at' => '2020-01-20 17:58:28',
                    'updated_at' => '2020-01-20 17:58:28'
                ),
           ));
        }
    }
}

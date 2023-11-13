<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class CustomConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $status = DB::table('custom_configs')->first();
        if($status == null){
            DB::table('custom_configs')->insert(array(
                array(   
                    'event_name' => 'Remote Import Procedures',
                    'event_metadata' => null,
                    'is_active' => '1',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ),
                array(   
                    'event_name' => 'Sutlej Connections IP Whitelisting',
                    'event_metadata' => '45.127.89.242,45.127.89.243,45.127.89.244,103.205.125.198',
                    'is_active' => '0',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                )
           ));
        }
    }
}

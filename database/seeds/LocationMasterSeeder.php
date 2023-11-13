<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LocationMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $status = DB::table('location_master')->first();
        if($status == null){
            DB::table('location_master')->insert(array(
                array(   
                    'location_name' => 'BIN 1',
                    'is_active' => '1',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ),
                array(   
                    'location_name' => 'BIN 2',
                    'is_active' => '1',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ),
                array(   
                    'location_name' => 'BIN 3',
                    'is_active' => '1',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ),
                array(   
                    'location_name' => 'BIN 4',
                    'is_active' => '1',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ),
           ));
        }
    }
}

<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserRoleMaster extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $status = DB::table('user_role_master')->first();
        if($status == null){
            DB::table('user_role_master')->insert(array(
                array(   
                    'role_name' => 'Super Admin',
                    'short_code' => 'SA',
                    'is_active' => '1',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ),
                array(   
                    'role_name' => 'Admin',
                    'short_code' => 'AD',
                    'is_active' => '1',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ),
                array(   
                    'role_name' => 'Sales Executive',
                    'short_code' => 'SE',
                    'is_active' => '1',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ),
                array(   
                    'role_name' => 'Factory Employee',
                    'short_code' => 'FE',
                    'is_active' => '1',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ),
                array(   
                    'role_name' => 'Image Uploader',
                    'short_code' => 'IE',
                    'is_active' => '1',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ),
           ));
        }
    }
}

<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\UserRoleMaster;
use DB;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    /**
     * This is created for user dump export.
    * @return \Illuminate\Support\Collection
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    private $user_headings = ["Name","Email Address","Role Name","Company","Designation","Active Status","Created Date"];
    public function collection()
    {
        return $userDump = User::leftJoin('user_role_master', 'users.role_id', '=', 'user_role_master.id')->select('users.name','users.email','user_role_master.role_name','users.company','users.designation',DB::raw("CASE WHEN users.is_active = 1 THEN 'YES' ELSE 'NO' END as is_active"),'users.created_at')->whereNotIn('users.role_id',[1])->orderBy('users.created_at', 'DESC')->get(); 
    }
    
    /**
     * This returns User headings only for this export.
    * @return Array
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    public function headings(): array
    {
        return $this->user_headings;
    }
}

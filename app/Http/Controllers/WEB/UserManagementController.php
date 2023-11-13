<?php

namespace App\Http\Controllers\WEB;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use GuzzleHttp\Client;
use URL;
use DB;
use Carbon\Carbon;
use App\Models\UserRoleMaster;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\Rule;
use App\Helpers\Helper;

class UserManagementController extends Controller
{
    /**
     * This controller is used for User Management module which will be used only by the Admin user in web portal.
     * @access Rights : Admin
     * @param Array : Validation Declaration.
     * @param Constant : Declaration.
     * @param Parameter : Declaration.
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    const active_user = 1;
    const inactivate_user = 0;
    protected $date_bracket = null;
    protected $status = null;
    protected $code = null;
    protected $today = null;
    protected $rand_string = null;
    protected $role_master = [];
    protected $storing_rules = [
        'name' => 'required|max:50',
        'company' => 'required|max:50',
        'designation' => 'required|max:50',
        'role_id' => 'required|not_in:0',
        'email' => 'required|email|unique:users', 
        'password' => 'required|min:8|max:16|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*#?&]/|confirmed',
        'password_confirmation' => 'required',
        'is_active' => 'required'
    ];
    protected $updation_rules = [
        'name' => 'required|max:50',
        'company' => 'required|max:50',
        'designation' => 'required|max:50',
        'role_id' => 'required|not_in:0',
        'email' => 'required',
        'password' => 'nullable|min:8|max:16|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*#?&]/|confirmed',
        'password_confirmation' => 'nullable|required_if:password,!=,null',
    ];
    protected $static_return = [
        'inactivate' => 'User inactivated successfully.',
        'create' => 'User created successfully.',
        'updation' => 'User details updated successfully.',
        'error' => 'Something went wrong,Try Again.',
        'success_code' => 'success',
        'error_code' => 'error'
    ];

    /**
    * @Initialization constructor
    */
    public function __construct(){
        $this->date_bracket = Carbon::now();
        $this->today = Carbon::today()->toDateString();
        $this->role_master = Helper::getRoleMaster();
        $this->rand_string = rand(10,1000);
    } // end : construct

    /** This is a validation function which checks certain parameters before creating users.
     * @param Request object
     * @return Validator
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    private function checkValidation(Request $request,$module_identity,$user_id=null){
        switch($module_identity){
            case 'STORE':
                Validator::make($request->all(),$this->storing_rules)->validate();
            break;
            case 'UPDATION':
                Validator::make($request->all(),$this->updation_rules)->validate();
                $request->validate([
                    'email' => "unique:users,email,$user_id"
                ]);
            break;
        }
        
    } // end : checkValidation

    /**
    * This function is used for displaying list of users to admin.
    * @return View : User view with list of users in a paginated manner.
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    protected function showUsers(){
        $getUsers = User::with('roles')->whereNotIn('role_id',[1])->orderBy('created_at', 'DESC')->get()->toArray();
        return view('UserManagement.show-user',compact('getUsers'));
    } // end : showUsers

    /**
    * This function is used for inactivating users by the admin.
    * @param ID : User ID
    * @return Route : User view with status message is returned.
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    protected function inactivateUser($user_id){
        $inactivate = User::find($user_id);
        $inactivate->is_active = self::inactivate_user;
        $inactivate->save();
        $this->code = $this->static_return['success_code'];
        $this->status = $this->static_return['inactivate'];
        return redirect()->route('showUsers')->with($this->code,$this->status);
    } // end : inactivateUser

    /**
    * This function is used for editing users by the admin.
    * @param ID : User ID
    * @return View : Edit user view with user data is returned.
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    protected function editUser($user_id){
        $userData = User::find($user_id);
        return view('UserManagement.edit-user',compact('userData'))->with(['role_master' => $this->role_master]);
    } // end : editUser

    /**
    * This function is used for editing users by the admin.
    * @param Request : $request object.
    * @param ID : User ID
    * @return Route : Show-user view with status message is returned.
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    protected function updateExistingUser(Request $request, $user_id){
        $this->checkValidation($request,'UPDATION',$user_id);
        $findUser = User::find($user_id);
        $findUser->name = $request->name;
        $findUser->email = $request->email;
        $findUser->company = $request->company;
        $findUser->designation = $request->designation;
        $findUser->role_id = $request->role_id;
        $findUser->is_active = $request->is_active;
        if(isset($request->password) && $request->password!=''){
            $findUser->password = bcrypt($request->password);
        }
        $findUser->save();
        $this->code = $this->static_return['success_code'];
        $this->status = $this->static_return['updation'];
        return redirect()->route('showUsers')->with($this->code,$this->status);
    } // end : updateExisting

    /**
    * This function is used for creating users by the admin.
    * @return View : Create user view is returned.
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    protected function createUser(){
        return view('UserManagement.add-user')->with(['role_master' => $this->role_master]);
    } // end : createUser

    /**
    * This function is used for storing new users by the admin.
    * @param Request : $request object.
    * @return Route : Show-user view with status message is returned.
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    protected function storeUser(Request $request){
        $this->checkValidation($request,'STORE');
        $createUser = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'company' => $request->company,
            'designation' => $request->designation,
            'role_id' => $request->role_id,
            'is_active' => $request->is_active,
            'created_at' => $this->date_bracket,
            'updated_at' => $this->date_bracket
        ]);
        if($createUser){
            $lastInserted = $createUser->id;
            $this->code = $this->static_return['success_code'];
            $this->status = $this->static_return['create'];
        }else{
            $this->code = $this->static_return['error_code'];
            $this->status = $this->static_return['error'];
        }
        return redirect()->route('showUsers')->with($this->code,$this->status);
    } // end : storeUser  
    
    /**
    * This function is used for exporting users dump into excel using exports collection.
    * @return Excel
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    protected function export_users(){
        return \App::call('App\Http\Controllers\WEB\ReportsMasterController@dump_users');
    } // end : export_users  
}

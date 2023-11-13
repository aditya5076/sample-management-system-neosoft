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
use App\Models\CustomConfig;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\Rule;
use App\Helpers\Helper;
use DataTables;

class CustomConfigController extends Controller
{
    /**
     * This controller is used for managing all backend level custom configurations.
     * @access Rights : Super Admin, Admin(Only view access)
     * @param Array : Validation Declaration.
     * @param Constant : Declaration.
     * @param Parameter : Declaration.
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    const process_success = 66;
    const inactive_event = 0;
    const active_event = 1;
    protected $date_bracket = null;
    protected $status = null;
    protected $code = null;
    protected $whitelisted_ips_array = [];
    protected $static_return = [
        'success_code' => 'success',
        'error_code' => 'error',
        'error' => 'Something went wrong,Try Again.',
        'Inactivate' => [
            'success' => 'Event inactivated successfully.',
        ]
    ];

    /**
    * @Initialization constructor
    */
    public function __construct()
    {
        $this->date_bracket = Carbon::now();
        $this->role_master = Helper::getRoleMaster();
    } // end : construct

    /**
        * This function is used for loading the landing screen of module with table data.
        * @return View : Config data view
        * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    public function showConfigScreen()
    {
        $configData = CustomConfig::get()->toArray();
        return view('CustomConfig.show-config-screen',compact('configData'));
    } // end : showConfigScreen

    /**
        * This function is used for activation / in-activation of configuration events.
        * @param Request : $request object : Action status, Event ID
        * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    protected function eventActions(Request $request)
    {
        try {
            CustomConfig::where('id',$request->event_id)
            ->update([
                'is_active' => $request->action_status
            ]);
            return self::process_success;
        } catch (\Exception $e) {
            return $e;
        }
    } // end : eventActions

    /**
        * This function is used for adding whitelisted IPs in mysql database.
        * @param Request : $request object : Whitelisted IPS, Event ID
        * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    protected function add_whitelisted_ips(Request $request)
    {
        try {
            CustomConfig::where('id',$request->event_id)
            ->update([
                'event_metadata' => $request->user_entered_whitelisted_ips
            ]);
            return self::process_success;
        } catch (\Exception $e) {
            return $e;
        }
    } // end : add_whitelisted_ips
}

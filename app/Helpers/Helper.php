<?php

namespace App\Helpers;

use Auth;
use Illuminate\Http\Request;
use App\User;
use DB;
use Carbon\Carbon;
use App\Models\UserRoleMaster;
use App\Models\LocationMaster;
use App\Models\RequestsTable;
use App\Models\ProductMaster;
use App\Models\CustomConfig;

class Helper
{
	/**
	 * This is a customized Helper created for performing some common functionalities for ease of developers access.
	 * @access Rights : WEB & IPAD
	 * @param Parameter : Declaration.
	 * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
	 */
	const active_user = 1;
	const inactivate_user = 0;
	const accept = true;
	const reject = false;
	protected static $whitelisted_ips_array = [];
	protected static $roleMapper = array(
		'super_admin' => 'SA',
		'admin' => 'AD',
		'sales_executive' => 'SE',
		'factory_employee' => 'FE',
		'image_uploader' => 'IE'
	);
	protected static $modules_prohitibited = array(
		'SA' => [],
		'AD' => ['image_compression', 'custom_configs_action'],
		'SE' => ['user_management', 'request_listing_write_access', 'image_compression', 'custom_configs'],
		'FE' => ['user_management', 'image_compression', 'ipad_reports', 'custom_configs'],
		'IE' => ['user_management', 'request_listing_write_access', 'stocks_listing', 'request_listing', 'reports', 'custom_configs']
	);

	/**
	 * This function returns role name of logged in user.
	 * @param Return : Role Name
	 * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
	 */
	public static function returnAuthRole()
	{
		return Auth::user()->roles->role_name;
	}

	/**
	 * This function returns role id of logged in user.
	 * @param Return : ID
	 * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
	 */
	public static function returnAuthID()
	{
		return Auth::user()->id;
	}

	/**
	 * This function is used for ACL priviledge granting on which module to allow & which to not based on roles.
	 * @param RouteName : Name of the module
	 * @return boolean return true and false
	 * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
	 */
	public static function acl_privilege($routeName)
	{
		$roleName = self::get_loggedIn_role();
		$isAllow = self::check_module_privilege($roleName, $routeName);
		return $isAllow;
	}

	/**
	 * This function is used for ACL priviledge granting on which module to allow & which to not based on roles.
	 * @return Role : Shortcode
	 * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
	 */
	public static function get_loggedIn_role()
	{
		$roleName = self::returnAuthRole();
		$roleName = str_replace(' ', '_', strtolower($roleName));
		$roleName = str_replace(':', '', $roleName);
		$role = self::$roleMapper[$roleName];
		return $role;
	}

	/**
	 * This function is used for ACL priviledge granting on which module to allow & which to not based on roles.
	 * @param Role : shortcode
	 * @param RouteName : Name of the module
	 * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
	 */
	public static function check_module_privilege($role, $routeName)
	{
		$userProhibited = self::$modules_prohitibited[$role];
		$allow = self::accept;
		if (!empty($userProhibited) && in_array($routeName, $userProhibited)) {
			$allow = self::reject;
		}
		return $allow;
	}

	/**
	 * This function is used to restrict Sidebar for specific logins. [ Developed for future scope for developer ease ]
	 * @return boolean return true and false
	 * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
	 */
	public static function allowSideBar()
	{
		$roleName = self::returnAuthRole();
		$roleName = str_replace(' ', '_', strtolower($roleName));
		$roleName = str_replace(':', '', $roleName);
		$roleMappper = self::$roleMapper;
		$role = $roleMappper[$roleName];
		if ($role == 'shortcode') {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * This function returns role names dropdown from user role master.
	 * @param Return : Role Array
	 * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
	 */
	public static function getRoleMaster(): array
	{
		return UserRoleMaster::select('id', 'role_name', 'short_code')->where('is_active', self::active_user)->whereNotIn('id', [1])->get()->toArray();
	}

	/**
	 * This function returns dropdown of barcode types when called
	 * @param Return : Barcode type Array
	 * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
	 */
	public static function barcode_types(): array
	{
		return [
			1 => 'Visiting card size',
			2 => '9" X 13" Small size',
			3 => '13" X 16" Small size',
			4 => '13" X 16" Big size',
			5 => '17" X 20" Small size',
			6 => '17" X 20" Large size'
		];
	}

	/**
	 * This function returns dropdown of location master when called.
	 * @param Return : Location master Array
	 * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
	 */
	public static function getLocations(): array
	{
		return LocationMaster::select('id', 'location_name')->where('is_active', self::active_user)->get()->toArray();
	}

	/**
	 * This function returns dropdown of filters on landing pages.
	 * @param Return : Dropdowns
	 * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
	 */
	public static function filter_dropdowns(): array
	{
		return [
			'quality_dropdown' => [],
			'design_dropdown' => [],
			'shade_dropdown' => [],
		];
		return [
			'quality_dropdown' => ProductMaster::select('quality')->distinct()->get()->toArray(),
			'design_dropdown' => ProductMaster::select('design_name')->distinct()->get()->toArray(),
			'shade_dropdown' => ProductMaster::select('shade')->distinct()->get()->toArray()
		];
	}

	/**
	 * This function returns whitelisted IP Addresses of SUTLEJ connections
	 * @param Return : Whitelisted IP Addresses
	 * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
	 */
	public static function whitelisted_sutlej_ip_addresses($module_identity): array
	{
		$data = CustomConfig::select('event_metadata')->where('event_name', $module_identity)->first()->toArray();
		self::$whitelisted_ips_array = explode(",", $data['event_metadata']);
		return self::$whitelisted_ips_array;
	}

	/**
	 * This function returns custom configurations related data for managing backend activities by super admin only.
	 * @param Module-Identity
	 * @return Array
	 * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
	 */
	public static function custom_config_procedures($module_identity): array
	{
		$return = CustomConfig::select('is_active')->where('event_name', $module_identity)->get()->toArray();
		if (!empty($return)) {
			return $return[0];
		} else {
			return [];
		}
	}
}

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
use App\Models\RequestsTable;
use App\Models\Inward;
use App\Models\Outward;
use App\Models\ReportsMaster;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\Rule;
use App\Helpers\Helper;
use DataTables;
use App\Exports\UsersExport;
use App\Exports\SampleEfficiency;
use App\Exports\Stocks;
use App\Exports\Issuance;
use App\Exports\MSSQLDumps;
use App\Exports\CustomersDump;
use App\Exports\CustomersWishlist;
use App\Exports\ProductsWishlist;
use App\Exports\OrdersDump;
use App\Exports\ProductsOrders;
use App\Exports\CustomersQuantitySKU;
use App\Exports\ProductMasterMsSqlDump;
use App\Exports\RequestDetailsDump;
use App\Jobs\ExportProductsMasterTable;

class ReportsMasterController extends Controller
{
    /**
     * This controller is used as a master controller for all reports.
     * @access Rights : Admin,Sales Executives,Factory Employees
     * @param Array : Validation Declaration.
     * @param Constant : Declaration.
     * @param Parameter : Declaration.
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */

    const active_report = 1;
    const inactivate_report = 0;
    const sample_efficiency = 1;
    const stock = 2;
    const issuance = 3;
    const request_product = 4;
    const customer_master = 5;
    const order_master = 6;
    const customers_wishlist = 7;
    const products_wishlist = 8;
    const products_orders = 9;
    const customers_quantity_sku = 10;
    const request_details = 11;
    protected $date_bracket = null;
    protected $status = null;
    protected $code = null;
    protected $today = null;
    protected $rand_string = null;
    protected $role_master = [];
    protected $static_return = [
        'error' => 'Something went wrong,Try Again.',
        'error_code' => 'error'
    ];
    protected $report_types = [
        'web_report' => 'WEB',
        'ipad_report' => 'IPAD'
    ];

    /**
     * @Initialization constructor
     */
    public function __construct()
    {
        $this->date_bracket = Carbon::now();
        $this->today = Carbon::today()->toDateString();
        $this->role_master = Helper::getRoleMaster();
        $this->rand_string = rand(10, 1000);
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', -1);
    } // end : construct

    /**
     * This function is used for exporting users dump into excel using exports collection.
     * @return Excel
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    public function dump_users()
    {
        return Excel::download(new UsersExport, 'users_' . $this->today . '_' . $this->rand_string . '.xlsx');
    } // end : dump_users

    /**
     * This function is used for displaying dynamic report names on landing page of reports module.
     * @return View
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    public function show_reports()
    {
        $getReports = ReportsMaster::where('is_active', self::active_report)->where('report_type', $this->report_types['web_report'])->get()->toArray();
        $getIpadReports = ReportsMaster::where('is_active', self::active_report)->where('report_type', $this->report_types['ipad_report'])->get()->toArray();
        return view('Reports.show-reports', compact('getReports', 'getIpadReports'));
    } // end : dump_users

    /**
     * This function is used for generating reports. It is used as a generic function for all reports generation.
     * @param Request : $request object containing filters(If any), Unique report ID.
     * @return Excel
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    public function generic_reports_generation(Request $request)
    {
        try {
            switch ($request->hidden_report_id) {
                case self::sample_efficiency:
                    return Excel::download(new SampleEfficiency($request->from_date, $request->to_date), 'sample_efficiency_' . $this->today . '_' . $this->rand_string . '.xlsx');
                    break;
                case self::stock:
                    return Excel::download(new Stocks($request->quality, $request->design, $request->shade), 'stock_' . $this->today . '_' . $this->rand_string . '.xlsx');
                    break;
                case self::issuance:
                    return Excel::download(new Issuance($request->from_date, $request->to_date), 'issuance_' . $this->today . '_' . $this->rand_string . '.xlsx');
                    break;
                case self::request_product:
                    // return Excel::download(new MSSQLDumps($request->from_date,$request->to_date), 'products_requests_'.$this->today.'_'.$this->rand_string.'.xlsx');

                    // dispatch(new ExportProductsMasterTable($request->from_date, $request->to_date));
                    dispatch(new ExportProductsMasterTable($request->from_date, $request->to_date))->delay(now()->addSeconds(10));

                    return redirect()->back()->with('success', 'Export has been queued.');
                    break;
                case self::request_details:
                    return Excel::download(new RequestDetailsDump($request->quality, $request->design, $request->shade), 'request_details_' . $this->today . '_' . $this->rand_string . '.xlsx');
                    break;
                case self::customer_master:
                    return Excel::download(new CustomersDump($request->from_date, $request->to_date), 'customers_master_' . $this->today . '_' . $this->rand_string . '.xlsx');
                    break;
                case self::order_master:
                    return Excel::download(new OrdersDump($request->from_date, $request->to_date), 'orders_master_' . $this->today . '_' . $this->rand_string . '.xlsx');
                    break;
                case self::customers_wishlist:
                    return Excel::download(new CustomersWishlist($request->from_date, $request->to_date), 'customers_wishlist_' . $this->today . '_' . $this->rand_string . '.xlsx');
                    break;
                case self::products_wishlist:
                    return Excel::download(new ProductsWishlist($request->from_date, $request->to_date), 'products_wishlist_' . $this->today . '_' . $this->rand_string . '.xlsx');
                    break;
                case self::products_orders:
                    return Excel::download(new ProductsOrders($request->from_date, $request->to_date), 'products_orders_' . $this->today . '_' . $this->rand_string . '.xlsx');
                    break;
                case self::customers_quantity_sku:
                    return Excel::download(new CustomersQuantitySKU($request->from_date, $request->to_date), 'customers_quantity_sku_' . $this->today . '_' . $this->rand_string . '.xlsx');
                    break;
            }
        } catch (\Exception $e) {
            $this->code = $this->static_return['error_code'];
            $this->status = $this->static_return['error'];
            return redirect()->route('show_reports')->with($this->code, $this->status);
        }
    } // end : generic_reports_generation
}

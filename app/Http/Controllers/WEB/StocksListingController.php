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
use App\Models\ProductMaster;
use App\Models\Inward;
use App\Models\Outward;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\Rule;
use App\Helpers\Helper;
use DataTables;
use App\Http\Traits\GenericCloudComputing;

class StocksListingController extends Controller
{
    /**
     * This controller is used for Stocks listing module in web portal.
     * @access Rights : Admin,Sales Executives,Factory Employees
     * @param Array : Validation Declaration.
     * @param Constant : Declaration.
     * @param Parameter : Declaration.
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    use GenericCloudComputing;
    const inactivation_success = 66;
    const activation_success = 88;
    const inactive_product = 0;
    const active_product = 1;
    protected $date_bracket = null;
    protected $status = null;
    protected $code = null;
    protected $search_parameters = [];
    protected $stocks_array = [];
    protected $role_master = [];
    protected $checked_entries_data = [];
    protected $static_return = [
        'success_code' => 'success',
        'error_code' => 'error',
        'error' => 'Something went wrong,Try Again.',
        'Inactivate' => [
            'success' => 'Product inactivated successfully.',
        ]
    ];
    protected $cloud_space_folders = [
        'org' => 'original_images',
        'thumb' => 'thumbnail_images'
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
     * This function is used for loading stocks listing screen.
     * @return View : Stocks listing view.
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    public function showStocks()
    {
        return view('StocksListing.show-stocks-listing');
    } // end : showStocks

    /**
     * This function is used fetching the stocks data from our local table. Server side datatables are used here for faster & optimized performance.
     * @param Filters : If any applied / Default
     * @return Datatable : Request Data
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    protected function getStocksData(Request $request)
    {
        $this->search_parameters = $request->all();
        $this->stocks_array = DB::table('products_master')
            ->leftJoin(DB::raw('(SELECT 
            inward.unique_sku_id, 
            inward.location_id, 
            location_master.location_name,
            (SUM(inward.quantity) - COALESCE(SUM(outward.issued_quantity), 0)) as available_quantity
            FROM inward
            LEFT JOIN outward ON outward.unique_sku_id = inward.unique_sku_id
            INNER JOIN location_master ON inward.location_id = location_master.id
            GROUP BY inward.unique_sku_id) as inward_outward'), function ($join) {
                $join->on('inward_outward.unique_sku_id', '=', 'products_master.unique_sku_id');
            })
            ->leftJoin('requests', 'products_master.unique_sku_id', '=', 'requests.unique_sku_id')
            ->select(
                'products_master.id',
                'products_master.mssql_products_id',
                'products_master.unique_sku_id',
                'products_master.quality',
                'products_master.design_name',
                'products_master.shade',
                'products_master.is_active',
                DB::raw('COALESCE(available_quantity, 0) as available_quantity')
            );


        if (!empty($this->search_parameters['sku_id_filter'])) {
            $this->stocks_array = $this->stocks_array->where('products_master.unique_sku_id', $this->search_parameters['sku_id_filter']);
        }
        if (!empty($this->search_parameters['quality_filter'])) {
            $this->stocks_array = $this->stocks_array->where('products_master.quality', $this->search_parameters['quality_filter']);
        }
        if (!empty($this->search_parameters['design_filter'])) {
            $this->stocks_array = $this->stocks_array->where('products_master.design_name', $this->search_parameters['design_filter']);
        }
        if (!empty($this->search_parameters['shade_filter'])) {
            $this->stocks_array = $this->stocks_array->where('products_master.shade', $this->search_parameters['shade_filter']);
        }
        $this->stocks_array = $this->stocks_array->groupBy('products_master.unique_sku_id')->orderBy('requests.delivery_date', 'desc');
        return DataTables::of($this->stocks_array)
            ->addColumn('checkbox', function ($item) {
                return '<label style="text-align: center;" class="m-checkbox m-checkbox--focus"><input class="form-check-input" value="' . $item->id . '" type="checkbox" location-id="0" sku-id="' . $item->unique_sku_id . '" name="select_checkbox[]"><span></span></label>';
            })
            ->addColumn('thumbnail', function ($item) {
                return '<a href="#" data-target="#show_product_image_modal" id="show_original_image_modal1" onclick=show_product_image("' . $item->unique_sku_id . '") data-toggle="m-tooltip" value="" title="View original image"><img src="' . $this->generic_image_retrieval($this->cloud_space_folders['thumb'], $item->unique_sku_id, null) . '" style="width: 100px;" /></a>';
            })
            ->addColumn('active_status', function ($item) {
                return ($item->is_active == 1) ? '<i class="fa fa-check-circle" style="color:green"></i>' : '<i class="fa fa-window-close" style="color:red"></i>';
            })
            ->rawColumns(['checkbox', 'available_quantity', 'thumbnail', 'active_status'])
            ->make(true);
    } // end : getStocksData

    /**
     * This function is used to fetch the product image from cloud on click of thumbnail -> Hyperlink functionality.
     * @param Request : $request object : sku_id
     * @return View : Request details on HTML page -> AJAX
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    protected function showProductImageStocks(Request $request)
    {
        try {
            $image_URL = $this->generic_image_retrieval($this->cloud_space_folders['thumb'], $request->sku_id, null);
            return view('RequestListing.Ajax.product-image', compact('image_URL'));
        } catch (\Exception $e) {
            return $e;
        }
    } // end : showProductImageStocks

    /**
     * This function is used to perform activation & inactivation of products.
     * @param Request : $request object : checked entries, module_identity
     * @return Constants: Success / Failure
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    protected function actionsProcedure(Request $request)
    {
        try {
            switch ($request->identity) {
                case 'INACTIVATE':
                    ProductMaster::whereIn('id', $request->checked_entries)
                        ->update([
                            'is_active' => self::inactive_product
                        ]);
                    return self::inactivation_success;
                    break;
                case 'ACTIVATE':
                    ProductMaster::whereIn('id', $request->checked_entries)
                        ->update([
                            'is_active' => self::active_product
                        ]);
                    return self::activation_success;
                    break;
            }
        } catch (\Exception $e) {
            return $e;
        }
    } // end : actionsProcedure
}

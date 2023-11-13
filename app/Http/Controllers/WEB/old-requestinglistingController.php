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
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\Rule;
use App\Helpers\Helper;
use DataTables;
use App\Http\Traits\GenericCloudComputing;

class RequestListingController extends Controller
{
    /**
     * This controller is used for Request listing module in web portal.
     * @access Rights : Admin,Sales Executives,Factory Employees
     * @param Array : Validation Declaration.
     * @param Constant : Declaration.
     * @param Parameter : Declaration.
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    use GenericCloudComputing;
    const outward_ineligible = 0;
    const outward_eligibile_flag = 55;
    const outward_ineligible_flag = 99;
    const no_quantity_flag = 88;
    const inward_ineligible = null;
    const inward_ineligible_flag = 44;
    const inward_eligible_flag = 66;
    protected $date_bracket = null;
    protected $status = null;
    protected $code = null;
    protected $search_parameters = [];
    protected $requests_array = [];
    protected $role_master = [];
    protected $checked_entries_data = [];
    protected $inward_array = [];
    protected $generated_barcodes = [];
    protected $edit_inward_ids = [];
    protected $check_outward_eligibility = [];
    protected $inward_quantity_array = [];
    protected $outward_array = [];
    protected $available_quantity_check = [];
    protected $check_inward_eligibility = [];
    protected $barcode_status_array = [];
    protected $static_return = [
        'success_code' => 'success',
        'error_code' => 'error',
        'error' => 'Something went wrong,Try Again.',
        'Inward' => [
            'success' => 'Inward generated successfully.',
            'update' => 'Inwards updated successfully.'
        ],
        'Outward' => [
            'success' => 'Outward generated successfully.'
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
     * This function is used for loading request listing screen as landing page for the module.
     * @return View : Request listing view.
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    public function showScreen()
    {
        $filters = [];
        if (session()->has('filters')) {
            $filters['request_id_search'] = session()->get('filters')['request_id_search'];
            $filters['sku_id_search'] = session()->get('filters')['sku_id_search'];
            $filters['barcode_search'] = session()->get('filters')['barcode_search'];
            $filters['quality_search'] = session()->get('filters')['quality_search'];
            $filters['design_search'] = session()->get('filters')['design_search'];
            $filters['shade_search'] = session()->get('filters')['shade_search'];
            session()->forget('filters');
        }
        return view('RequestListing.show-listing', compact('filters'));
    } // end : showScreen

    /**
     * This function is used fetching the requests data from our local table. Server side datatables are used here for faster & optimized performance.
     * @param Filters : If any applied / Default
     * @return Datatable : Request Data
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    protected function getRequestsData(Request $request)
    {
        $this->search_parameters = $request->all();
        $this->requests_array = DB::table('requests')
            ->select(
                'requests.id',
                'requests.request_no',
                'requests.unique_sku_id',
                'requests.quality_name',
                'requests.design_name',
                'requests.shade_name',
                'requests.delivery_date',
                'requests.barcode',
                'requests.requirement',
                'l.location_name',
                'l.id as location_id',
                'requests.print_design',
                'requests.print_colorway',
                'requests.emb_design',
                'requests.emb_colorway',
                'requests.emb_vendor',
                DB::raw('COALESCE(i.total_quantity, 0) as total_quantity'),
                DB::raw('COALESCE(o.total_outward_quantity, 0) as total_outward_quantity'),
                DB::raw('CASE WHEN o.total_outward_quantity IS NULL THEN COALESCE(i.total_quantity, 0) ELSE COALESCE(i.total_quantity - o.total_outward_quantity, 0) END as available_quantity')
            )
            ->leftJoin(DB::raw('
            (SELECT inward.request_id, inward.unique_sku_id, inward.location_id, COALESCE(SUM(inward.quantity), 0) as total_quantity
                FROM inward
                INNER JOIN location_master l ON inward.location_id = l.id
                GROUP BY inward.request_id, inward.unique_sku_id, inward.location_id) as i'), function ($join) {
                $join->on('requests.id', '=', DB::raw('i.request_id'))
                    ->on('requests.unique_sku_id', '=', DB::raw('i.unique_sku_id'));
            })
            ->leftJoin(DB::raw('
            (SELECT request_id, unique_sku_id, location_id, COALESCE(SUM(outward.issued_quantity), 0) as total_outward_quantity
                FROM outward
                GROUP BY request_id, unique_sku_id, location_id) as o'), function ($join) {
                $join->on('requests.id', '=', DB::raw('o.request_id'))
                    ->on('requests.unique_sku_id', '=', DB::raw('o.unique_sku_id'));
            })
            ->leftJoin(DB::raw('
        (SELECT location_master.location_name, location_master.id
            FROM location_master) as l'), function ($join) {
                $join->on(DB::raw('i.location_id'), '=', DB::raw('l.id'));
            });
        // return response($this->requests_array);
        if (!empty($this->search_parameters['request_id_filter'])) {
            $this->requests_array = $this->requests_array->where('request_no', $this->search_parameters['request_id_filter']);
        }
        if (!empty($this->search_parameters['sku_id_filter'])) {
            $this->requests_array = $this->requests_array->where('requests.unique_sku_id', $this->search_parameters['sku_id_filter']);
        }
        if (!empty($this->search_parameters['barcode_filter'])) {
            $this->requests_array = $this->requests_array->where('barcode', $this->search_parameters['barcode_filter']);
        }
        if (!empty($this->search_parameters['quality_filter'])) {
            $this->requests_array = $this->requests_array->where('quality_name', $this->search_parameters['quality_filter']);
        }
        if (!empty($this->search_parameters['design_filter'])) {
            $this->requests_array = $this->requests_array->where('design_name', $this->search_parameters['design_filter']);
        }
        if (!empty($this->search_parameters['shade_filter'])) {
            $this->requests_array = $this->requests_array->where('shade_name', $this->search_parameters['shade_filter']);
        }
        if (!empty($this->search_parameters['emb_vendor_filter'])) {
            $this->requests_array = $this->requests_array->where('emb_vendor', $this->search_parameters['emb_vendor_filter']);
        }
        if (!empty($this->search_parameters['print_design_filter'])) {
            $this->requests_array = $this->requests_array->where('print_design', $this->search_parameters['print_design_filter']);
        }
        if (!empty($this->search_parameters['print_colorway_filter'])) {
            $this->requests_array = $this->requests_array->where('print_colorway', $this->search_parameters['print_colorway_filter']);
        }
        if (!empty($this->search_parameters['emb_design_filter'])) {
            $this->requests_array = $this->requests_array->where('emb_design', $this->search_parameters['emb_design_filter']);
        }
        if (!empty($this->search_parameters['emb_colorway_filter'])) {
            $this->requests_array = $this->requests_array->where('emb_colorway', $this->search_parameters['emb_colorway_filter']);
        }
        if (!empty($this->search_parameters['emb_vendor_filter'])) {
            $this->requests_array = $this->requests_array->where('emb_vendor', $this->search_parameters['emb_vendor_filter']);
        }
        $this->requests_array = $this->requests_array->latest();
        return DataTables::of($this->requests_array)
            ->addColumn('checkbox', function ($item) {
                // if (!empty($item->location_id)) {
                //     return '<label style="text-align: center;" class="m-checkbox m-checkbox--focus"><input class="form-check-input" value="' . $item->id . '" type="checkbox" location-id=' . $item->location_id . ' sku-id="' . $item->unique_sku_id . '" name="select_checkbox[]"><span></span></label>'
                // } else {
                //     return '<label style="text-align: center;" class="m-checkbox m-checkbox--focus"><input class="form-check-input" value="' . $item->id . '" type="checkbox" location-id="0" sku-id="' . $item->unique_sku_id . '" name="select_checkbox[]"><span></span></label>';
                // }

                return json_encode($item, true);
            })
            ->addColumn('thumbnail', function ($item) {
                $item = json_decode(json_encode($item), true);
                $item['generic_image_retrieval'] = $this->generic_image_retrieval($this->cloud_space_folders['thumb'], $item['unique_sku_id'], null);

                return $item;
            })
            ->editColumn('delivery_date', function ($item) {
                return ($item->delivery_date != '') ? date("jS F,  Y", strtotime($item->delivery_date)) : '<i class="fa fa-minus"></i>';
            })
            ->editColumn('barcode', function ($item) {
                return ($item->barcode != '') ? '<i class="fa fa-check-circle" style="color:green"></i>' : '<i class="fa fa-window-close" style="color:red"></i>';
            })
            ->editColumn('request_no', function ($item) {
                return '<a href="#" data-target="#show_request_modal" id="show_request_modal1" onclick="return show_request(' . $item->id . ');" data-toggle="m-tooltip" value="' . $item->id . '" title="View details">' . $item->request_no . '</a>';
            })
            ->editColumn('location_name', function ($item) {
                return ($item->location_name != '') ? $item->location_name : '<i class="fa fa-minus"></i>';
            })
            ->editColumn('total_quantity', function ($item) {
                return ($item->total_quantity != '') ? $item->total_quantity : '<i class="fa fa-minus"></i>';
            })
            ->editColumn('print_design', function ($item) {
                return ($item->print_design != '') ? $item->print_design : '<i class="fa fa-minus"></i>';
            })
            ->editColumn('print_colorway', function ($item) {
                return ($item->print_colorway != '') ? $item->print_colorway : '<i class="fa fa-minus"></i>';
            })
            ->editColumn('emb_design', function ($item) {
                return ($item->emb_design != '') ? $item->emb_design : '<i class="fa fa-minus"></i>';
            })
            ->editColumn('emb_colorway', function ($item) {
                return ($item->emb_colorway != '') ? $item->emb_colorway : '<i class="fa fa-minus"></i>';
            })
            ->editColumn('emb_vendor', function ($item) {
                return ($item->emb_vendor != '') ? $item->emb_vendor : '<i class="fa fa-minus"></i>';
            })
            ->addColumn('edit_inward_action', function ($item) {
                if (Helper::acl_privilege('request_listing_write_access')) {
                    if (!empty($item->location_name)) {
                        if (floatval($item->total_outward_quantity) > 0) {
                            return '<a href="#" data-target="#edit_inward_modal" class="edit_inward_data" id="edit_inward_data" data-toggle="m-tooltip" title="Edit Inward" onclick=edit_inward_data("' . $item->unique_sku_id . '","NO",' . $item->location_id . ',' . $item->id . ')><i class="fas fa-edit"></i></a>';
                        } else {
                            return '<a href="#" data-target="#edit_inward_modal" class="edit_inward_data" id="edit_inward_data" data-toggle="m-tooltip" title="Edit Inward" onclick=edit_inward_data("' . $item->unique_sku_id . '","YES",' . $item->location_id . ',' . $item->id . ')><i class="fas fa-edit"></i></a>';
                        }
                    } else {
                        return '<i class="fa fa-minus"></i>';
                    }
                } else {
                    return '<i class="fa fa-minus"></i>';
                }
            })
            ->rawColumns(['checkbox', 'barcode', 'request_no', 'location_name', 'total_quantity', 'total_outward_quantity', 'available_quantity', 'edit_inward_action', 'delivery_date', 'thumbnail', 'print_design', 'print_colorway', 'emb_design', 'emb_colorway', 'emb_vendor'])
            ->make(true);
    } // end : getRequestsData

    /**
     * This function is used to fetch the request details from requests master based on selected ID -> Hyperlink functionality.
     * @param Request : $request object : request_id
     * @return View : Request details on HTML page -> AJAX
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    protected function showRequestDetails(Request $request)
    {
        try {
            $request_Data = RequestsTable::where('id', $request->request_id)
                ->select('request_no as Request Number', 'unique_sku_id as Unique SKU ID', 'quality_name as Quality', 'design_name as Design', 'shade_name as Shade', 'requirement as Requirement', 'designer_name as Designer Name', 'sample_length as Sample Length', 'barcode as Barcode Status', 'delivery_date as Delivery Date', 'print_design as Print Design', 'print_colorway as Print Colorway', 'emb_design as Emb Design', 'emb_colorway as Emb Colorway', 'emb_vendor as Emb Vendor')->get()->toArray();
            return view('RequestListing.Ajax.request-details-table', compact('request_Data'));
        } catch (\Exception $e) {
            return $e;
        }
    } // end : showRequestDetails

    /**
     * This function is used to fetch the product image from cloud on click of thumbnail -> Hyperlink functionality.
     * @param Request : $request object : sku_id
     * @return View : Request details on HTML page -> AJAX
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    protected function showProductImage(Request $request)
    {
        try {
            $image_URL = $this->generic_image_retrieval($this->cloud_space_folders['thumb'], $request->sku_id, null);
            return view('RequestListing.Ajax.product-image', compact('image_URL'));
        } catch (\Exception $e) {
            return $e;
        }
    } // end : showProductImage

    /**
     * This function is used to generate Inward modal data which is called via Ajax.
     * @param Array : Selected Checkbox IDs
     * @return View : Table & Dropdown
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    protected function getInwardModalData(Request $request)
    {

        $checked_entries = RequestsTable::select('id', 'request_no', 'unique_sku_id', 'quality_name', 'design_name', 'shade_name', 'barcode', 'requirement')
            ->where('barcode', '!=', null)
            ->whereIn('id', $request->checked_entries)->get()->toArray();

        if (empty($checked_entries)) {
            return self::inward_ineligible_flag;
        }
        return view('RequestListing.Ajax.inward-modal-table-data', compact('checked_entries'));
    } // end : getInwardModalData



    /**
     * This function is used to generate Inwards against request ID's
     * @param Array : Selected Checkbox IDs
     * @return View : Success / Failure message
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    protected function generateInwards(Request $request)
    {
        try {
            $this->checked_entries_data = json_decode($request->hidden_checked_entries, true);
            foreach ($this->checked_entries_data as $key => $request_id) {
                $this->inward_array[] = [
                    'request_id' => $request_id,
                    'unique_sku_id' => $request->unique_sku_id[$request_id],
                    'location_id' => $request->location_master[$request_id],
                    'quantity' => $request->quantity[$request_id],
                    'remarks' => $request->remarks[$request_id],
                    'created_by' => Helper::returnAuthID(),
                    'created_at' => Carbon::now(),
                    'last_modified_by' => Helper::returnAuthID(),
                    'updated_at' => Carbon::now()
                ];
            }

            Inward::insert($this->inward_array);
            unset($this->inward_array);
            $this->code = $this->static_return['success_code'];
            $this->status = $this->static_return['Inward']['success'];
            $this->storefiltersInSession($request);
            return redirect()->route('showScreen')->with($this->code, $this->status);
        } catch (\Exception $e) {

            $this->code = $this->static_return['error_code'];
            $this->status = $this->static_return['error'];
            return redirect()->route('showScreen')->with($this->code, $this->status);
        }
    } // end : generateInwards

    /**
     * This function is used to edit inwards & called via Ajax
     * @param UniqueSKU : Identification
     * @return View : Inward details on HTML page -> AJAX
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    protected function showInwardDetails(Request $request)
    {
        try {
            $inward_Data = Inward::where('unique_sku_id', $request->unique_sku_id)
                ->where('location_id', $request->location_iD)
                ->where('request_id', $request->request_iD)
                ->select('id as inward_id', 'unique_sku_id', 'location_id', 'quantity', 'remarks', 'created_at', 'updated_at')->get()->toArray();
            return view('RequestListing.Ajax.edit-inward-modal-table', compact('inward_Data'));
        } catch (\Exception $e) {
            return $e;
        }
    } // end : showInwardDetails

    /**
     * This function is used to save edited inward data in MYSQL
     * @param Edited-Array : Array
     * @return View : Show Screen with success or failure message
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    protected function editInwardData(Request $request)
    {
        try {
            $this->edit_inward_ids = $request->inward_id;
            foreach ($this->edit_inward_ids as $key => $inward_id) {
                Inward::where('id', '=', $inward_id)
                    ->update([
                        'location_id' => $request->location_master[$inward_id],
                        'quantity' => $request->quantity[$inward_id],
                        'remarks' => $request->remarks[$inward_id],
                        'last_modified_by' => Helper::returnAuthID(),
                        'updated_at' => Carbon::now()
                    ]);
            }
            $this->code = $this->static_return['success_code'];
            $this->status = $this->static_return['Inward']['update'];
            $this->storefiltersInSession($request);
            return redirect()->route('showScreen')->with($this->code, $this->status);
        } catch (\Exception $e) {
            $this->code = $this->static_return['error_code'];
            $this->status = $this->static_return['error'];
            return redirect()->route('showScreen')->with($this->code, $this->status);
        }
    } // end : editInwardData

    /**
     * This function is used to generate barcode modal data which is called via Ajax.
     * @param Array : Selected Checkbox IDs
     * @return View : Table & Dropdown
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    protected function getBarcodeModalData(Request $request)
    {
        $checked_entries = RequestsTable::select('request_no', 'unique_sku_id', 'quality_name', 'design_name', 'shade_name')
            ->whereIn('id', $request->checked_entries)->groupBy(['unique_sku_id', 'id'])->get()->toArray();
        return view('RequestListing.Ajax.barcode-modal-table-data', compact('checked_entries'));
    } // end : getBarcodeModalData

    /**
     * This function is used to generate one dimensional barcode stickers which is called via Ajax.
     * @param Array : Selected Checkbox IDs
     * @return View : One dimensional barcodes.
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    protected function generateBarcodes(Request $request)
    {
        try {
            $this->checked_entries_data = json_decode($request->hidden_checked_entries, true);
            $barcode_type = $request->barcode_type;
            $collection_input = $request->collection_input;
            $barcode_data = RequestsTable::leftJoin('products_master', 'requests.unique_sku_id', '=', 'products_master.unique_sku_id')->select('requests.unique_sku_id', 'requests.quality_name', 'requests.design_name', 'requests.shade_name', 'products_master.finish_width', 'products_master.gsm', DB::raw("CASE WHEN (products_master.Composition IS NOT NULL) THEN (products_master.Composition) ELSE 'NA' END as composition"), 'requests.request_no', 'products_master.end_use')
                ->whereIn('requests.id', $this->checked_entries_data)->get()->toArray();
            // DB::raw("'70% Polyester' as composition")
            $this->update_requests_barcodes($this->checked_entries_data);
            $this->storefiltersInSession($request);
            return view('RequestListing.Ajax.show-barcodes', compact('barcode_data', 'barcode_type', 'collection_input'));
        } catch (\Exception $e) {
            return $e;
        }
    } // end : generateBarcodes

    /**
     * This function is used to update the request entries whose barcodes are generated along with delivery date.
     * @param Array : Selected Checkbox IDs
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    protected function update_requests_barcodes($checked_entries)
    {
        try {
            $this->generated_barcodes = RequestsTable::whereIn('id', $checked_entries)
                ->select('unique_sku_id', 'id', 'barcode', 'delivery_date')
                ->get()->toArray();
            foreach ($this->generated_barcodes as $value) {
                RequestsTable::where('id', $value['id'])->whereNull('barcode')->whereNull('delivery_date')
                    ->update([
                        'barcode' => $value['unique_sku_id'],
                        'delivery_date' => Carbon::now()
                    ]);
            }
        } catch (\Exception $e) {
            return $e;
        }
    } // end : update_requests_barcodes

    /**
     * This function is used to generate Outward modal data which is called via Ajax.
     * @param Array : Selected Checkbox IDs
     * @return View : Table & Dropdown
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    protected function getOutwardModalData(Request $request)
    {
        $checked_entries = [];
        foreach ($request->checked_entries as $key => $value) {
            $data[] = DB::table('requests')
                ->leftJoin(DB::raw('(select inward.request_id, inward.unique_sku_id, inward.location_id, location_master.location_name,COALESCE(SUM(inward.quantity),0) as total_quantity,(select COALESCE(sum(issued_quantity),0) from outward where request_id=inward.request_id and unique_sku_id=inward.unique_sku_id and location_id=inward.location_id)as total_outward_quantity,(COALESCE(SUM( inward.quantity),0)-(select COALESCE(sum(issued_quantity),0) from outward where request_id=inward.request_id and unique_sku_id=inward.unique_sku_id and location_id=inward.location_id)) as available_quantity from inward  inner join location_master on inward.location_id = location_master.id group by inward.unique_sku_id, inward.location_id,inward.request_id) as inward_outward'), function ($join) {
                    $join->on('inward_outward.unique_sku_id', '=', 'requests.unique_sku_id');
                    $join->on('requests.id', '=', 'inward_outward.request_id');
                })
                ->select('requests.id', 'requests.request_no', 'requests.unique_sku_id', 'requests.quality_name', 'requests.design_name', 'requests.shade_name', 'requests.delivery_date', 'requests.barcode', 'location_name', 'location_id', 'requests.requirement')
                ->selectRaw(DB::raw('COALESCE(total_quantity,0) as total_quantity'))
                ->selectRaw(DB::raw('COALESCE(total_outward_quantity,0) as total_outward_quantity'))
                ->selectRaw(DB::raw('COALESCE(available_quantity,0) as available_quantity'))
                ->where('available_quantity', '>', self::outward_ineligible)
                ->where('total_quantity', '>', self::outward_ineligible)->where('location_id', $value['location_id'])->where('requests.unique_sku_id', $value['sku_id'])->where('requests.id', $value['request_id'])->get()->toArray();
        }
        foreach ($data as $value) {
            if (!empty($value)) {
                $checked_entries[] = $value;
            }
        }
        if (!array_filter($checked_entries)) {
            return self::outward_ineligible_flag;
        }
        // ->whereIn('requests.id',$request->checked_entries)
        return view('RequestListing.Ajax.outward-modal-table-data', compact('checked_entries'));
    } // end : getOutwardModalData


    /**
     * This function is used to generate outwards process.
     * @param Request : $request object 
     * @return View : Show Screen with success or failure message
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    protected function generateOutwards(Request $request)
    {
        try {
            $data = $request->all();
            foreach ($data['quantity'] as $sku => $location) {
                foreach ($location as $location_id => $request) {
                    foreach ($request as $request_id => $data_value) {
                        $issued_to_data = $data['issued_to'][$sku][$location_id];
                        $remarks_data = $data['remarks'][$sku][$location_id];
                        $this->outward_array[] = array(
                            'request_id' => $request_id,
                            'unique_sku_id' => $sku,
                            'location_id' => $location_id,
                            'issued_to' => $issued_to_data[key($issued_to_data)],
                            'issued_quantity' => $request[$request_id],
                            'issued_date' => Carbon::now(),
                            'remarks' => $remarks_data[key($remarks_data)],
                            'created_by' => Helper::returnAuthID(),
                            'created_at' => Carbon::now(),
                            'last_modified_by' => Helper::returnAuthID(),
                            'updated_at' => Carbon::now()
                        );
                    }
                }
            }
            Outward::insert($this->outward_array);
            unset($this->outward_array);
            $this->code = $this->static_return['success_code'];
            $this->status = $this->static_return['Outward']['success'];
            $this->storefiltersInSession($data);
            return redirect()->route('showScreen')->with($this->code, $this->status);
        } catch (\Exception $e) {
            $this->code = $this->static_return['error_code'];
            $this->status = $this->static_return['error'];
            return redirect()->route('showScreen')->with($this->code, $this->status);
        }
    } // end : generateOutwards


    /**
     * This function is used to store filters in session.
     * @param data : $data object 
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    protected function storefiltersInSession($data)
    {
        $temp['request_id_search'] = $data['filter_request_id_search'];
        $temp['sku_id_search'] = $data['filter_sku_id_search'];
        $temp['barcode_search'] = $data['filter_barcode_search'];
        $temp['quality_search'] = $data['filter_quality_search'];
        $temp['design_search'] = $data['filter_design_search'];
        $temp['shade_search'] = $data['filter_shade_search'];
        session(['filters' => $temp]);
        unset($temp);
    } // end : storefiltersInSession

}

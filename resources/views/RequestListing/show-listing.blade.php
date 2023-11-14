@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <!-- BEGIN: Subheader -->
    <div class="m-subheader ">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
                    <li class="m-nav__item m-nav__item--home">
                        <a href="{{ Route('showScreen') }}" class="m-nav__link m-nav__link--icon">
                            <i class="m-nav__link-icon la la-home"></i>
                        </a>
                    </li>
                    <li class="m-nav__separator">
                        -
                    </li>
                    <li class="m-nav__item">
                        <a href="" class="m-nav__link">
                            <span class="m-nav__link-text">
                                Request Listing
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- END: Subheader -->
    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Request Listing
                        </h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                    <ul class="m-portlet__nav">
                        <li class="m-portlet__nav-item">
                            <div class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover" aria-expanded="true">
                                <a onclick="window.location.href=this" data-toggle="m-tooltip" title="Refresh filters" class="m-portlet__nav-link btn btn-lg btn-secondary  m-btn m-btn--icon m-btn--icon-only m-btn--pill  m-dropdown__toggle">
                                    <i class="fa fa-refresh" aria-hidden="true"></i>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            @if(App\Helpers\Helper::acl_privilege('request_listing_write_access'))
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption col-sm-12 d-flex justify-content-center">
                    <button type="button" data-target="#inward_modal" id="inward_process" onclick="return generate_inward_procedures();" class="btn btn-primary btn-submit">INWARD</button>
                    <button type="button" data-target="#print_barcode_modal" id="print_barcode" onclick="return print_barcode_procedures();" class="btn btn-primary btn-submit">PRINT BARCODE</button>
                    <button type="button" data-target="#outward_modal" onclick="return generate_outward_procedures();" id="outward_process" class="btn btn-primary btn-submit">OUTWARD</button>
                </div>
            </div>
            @endif
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption col-sm-12">
                    <div class="row wd_input_100">
                        <div class="col-md-2"><i class="fa-search fa" style="position: absolute;left: 44px; top: 12px;color: #808080;"></i>
                            <input name="request_id_search" style="text-indent: 50px; padding-top: 11px; padding-bottom: 11px; border: 1px solid black; border-radius: 7px;" class="fa" placeholder="Request ID" type="text" id="request_id_search" value="{{isset($filters['request_id_search'])?$filters['request_id_search']:''}}" />
                        </div>
                        <div class="col-md-2"><i class="fa-search fa" style="position: absolute;left: 44px; top: 12px;color: #808080;"></i>
                            <input name="sku_id_search" style="text-indent: 50px; padding-top: 11px; padding-bottom: 11px; border: 1px solid black; border-radius: 7px;" class="fa" placeholder="SKU ID" type="text" id="sku_id_search" value="{{isset($filters['sku_id_search'])?$filters['sku_id_search']:''}}" />
                        </div>

                        @php $filterDropdowns = App\Helpers\Helper::filter_dropdowns(); @endphp

                        <div class="col-md-2">
                            <!-- <input name="quality_search" style="text-indent: 50px;" class="fa" placeholder="Quality" type="text" id="quality_search" /> -->
                            <select name="quality_search" id="quality_search" class="js-example-basic-single btn btn-primary dropdown-toggle quality select2" style="color: black !important;width: auto;display: inline-block;">
                                <option value="0"> <i style="color: #808080;margin-right: 1%;margin-left: 2%;" class='fa fa-search' /> Quality</option>
                                <!-- @isset($filterDropdowns['quality_dropdown'])
                                @foreach($filterDropdowns['quality_dropdown'] as $quality_array)
                                @foreach($quality_array as $quality)
                                <option value="{{ $quality }}" {{(isset($filters['quality_search'])&&$quality==$filters['quality_search'])?'selected':''}}>{{ $quality }}</option>
                                @endforeach
                                @endforeach
                                @endisset -->
                            </select>
                        </div>
                        <div class="col-md-2">
                            <!-- <input name="design_search" style="text-indent: 50px;" class="fa" placeholder="Design" type="text" id="design_search" /> -->
                            <select name="design_search" id="design_search" class="js-example-basic-single btn btn-primary dropdown-toggle  design select2" style="color: black !important;width: auto;display: inline-block;">
                                <option value="0"> <i style="color: #808080;margin-right: 1%;margin-left: 2%;" class='fa-search fa'></i> Design</option>
                                <!-- @isset($filterDropdowns['design_dropdown'])
                                @foreach($filterDropdowns['design_dropdown'] as $design_array)
                                @foreach($design_array as $design)
                                <option value="{{ $design }}" {{(isset($filters['design_search'])&&$design==$filters['design_search'])?'selected':''}}>{{ $design }}</option>
                                @endforeach
                                @endforeach
                                @endisset -->
                            </select>
                        </div>
                        <div class="col-md-2">
                            <!-- <input name="shade_search" style="text-indent: 50px;" class="fa" placeholder="Shade" type="text" id="shade_search" /> -->
                            <select name="shade_search" id="shade_search" class="js-example-basic-single btn btn-primary dropdown-toggle shade select2" style="color: black !important;width: auto;display: inline-block;">
                                <option value="0"> <i style="color: #808080;margin-right: 1%;margin-left: 2%;" class='fa-search fa'></i> Shade</option>
                                <!-- @isset($filterDropdowns['shade_dropdown'])
                                @foreach($filterDropdowns['shade_dropdown'] as $shade_array)
                                @foreach($shade_array as $shade)
                                <option value="{{ $shade }}" {{(isset($filters['shade_search'])&&$shade==$filters['shade_search'])?'selected':''}}>{{ $shade }}</option>
                                @endforeach
                                @endforeach
                                @endisset -->
                            </select>
                        </div>
                        <div class="col-md-2"><i class="fa-search fa" style="position: absolute;left: 44px; top: 12px;color: #808080;"></i>
                            <input name="barcode_search" style="text-indent: 50px; padding-top: 11px; padding-bottom: 11px; border: 1px solid black; border-radius: 7px;" class="fa" placeholder="Barcode" type="text" id="barcode_search" value="{{isset($filters['barcode_search'])?$filters['barcode_search']:''}}" />
                        </div>
                    </div>

                </div>
            </div>

            <div class="m-portlet__head">
                <div class="m-portlet__head-caption col-sm-12">
                    <div class="row wd_input_100" style="justify-content: space-evenly">
                        <div class="col-md-2">
                            <i class="fa-search fa" style="position: absolute;left: 44px; top: 12px;color: #808080;"></i>
                            <input name="emb_vendor_search" style="text-indent: 50px; padding-top: 11px; padding-bottom: 11px; border: 1px solid black; border-radius: 7px;" class="fa" placeholder="Emb Vendor" type="text" id="emb_vendor_search" value="{{isset($filters['emb_vendor_search'])?$filters['emb_vendor_search']:''}}" />
                        </div>
                        <div class="col-md-2">
                            <i class="fa-search fa" style="position: absolute;left: 44px; top: 12px;color: #808080;"></i>
                            <input name="print_design_search" style="text-indent: 50px; padding-top: 11px; padding-bottom: 11px; border: 1px solid black; border-radius: 7px;" class="fa" placeholder="Print Design" type="text" id="print_design_search" value="{{isset($filters['print_design_search'])?$filters['print_design_search']:''}}" />
                        </div>
                        <div class="col-md-2">
                            <i class="fa-search fa" style="position: absolute;left: 44px; top: 12px;color: #808080;"></i>
                            <input name="print_colorway_search" style="text-indent: 50px; padding-top: 11px; padding-bottom: 11px; border: 1px solid black; border-radius: 7px;" class="fa" placeholder="Print Colorway" type="text" id="print_colorway_search" value="{{isset($filters['print_colorway_search'])?$filters['print_colorway_search']:''}}" />
                        </div>
                        <div class="col-md-2">
                            <i class="fa-search fa" style="position: absolute;left: 44px; top: 12px;color: #808080;"></i>
                            <input name="emb_design_search" style="text-indent: 50px; padding-top: 11px; padding-bottom: 11px; border: 1px solid black; border-radius: 7px;" class="fa" placeholder="Emb Design" type="text" id="emb_design_search" value="{{isset($filters['emb_design_search'])?$filters['emb_design_search']:''}}" />
                        </div>
                        <div class="col-md-2">
                            <i class="fa-search fa" style="position: absolute;left: 44px; top: 12px;color: #808080;"></i>
                            <input name="emb_colorway_search" style="text-indent: 50px; padding-top: 11px; padding-bottom: 11px; border: 1px solid black; border-radius: 7px;" class="fa" placeholder="Emb Colorway" type="text" id="emb_colorway_search" value="{{isset($filters['emb_colorway_search'])?$filters['emb_colorway_search']:''}}" />
                        </div>
                    </div>
                </div>
            </div>

            {{-- @php $filterDropdowns = App\Helpers\Helper::filter_dropdowns(); @endphp --}}

            {{-- <div class="m-portlet__head">
                    <div class="m-portlet__head-caption col-sm-12">
                        <div class="row">
                            <div class="col-md-2">
                                <!-- <input name="quality_search" style="text-indent: 50px;" class="fa" placeholder="Quality" type="text" id="quality_search" /> -->
                                <select name="quality_search" id="quality_search" class="js-example-basic-single btn btn-primary dropdown-toggle quality select2" style="color: black !important;width: auto;display: inline-block;">
                                    <option value="0"> <i style="color: #808080;margin-right: 1%;margin-left: 2%;" class='fa-search fa'></i> Quality</option>
                                    <!-- @isset($filterDropdowns['quality_dropdown'])
                                        @foreach($filterDropdowns['quality_dropdown'] as $quality_array)
                                            @foreach($quality_array as $quality)
                                                <option value="{{ $quality }}"
            {{(isset($filters['quality_search'])&&$quality==$filters['quality_search'])?'selected':''}}


            >{{ $quality }}</option>
            @endforeach
            @endforeach
            @endisset -->
            </select>
        </div>
        <div class="col-md-2">
            <!-- <input name="design_search" style="text-indent: 50px;" class="fa" placeholder="Design" type="text" id="design_search" /> -->
            <select name="design_search" id="design_search" class="js-example-basic-single btn btn-primary dropdown-toggle design select2" style="color: black !important;width: auto;display: inline-block;">
                <option value="0"> <i style="color: #808080;margin-right: 1%;margin-left: 2%;" class='fa-search fa'></i> Design</option>
                <!-- @isset($filterDropdowns['design_dropdown'])
                @foreach($filterDropdowns['design_dropdown'] as $design_array)
                @foreach($design_array as $design)
                <option value="{{ $design }}" {{(isset($filters['design_search'])&&$design==$filters['design_search'])?'selected':''}}>{{ $design }}</option>
                @endforeach
                @endforeach
                @endisset -->
            </select>
        </div>
        <div class="col-md-2">
            <!-- <input name="shade_search" style="text-indent: 50px;" class="fa" placeholder="Shade" type="text" id="shade_search" /> -->
            <select name="shade_search" id="shade_search" class="js-example-basic-single btn btn-primary dropdown-toggle shade select2" style="color: black !important;width: auto;display: inline-block;">
                <option value="0"> <i style="color: #808080;margin-right: 1%;margin-left: 2%;" class='fa-search fa'></i> Shade</option>
                <!-- @isset($filterDropdowns['shade_dropdown'])
                @foreach($filterDropdowns['shade_dropdown'] as $shade_array)
                @foreach($shade_array as $shade)
                <option value="{{ $shade }}" {{(isset($filters['shade_search'])&&$shade==$filters['shade_search'])?'selected':''}}>{{ $shade }}</option>
                @endforeach
                @endforeach
                @endisset -->
            </select>
        </div>
    </div>
</div>
</div> --}}

<div class="m-portlet__body">
    <!--begin: Datatable -->
    <div class="report-datatable">
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table style="text-align: center !important;" class="table table-striped  request_listing_DT">
                        <thead>
                            <tr>
                                <th><label style="text-align: center;" class="m-checkbox m-checkbox--focus"><input class="form-check-input" value="" type="checkbox" onclick="return select_all_checkboxes(this);" id="select_checkbox_all" name="select_checkbox_all"><span></span></label></th>
                                <th>Thumbnail</th>
                                <th>Request No</th>
                                <th nowrap>Unique SKU ID</th>
                                <th nowrap>Quality</th>
                                <th nowrap>Design</th>
                                <th nowrap>Shade</th>
                                <th nowrap>Requirement</th>
                                <th nowrap>Barcode Status</th>
                                <th nowrap>Delivery Date</th>
                                <th nowrap>Location Name</th>
                                <th nowrap>Inward Quantity</th>
                                <th nowrap>Outward Quantity</th>
                                <th nowrap>Available Quantity</th>
                                <th nowrap>Print Design</th>
                                <th nowrap>Print Colorway</th>
                                <th nowrap>Emb Design</th>
                                <th nowrap>Emb Colorway</th>
                                <th nowrap>Emb Vendor</th>
                                <th nowrap>Action &nbsp;&nbsp;&nbsp;&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--end: Datatable -->
</div>
</div>
@include('RequestListing.Modals.show-request-details-modal')
@include('RequestListing.Modals.show-inward-modal')
@include('RequestListing.Modals.show-edit-inward-modal')
@include('RequestListing.Modals.show-barcode-modal')
@include('RequestListing.Modals.show-outward-modal')
@include('RequestListing.Modals.show-product-image-modal')
</div>
</div>
@endsection
@section('scripts')
@parent
<script type="text/javascript">
    $(document).ready(function() {


        // CODE TO SHOW THE LIST OF DROPDOWNS FROM SELECT 2 THROUGH AJAX - QUALITY
        $('.js-example-basic-single.btn.btn-primary.dropdown-toggle.quality.select2').ready(function() {
            $('.js-example-basic-single.btn.btn-primary.dropdown-toggle.quality.select2').select2({
                placeholder: "Please select a quality",
                allowClear: true,
                ajax: {
                    url: "{{ route('get-quality-dropdown') }}",
                    data: function(params) {
                        var query = {
                            search: params.term,
                            type: 'quality'
                        }
                        console.log('query - ', query);
                        return query;
                    }
                }
            })
        });
        // CODE TO SHOW THE LIST OF DROPDOWNS FROM SELECT 2 THROUGH AJAX - DESIGN
        // CODE TO SHOW THE LIST OF DROPDOWNS FROM SELECT 2 THROUGH AJAX - DESIGN
        $('.js-example-basic-single.btn.btn-primary.dropdown-toggle.design.select2').ready(function() {
            $('.js-example-basic-single.btn.btn-primary.dropdown-toggle.design.select2').select2({
                placeholder: "Please select a design-name",
                allowClear: true,
                ajax: {
                    url: "{{ route('get-design-dropdown') }}",
                    data: function(params) {
                        var query = {
                            search: params.term,
                        }
                        console.log('query - ', query);
                        return query;
                    }
                }
            })
        });
        // CODE TO SHOW THE LIST OF DROPDOWNS FROM SELECT 2 THROUGH AJAX - SHADE
        $('.js-example-basic-single.btn.btn-primary.dropdown-toggle.shade.select2').ready(function() {
            $('.js-example-basic-single.btn.btn-primary.dropdown-toggle.shade.select2').select2({
                placeholder: "Please select a shade",
                allowClear: true,
                ajax: {
                    url: "{{ route('get-shade-dropdown') }}",
                    data: function(params) {
                        var query = {
                            search: params.term,
                        }
                        console.log('query - ', query);
                        return query;
                    }
                }
            })
        });

        @if($status = Session::get('success'))
        swal({
            title: "Success!",
            text: "{{ $status }}",
            type: "success"
        });
        @endif
        @if($status = Session::get('error'))
        swal({
            title: "Failed!",
            text: "{{ $status }}",
            type: "error"
        });
        @endif
        $('.js-example-basic-single').select2({
            escapeMarkup: function(markup) {
                return markup;
            }
        });
        $('.select2-selection__rendered').unbind('mouseenter mouseleave');
        $('.select2-selection__rendered').hover(function() {
            $(this).removeAttr('title');
        });

        /**
         * Serverside JS Datatable for request listing used for optimized performance, with dynamic run-time added parameters as per clients requirements.
         * @param Filters : Request No, SKU ID, Barcodes, Quality, Design, Shade | Default : Blank
         * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
         */
        var requests_datatable = $('.request_listing_DT').DataTable({
            "scrollY": "300px",
            "scrollCollapse": true,
            "sScrollX": "100%",
            processing: true,
            serverSide: true,
            "sDom": "ltipr",
            "targets": 'no-sort',
            "bSort": false,
            "order": [],
            "iDisplayLength": 10,
            ajax: {
                url: "{{ route('getRequestsData') }}",
                method: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                data: function(filter) {
                    // filter.request_id_filter = '';
                    // filter.sku_id_filter = '';
                    // filter.barcode_filter = '';
                    // filter.quality_filter = '';
                    // filter.design_filter = '';
                    // filter.shade_filter = '';
                    var dt_params = $('.request_listing_DT').data('dt_params');
                    if (dt_params) {
                        $.extend(filter, dt_params);
                    }
                }
            },
            columns: [{
                    data: 'checkbox',
                    name: 'checkbox',
                    sortable: false,
                    // render: function(item, type, row) {
                    //     item = JSON.parse(item);
                    //     console.log("checkboc - ", item.id);

                    //     if (!(item.location_id)) {
                    //         return `<label style="text-align: center;" class="m-checkbox m-checkbox--focus"><input class="form-check-input" value="${item.id}" type="checkbox" location-id=${item.location_id}
                    //      sku-id="${item.unique_sku_id}" name="select_checkbox[]"><span></span></label>`;
                    //     } else {
                    //         return `<label style="text-align: center;" class="m-checkbox m-checkbox--focus"><input class="form-check-input" value="${item.id}" type="checkbox" location-id="0" sku-id="${item.unique_sku_id}" name="select_checkbox[]"><span></span></label>`;
                    //     }
                    // },
                },
                {
                    data: 'thumbnail',
                    name: 'thumbnail',
                    sortable: false,
                    // render: function(item, type, row) {
                    //     // item = JSON.parse(item);
                    //     console.log(item.generic_image_retrieval);
                    //     console.log("thumbnail - ", item);
                    //     return `<a href="#" data-target="#show_product_image_modal" id="show_original_image_modal1" onclick=show_product_image("${item.unique_sku_id}") data-toggle="m-tooltip" value="" title="View original image"><img src="${item.generic_image_retrieval}" style="width: 100px;" /></a>`;
                    // }
                },
                {
                    data: 'request_no',
                    name: 'request_no',
                    sortable: false
                },
                {
                    data: 'unique_sku_id',
                    name: 'requests.unique_sku_id',
                    sortable: false
                },
                {
                    data: 'quality_name',
                    name: 'quality_name',
                    sortable: false
                },
                {
                    data: 'design_name',
                    name: 'design_name',
                    sortable: false
                },
                {
                    data: 'shade_name',
                    name: 'shade_name',
                    sortable: false
                },
                {
                    data: 'requirement',
                    name: 'requirement',
                    sortable: false
                },
                {
                    data: 'barcode',
                    name: 'barcode',
                    sortable: false
                },
                {
                    data: 'delivery_date',
                    name: 'delivery_date',
                    sortable: true
                },
                {
                    data: 'location_name',
                    name: 'location_name',
                    sortable: false
                },
                {
                    data: 'total_quantity',
                    name: 'total_quantity',
                    sortable: false
                },
                {
                    data: 'total_outward_quantity',
                    name: 'total_outward_quantity',
                    sortable: false
                },
                {
                    data: 'available_quantity',
                    name: 'available_quantity',
                    sortable: false
                },
                {
                    data: 'print_design',
                    name: 'print_design',
                    sortable: false
                },
                {
                    data: 'print_colorway',
                    name: 'print_colorway',
                    sortable: false
                },
                {
                    data: 'emb_design',
                    name: 'emb_design',
                    sortable: false
                },
                {
                    data: 'emb_colorway',
                    name: 'emb_colorway',
                    sortable: false
                },
                {
                    data: 'emb_vendor',
                    name: 'emb_vendor',
                    sortable: false
                },
                {
                    data: 'edit_inward_action',
                    name: 'edit_inward_action',
                    sortable: false
                }
            ]
        });


        if ($('#sku_id_search').val() != '' || $('#request_id_search').val() != '' || $('#barcode_search').val() != '' || $('#quality_search').val() != 0 || $('#design_search').val() != 0 || $('#shade_search').val() != 0 || $('#emb_vendor_search').val() != '' || $('#print_design_search').val() != '' || $('#print_colorway_search').val() != '' || $('#emb_design_search').val() != '' || $('#emb_colorway_search').val() != '' || $('#emb_vendor_search').val() != '') {
            var request_search = $('#request_id_search').val();
            var sku_search = $('#sku_id_search').val();
            var barcode_search = $('#barcode_search').val();
            var quality_search = $('#quality_search').val();
            var design_search = $('#design_search').val();
            var shade_search = $('#shade_search').val();
            var emb_vendor_search = $('#emb_vendor_search').val();
            var print_design_search = $('#print_design_search').val();
            var print_colorway_search = $('#print_colorway_search').val();
            var emb_design_search = $('#emb_design_search').val();
            var emb_colorway_search = $('#emb_colorway_search').val();
            var emb_vendor_search = $('#emb_vendor_search').val();
            $('.request_listing_DT').data('dt_params', {
                request_id_filter: request_search,
                sku_id_filter: sku_search,
                barcode_filter: barcode_search,
                quality_filter: quality_search,
                design_filter: design_search,
                shade_filter: shade_search,
                emb_vendor_filter: emb_vendor_search,
                print_design_filter: print_design_search,
                print_colorway_filter: print_colorway_search,
                emb_design_filter: emb_design_search,
                emb_colorway_filter: emb_colorway_search,
                emb_vendor_filter: emb_vendor_search
            });
            requests_datatable.draw();

        }


        $("#request_id_search, #sku_id_search, #barcode_search, #emb_vendor_search, #print_design_search, #print_colorway_search, #emb_design_search, #emb_colorway_search, #emb_vendor_search").blur(function() {
            var request_search = $('#request_id_search').val();
            var sku_search = $('#sku_id_search').val();
            var barcode_search = $('#barcode_search').val();
            var quality_search = $('#quality_search').val();
            var design_search = $('#design_search').val();
            var shade_search = $('#shade_search').val();
            var emb_vendor_search = $('#emb_vendor_search').val();
            var print_design_search = $('#print_design_search').val();
            var print_colorway_search = $('#print_colorway_search').val();
            var emb_design_search = $('#emb_design_search').val();
            var emb_colorway_search = $('#emb_colorway_search').val();
            var emb_vendor_search = $('#emb_vendor_search').val();
            $('.request_listing_DT').data('dt_params', {
                request_id_filter: request_search,
                sku_id_filter: sku_search,
                barcode_filter: barcode_search,
                quality_filter: quality_search,
                design_filter: design_search,
                shade_filter: shade_search,
                emb_vendor_filter: emb_vendor_search,
                print_design_filter: print_design_search,
                print_colorway_filter: print_colorway_search,
                emb_design_filter: emb_design_search,
                emb_colorway_filter: emb_colorway_search,
                emb_vendor_filter: emb_vendor_search,
            });
            requests_datatable.draw();
        });
        $("#quality_search, #design_search, #shade_search").change(function() {
            var request_search = $('#request_id_search').val();
            var sku_search = $('#sku_id_search').val();
            var barcode_search = $('#barcode_search').val();
            var quality_search = $('#quality_search').val();
            var design_search = $('#design_search').val();
            var shade_search = $('#shade_search').val();
            $('.request_listing_DT').data('dt_params', {
                request_id_filter: request_search,
                sku_id_filter: sku_search,
                barcode_filter: barcode_search,
                quality_filter: quality_search,
                design_filter: design_search,
                shade_filter: shade_search
            });
            requests_datatable.draw();
        });
    });

    /**
     * This function is used for returning common swal errors on Inward, Print Barcode & Outward procedures.
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    function generic_swal_errors(condition) {
        switch (condition) {
            case 'Buttons Eligibility':
                return swal({
                    title: "Validation",
                    text: "Select atleast one entry to perform this action.",
                    type: "error"
                });
                break;
            case 'Outward Ineligible':
                return swal({
                    title: "Ineligible",
                    text: "Select only those entries that have completed inward procedure!",
                    type: "error"
                });
                break;
            case 'Edit Inward Ineligible':
                return swal({
                    title: "Ineligible",
                    text: "You cannot edit this inward!",
                    type: "error"
                });
                break;
            case 'No Available Quantity':
                return swal({
                    title: "Ineligible",
                    text: "No quantity available to perform outward procedure!",
                    type: "error"
                });
                break;
            case 'Server Error':
                return swal({
                    title: "Failed",
                    text: "Something went wrong,Try again.",
                    type: "error"
                });
                break;
            case 'Inward Eligibility':
                return swal({
                    title: "Ineligible",
                    text: "Ineligible InWard",
                    type: "error"
                });
                break;
        }

    }

    /**
     * This function is used on hyperlink functionality to show request details in modal based on request id.
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    function show_request(request_id) {
        if (request_id != '') {
            $.ajax({
                url: '{{ route("showRequestDetails") }}',
                type: "POST",
                data: {
                    '_token': "{{ csrf_token() }}",
                    'request_id': request_id
                },
                success: function(result) {
                    $("#request_details_modal_data").html(result);
                    $("#hidden_request_id").val(request_id);
                    $('#show_request_modal').modal('show');
                }
            });
        } else {
            generic_swal_errors('Server Error');
        }
    }

    /**
     * This function is used on hyperlink functionality to show product image on click of thumbnail.
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    function show_product_image(sku_id) {
        if (sku_id != '') {
            $.ajax({
                url: '{{ route("showProductImage") }}',
                type: "POST",
                data: {
                    '_token': "{{ csrf_token() }}",
                    'sku_id': sku_id
                },
                success: function(result) {
                    $("#product_image_modal_data").html(result);
                    $("#hidden_sku_id").val(sku_id);
                    $('#show_product_image_modal').modal('show');
                }
            });
        } else {
            generic_swal_errors('Server Error');
        }
    }

    /**
     * This function is used to select all peer checkboxes on click.
     * @param Selected checkboxes
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    function select_all_checkboxes(selected) {
        $('input:checkbox').prop('checked', selected.checked);
    }

    /**
     * This function is used generically to get request ID's of all selected entries.
     * @return Selected checkboxes -> Request ID's
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    function generic_checked_entries(get_location = false) {
        if (get_location) {
            var checked_entries = [];
            $("[name='select_checkbox[]']:checked").each(function() {
                if ($(this).val() != "") {
                    checked_entries.push({
                        request_id: $(this).val(),
                        location_id: $(this).attr('location-id'),
                        sku_id: $(this).attr('sku-id'),
                    });
                }
            });
            return checked_entries;
        } else {
            var checked_entries = [];
            $("[name='select_checkbox[]']:checked").each(function() {
                if ($(this).val() != "") {
                    checked_entries.push($(this).val());
                }
            });
            return checked_entries;
        }
    }

    /**
     * This function is used to perform Inward functionality.
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    function generate_inward_procedures() {
        var checkbox_count = $("[name='select_checkbox[]']:checked").length;
        if (checkbox_count > 0) {
            var checked_entries = generic_checked_entries();
            checked_entries = checked_entries.filter((item, i, ar) => ar.indexOf(item) === i);
            $.ajax({
                url: '{{ route("getInwardModalData") }}',
                type: "POST",
                data: {
                    '_token': "{{ csrf_token() }}",
                    'checked_entries': checked_entries
                },
                success: function(result) {
                    if (result != 44) {
                        $("#inward_modal_data").html(result);
                        $('#filter_inward_request_id_search').val($('#request_id_search').val());
                        $('#filter_inward_sku_id_search').val($('#sku_id_search').val());
                        $('#filter_inward_barcode_search').val($('#barcode_search').val());
                        $('#filter_inward_quality_search').val($('#quality_search').val());
                        $('#filter_inward_design_search').val($('#design_search').val());
                        $('#filter_inward_shade_search').val($('#shade_search').val());
                        $('#inward_modal').modal('show');
                    } else {
                        generic_swal_errors('Inward Eligibility');
                    }
                }
            });
        } else {
            generic_swal_errors('Buttons Eligibility');
        }
    }

    /**
     * This function is used to edit Inward details.
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    function edit_inward_data(unique_sku_id, check_eligibility, location_ID, request_ID) {
        if (unique_sku_id != '') {
            if (check_eligibility == 'YES') {
                $.ajax({
                    url: '{{ route("showInwardDetails") }}',
                    type: "POST",
                    data: {
                        '_token': "{{ csrf_token() }}",
                        'unique_sku_id': unique_sku_id,
                        'location_iD': location_ID,
                        'request_iD': request_ID
                    },
                    success: function(result) {
                        $("#edit_inward_modal_data").html(result);
                        $("#hidden_unique_sku_id").val(unique_sku_id);
                        $('#filter_edit_request_id_search').val($('#request_id_search').val());
                        $('#filter_edit_sku_id_search').val($('#sku_id_search').val());
                        $('#filter_edit_barcode_search').val($('#barcode_search').val());
                        $('#filter_edit_quality_search').val($('#quality_search').val());
                        $('#filter_edit_design_search').val($('#design_search').val());
                        $('#filter_edit_shade_search').val($('#shade_search').val());
                        $('#edit_inward_modal').modal('show');
                    }
                });
            } else {
                generic_swal_errors('Edit Inward Ineligible');
            }
        } else {
            generic_swal_errors('Server Error');
        }
    }

    /**
     * This function is used to perform print barcode functionality.
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    function print_barcode_procedures() {
        var checkbox_count = $("[name='select_checkbox[]']:checked").length;
        if (checkbox_count > 0) {
            var checked_entries = generic_checked_entries();
            $.ajax({
                url: '{{ route("getBarcodeModalData") }}',
                type: "POST",
                data: {
                    '_token': "{{ csrf_token() }}",
                    'checked_entries': checked_entries
                },
                success: function(result) {
                    $("#barcode_modal_data").html(result);
                    $("#hidden_checked_entries").val(JSON.stringify(checked_entries));
                    $('#filter_barcode_request_id_search').val($('#request_id_search').val());
                    $('#filter_barcode_sku_id_search').val($('#sku_id_search').val());
                    $('#filter_barcode_barcode_search').val($('#barcode_search').val());
                    $('#filter_barcode_quality_search').val($('#quality_search').val());
                    $('#filter_barcode_design_search').val($('#design_search').val());
                    $('#filter_barcode_shade_search').val($('#shade_search').val());
                    $('#print_barcode_modal').modal('show');
                }
            });
        } else {
            generic_swal_errors('Buttons Eligibility');
        }
    }

    /**
     * This function is used to perform Outward functionality.
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    function generate_outward_procedures() {
        var checkbox_count = $("[name='select_checkbox[]']:checked").length;
        if (checkbox_count > 0) {
            var checked_entries = generic_checked_entries(true);
            $.ajax({
                url: '{{ route("getOutwardModalData") }}',
                type: "POST",
                data: {
                    '_token': "{{ csrf_token() }}",
                    'checked_entries': checked_entries
                },
                success: function(result) {
                    if (result == 99) {
                        generic_swal_errors('Outward Ineligible');
                        return false;
                    } else if (result == 88) {
                        generic_swal_errors('No Available Quantity');
                        return false;
                    } else {
                        $("#outward_modal_data").html(result);
                        $('#filter_outward_request_id_search').val($('#request_id_search').val());
                        $('#filter_outward_sku_id_search').val($('#sku_id_search').val());
                        $('#filter_outward_barcode_search').val($('#barcode_search').val());
                        $('#filter_outward_quality_search').val($('#quality_search').val());
                        $('#filter_outward_design_search').val($('#design_search').val());
                        $('#filter_outward_shade_search').val($('#shade_search').val());
                        $('#outward_modal').modal('show');
                    }
                }
            });
        } else {
            generic_swal_errors('Buttons Eligibility');
        }
    }
</script>
@endsection
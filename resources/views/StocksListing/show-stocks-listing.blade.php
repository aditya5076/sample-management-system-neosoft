@extends('layouts.app')

@section('content')
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
                                Stocks Listing
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
                            Stocks Listing
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
                    <button type="button" id="activate_products" onclick="return active_products_procedure();" class="btn btn-primary btn-submit">ACTIVATE PRODUCTS</button>
                    <button type="button" style="background-color: #dc3545;border-color: #dc3545;" id="inactivate_products" onclick="return inactive_products_procedure();" class="btn btn-primary btn-submit">INACTIVATE PRODUCTS</button>
                </div>
            </div>
            @endif
            @php $filterDropdowns = App\Helpers\Helper::filter_dropdowns(); @endphp
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption col-sm-12">
                    <div class="row">
                        <div class="col-md-4">
                            <i class="fa-search fa" style="position: absolute;left: 44px; top: 12px;color: #808080;"></i>
                            <input name="sku_id_search" style="text-indent: 50px;" class="fa" placeholder="SKU ID" type="text" id="sku_id_search" />
                        </div>
                        <div class="col-md-4"></i>
                        </div>
                        <div class="col-md-4">
                            <select name="quality_search" id="quality_search" class="js-example-basic-single btn btn-primary dropdown-toggle quality select2" style="color: black !important;width: auto;display: inline-block;">
                                <option value="0"> <i style="color: #808080;margin-right: 1%;margin-left: 2%;" class='fa-search fa'></i> Quality</option>
                                <!-- @isset($filterDropdowns['quality_dropdown'])
                                @foreach($filterDropdowns['quality_dropdown'] as $quality_array)
                                @foreach($quality_array as $quality)
                                <option value="{{ $quality }}">{{ $quality }}</option>
                                @endforeach
                                @endforeach
                                @endisset -->
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption col-sm-12">
                    <div class="row">

                        <div class="col-md-4">
                            <!-- <input name="design_search" style="text-indent: 50px;" class="fa" placeholder="Design" type="text" id="design_search" /> -->
                            <select name="design_search" id="design_search" class="js-example-basic-single btn btn-primary dropdown-toggle design select2" style="color: black !important;width: auto;display: inline-block;">
                                <option value="0">
                                    <i style="color: #808080;margin-right: 1%;margin-left: 2%;" class='fa-search fa'></i>
                                    Design
                                </option>
                                <!-- @isset($filterDropdowns['design_dropdown'])
                                @foreach($filterDropdowns['design_dropdown'] as $design_array)
                                @foreach($design_array as $design)
                                <option value="{{ $design }}">{{ $design }}</option>
                                @endforeach
                                @endforeach
                                @endisset -->
                            </select>
                        </div>
                        <div class="col-md-4"></i>

                        </div>
                        <div class="col-md-4">
                            <!-- <input name="shade_search" style="text-indent: 50px;" class="fa" placeholder="Shade" type="text" id="shade_search" /> -->
                            <select name="shade_search" id="shade_search" class="js-example-basic-single btn btn-primary dropdown-toggle shade select2" style="color: black !important;width: auto;display: inline-block;">
                                <option value="0"> <i style="color: #808080;margin-right: 1%;margin-left: 2%;" class='fa-search fa'></i> Shade</option>
                                <!-- @isset($filterDropdowns['shade_dropdown'])
                                @foreach($filterDropdowns['shade_dropdown'] as $shade_array)
                                @foreach($shade_array as $shade)
                                <option value="{{ $shade }}">{{ $shade }}</option>
                                @endforeach
                                @endforeach
                                @endisset -->
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">
                <!--begin: Datatable -->
                <div class="report-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table style="text-align: center !important;" class="table table-striped request_listing_DT">
                                    <thead>
                                        <tr>
                                            <th><label style="text-align: center;" class="m-checkbox m-checkbox--focus"><input class="form-check-input" value="" type="checkbox" onclick="return select_all_checkboxes(this);" id="select_checkbox_all" name="select_checkbox_all"><span></span></label></th>
                                            <th>Thumbnail</th>
                                            <th nowrap>Unique SKU ID</th>
                                            <th nowrap>Quality</th>
                                            <th nowrap>Design</th>
                                            <th nowrap>Shade</th>
                                            <th nowrap>Available Quantity</th>
                                            <th nowrap>Active Status</th>
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
         * @param Filters : SKU ID, Quality, Design, Shade | Default : Blank
         * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
         */
        var requests_datatable = $('.request_listing_DT').DataTable({
            "scrollY": "300px",
            "scrollCollapse": true,
            processing: true,
            serverSide: true,
            "sDom": "ltipr",
            "targets": 'no-sort',
            "bSort": false,
            "order": [],
            "iDisplayLength": 10,
            "columnDefs": [{
                "width": "1%",
                "targets": 0
            }],
            ajax: {
                url: "{{ route('getStocksData') }}",
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                data: function(filter) {
                    // filter.sku_id_filter = '';
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
                    sortable: false
                },
                {
                    data: 'thumbnail',
                    name: 'thumbnail',
                    sortable: false
                },
                {
                    data: 'unique_sku_id',
                    name: 'requests.unique_sku_id',
                    sortable: false
                },
                {
                    data: 'quality',
                    name: 'quality',
                    sortable: false
                },
                {
                    data: 'design_name',
                    name: 'design_name',
                    sortable: false
                },
                {
                    data: 'shade',
                    name: 'shade',
                    sortable: false
                },
                {
                    data: 'available_quantity',
                    name: 'available_quantity',
                    sortable: false
                },
                {
                    data: 'active_status',
                    name: 'active_status',
                    sortable: false
                }
            ]
        });

        $("#sku_id_search").blur(function() {
            var sku_search = $('#sku_id_search').val();
            var quality_search = $('#quality_search').val();
            var design_search = $('#design_search').val();
            var shade_search = $('#shade_search').val();
            $('.request_listing_DT').data('dt_params', {
                sku_id_filter: sku_search,
                quality_filter: quality_search,
                design_filter: design_search,
                shade_filter: shade_search
            });
            requests_datatable.draw();
        });
        $("#quality_search, #design_search, #shade_search").change(function() {
            var sku_search = $('#sku_id_search').val();
            var quality_search = $('#quality_search').val();
            var design_search = $('#design_search').val();
            var shade_search = $('#shade_search').val();
            $('.request_listing_DT').data('dt_params', {
                sku_id_filter: sku_search,
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
            case 'Activation Success':
                return swal({
                    title: "Success!",
                    text: "Products activated successfully.",
                    type: "success"
                }, function() {
                    window.location.reload();
                }, 2000);
                break;
            case 'Inactivation Success':
                return swal({
                    title: "Success!",
                    text: "Products in-activated successfully.",
                    type: "success"
                }, function() {
                    window.location.reload();
                }, 2000);
                break;
            case 'CANCEL POPUP':
                return swal("Cancelled", "", "error");
                break;
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
    function generic_checked_entries() {
        var checked_entries = [];
        $("[name='select_checkbox[]']:checked").each(function() {
            if ($(this).val() != "") {
                checked_entries.push($(this).val());
            }
        });
        return checked_entries;
    }

    /**
     * This function is used to perform inactivation of products functionality.
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    function inactive_products_procedure() {
        var checkbox_count = $("[name='select_checkbox[]']:checked").length;
        if (checkbox_count > 0) {
            var checked_entries = generic_checked_entries();
            swal({
                    title: "Are you sure?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes, Submit!",
                    cancelButtonText: "No, Cancel!",
                    closeOnConfirm: false,
                    closeOnCancel: false
                },
                function(isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            url: '{{ route("actionsProcedure") }}',
                            type: "POST",
                            data: {
                                '_token': "{{ csrf_token() }}",
                                'checked_entries': checked_entries,
                                'identity': 'INACTIVATE'
                            },
                            success: function(result) {
                                if (result == 66) {
                                    generic_swal_errors('Inactivation Success');
                                } else {
                                    generic_swal_errors('Server Error');
                                }
                            }
                        });
                    } else {
                        generic_swal_errors('CANCEL POPUP');
                    }
                });
        } else {
            generic_swal_errors('Buttons Eligibility');
        }
    }

    /**
     * This function is used to perform activation of products functionality.
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    function active_products_procedure() {
        var checkbox_count = $("[name='select_checkbox[]']:checked").length;
        if (checkbox_count > 0) {
            var checked_entries = generic_checked_entries();
            swal({
                    title: "Are you sure?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes, Submit!",
                    cancelButtonText: "No, Cancel!",
                    closeOnConfirm: false,
                    closeOnCancel: false
                },
                function(isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            url: '{{ route("actionsProcedure") }}',
                            type: "POST",
                            data: {
                                '_token': "{{ csrf_token() }}",
                                'checked_entries': checked_entries,
                                'identity': 'ACTIVATE'
                            },
                            success: function(result) {
                                if (result == 88) {
                                    generic_swal_errors('Activation Success');
                                } else {
                                    generic_swal_errors('Server Error');
                                }
                            }
                        });
                    } else {
                        generic_swal_errors('CANCEL POPUP');
                    }
                });
        } else {
            generic_swal_errors('Buttons Eligibility');
        }
    }
</script>
@endsection
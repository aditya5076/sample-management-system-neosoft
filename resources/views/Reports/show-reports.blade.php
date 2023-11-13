@extends('layouts.app')

@section('content')
<div class="loader-wrapper">
    <div class="loader">
    </div>
</div>
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
                                Reports
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- END: Subheader -->
    @php $filterDropdowns = App\Helpers\Helper::filter_dropdowns(); @endphp
    <!-- BEGIN: WEB REPORTS -->
    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            WEB Reports
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
            <div class="m-portlet__body">
                <!--begin: Datatable -->
                <div class="report-datatable">

                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table style="text-align: center !important;" id="reports_DT" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Report Name</th>
                                            <th>Filters</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($getReports))
                                        @foreach($getReports as $data)
                                        @if(is_object($data))
                                        @php $data=(array)$data @endphp
                                        @endif
                                        <tr>
                                            <form class="reports_form_{{$data['id']}}" action="{{ Route('generic_reports_generation') }}" method="POST">
                                                @csrf
                                                <td nowrap align="left"><b>{{ Str::upper($data['report_name']) }}<b></td>
                                                @if($data['id'] == 2 || $data['id'] == 11)
                                                <td style="width: 630px !important;">
                                                    <div class="report_filter_dropdown" style="width: 190px;display: inline;">
                                                        <select name="quality" id="quality-dropdown-id" class="js-example-basic-single btn btn-primary dropdown-toggle select2" style="color: black !important;width: auto;display: inline-block;">
                                                            <option value="0">
                                                                <i style="color: #808080;margin-right: 1%;margin-left: 2%;" class="fa-search fa"></i>
                                                                Quality
                                                            </option>
                                                            <!-- @isset($filterDropdowns['quality_dropdown'])
                                                            @foreach($filterDropdowns['quality_dropdown'] as $quality_array)
                                                            @foreach($quality_array as $quality)
                                                            <option value="{{ $quality }}">{{ $quality }}</option>
                                                            @endforeach
                                                            @endforeach
                                                            @endisset -->
                                                        </select>
                                                    </div>
                                                    <div class="report_filter_dropdown" style="width: 190px;display: inline;">
                                                        <select name="design" id="design-dropdown-id" class=" quality" style="color: black !important;width: auto;display: inline-block;">
                                                            <option value="0"> <i style="color: #808080;margin-right: 1%;margin-left: 2%;" class='fa-search fa'></i> Design</option>
                                                            <!-- @isset($filterDropdowns['design_dropdown'])
                                                            @foreach($filterDropdowns['design_dropdown'] as $design_array)
                                                            @foreach($design_array as $design)
                                                            <option value="{{ $design }}">{{ $design }}</option>
                                                            @endforeach
                                                            @endforeach
                                                            @endisset -->


                                                        </select>
                                                    </div>
                                                    <div class="report_filter_dropdown" style="width: 190px;display: inline;">
                                                        <select name="shade" id="shade-dropdown-id" class=" quality" style="color: black !important;width: auto;display: inline-block;">
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
                                                </td>
                                                @else
                                                <td nowrap>
                                                    <div style="width: 190px; display: inline; position: relative;"><i class="fa-search fa" style="position: absolute;left: 44px;top: 2px;color: #808080;"></i>
                                                        <input name="from_date" onkeydown="return false" autocomplete="off" class="fa m_datepicker_4_3" placeholder="From-Date" style="text-align: center;width: inherit;" type="text" />
                                                    </div>
                                                    <div style="width: 190px; display: inline; position: relative;"><i class="fa-search fa" style="position: absolute;left: 44px;top: 2px;color: #808080;"></i>
                                                        <input name="to_date" onkeydown="return false" autocomplete="off" class="fa m_datepicker_4_3_1" placeholder="To-Date" style="text-align: center;width: inherit;" type="text" />
                                                    </div>
                                                </td>
                                                @endif
                                                <td nowrap>
                                                    <a class="generic_action" href="#" data-toggle="m-tooltip" title="Download"><i class="fa fa-download" aria-hidden="true"></i></a>
                                                    <input type="hidden" name="hidden_report_id" value="{{ $data['id'] }}">
                                                </td>
                                            </form>
                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end: Datatable -->
            </div>
        </div>
    </div>
    <!-- END: WEB REPORTS -->
    @if(App\Helpers\Helper::acl_privilege('ipad_reports'))
    <!-- START: IPAD REPORTS -->
    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            IPAD Reports
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
            <div class="m-portlet__body">
                <!--begin: Datatable -->
                <div class="report-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table style="text-align: center !important;" id="reports_DT_1" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th style="text-align: left !important;">Report Name</th>
                                            <th>Filters</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($getIpadReports))
                                        @foreach($getIpadReports as $data)
                                        @if(is_object($data))
                                        @php $data=(array)$data @endphp
                                        @endif
                                        <tr>
                                            <form class="reports_form_{{$data['id']}}" action="{{ Route('generic_reports_generation') }}" method="POST">
                                                @csrf
                                                <td nowrap align="left"><b>{{ Str::upper($data['report_name']) }}<b></td>
                                                <td nowrap>
                                                    <div style="width: 190px; display: inline; position: relative;"><i class="fa-search fa" style="position: absolute;left: 44px;top: 2px;color: #808080;"></i>
                                                        <input name="from_date" onkeydown="return false" autocomplete="off" class="fa m_datepicker_4_3" placeholder="From-Date" style="text-align: center;width: inherit;" type="text" />
                                                    </div>
                                                    <div style="width: 190px; display: inline; position: relative;"><i class="fa-search fa" style="position: absolute;left: 44px;top: 2px;color: #808080;"></i>
                                                        <input name="to_date" onkeydown="return false" autocomplete="off" class="fa m_datepicker_4_3_1" placeholder="To-Date" style="text-align: center;width: inherit;" type="text" />
                                                    </div>
                                                </td>
                                                <td nowrap>
                                                    <a class="generic_action" href="#" data-toggle="m-tooltip" title="Download"><i class="fa fa-download" aria-hidden="true"></i></a>
                                                    <input type="hidden" name="hidden_report_id" value="{{ $data['id'] }}">
                                                </td>
                                            </form>
                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end: Datatable -->
            </div>
        </div>
    </div>
    <!-- END: IPAD REPORTS -->
    @endif
</div>
<!-- </div>
</div> -->
@endsection
@section('scripts')
@parent

<script type="text/javascript">
    $(() => {
        $('#quality-dropdown-id').ready(function() {
            $('#quality-dropdown-id').select2({
                ajax: {
                    url: "{{ route('get-product-dropdown') }}",
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

        $('#design-dropdown-id').ready(function() {
            $('#design-dropdown-id').select2({
                ajax: {
                    url: "{{ route('get-product-dropdown') }}",
                    data: function(params) {
                        var query = {
                            design_search: params.term,
                        }
                        console.log('query - ', query);
                        return query;
                    }
                }
            })
        });

        $('#shade-dropdown-id').ready(function() {
            $('#shade-dropdown-id').select2({
                ajax: {
                    url: "{{ route('get-product-dropdown') }}",
                    data: function(params) {
                        var query = {
                            shade_search: params.term,
                        }
                        console.log('query - ', query);
                        return query;
                    }
                }
            })
        });
    });
    document.addEventListener("DOMContentLoaded", function() {
        // DOM is fully loaded
        var loaderWrapper = document.querySelector('.loader-wrapper');
        loaderWrapper.classList.add('loaded'); // Hide the loader
    });


    $('#reports_DT').DataTable({
        "scrollX": true,
        "sDom": "ltipr",
        "ordering": false,
        "bPaginate": false,
        "bLengthChange": false
    });

    $('#reports_DT_1').DataTable({
        "scrollX": true,
        "sDom": "ltipr",
        "ordering": false,
        "bPaginate": false,
        "bLengthChange": false
    });

    $(document).ready(function() {
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
        // $('.select2-selection__rendered').unbind('mouseenter mouseleave');
        // $('.select2-selection__rendered').hover(function() {
        //     $(this).removeAttr('title');
        // });
        // Set Default dates for search & filter - My leads
        // var start = moment().startOf('month').format('YYYY-MM-DD');
        // var end = moment().format('YYYY-MM-DD');
        // if(($('input[name="from_date"]').val() == '')){
        //     $('input[name="from_date"]').val(start);
        // }
        // if(($('input[name="to_date"]').val() == '')){
        //     $('input[name="to_date"]').val(end);
        // }
        // Set Default dates for search & filter - My leads
    });
    $(".m_datepicker_4_3").datepicker({
        orientation: "bottom left",
        todayHighlight: !0,
        templates: {
            leftArrow: '<i class="la la-angle-left"></i>',
            rightArrow: '<i class="la la-angle-right"></i>'
        },
        format: 'yyyy-mm-dd',
        autoclose: true,
        endDate: "today"
        // startDate: FromStartDate
    }).on('changeDate', function() {
        $('.m_datepicker_4_3_1').datepicker('setStartDate', new Date($(this).val()));
    });
    $(".m_datepicker_4_3_1").datepicker({
        orientation: "bottom left",
        todayHighlight: !0,
        templates: {
            leftArrow: '<i class="la la-angle-left"></i>',
            rightArrow: '<i class="la la-angle-right"></i>'
        },
        format: 'yyyy-mm-dd',
        autoclose: true,
        endDate: "today"
    }).on('changeDate', function() {
        $('.m_datepicker_4_3').datepicker('setEndDate', new Date($(this).val()));
    });
    $(document).on('click', '.generic_action', function(e) {
        var report_id = $(this).next("input[name='hidden_report_id']").val();
        var current_form = $('.reports_form_' + report_id);
        current_form.submit();
        e.preventDefault();
    });
</script>
@endsection
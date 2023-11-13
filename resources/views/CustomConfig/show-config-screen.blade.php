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
                                        Custom Configurations
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
                                Custom Configurations
                            </h3>
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
                                            <th nowrap>Event Name</th>
                                            <th nowrap>Current Active Status</th>
                                            <th nowrap>Created date</th>
                                            @if(App\Helpers\Helper::acl_privilege('custom_configs_action'))
                                                <th nowrap>Action</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($configData))	
                                        @foreach($configData as $data)
                                            @if(is_object($data))
                                                @php $data=(array)$data @endphp
                                            @endif
                                            <tr>
                                                @if($data['id'] == 2)
                                                    <td nowrap>{{ $data['event_name'] }} <i title="Add IP Addresses" class="fa fa-plus-circle" onclick="show_whitelisted_ips('{{$data["id"]}}','{{$data["event_metadata"]}}')" style="color: green;font-size: inherit;cursor: pointer;" aria-hidden="true"></i></td>
                                                @else
                                                    <td nowrap>{{ $data['event_name'] }}</td>
                                                @endif
                                                @if($data['is_active'] == 1)
                                                <td nowrap><i class="fa fa-check-circle" style="color:green"></i></td>
                                                @else
                                                <td nowrap><i class="fa fa-window-close" style="color:red"></i></td>
                                                @endif
                                                <td nowrap>{{ date("jS F,  Y", strtotime($data['created_at'])) }}</td>
                                                @if(App\Helpers\Helper::acl_privilege('custom_configs_action'))
                                                    <td nowrap>
                                                        <input data-id="{{$data['id']}}" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Activated" data-off="In-Activated" {{ ($data['is_active'] == 1) ? 'checked' : '' }}>
                                                    </td>
                                                @endif
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
    </div>
@endsection
@section('scripts')
@parent
    <script type="text/javascript">
        $(document).ready(function() {
            @if ($status = Session::get('success'))
                swal({
                    title: "Success!",
                    text: "{{ $status }}",
                    type: "success"
                });
            @endif
            @if ($status = Session::get('error'))
                swal({
                    title: "Failed!",
                    text: "{{ $status }}",
                    type: "error"
                });
            @endif

        });

        /**
            * This function is used for returning common swal errors.
            * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
        */
        function generic_swal_errors(condition){
            switch (condition) {
                case 'Server Error':
                    return swal({
                        title: "Failed",
                        text: "Something went wrong,Try again.",
                        type: "error"
                    });
                break;
                case 'Process Success':
                    return swal({
                        title: "Success!",
                        text: "Process completed successfully.",
                        type: "success"
                    },function() {
						window.location.reload();
                    }, 2000);
                break;
                case 'CANCEL POPUP':
                    return swal({
                        title: "Cancelled",
                        text: "",
                        type: "error"
                    },function() {
						window.location.reload();
                    }, 2000);
                break;
                case 'Validation Error':
                    return swal({
                        title: "<p style='font-size: large !important;'>IP Addresses are required to perform this operation!<p>",
                        html: true,
                        text: "",
                        type: "error"
                    });
                break;
            }
            
        }

        /**
            * This function is used to perform actions as per toggle.
            * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
        */
        $('.toggle-class').change(function() {
            var action_status = $(this).prop('checked') == true ? 1 : 0; 
            var event_id = $(this).data('id'); 
            if(event_id){
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
                            url : '{{ route("eventActions") }}',
                            type: "POST",
                            data : { '_token' : "{{ csrf_token() }}", 'action_status' : action_status, 'event_id' : event_id},
                            success: function(result){
                                if(result == 66){
                                    generic_swal_errors('Process Success');
                                }else{
                                    generic_swal_errors('Server Error');
                                } 
                            }
                        });
                    } else {
                        generic_swal_errors('CANCEL POPUP');
                    }
                });
            }
        })
        
        /**
            * This function is used to add whitelisted ip addresses in mysql database for managing website view access.
            * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
        */
        function show_whitelisted_ips(event_id,whitelisted_ips)
        {
            swal({
                title: "<p style='font-size: large !important;'>Whitelisted IPs (Comma separated):</p>",
                text: "<textarea style='height: 200px;width: 380px;' id='whitelisted_ips'>"+whitelisted_ips+"</textarea>",
                html: true,
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, Submit!",
                cancelButtonText: "No, Cancel!",
                closeOnConfirm: false,
                closeOnCancel: true,
                animation: "slide-from-top"
            }, function(isConfirm) {
                if (isConfirm) 
                {
                    var user_entered_whitelisted_ips = $('#whitelisted_ips').val();
                    if(user_entered_whitelisted_ips)
                    {
                        $.ajax({
                            url : '{{ route("add_whitelisted_ips") }}',
                            type: "POST",
                            data : { '_token' : "{{ csrf_token() }}", 'user_entered_whitelisted_ips' : user_entered_whitelisted_ips, 'event_id' : event_id},
                            success: function(result){
                                if(result == 66){
                                    $('#whitelisted_ips').hide();
                                    generic_swal_errors('Process Success');
                                }else{
                                    $('#whitelisted_ips').hide();
                                    generic_swal_errors('Server Error');
                                } 
                            }
                        });
                    }
                    else
                    {
                        $('#whitelisted_ips').hide();
                        generic_swal_errors('Validation Error');
                    }
                }
            });
        }

    </script>
@endsection

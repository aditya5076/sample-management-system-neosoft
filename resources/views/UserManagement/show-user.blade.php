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
                                        User Management
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
                                User Management
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <div class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover" aria-expanded="true">
                                    <a href="{{ Route('createUser') }}" class="m-portlet__nav-link btn btn-lg btn-secondary  m-btn m-btn--icon m-btn--icon-only m-btn--pill  m-dropdown__toggle">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </li>
                            <li class="m-portlet__nav-item">
                                <div class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover" aria-expanded="true">
                                    <a href="{{ Route('export_users') }}" class="m-portlet__nav-link btn btn-lg btn-secondary  m-btn m-btn--icon m-btn--icon-only m-btn--pill  m-dropdown__toggle">
                                    <img src="{{ asset('public/default/images/excel.png') }}">
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
                                    <table style="text-align: center !important;" id="user_manage_DT" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th nowrap>Email</th>
                                            <th>Company</th>
                                            <th nowrap>Desgination</th>
                                            <th nowrap>Role</th>
                                            <th nowrap>Active</th>
                                            <th nowrap>Created Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($getUsers))	
                                        @foreach($getUsers as $data)
                                            @if(is_object($data))
                                                @php $data=(array)$data @endphp
                                            @endif
                                            <tr>
                                                <td nowrap>{{ $data['name'] }}</td>
                                                <td nowrap>{{ $data['email'] }}</td>
                                                <td nowrap>{{ $data['company'] }}</td>
                                                <td nowrap>{{ $data['designation'] }}</td>
                                                <td nowrap>{{ $data['roles']['role_name'] }}</td>
                                                @if($data['is_active'] == 1)
                                                <td nowrap><i class="fa fa-check-circle" style="color:green"></i></td>
                                                @else
                                                <td nowrap><i class="fa fa-window-close" style="color:red"></i></td>
                                                @endif
                                                <!-- <td nowrap>{{ ($data['is_active'] == 1) ? 'YES' : 'NO' }}</td> -->
                                                <td nowrap>{{ date("jS F,  Y", strtotime($data['created_at'])) }}</td>
                                                <td nowrap>
                                                    <form class="user_management_search" action="{{ route('inactivateUser',['user_id'=>$data['id']]) }}" method="POST">
                                                        @csrf
                                                        <a href="{{ route('editUser',['user_id'=>$data['id']]) }}" data-toggle="m-tooltip" title="Edit"><i class="fas fa-edit"></i></a>
                                                        <a class="in_activate_user" href="{{ route('inactivateUser',['user_id'=>$data['id']]) }}" data-toggle="m-tooltip" title="Inactivate"><i class="fas fa-trash"></i></a>
                                                    </form>
                                                </td>
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
    $('#user_manage_DT').DataTable({
        "scrollX": true,
        "order": [ 6, "desc" ],
        "columnDefs": [
            { "orderable": false, "targets": 7 }
        ]
    });
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
    $(document).on('click', '.in_activate_user', function(e) {
        var current_form = $(this).closest('.user_management_search');
        e.preventDefault();
        swal({
            title: "Are you sure?",
            text: "You will not be able to rollback this procedure!",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, Inactivate!",
            cancelButtonText: "No, Cancel!",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        function(isConfirm) {
            if (isConfirm) {
                current_form.submit();
            } else {
                swal("Cancelled", "", "error");
            }
        });
    });
    </script>
@endsection

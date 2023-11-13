@extends('layouts.app')

@section('content')
    <div class="m-grid__item m-grid__item--fluid m-wrapper">
        <!-- BEGIN: Subheader -->
            <div class="m-subheader ">
                <div class="d-flex align-items-center">
                    <div class="mr-auto">
                        <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
                            <li class="m-nav__item m-nav__item--home">
                                <a href="{{ url('/home') }}" class="m-nav__link m-nav__link--icon">
                                    <i class="m-nav__link-icon la la-home"></i>
                                </a>
                            </li>
                            <li class="m-nav__separator">
                                -
                            </li>
                            <li class="m-nav__item">
                                <a href="{{ Route('showUsers') }}" class="m-nav__link">
                                    <span class="m-nav__link-text">
                                        User Management
                                    </span>
                                </a>
                            </li>
                            <li class="m-nav__separator">
                                -
                            </li>
                            <li class="m-nav__item">
                                <a href="#" class="m-nav__link">
                                    <span class="m-nav__link-text">
                                        Create User
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
                                Create User
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <div class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover" aria-expanded="true">
                                    <a href="{{ Route('showUsers') }}" class="m-portlet__nav-link btn btn-lg btn-secondary  m-btn m-btn--icon m-btn--icon-only m-btn--pill  m-dropdown__toggle">
                                        <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="m-portlet__body">
                    <!--begin: Datatable -->
                    <div class="report-datatable">
                        <!--begin: Search Form -->
                            <form action="{{ route('storeUser') }}" method="POST" autocomplete="off">
                            @csrf
                            <div class="container">
                                <div class="row">


                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <strong>Name:</strong>
                                                <input type="text" autocomplete="off" value="{{ $user->name ?? old('name') }}" name="name" class="form-control" >
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <strong>Password:</strong>
                                                <input type="password" autocomplete="off" value="" name="password" class="form-control" >
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <strong>Confirm Password:</strong>
                                                <input type="password" autocomplete="off" value="" name="password_confirmation" class="form-control" >
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                           <div class="form-group">
                                            <strong>Role:</strong>
                                            <select name="role_id" class="btn btn-primary dropdown-toggle" style="color: black !important;">
                                            <option value="0">Select Role</option>
                                            @isset($role_master)
                                                @foreach($role_master as $role)
                                                @if(is_object($role))
                                                    @php $role=(array)$role @endphp
                                                @endif
                                                    <option value="{{ $role['id'] }}" {{ old('role_id',0) == $role['id'] ? 'selected' : '' }}>{{$role['role_name']}}</option>
                                                @endforeach
                                            @endisset     
                                            </select>
                                          </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <strong>Email:</strong>
                                                <input type="text" autocomplete="off" value="{{ $user->email ?? old('email') }}" name="email" class="form-control" >
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <strong>Company:</strong>
                                                <input type="text" autocomplete="off" value="{{ $user->company ?? old('company') }}" name="company" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <strong>Designation:</strong>
                                                <input type="text" autocomplete="off" value="{{ $user->designation ?? old('designation') }}" name="designation" class="form-control" >
                                            </div>
                                        </div>
                                         <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <strong>Is Active:</strong>
                                                <select name="is_active" class="btn btn-primary dropdown-toggle" style="color: black !important;">
                                                        <option value="1" selected>YES</option>
                                                        <option value="0">NO</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                        <button type="submit" class="btn btn-primary btn-submit">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!--end: Datatable -->
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@parent
@endsection

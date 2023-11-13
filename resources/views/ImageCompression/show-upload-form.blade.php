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
                                        Image Compression
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
                                Image Compression
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
                            <form action="{{ route('compress_and_store') }}" enctype="multipart/form-data" method="POST" autocomplete="off">
                            @csrf
                            <div class="container">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="form-group text-center select-file-text">
                                                <!-- <p>Select File:</p> -->
                                                <input id="upload_file" style="border:1px solid #ced4da !important;" required="" type="file" class="form-control" name="upload_file[]" multiple>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                        <button type="submit" class="btn btn-primary btn-submit">Upload</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <br />
                        <div class="progress" style="height: 24px;">
                            <div class="progress-bar" role="progressbar" aria-valuenow=""
                            aria-valuemin="0" aria-valuemax="100" style="width: 0%;height: auto;">
                                0%
                            </div>
                        </div>
                        <br />
                        <div id="success"></div>
                        <br />
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
            /**
                * This function is used for displaying progress bar as per conditions & situations
                * @return : SWAL
                * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
            */
            $('form').ajaxForm({
                beforeSend:function(){
                    $('#success').empty();
                },
                uploadProgress:function(event, position, total, percentComplete)
                {
                    $('.progress-bar').html("<p style='margin-top: 6% !important;'><b>Uploading images... </b> <i class='fa fa-spinner fa-spin' style='font-size: 1rem;'></i> </p>");
                    $('.progress-bar').css('width', '20%');
                    $('.progress-bar').css('background-color', '#006341');
                    $('.progress-bar').css('border', '1px solid black');
                },
                success:function(data)
                {
                    if(data.validation){
                        $('.progress-bar').text('FAILED TO UPLOAD IMAGES');
                        $('.progress-bar').css('background-color', 'red');
                        $('.progress-bar').css('border', '1px solid black');
                        generic_form_swals('validation',data.validation);
                    }
                    if(data.success){
                        generic_form_swals('success',data.success);
                        $('.progress-bar').text('100%');
                        $('.progress-bar').css('width', '100%');
                        $('.progress-bar').css('background-color', '#006341');
                        $('.progress-bar').css('border', '1px solid black');
                    }
                    if(data.error){
                        generic_form_swals('error',data.error);
                        $('.progress-bar').text('FAILED TO UPLOAD IMAGES');
                        $('.progress-bar').css('background-color', 'red');
                        $('.progress-bar').css('border', '1px solid black');
                    }
                }
            }); // End : Progress bar functionality.
        });

        /**
            * This function is used to for common swals
            * @param : Condition, Display Messages.
            * @return : SWAL
            * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
        */
        function generic_form_swals(condition,display_message){
            switch (condition) {
                case 'validation':
                    setTimeout(function() {
						swal({
                            title: "Validation",
                            text: display_message,
                            type: "error"
                        },function() {
							window.location.reload();
						}, 3000);
					});
                break;
                case 'error':
                    setTimeout(function() {
						swal({
                            title: "Failed",
                            text: display_message,
                            type: "error"
                        },function() {
							window.location.reload();
						}, 3000);
					});
                break;
                case 'success':
                    setTimeout(function() {
						swal({
                            title: "Success",
                            text: display_message,
                            type: "success"
                        },function() {
							window.location.reload();
						}, 3000);
					});
                break;
                default:
                    setTimeout(function() {
                        swal({
                            title: "Failed",
                            text: "Something went wrong,Try again",
                            type: "error"
                        },function() {
                            window.location.reload();
                        }, 3000);
                    });
                break;
            }
        } // End : generic_form_swals
    </script>
@endsection

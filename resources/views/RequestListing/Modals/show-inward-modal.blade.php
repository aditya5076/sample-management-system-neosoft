<!-- Start of Inward barcode Modal -->
<div class="modal fade" id="inward_modal">
	<div class="modal-dialog modal-dialog-centered modal-lg schedule-modal">
		<div class="modal-content">
			<!-- Modal body -->
				<div class="m-form__group form-group modal-header lead-type">
					<p class="lead-title"><img src="{{ asset('public/default/images/task.png') }}"> <strong>Inward <strong></p>
				</div>
				<div class="modal-body">
                    <form action="{{ Route('generateInwards') }}" class="inward_procedure_form" method="POST">
						@csrf
                        {{-- <input type="hidden" name="hidden_checked_entries" id="hidden_checked_entries"> --}}
                        <input type="hidden" name="filter_request_id_search" id="filter_inward_request_id_search">
                        <input type="hidden" name="filter_sku_id_search" id="filter_inward_sku_id_search">
                        <input type="hidden" name="filter_barcode_search" id="filter_inward_barcode_search">
                        <input type="hidden" name="filter_quality_search" id="filter_inward_quality_search">
                        <input type="hidden" name="filter_design_search" id="filter_inward_design_search">
                        <input type="hidden" name="filter_shade_search" id="filter_inward_shade_search">
                        <div id="inward_modal_data">
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="upload-btn-inner d-flex justify-content-center">
                                    <button type="button" id="inward_modal_close" data-dismiss="modal" class="btn btn-primary btn-submit">Close</button>
                                    <button type="button" id="inward_modal_submit" class="btn btn-primary btn-submit">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
				</div>
			<!-- Modal body -->
		</div>
	</div>
</div>
<!-- End of print Inward Modal-->
@section('scripts')
    @parent
    <script type="text/javascript">
    $(document).ready(function(){
    })
	
    $(document).on('click', '#inward_modal_submit', function(e) {
        var dataValid = true;
        $(".inward_quantity").each(function(){
            if ($(this).val() == ''){
                dataValid = false;
                this.focus();
                $(this).css('border-color', 'red');
                return false;
            }else{
                $(this).css('border-color', '');
            }
        });

        if (dataValid){
            var current_form = $(this).closest('.inward_procedure_form');
            e.preventDefault();
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
                    current_form.submit();
                } else {
                    swal("Cancelled", "", "error");
                }
            });
        } else {
            swal("Validation Error", "Kindly fill all details to submit inward!", "error");
        }
        
    });
    </script>
@endsection  
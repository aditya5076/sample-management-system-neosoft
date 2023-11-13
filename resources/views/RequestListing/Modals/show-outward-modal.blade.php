<!-- Start of Outward Modal -->
<div class="modal fade" id="outward_modal">
	<div class="modal-dialog modal-dialog-centered modal-lg schedule-modal">
		<div class="modal-content">
			<!-- Modal body -->
				<div class="m-form__group form-group modal-header lead-type">
					<p class="lead-title"><img src="{{ asset('public/default/images/task.png') }}"> <strong>Outward <strong></p>
				</div>
				<div class="modal-body">
                    <form action="{{ Route('generateOutwards') }}" class="outward_procedure_form" method="POST">
                        @csrf
                        <input type="hidden" name="filter_request_id_search" id="filter_outward_request_id_search">
                        <input type="hidden" name="filter_sku_id_search" id="filter_outward_sku_id_search">
                        <input type="hidden" name="filter_barcode_search" id="filter_outward_barcode_search">
                        <input type="hidden" name="filter_quality_search" id="filter_outward_quality_search">
                        <input type="hidden" name="filter_design_search" id="filter_outward_design_search">
                        <input type="hidden" name="filter_shade_search" id="filter_outward_shade_search">
                        <div id="outward_modal_data">
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="upload-btn-inner d-flex justify-content-center">
                                    <button type="button" id="outward_modal_close" data-dismiss="modal" class="btn btn-primary btn-submit">Close</button>
                                    <button type="button" id="outward_modal_submit" class="btn btn-primary btn-submit">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
				</div>
			<!-- Modal body -->
		</div>
	</div>
</div>
<!-- End of Outward Modal-->
@section('scripts')
    @parent
    <script type="text/javascript">
    $(document).ready(function(){
        
    })

    /**
        * This function is used to validate user entered quantity on frontend.
        * @param Selected checkboxes
        * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    function check_available_quantity(entered_quantity) {
        var available_quantity = $(entered_quantity).closest('tr').find('.available_quantity').text();
        var user_entered_quantity =  parseFloat($(entered_quantity).val()).toFixed(2);
        if(parseFloat(user_entered_quantity) > parseFloat(available_quantity)){
            $(entered_quantity).focus();
            $(entered_quantity).css('border-color', 'red');
            return false;
        }
        $(entered_quantity).css('border-color', '');
    }

    $(document).on('click', '#outward_modal_submit', function(e) {
        var dataValid = true;
        var quantityValid = true;
        $(".outward_quantity, .outward_issued_to").each(function(){
            if ($(this).val() == ''){
                dataValid = false;
                this.focus();
                $(this).css('border-color', 'red');
                return false;
            }else{
                $(this).css('border-color', '');
            }
        });

        $(".outward_quantity").each(function(){
            var available_quantity = $(this).closest('tr').find('.available_quantity').text();
            var user_entered_quantity =  parseFloat($(this).val()).toFixed(2);
            if(parseFloat(user_entered_quantity) > parseFloat(available_quantity)){
                quantityValid = false;
                this.focus();
                $(this).css('border-color', 'red');
                return false;
            }else{
                $(this).css('border-color', '');
            }
        }); 
        
        if (dataValid){
            if(quantityValid){
                var current_form = $(this).closest('.outward_procedure_form');
                generic_fields('DISABLE');
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
                        generic_swal_errors_outward('CANCEL POPUP');
                        generic_fields('ENABLE');
                    }
                });
            }else{
                generic_swal_errors_outward('QUANTITY ERROR');
                generic_fields('ENABLE');
            }
        } else {
            generic_swal_errors_outward('FIELDS INCOMPLETE');
            generic_fields('ENABLE');
        }
    });
    
    /**
        * This function is used to validate user fields by disabling them after hitting request toward server.
        * @param Generic condition
        * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    function generic_fields(condition)
    {
        switch (condition) {
            case 'ENABLE':
                $('.outward_quantity, .outward_issued_to').prop("readonly", false);
            break;
            case 'DISABLE':
                $('.outward_quantity, .outward_issued_to').prop("readonly", true);
            break;
        }
    }

    /**
        * This function is used for returning common swal errors on Outward procedure.
        * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
        */
    function generic_swal_errors_outward(condition)
    {
        switch (condition) {
            case 'CANCEL POPUP':
                return  swal("Cancelled", "", "error");
            break;
            case 'QUANTITY ERROR':
                return swal("Validation Error", "Outward quantity cant be greater than available quantity!", "error");
            break;
            case 'FIELDS INCOMPLETE':
                return swal("Validation Error", "Kindly fill all details to submit outward!", "error");
            break;
        }
    }
    </script>
@endsection  
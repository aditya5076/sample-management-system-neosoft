<!-- Start of print barcode Modal -->
<div class="modal fade" id="print_barcode_modal">
	<div class="modal-dialog modal-dialog-centered modal-lg schedule-modal">
		<div class="modal-content">
			<!-- Modal body -->
				<div class="m-form__group form-group modal-header lead-type">
					<p class="lead-title"><i class="fas fa-barcode"></i> <strong>Print Barcode <strong></p>
				</div>
				<div class="modal-body">
					<!-- <form action="{{ Route('generateBarcodes') }}" method="POST"> -->
						<!-- @csrf -->
						<input type="hidden" name="hidden_checked_entries" id="hidden_checked_entries">
						<input type="hidden" name="filter_request_id_search" id="filter_barcode_request_id_search">
						<input type="hidden" name="filter_sku_id_search" id="filter_barcode_sku_id_search">
						<input type="hidden" name="filter_barcode_search" id="filter_barcode_barcode_search">
						<input type="hidden" name="filter_quality_search" id="filter_barcode_quality_search">
						<input type="hidden" name="filter_design_search" id="filter_barcode_design_search">
						<input type="hidden" name="filter_shade_search" id="filter_barcode_shade_search">						
						<div id="barcode_modal_data">
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="upload-btn-inner d-flex justify-content-center">
									<button type="button" id="barcode_modal_close" data-dismiss="modal" class="btn btn-primary btn-submit">Close</button>
									<button type="button" onclick="return generate_barcodes();" id="barcode_modal_submit" class="btn btn-primary btn-submit">Print</button>
								</div>
							</div>
						</div>
					<!-- <form> -->
				</div>
			<!-- Modal body -->
		</div>
	</div>
</div>
<!-- End of print barcode Modal-->
@section('scripts')
    @parent
    <script type="text/javascript">
    $(document).ready(function(){
    })
	/**
		* This function is used to generate barcode & redirect user to new page with barcodes generated.
		* @return : Barcodes generated in new tab with parent page reloaded.
		* @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
	*/
	function generate_barcodes(){
		var hidden_checked_entries = $('#hidden_checked_entries').val();
		var barcode_type = $('#barcode_type').val();
		var collection_input = $('#collection_input').val();
		var filter_request_id_search=$('#filter_barcode_request_id_search').val();
		var filter_sku_id_search=$('#filter_barcode_sku_id_search').val();
		var filter_barcode_search=$('#filter_barcode_barcode_search').val();
		var filter_quality_search=$('#filter_barcode_quality_search').val();
		var filter_design_search=$('#filter_barcode_design_search').val();
		var filter_shade_search=$('#filter_barcode_shade_search').val();
		var process_access = true;
		if (barcode_type != 1) {
			if(!collection_input){
				process_access = false;
			}
		}
		if(process_access){
			$.ajax({
				url : '{{ route("generateBarcodes") }}',
				type: "POST",
				data : { '_token' : "{{ csrf_token() }}", 'hidden_checked_entries' : hidden_checked_entries, 'barcode_type' : barcode_type, 'collection_input' : collection_input,'filter_request_id_search':filter_request_id_search, 'filter_sku_id_search':filter_sku_id_search, 'filter_barcode_search':filter_barcode_search, 'filter_quality_search':filter_quality_search, 'filter_design_search':filter_design_search, 'filter_shade_search':filter_shade_search},
				success: function(result){
					var new_tab=window.open();
					new_tab.document.open();
					new_tab.document.write(result);
					new_tab.document.close();
					window.focus();
					$("#barcode_modal_close").click();
					// setTimeout(function() {
					// 	swal({
					// 		title: "Success!",
					// 		text: "Barcode generated successfully.",
					// 		type: "success",
					// 	})
					// 	,function() {
					// 		window.location.reload();
					// 	}, 3000
					// });
					setTimeout(function(){ 	window.location.reload(); }, 3000);



				}
			});
		}else{
			return swal({
				title: "Validation",
				text: "You need to enter collection.",
				type: "error"
			});
		}
	}
    </script>
@endsection  
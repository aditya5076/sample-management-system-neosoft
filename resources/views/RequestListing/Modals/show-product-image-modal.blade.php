<!-- Start of print barcode Modal -->
<div class="modal fade" id="show_product_image_modal">
	<div class="modal-dialog modal-dialog-centered modal-lg schedule-modal">
		<div class="modal-content">
			<!-- Modal body -->
				<div class="m-form__group form-group modal-header lead-type">
					<p class="lead-title"><i class="fas fa-camera" aria-hidden="true"></i> <strong>Product Image<strong></p>
				</div>
				<div class="modal-body">
                    <input type="hidden" name="hidden_sku_id" id="hidden_sku_id">
                    <div id="product_image_modal_data">
                    </div>
                    <div class="row">
                        <div class="col-sm-9 offset-4">
                            <div class="upload-btn-inner">
                                <button type="button" id="product_image_modal_close" data-dismiss="modal" class="btn btn-primary btn-submit">Close</button>
                            </div>
                        </div>
                    </div>
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
    </script>
@endsection  
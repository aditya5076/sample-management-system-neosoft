@if(isset($checked_entries))
<style>
    .scroll{
   overflow: auto;
 }
 th.fix{
     position: sticky;
     top: 0;
     background-color: #006341;
     z-index: 1;
 }
 
 </style>
<div class="row">
    <div class="col-lg-12">
        <div class="form-group form-inline" style="justify-content: center;">
            <input type="number" placeholder="Enter common quantity" style="text-align: center;" autocomplete="off" value="{{ old('common_quantity_outward') }}" name="common_quantity_outward" id="common_quantity_outward" class="form-control" >&nbsp;&nbsp;
            <input type="text" placeholder="Enter common remark" style="text-align: center;" autocomplete="off" value="{{ old('common_remark_outward') }}" name="common_remark_outward" id="common_remark_outward" class="form-control" >
        </div>
    </div>
</div>
<div class="report-datatable">
    <div class="row">
        <div class="col-12">
            <div class="table-responsive scroll">
                <table id="outward_DT_1" style="text-align: center;" class="table table-bordered">
                    <thead style="background-color: #006341;color: white;">
                        <tr>
                            <th class="fix" nowrap>Unique SKU ID</th>
                            <th class="fix" nowrap>Quality Name</th>
                            <th class="fix" nowrap>Design Name</th>
                            <th class="fix" nowrap>Shade Name</th>
                            <th class="fix" nowrap>Requirement</th>
                            <th class="fix" nowrap>Location Name</th>
                            <th class="fix" nowrap>Available Quantity</th>
                            <th class="fix" nowrap>Quantity</th>
                            <th class="fix" nowrap>Issued To</th>
                            <th class="fix" nowrap>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($checked_entries))	
                            @foreach($checked_entries as $key1=>$getData)
                                @foreach($getData as $key=>$getData1)
                                    <tr>
                                        <td>{{ $getData1->unique_sku_id }}</td>
                                        <td nowrap>{{ $getData1->quality_name }}</td>
                                        <td nowrap>{{ $getData1->design_name }}</td>
                                        <td nowrap>{{ $getData1->shade_name }}</td>
                                        <td nowrap>{{ $getData1->requirement }}</td>
                                        <td>{{ $getData1->location_name }}</td>
                                        <td class="available_quantity">{{ $getData1->available_quantity }}</td>
                                        <td>
                                        <input name="quantity[{{$getData1->unique_sku_id}}][{{$getData1->location_id}}][{{$getData1->id}}]" class="outward_quantity" onblur="check_available_quantity(this)"  style="text-align: center;" type="number" step=".01" />
                                        </td>
                                        <td><input name="issued_to[{{$getData1->unique_sku_id}}][{{$getData1->location_id}}][{{$getData1->id}}]" class="outward_issued_to" style="text-align: center;" type="text"  /></td>
                                        <td><input name="remarks[{{$getData1->unique_sku_id}}][{{$getData1->location_id}}][{{$getData1->id}}]" class="outward_remarks" style="text-align: center;" type="text" /></td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>

    $("#common_quantity_outward").blur(function(){
        var common_quantity_outward = $('#common_quantity_outward').val();
        if(common_quantity_outward != '')
        {
            swal({
                title: "Are you sure?",
                text: "Common quantity will be applied against the records.",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, Apply!",
                cancelButtonText: "No, Cancel!",
                closeOnConfirm: true,
                closeOnCancel: false
            },
            function(isConfirm) {
                if (isConfirm) 
                {
                    $('.outward_quantity').val(common_quantity_outward);
                }
                else 
                {
                    swal("Cancelled", "", "error");
                }
            });
        }
    });
    $("#common_remark_outward").blur(function(){
        var common_remark_outward = $('#common_remark_outward').val();
        if(common_remark_outward != '')
        {
            swal({
                title: "Are you sure?",
                text: "Common remark will be applied against the records.",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, Apply!",
                cancelButtonText: "No, Cancel!",
                closeOnConfirm: true,
                closeOnCancel: false
            },
            function(isConfirm) {
                if (isConfirm) 
                {
                    $('.outward_remarks').val(common_remark_outward);
                }
                else 
                {
                    swal("Cancelled", "", "error");
                }
            });
        }
    });
    </script>
@endif

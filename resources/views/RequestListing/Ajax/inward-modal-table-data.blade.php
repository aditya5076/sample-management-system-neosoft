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
        <div class="form-group form-inline " style="justify-content: center;">
            <input type="number" placeholder="Enter common quantity" style="text-align: center;" autocomplete="off" value="{{ old('common_quantity_inward') }}" name="common_quantity_inward" id="common_quantity_inward" class="form-control" >&nbsp;&nbsp;
            <input type="text" placeholder="Enter common remark" style="text-align: center;" autocomplete="off" value="{{ old('common_remark_inward') }}" name="common_remark_inward" id="common_remark_inward" class="form-control" >
        </div>
    </div> 
</div>
<div class="report-datatable">
    <div class="row">
        <div class="col-12">
            <div class="table-responsive scroll">
                <table id="inward_DT_1" style="text-align: center;" class="table table-bordered">
                    <thead style="background-color: #006341;color: white;">
                        <tr>
                            <th class="fix" nowrap>SR No</th>
                            <th class="fix" nowrap>Request No</th>
                            <th class="fix" nowrap>Unique SKU ID</th>
                            <th class="fix" nowrap>Quality Name</th>
                            <th class="fix" nowrap>Design Name</th>
                            <th class="fix" nowrap>Shade Name</th>
                            <th class="fix" nowrap>Requirement</th>
                            <th class="fix" nowrap>Location Name</th>
                            <th class="fix" nowrap>Quantity</th>
                            <th class="fix" nowrap>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($checked_entries))	
                            @php $location = App\Helpers\Helper::getLocations(); @endphp
                            @foreach($checked_entries as $key=>$getData)
                            @php $validated_entries[]=$getData['id'];@endphp
                                <tr>
                                    <td>{{ ++ $key }}</td>
                                    <td nowrap>{{ $getData['request_no'] }}</td>
                                    <td>{{ $getData['unique_sku_id'] }}</td>
                                    <td nowrap>{{ $getData['quality_name'] }}</td>
                                    <td nowrap>{{ $getData['design_name'] }}</td>
                                    <td nowrap>{{ $getData['shade_name'] }}</td>
                                    <td nowrap>{{ $getData['requirement'] }}</td>
                                    <td>
                                    <select name="location_master[{{$getData['id']}}]" class="js-example-basic-single btn btn-primary dropdown-toggle" style="color: black !important;width: auto;display: inline-block;">
                                        @isset($location)
                                            @foreach($location as $key=>$type)
                                                @if(is_object($type))
                                                    @php $type=(array)$type @endphp
                                                @endif
                                                <option value="{{ $type['id'] }}">{{ $type['location_name'] }}</option>
                                            @endforeach
                                        @endisset     
                                    </select>
                                    </td>
                                    <td><input name="quantity[{{$getData['id']}}]" class="inward_quantity" id="quantity_{{$getData['id']}}" style="text-align: center;" type="number" step=".01" /></td>
                                    <td><input name="remarks[{{$getData['id']}}]" class="inward_remarks" id="remarks_{{$getData['id']}}" style="text-align: center;" type="text" /></td>
                                    <input name="unique_sku_id[{{$getData['id']}}]" value="{{$getData['unique_sku_id']}}" type="hidden"/>
                                </tr>
                            @endforeach
                        @endif
                        <input type="hidden" name="hidden_checked_entries" id="hidden_checked_entries" value="{{json_encode($validated_entries)}}">    
                   
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>

    $(document).ready(function() {

        $('.js-example-basic-single').select2({
            escapeMarkup: function(markup) {
                return markup;
            }
        });
        $('.select2-selection__rendered').unbind('mouseenter mouseleave');
        $('.select2-selection__rendered').hover(function () {
            $(this).removeAttr('title');
        });
    });

    $("#common_quantity_inward").blur(function(){
        var common_inward_quantity = $('#common_quantity_inward').val();
        if(common_inward_quantity != '')
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
                    $('.inward_quantity').val(common_inward_quantity);
                }
                else 
                {
                    swal("Cancelled", "", "error");
                }
            });
        }
    });
    $("#common_remark_inward").blur(function(){
        var common_inward_remark = $('#common_remark_inward').val();
        if(common_inward_remark != '')
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
                    $('.inward_remarks').val(common_inward_remark);
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
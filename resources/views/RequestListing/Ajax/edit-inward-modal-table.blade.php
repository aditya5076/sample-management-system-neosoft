<div class="report-datatable">
    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table id="edit_inward_DT_1" style="text-align: center;" class="table table-bordered">
                    <thead style="background-color: #006341;color: white;">
                        <tr>
                            <th nowrap>SR No</th>
                            <th nowrap>Unique SKU ID</th>
                            <th nowrap>Inward creation date</th>
                            <th nowrap>Last modification date</th>
                            <th nowrap>Location Name</th>
                            <th nowrap>Quantity</th>
                            <th nowrap>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($inward_Data))	
                            @php $location = App\Helpers\Helper::getLocations(); @endphp
                            @foreach($inward_Data as $key=>$getData)
                                <tr>
                                    <td>{{ ++ $key }}</td>
                                    <td>{{ $getData['unique_sku_id'] }}</td>
                                    <td nowrap>{{ date("jS F,  Y", strtotime($getData['created_at'])) }}</td>
                                    <td nowrap>{{ date("jS F,  Y", strtotime($getData['updated_at'])) }}</td>
                                    <td>
                                    <select name="location_master[{{$getData['inward_id']}}]" class="btn btn-primary dropdown-toggle" style="color: black !important;width: auto;display: inline-block;">
                                        @isset($location)
                                            @foreach($location as $key=>$type)
                                                @if(is_object($type))
                                                    @php $type=(array)$type @endphp
                                                @endif
                                                <option value="{{ $type['id'] }}" {{ $getData['location_id'] == $type['id'] ? 'selected' : '' }}>{{ $type['location_name'] }}</option>
                                            @endforeach
                                        @endisset     
                                    </select>
                                    </td>
                                    <td><input name="quantity[{{$getData['inward_id']}}]" value="{{$getData['quantity']}}" class="inward_quantity" id="quantity_{{$getData['inward_id']}}" style="text-align: center;" type="number" step=".01" /></td>
                                    <td><input name="remarks[{{$getData['inward_id']}}]" value="{{$getData['remarks']}}" class="inward_remarks" id="remarks_{{$getData['inward_id']}}" style="text-align: center;" type="text" /></td>
                                    <input name="inward_id[{{$getData['inward_id']}}]" value="{{$getData['inward_id']}}" type="hidden"/>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
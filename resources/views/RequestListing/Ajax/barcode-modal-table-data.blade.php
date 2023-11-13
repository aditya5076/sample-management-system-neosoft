@if(isset($checked_entries))

<div class="row">
    <div class="col-lg-12">
        <div class="form-group form-inline">
            <label for="collection_input" style="margin-left: 10%;">Collection:&nbsp;</label>
            <input type="text" autocomplete="off" value="{{ old('collection_input') }}" name="collection_input" id="collection_input" class="form-control" >
            <label style="margin-left: 6%;" for="barcode_type">Barcode Type:&nbsp;</label>
            @php $barcode_type = App\Helpers\Helper::barcode_types(); @endphp
            <select name="barcode_type" id="barcode_type" class="btn btn-primary dropdown-toggle" style="color: black !important;width: auto;display: inline-block;">
                    @isset($barcode_type)
                        @foreach($barcode_type as $key=>$type)
                            @if(is_object($type))
                                @php $type=(array)$type @endphp
                            @endif
                            <option value="{{ $key }}">{{$type}}</option>
                        @endforeach
                    @endisset     
            </select>
        </div>
    </div>
</div>
<div class="report-datatable">
    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table id="leads_DT_1" style="text-align: center;" class="table table-bordered">
                    <thead style="background-color: #006341;color: white;">
                        <tr>
                            <th nowrap>SR No</th>
                            <th nowrap>Request No</th>
                            <th nowrap>Unique SKU ID</th>
                            <th nowrap>Quality Name</th>
                            <th nowrap>Design Name</th>
                            <th nowrap>Shade Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($checked_entries))	
                            @foreach($checked_entries as $key=>$getData)
                                <tr>
                                    <td>{{ ++ $key }}</td>
                                    <td nowrap>{{ $getData['request_no'] }}</td>
                                    <td>{{ $getData['unique_sku_id'] }}</td>
                                    <td nowrap>{{ $getData['quality_name'] }}</td>
                                    <td nowrap>{{ $getData['design_name'] }}</td>
                                    <td nowrap>{{ $getData['shade_name'] }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif
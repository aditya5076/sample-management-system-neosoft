@if(isset($request_Data))
<div class="report-datatable">
    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table id="show_request_table" style="text-align: center;" class="table table-bordered">
                    <thead style="background-color: #006341;color: white;">
                        <th>
                            FIELD
                        </th>
                        <th>
                            DESCRIPTION
                        </th>
                    </thead>
                    <tbody>
                        @if(isset($request_Data))	
                            @foreach($request_Data as $key=>$getData)
                                @foreach($getData as $request_key=>$request_value)
                                    <tr>
                                        <td>{{$request_key}}</td>
                                        @if($request_key == 'Barcode Status')
                                            @if($request_value != '')
                                                <td><i class="fa fa-check-circle" style="color:green"></i></td>
                                            @else
                                                <td><i class="fa fa-window-close" style="color:red"></i></td>
                                            @endif
                                        @elseif($request_key == 'Delivery Date')
                                            @if($request_value != '')
                                                <td>{{ date("jS F,  Y", strtotime($request_value)) }}</td>
                                            @else
                                                <td><i class="fa fa-minus"></i></td>
                                            @endif
                                        @else
                                            <td>{{$request_value}}</td>
                                        @endif
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
@endif
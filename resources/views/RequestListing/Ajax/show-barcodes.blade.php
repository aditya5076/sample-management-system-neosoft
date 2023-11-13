<html>
    <head>
        <title>Generated Barcodes</title>
        <link rel="icon" href="{{ asset('public/default/images/login_logo_ico') }}" />
        <style type="text/css">
        	body{
        		margin: 0px;
        	}
            table #tab1, th, tr {
                border: 1px solid #000000;
                border-collapse: collapse;
                width: 420px;
            }
            table #tab2, table #tab3,table #tab4,table #tab5,table #tab6{
                border: 1px solid #000000;
            }
            table #tab2{
                padding: 50px 135px 10px;
            }
            table #tab3{
                padding: 10px 90px 10px;
            }  
            table #tab4{
                padding: 5px 90px 5px;
            }
            table #tab5{
                padding: 5px 90px 5px;
            }            
            table #tab6{
                padding: 50px 90px 10px;
            }
            @media print {
			    .pagebreak { 
			    	page-break-before: always;
			        clear: both; 
			      } /* page-break-after works, as well */
			     .vistingcard{
			     	margin-top: 8.5px !important;
			     } 
			     .barcode9x13small{
			     	margin-top: 18px !important;
			     	/*width: 720px;*/
			     } 
			     .barcode13x16small{
			     	margin-top: 10px !important;
			     	/*width: 650px;*/
			     } 
			     .barcode13x16big{
			     	margin-top: 8.5px !important;
			     	/*width: 1250px;*/
			     	/*min-width: 1024px !important;*/
			     } 
			     .barcode17x20small{
			     	margin-top: 15px !important;
			     	width: 850px;
			     } 
			     .barcode17x20big{
			     	margin-top: 10px !important;
			     	/*width: 1550px;*/
			     } 
			}
        </style>
    </head>
    <body>
        @if(isset($barcode_data))
            @foreach($barcode_data as $data)
                @if($data['end_use'] == 'Upholostery')
                    <?php  $end_use = '<img style="height: 18px;" src="' . url("public/default/images/end-use-3.png") . '" alt="Image"/><img style="height: 18px;" src="' . url("public/default/images/end-use-4.png") . '" alt="Image"/>';
                    ?>
                @elseif($data['end_use'] == 'Curtians')
                    <?php  $end_use = '<img style="height: 18px;" src="' . url("public/default/images/end-use-1.png") . '" alt="Image"/><img style="height: 18px;" src="' . url("public/default/images/end-use-2.png") . '" alt="Image"/>';
                    ?>
                @else
                    <?php  $end_use = '<img style="height: 18px;" src="' . url("public/default/images/end-use.png") . '" alt="Image"/>';
                    ?>
                @endif
            @endforeach
            @if($barcode_type == 1)
                    @php $i=1 @endphp
                    @foreach($barcode_data as $data)
                    <table style="margin-left:auto; margin-right:auto;" cellspacing='10'>
                    <tr>
                       <td>
                            <table id="tab1" class="vistingcard" cellpadding="3">
                                <tr style="margin:5px;">
                                    <td colspan="2" style="padding:5px; width:300px !important;white-space:nowrap !important;text-overflow: ellipsis;overflow: hidden;text-align:center;">
                                    	<img style="width: 95;height: 42;" src="{{ asset('public/default/images/login_logo_sutlej.png') }}">
                                    	<br>
                                    	<br>
                                    	<img src="data:image/png;base64,{{DNS1D::getBarcodePNG($data['unique_sku_id'], 'C93')}}" alt="barcode" />
                                    	<br>
                                        <tr>
                                           <td width="50%"><span style="font-family:arial black;font-size:12px;"><b>SKU ID: </b>{{$data['unique_sku_id']}}<br><b>Request No: </b>{{$data['request_no']}}<br><b>Quality: </b>{{$data['quality_name']}}<br><b>Design: </b>{{$data['design_name']}}</span></td>
                                           <td><span style="font-family:arial black;font-size:12px;"><b>Shade: </b>{{$data['shade_name']}}<br><b>Width: </b>{{$data['finish_width']}}<br><b>GSM: </b>{{$data['gsm']}}<br><b>Composition: </b>{{$data['composition']}}</span></td>
                                       </tr>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    </table>
                @php $i=$i+1; @endphp

                @if ($i <= count($barcode_data))
                	<div class="pagebreak"></div>
				@endif
                @endforeach
            
            @elseif($barcode_type == 2)
                    @php $i=1 @endphp
                    @foreach($barcode_data as $data)
                    
                    <table style="margin-left:auto;margin-right:auto;" cellspacing='10'>
            	    <tr>
                        <td>
                            <table id="tab2" cellpadding="3" class="barcode9x13small" style="width:720px" cellspacing="5">
                                 <tr style="border-bottom: 2px solid #000000; margin:5px;">
                                    <td  colspan="3" style="border-bottom: 2px solid #000000;padding:5px; white-space:nowrap !important;text-overflow: ellipsis;line-height: 18px;overflow: hidden; font-size: 23px;font-weight: bold; font-family: sans-serif;">COLLECTION: {{ $collection_input }}   <img style="margin-bottom: -7px; margin-left: 3%;" src="data:image/png;base64,{{DNS1D::getBarcodePNG($data['unique_sku_id'], 'C93')}}" alt="barcode" /><br><br>
                                    </td>
                                 </tr>
                                        <tr style="border:1px solid #000000;margin:5px;">
                                          
                                           <td width="10%" align="center" style="border-right: 2px solid #000000;">
                                                <table>
                                                    <tbody>
                                                        <tr>
                                                            <td style="text-align: center;">
                                                                <img style="width: 95px;" src="{{ asset('public/default/images/login_logo_sutlej.png') }}">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-family: monospace; text-align: center; font-weight: bold; letter-spacing: 2.2px;font-size: 24px;">
                                                                NESTERRA
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-size: 10px; letter-spacing: 1.1; font-family: monospace;font-weight: bold; text-align: center;">
                                                                OUTDOORS INDOOR
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                           <td width="40%"  valign="top" style="border-right: 2px solid #000000;padding-top: 10px;">
                                            <table>
                                            <tr>
                                                <td><span style="font-family:sans-serif;font-size:16px;">QUALITY</span></td>
                                                <td style="width: 15px; text-align: center;">:</td>
                                                <td><span style="font-family:sans-serif;font-size:16px;">{{$data['quality_name']}}</span></td>
                                            </tr>
                                            <tr>
                                                <td><span style="font-family:sans-serif;font-size:16px;">DESIGN</span></td>
                                                <td style="width: 15px; text-align: center;">:</td>
                                                <td><span style="font-family:sans-serif;font-size:16px;">{{$data['design_name']}}</span></td>
                                            </tr>
                                            <tr>
                                                <td><span style="font-family:sans-serif;font-size:16px;">SHADE</span></td>
                                                <td style="width: 15px; text-align: center;">:</td>
                                                <td><span style="font-family:sans-serif;font-size:16px;">{{$data['shade_name']}}</span></td>
                                            </tr>
                                            <tr>
                                                <td><span style="font-family:sans-serif;font-size:16px;">COM.</span></td>
                                                <td style="width: 15px; text-align: center;">:</td>
                                                <td><span style="font-family:sans-serif;font-size:16px;">{{$data['composition']}}</span></td>
                                            </tr>
                                            </table>
                                            </td>
                                           <td width="50%" valign="top" style="padding-top: 10px;">
                                             <table>
                                                <tr>
                                                    <td><span style="font-family:sans-serif;font-size:16px;">MTR</span></td>
                                                    <td style="width: 15px; text-align: center;">:</td>
                                                    <td><span style="font-family:sans-serif;font-size:16px;">472</span></td>
                                                </tr>
                                                <tr>
                                                    <td><span style="font-family:sans-serif;font-size:16px;">WIDTH</span></td>
                                                    <td style="width: 15px; text-align: center;">:</td>
                                                    <td><span style="font-family:sans-serif;font-size:16px;">{{$data['finish_width']}}</span></td>
                                                </tr>
                                                <tr>
                                                    <td><span style="font-family:sans-serif;font-size:16px;">Care Instruction</span></td>
                                                    <td style="width: 15px; text-align: center;">:</td>
                                                    <td><span style="font-family:sans-serif;font-size:16px;"><img style="height: 18px;" src="{{ asset('public/default/images/care-instruction.png') }}" /></span></td>
                                                </tr>
                                                <tr>
                                                    <td><span style="font-family:sans-serif;font-size:16px;">END USE</span></td>
                                                    <td style="width: 15px; text-align: center;">:</td>
                                                    <td><span style="font-family:sans-serif;font-size:16px;">{!! $end_use !!}</span></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3"><span style="font-family:sans-serif;font-size:14px;">Colour Shades may vary from dye lot to dye lot</td></span></td>
                                                </tr>
                                            </table>
                                       </tr>
                                   
                               
                            </table>
                        </td>
                        </tr>
                    </table>
                @php $i=$i+1; @endphp

                @if ($i <= count($barcode_data))
                	<div class="pagebreak"></div>
				@endif
                @endforeach
            

            @elseif($barcode_type == 3)
            @php $i=1 @endphp
			 @foreach($barcode_data as $data)
             <table style="margin-left:auto;margin-right:auto;" cellspacing='10'>
                <tr>
                    <td>
                            <table id="tab3" class="barcode13x16small" cellpadding="3" style="width:800px" cellspacing="5">
                                 <tr style="border-bottom: 2px solid #000000;margin:5px;">
                                    <td  colspan="3" style="border-bottom: 2px solid #000000;padding:5px;white-space:nowrap !important;text-overflow: ellipsis;overflow: hidden; font-size: 15px;font-weight: bold; font-family: sans-serif;">COLLECTION: {{ $collection_input }}   <img style="margin-bottom: -7px; margin-left: 3%;" src="data:image/png;base64,{{DNS1D::getBarcodePNG($data['unique_sku_id'], 'C93')}}" alt="barcode" /><br><br>
                                    </td>
                                 </tr>
                                        <tr style="border:1px solid #000000;margin:5px;">
                                          
                                           <td width="15%" align="center" style="border-right: 2px solid #000000;">
                                                <table>
                                                    <tbody>
                                                        <tr>
                                                            <td style="text-align: center;">
                                                                <img style="width: 95px;" src="{{ asset('public/default/images/login_logo_sutlej.png') }}">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-family: monospace; text-align: center; font-weight: bold; letter-spacing: 1.4px;font-size: 18px;">
                                                                NESTERRA
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-size: 9px; letter-spacing: 1.1; font-family: monospace;font-weight: bold; text-align: center;">
                                                                OUTDOORS INDOOR
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                           <td width="40%"  valign="top" style="border-right: 2px solid #000000;padding-top: 10px;">
                                            <table>
                                            <tr>
                                                <td><span style="font-family:sans-serif;font-size:12px;">QUALITY</span></td>
                                                <td style="width: 15px; text-align: center;">:</td>
                                                <td><span style="font-family:sans-serif;font-size:12px;">{{$data['quality_name']}}</span></td>
                                            </tr>
                                            <tr>
                                                <td><span style="font-family:sans-serif;font-size:12px;">DESIGN</span></td>
                                                <td style="width: 15px; text-align: center;">:</td>
                                                <td><span style="font-family:sans-serif;font-size:12px;">{{$data['design_name']}}</span></td>
                                            </tr>
                                            <tr>
                                                <td><span style="font-family:sans-serif;font-size:12px;">SHADE</span></td>
                                                <td style="width: 15px; text-align: center;">:</td>
                                                <td><span style="font-family:sans-serif;font-size:12px;">{{$data['shade_name']}}</span></td>
                                            </tr>
                                            <tr>
                                                <td><span style="font-family:sans-serif;font-size:12px;">COM.</span></td>
                                                <td style="width: 15px; text-align: center;">:</td>
                                                <td><span style="font-family:sans-serif;font-size:12px;">{{$data['composition']}}</span></td>
                                            </tr>
                                            </table>
                                            </td>
                                           <td width="45%" valign="top" style="padding-top: 10px;">
                                             <table>
                                                <tr>
                                                    <td><span style="font-family:sans-serif;font-size:12px;">MTR</span></td>
                                                    <td style="width: 15px; text-align: center;">:</td>
                                                    <td><span style="font-family:sans-serif;font-size:12px;">472</span></td>
                                                </tr>
                                                <tr>
                                                    <td><span style="font-family:sans-serif;font-size:12px;">WIDTH</span></td>
                                                    <td style="width: 15px; text-align: center;">:</td>
                                                    <td><span style="font-family:sans-serif;font-size:12px;">{{$data['finish_width']}}</span></td>
                                                </tr>
                                                <tr>
                                                    <td><span style="font-family:sans-serif;font-size:12px;">Care Instruction</span></td>
                                                    <td style="width: 15px; text-align: center;">:</td>
                                                    <td><span style="font-family:sans-serif;font-size:12px;"><img style="height: 18px;" src="{{ asset('public/default/images/care-instruction.png') }}" /></span></td>
                                                </tr>
                                                <tr>
                                                    <td><span style="font-family:sans-serif;font-size:12px;">END USE</span></td>
                                                    <td style="width: 15px; text-align: center;">:</td>
                                                    <td><span style="font-family:sans-serif;font-size:12px;">{!! $end_use !!}</span></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3"><span style="font-family:sans-serif;font-size:10px;">Colour Shades may vary from dye lot to dye lot</span></td>
                                                </tr>
                                            </table>
                                        </td>
                                       </tr>
                                   
                               
                            </table>
                        </td>
                        </tr>
                    </table>

                      @php $i=$i+1; @endphp

                @if ($i <= count($barcode_data))
                	
				@endif
                @endforeach

            @elseif($barcode_type == 4)
             

   			@php $i=1 @endphp
             @foreach($barcode_data as $data)
             <table style="margin-left:auto;margin-right:auto;" cellspacing='10'>
                <tr>
                    <td>
                        <table id="tab4" class="barcode13x16big" cellpadding="3" style="width:1500px" cellspacing="5">
                                 <tr style="border-bottom: 2px solid #000000;margin:5px;">
                                    
                                    <td colspan="4" style="border-bottom: 2px solid #000000;padding:5px 5px 15px 5px;width:850px !important;white-space:nowrap !important;text-overflow: ellipsis;overflow: hidden; font-size: 23px;font-weight: bold; font-family: sans-serif;">COLLECTION: {{ $collection_input }}   <img style="margin-bottom: -7px; margin-left: 3%;" src="data:image/png;base64,{{DNS1D::getBarcodePNG($data['unique_sku_id'], 'C93')}}" alt="barcode" />
                                    	<br>

                                    </td>
                                 </tr>
                                        <tr style="border:1px solid #000000;margin:5px;">
                                          
                                           <td width="15%" align="center" style="border-right: 2px solid #000000;">
                                                <table>
                                                    <tbody>
                                                        <tr>
                                                            <td style="text-align: center;">
                                                                <img style="width: 95px;" src="{{ asset('public/default/images/login_logo_sutlej.png') }}">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-family: monospace; text-align: center; font-weight: bold; letter-spacing: 2.2px;font-size: 24px;">
                                                                NESTERRA
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-size: 11px; letter-spacing: 1.1; font-family: monospace;font-weight: bold; text-align: center;">
                                                                OUTDOORS INDOOR
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                           <td width="30%"  valign="top" style="border-right: 2px solid #000000;padding-top: 2px;">
                                            <table>
                                            <tr>
                                                <td><span style="font-family:sans-serif;font-size:18px;">QUALITY</span></td>
                                                <td style="width: 15px; text-align: center;">:</td>
                                                <td><span style="font-family:sans-serif;font-size:18px;">{{$data['quality_name']}}</span></td>
                                            </tr>
                                            <tr>
                                                <td><span style="font-family:sans-serif;font-size:18px;">DESIGN</span></td>
                                                <td style="width: 15px; text-align: center;">:</td>
                                                <td><span style="font-family:sans-serif;font-size:18px;">{{$data['design_name']}}</span></td>
                                            </tr>
                                            <tr>
                                                <td><span style="font-family:sans-serif;font-size:18px;">SHADE</span></td>
                                                <td style="width: 15px; text-align: center;">:</td>
                                                <td><span style="font-family:sans-serif;font-size:18px;">{{$data['shade_name']}}</span></td>
                                            </tr>
                                            <tr>
                                                <td><span style="font-family:sans-serif;font-size:18px;">COM.</span></td>
                                                <td style="width: 15px; text-align: center;">:</td>
                                                <td><span style="font-family:sans-serif;font-size:18px;">{{$data['composition']}}</span></td>
                                            </tr>
                                            </table>
                                            </td>
                                           <td width="30%" valign="top" style="padding-top: 2px; border-right: 2px solid #000000;">
                                             <table>
                                                <tr>
                                                    <td><span style="font-family:sans-serif;font-size:18px;">MTR</span></td>
                                                    <td style="width: 15px; text-align: center;">:</td>
                                                    <td><span style="font-family:sans-serif;font-size:18px;">472</span></td>
                                                </tr>
                                                <tr>
                                                    <td><span style="font-family:sans-serif;font-size:18px;">WIDTH</span></td>
                                                    <td style="width: 15px; text-align: center;">:</td>
                                                    <td><span style="font-family:sans-serif;font-size:18px;">{{$data['finish_width']}}</span></td>
                                                </tr>
                                                <tr>
                                                    <td><span style="font-family:sans-serif;font-size:18px;">Care Instruction</span></td>
                                                    <td style="width: 15px; text-align: center;">:</td>
                                                    <td><span style="font-family:sans-serif;font-size:18px;"><img style="height: 18px;" src="{{ asset('public/default/images/care-instruction.png') }}" /></span></td>
                                                </tr>
                                                <tr>
                                                    <td><span style="font-family:sans-serif;font-size:18px;">END USE</span></td>
                                                    <td style="width: 15px; text-align: center;">:</td>
                                                    <td><span style="font-family:sans-serif;font-size:18px;">{!! $end_use !!}</span></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3"><span style="font-family:sans-serif;font-size:14px;">Colour Shades may vary from dye lot to dye lot</span></td>
                                                </tr>
                                            </table>
                                        </td>
                                           <td width="25%" align="center">
                                                <img style="max-width: 95%;" src="{{ asset('public/default/images/oeko-tex.png') }}">
                                            </td>
                                       </tr>
                                   
                               
                            </table>
                        </td>
                            </tr> 
                                          </table>
                     @php $i=$i+1; @endphp

                @if ($i <= count($barcode_data))
                	<div class="pagebreak"></div>
				@endif
                @endforeach

            @elseif($barcode_type == 5)
            @php $i=1 @endphp
			@foreach($barcode_data as $data)
             <table style="margin-left:auto;margin-right:auto;" cellspacing='10'>
                <tr>
                    <td>
                            <table id="tab5" class="barcode17x20small" cellpadding="3" style="width:800px" cellspacing="5">
                                 <tr style="border-bottom: 2px solid #000000;margin:5px;">
                                    <td  colspan="4" style="border-bottom: 2px solid #000000;padding:5px 5px 15px 5px;white-space:nowrap !important;text-overflow: ellipsis;overflow: hidden; font-size: 23px;font-weight: bold; font-family: sans-serif;">COLLECTION: {{ $collection_input }}   <img style="margin-bottom: -7px; margin-left: 3%;" src="data:image/png;base64,{{DNS1D::getBarcodePNG($data['unique_sku_id'], 'C93')}}" alt="barcode" /><br>
                                    </td>
                                 </tr>
                                        <tr style="border:1px solid #000000;margin:5px;">
                                          
                                           <td width="15%" align="center" style="border-right: 2px solid #000000;">
                                                <table>
                                                    <tbody>
                                                        <tr>
                                                            <td style="text-align: center;">
                                                                <img style="width: 95px;" src="{{ asset('public/default/images/login_logo_sutlej.png') }}">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-family: monospace; text-align: center; font-weight: bold; letter-spacing: 2.2px;font-size: 24px;">
                                                                NESTERRA
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-size: 11px; letter-spacing: 1.1; font-family: monospace;font-weight: bold; text-align: center;">
                                                                OUTDOORS INDOOR
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                           <td width="30%"  valign="top" style="border-right: 2px solid #000000;padding-top: 2px;">
                                            <table>
                                            <tr>
                                                <td><span style="font-family:sans-serif;font-size:18px;">QUALITY</span></td>
                                                <td style="width: 15px; text-align: center;">:</td>
                                                <td><span style="font-family:sans-serif;font-size:18px;">{{$data['quality_name']}}</span></td>
                                            </tr>
                                            <tr>
                                                <td><span style="font-family:sans-serif;font-size:18px;">DESIGN</span></td>
                                                <td style="width: 15px; text-align: center;">:</td>
                                                <td><span style="font-family:sans-serif;font-size:18px;">{{$data['design_name']}}</span></td>
                                            </tr>
                                            <tr>
                                                <td><span style="font-family:sans-serif;font-size:18px;">SHADE</span></td>
                                                <td style="width: 15px; text-align: center;">:</td>
                                                <td><span style="font-family:sans-serif;font-size:18px;">{{$data['shade_name']}}</span></td>
                                            </tr>
                                            <tr>
                                                <td><span style="font-family:sans-serif;font-size:18px;">COM.</span></td>
                                                <td style="width: 15px; text-align: center;">:</td>
                                                <td><span style="font-family:sans-serif;font-size:18px;">{{$data['composition']}}</span></td>
                                            </tr>
                                            </table>
                                            </td>
                                           <td width="30%" valign="top" style="padding-top: 2px; border-right: 2px solid #000000;">
                                             <table>
                                                <tr>
                                                    <td><span style="font-family:sans-serif;font-size:18px;">MTR</span></td>
                                                    <td style="width: 15px; text-align: center;">:</td>
                                                    <td><span style="font-family:sans-serif;font-size:18px;">472</span></td>
                                                </tr>
                                                <tr>
                                                    <td><span style="font-family:sans-serif;font-size:18px;">WIDTH</span></td>
                                                    <td style="width: 15px; text-align: center;">:</td>
                                                    <td><span style="font-family:sans-serif;font-size:18px;">{{$data['finish_width']}}</span></td>
                                                </tr>
                                                <tr>
                                                    <td><span style="font-family:sans-serif;font-size:18px;">Care Instruction</span></td>
                                                    <td style="width: 15px; text-align: center;">:</td>
                                                    <td><span style="font-family:sans-serif;font-size:18px;"><img style="height: 18px;" src="{{ asset('public/default/images/care-instruction.png') }}" /></span></td>
                                                </tr>
                                                <tr>
                                                    <td><span style="font-family:sans-serif;font-size:18px;">END USE</span></td>
                                                    <td style="width: 15px; text-align: center;">:</td>
                                                    <td><span style="font-family:sans-serif;font-size:18px;">{!! $end_use !!}</span></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3"><span style="font-family:sans-serif;font-size:14px;">Colour Shades may vary from dye lot to dye lot</span></td>
                                                </tr>
                                            </table>
                                        </td>
                                           <td width="25%" align="center">
                                                <img style="max-width: 95%;" src="{{ asset('public/default/images/oeko-tex.png') }}">
                                            </td>
                                       </tr>
                                   
                               
                            </table>
                        </td>
                         </tr> 
						 </table>
                     @php $i=$i+1; @endphp

                @if ($i <= count($barcode_data))
                	<div class="pagebreak"></div>
				@endif
                @endforeach

            @elseif($barcode_type == 6)
             
			@php $i=1 @endphp
			@foreach($barcode_data as $data)
             <table style="margin-left:auto;margin-right:auto;" cellspacing='10'>
                <tr>
                    
                        <td>
                            <table id="tab4" class="barcode17x20big" cellpadding="3" style="width:1600px" cellspacing="5">
                                 <tr style="border-bottom: 2px solid #000000;margin:5px;">
                                    <td  colspan="4" style="border-bottom: 2px solid #000000;padding:5px 5px 15px 5px;line-height:30px;width:  850px !important;white-space:nowrap !important;text-overflow: ellipsis;overflow: hidden; font-size: 20px;font-weight: bold; font-family: sans-serif;">COLLECTION: {{ $collection_input }}   <img style="margin-bottom: -7px; margin-left: 3%;" src="data:image/png;base64,{{DNS1D::getBarcodePNG($data['unique_sku_id'], 'C93')}}" alt="barcode" /><br>
                                    </td>
                                 </tr>
                                        <tr style="border:1px solid #000000;margin:5px;">
                                          
                                           <td width="15%" align="center" style="border-right: 2px solid #000000;">
                                                <table>
                                                    <tbody>
                                                        <tr>
                                                            <td style="text-align: center;">
                                                                <img style="width: 95px;" src="{{ asset('public/default/images/login_logo_sutlej.png') }}">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-family: monospace; text-align: center; font-weight: bold; letter-spacing: 4.5px;font-size: 30px;">
                                                                NESTERRA
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-size: 13px; letter-spacing: 2.1; font-family: monospace;font-weight: bold; text-align: center;">
                                                                OUTDOORS INDOOR
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                           <td width="30%"  valign="top" style="border-right: 2px solid #000000;padding-top: 2px;">
                                            <table>
                                            <tr>
                                                <td><span style="font-family:sans-serif;font-size:20px;">QUALITY</span></td>
                                                <td style="width: 15px; text-align: center;font-size:15px;">:</td>
                                                <td><span style="font-family:sans-serif;font-size:20px;">{{$data['quality_name']}}</span></td>
                                            </tr>
                                            <tr>
                                                <td><span style="font-family:sans-serif;font-size:20px;">DESIGN</span></td>
                                                <td style="width: 15px; text-align: center;font-size:15px;">:</td>
                                                <td><span style="font-family:sans-serif;font-size:20px;">{{$data['design_name']}}</span></td>
                                            </tr>
                                            <tr>
                                                <td><span style="font-family:sans-serif;font-size:20px;">SHADE</span></td>
                                                <td style="width: 15px; text-align: center;font-size:15px;">:</td>
                                                <td><span style="font-family:sans-serif;font-size:20px;">{{$data['shade_name']}}</span></td>
                                            </tr>
                                            <tr>
                                                <td><span style="font-family:sans-serif;font-size:20px;">COM.</span></td>
                                                <td style="width: 15px; text-align: center;font-size:15px;">:</td>
                                                <td><span style="font-family:sans-serif;font-size:20px;">{{$data['composition']}}</span></td>
                                            </tr>
                                            </table>
                                            </td>
                                           <td width="30%" valign="top" style="padding-top: 2px; border-right: 2px solid #000000;">
                                             <table>
                                                <tr>
                                                    <td><span style="font-family:sans-serif;font-size:20px;">MTR</span></td>
                                                    <td style="width: 15px; text-align: center;font-size:15px;">:</td>
                                                    <td><span style="font-family:sans-serif;font-size:20px;">472</span></td>
                                                </tr>
                                                <tr>
                                                    <td><span style="font-family:sans-serif;font-size:20px;">WIDTH</span></td>
                                                    <td style="width: 15px; text-align: center;font-size:15px;">:</td>
                                                    <td><span style="font-family:sans-serif;font-size:20px;">{{$data['finish_width']}}</span></td>
                                                </tr>
                                                <tr>
                                                    <td><span style="font-family:sans-serif;font-size:20px;">Care Instruction</span></td>
                                                    <td style="width: 15px; text-align: center;font-size:15px;">:</td>
                                                    <td><span style="font-family:sans-serif;font-size:20px;"><img style="height: 25px;" src="{{ asset('public/default/images/care-instruction.png') }}" /></span></td>
                                                </tr>
                                                <tr>
                                                    <td><span style="font-family:sans-serif;font-size:20px;">END USE</span></td>
                                                    <td style="width: 15px; text-align: center;font-size:15px;">:</td>
                                                    <td><span style="font-family:sans-serif;font-size:20px;">{!! $end_use !!}</span></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3"><span style="font-family:sans-serif;font-size:15px;">Colour Shades may vary from dye lot to dye lot</span></td>
                                                </tr>
                                            </table>
                                        </td>
                                           <td width="25%" align="center">
                                                <img style="max-width: 95%;" src="{{ asset('public/default/images/oeko-tex.png') }}">
                                            </td>
                                       </tr>
                                   
                               
                            </table>
                        </td>
                         </tr> 
						 </table>
                     @php $i=$i+1; @endphp

                @if ($i <= count($barcode_data))
                	<div class="pagebreak"></div>
				@endif
                @endforeach
            
            @endif
        @endif
        <script src="{{ asset('public/default/js/jquery.js') }}"></script>
        <script type="text/javascript">
            
            $(document).ready(function(){
            	
            	setTimeout(function(){
            		window.print();
            	}, 3000);
            })
        </script>
    </body>
</html>
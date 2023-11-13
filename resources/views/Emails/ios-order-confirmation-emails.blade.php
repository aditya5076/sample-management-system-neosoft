<html>
    <head> 
        <meta name="viewport" content="width=device-width">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <style>
    /* -------------------------------------
        INLINED WITH htmlemail.io/inline
    ------------------------------------- */
    /* -------------------------------------
        RESPONSIVE AND MOBILE FRIENDLY STYLES
    ------------------------------------- */
    @media only screen and (max-width: 620px) {
      table[class=body] h1 {
        font-size: 28px !important;
        margin-bottom: 10px !important;
      }
      table[class=body] p,
            table[class=body] ul,
            table[class=body] ol,
            table[class=body] td,
            table[class=body] span,
            table[class=body] a {
        font-size: 16px !important;
      }
      table[class=body] .wrapper,
            table[class=body] .article {
        padding: 10px !important;
      }
      table[class=body] .content {
        padding: 0 !important;
      }
      table[class=body] .container {
        padding: 0 !important;
        width: 100% !important;
      }
      table[class=body] .main {
        border-left-width: 0 !important;
        border-radius: 0 !important;
        border-right-width: 0 !important;
      }
      table[class=body] .btn table {
        width: 100% !important;
      }
      table[class=body] .btn a {
        width: 100% !important;
      }
      table[class=body] .img-responsive {
        height: auto !important;
        max-width: 100% !important;
        width: auto !important;
      }
    }

    /* -------------------------------------
        PRESERVE THESE STYLES IN THE HEAD
    ------------------------------------- */
    @media all {
      .ExternalClass {
        width: 100%;
      }
      .ExternalClass,
            .ExternalClass p,
            .ExternalClass span,
            .ExternalClass font,
            .ExternalClass td,
            .ExternalClass div {
        line-height: 100%;
      }
      .apple-link a {
        color: inherit !important;
        font-family: inherit !important;
        font-size: inherit !important;
        font-weight: inherit !important;
        line-height: inherit !important;
        text-decoration: none !important;
      }
      #MessageViewBody a {
        color: inherit;
        text-decoration: none;
        font-size: inherit;
        font-family: inherit;
        font-weight: inherit;
        line-height: inherit;
      }
      .btn-primary table td:hover {
        /*background-color: #34495e !important;*/
      }
      .btn-primary a:hover {
       /* background-color: #34495e !important;
        border-color: #34495e !important;*/
      }
    }
    </style>
    </head>
    <body class="" style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">
    <table border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;">
      <tr>
        <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>
        <td class="container" style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; Margin: 0 auto;  padding: 10px;max-width: 600px;">
          <div class="content" style="box-sizing: border-box; display: block; Margin: 0 auto; padding: 10px;border: 2px solid black;">
            <div style="text-align: center; background-color: white; padding: 10px 0; border-radius: 15px 15px 0 0 ">
              <img src="<?php echo $message->embed(public_path()."/default/images/login_logo.png"); ?>" style="padding-top: 20px;">
              <br>
              <span style="font-size: 1.5rem">NESTERRA</span>
            </div>
            
            <!-- START CENTERED WHITE CONTAINER -->
            <table class="main" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 0 0 15px 15px;table-layout: fixed;">

              <!-- START MAIN CONTENT AREA -->
              <tr>
                <td class="wrapper" style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">
                  <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                    <tr>
                      <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">
                        <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Hi,</p>
                        <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Thank you for confirming your order with Nesterra.<br> Please find your order details below:</p>
                        <p><b>Order ID: </b>{{ $orderID }}</p>
                        <p><b>Order Date: </b>{{ $orderDate }}</p>
                        <p><b>Order Created By: </b>{{ $orderCreatedBy }}</p>
                        <p><b>Order Note: </b>{{ $orderNote }}</p>
                        <p><b>Customer Name: </b>{{ $customerName }}</p>
                        <p><b>Contact Person: </b>{{ $customerContactPerson }}</p>
                        <p><b>Customer Email: </b>{{ $customerEmail }}</p>
                        <table border="0" cellpadding="0" cellspacing="0" class="btn btn-primary" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; box-sizing: border-box;">
                          <tbody>
                            <tr>
                              <td align="left" style="font-family: sans-serif; font-size: 14px; vertical-align: top; padding-bottom: 15px;">
                                <table border="2" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: auto;">
                                  <tbody>
                                    <thead>
                                        
                                        <th style="padding: 10px 0">Product</th>
                                        <th style="padding: 10px 0">Quality</th>
                                        <th style="padding: 10px 0">Design</th>
                                        <th style="padding: 10px 0">Shade</th>
                                        <th style="padding: 10px 0">Product Note</th>
                                        <th style="padding: 10px 5px">Qty</th>
                                        {{-- <th style="padding: 10px 0">Listing Price</th> --}}
                                        <th style="padding: 10px 0">Finalised Price</th>
                                        <th style="padding: 10px 0">Total Price</th>
                                    <thead>
                                    <tbody>
                                        @foreach($ordersData as $key=>$getData)
                                        <tr>
                                            
                                            <td style="padding: 2px; font-size: 14px; vertical-align: middle; text-align: center;word-break: break-all;">{{ $getData['unique_sku_id'] }}</td>
                                            <td style="padding: 2px; font-size: 14px; vertical-align: middle; text-align: center;word-break: break-all;">{{ $getData['quality'] }}</td>
                                            <td style="padding: 2px; font-size: 14px; vertical-align: middle; text-align: center;word-break: break-all;">{{ $getData['design_name'] }}</td>
                                            <td style="padding: 2px; font-size: 14px; vertical-align: middle; text-align: center;word-break: break-all;">{{ $getData['shade'] }}</td>
                                            <td style="padding: 5px; font-size: 14px; vertical-align: middle; text-align: center;min-width: 135px;word-break: break-all;">{{ $getData['productnote'] }}</td>
                                            <td style="padding: 2px; font-size: 14px; vertical-align: middle; text-align: center;word-break: break-all;">{{ $getData['qty'] }}</td>
                                            {{-- <td style="padding: 2px; font-size: 14px; vertical-align: middle; text-align: center;word-break: break-all;">{{ $getData['p3_price'] }}</td> --}}
                                            <td style="padding: 2px; font-size: 14px; vertical-align: middle; text-align: center;word-break: break-all;">{{ $getData['productprice'] }}</td>
                                            <td style="padding: 2px; font-size: 14px; vertical-align: middle; text-align: center;word-break: break-all;">
                                            {{ $getData['total_calculated_price'] }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>

                                    <tr>
                                      <td colspan="7" align="right" style="padding-right: 20px; font-weight: bold;"> 
                                        Total
                                      </td>
                                      <td class="total_price" style="padding: 0 10px; font-size: 14px; vertical-align: middle; text-align: center;"> 
                                        {{ $productPrice }}
                                      </td>
                                    </tr>


                                  </tbody>
                                </table>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                       </td>
                    </tr>
                  </table>
                </td>
              </tr>

            <!-- END MAIN CONTENT AREA -->
            </table>

            <!-- START FOOTER -->
            <div class="footer" style="clear: both; Margin-top: 10px; text-align: center; width: 100%;">
              <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                
              </table>
            </div>
            <!-- END FOOTER -->

          <!-- END CENTERED WHITE CONTAINER -->
          </div>
        </td>
        <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>
      </tr>
    </table>
  </body>
</html>



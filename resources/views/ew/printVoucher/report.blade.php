<html moznomarginboxes mozdisallowselectionprint>
    <head>
        <!--<meta charset="utf-8">-->
        <meta http-equiv="X-UA-Compatible" content="IE-edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Report Preview</title>
        <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&amp;lang=en" />
        <!-- <link type="text/css" rel="stylesheet" href="{!! asset('public/css/bootstrap.css') !!}" />
        <link rel="stylesheet" href="{!! asset('public/css/font-awesome.min.css') !!}"> -->
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="{!! asset('public/css/print-page.css') !!}">
        <style type="text/css">
            <?php
                $table_font = (isset($pdf)) ? '12px' : '12px';
            ?>
            @page {
                margin: 10%;
            }
            @media print {
                .voucher {
                    page-break-after: always;
                }
            }
            .print_button {
                position: relative;
                margin:20px auto;
                text-align: center;
                top: 0px;
                padding-right: 0px;
            }
            .voucher {
                width: 700px;
                position: relative;
                margin: 20px auto;
                padding: 15px;
                border: 2px solid #666;
                margin-bottom: 60px;
            }
            .header {
                text-align: center;
                margin: 5px;
            }
            .company_name {
                font-size: 20px;
            }
            .address {
                font-size: 14px;
            }
            .contact {
                font-size: 14px;
            }
            .title {
                margin: 20px auto;
                font-size: 16px;
            }
            .border-none{
                border: 0px!important;
            }
            table td {
                border: none !important;
                font-size: {{$table_font}}
            }
            tbody,td,th {
                font-size: 12px;
            }
            .particulars {
                width: 100%;
                border: 1px solid #666 !important;
            }
            .particulars td {
                border: 1px solid #666 !important;
            }
            .voucher_footer td {
                text-decoration: overline;]
            }
        </style>
    </head>
    <body>
        <div class="print_button">
            <button class="btn btn-default" onclick="printDocument()"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
            <a href="{{$pdf_url}}" class="btn btn-default" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>
        </div>
        @if(count($vouchers)>0)
            @foreach($vouchers as $voucher)
            <?php
                $transaction_date = DateTime::createFromFormat('Y-m-d', $voucher[0]->transaction_date)->format('d/m/Y');
            ?>
                <div class="voucher">
                    <table width="100%"  style="border: none;">
                        <tr>
                            <td class="text-center" align="center" colspan="4" class="border-none" style="border: none;">
                                <div class="header">
                                <?php echo Helper::companyDetails(); ?>
                                    <p class="title">{{$voucher_names[$voucher[0]->transaction_status-1]}}</p>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td width="50%" colspan="2" align="left" valign="middle" >Voucher No : {{$voucher[0]->voucher_type.'-'.$voucher[0]->instrument_no}}</td>
                            <td width="50%" colspan="2">&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="50%" colspan="2" align="left" valign="middle">Transaction No : {{$voucher[0]->transaction_no}}</td>
                            <td width="50%" colspan="2" align="right" valign="middle">Date: {{$transaction_date}}</td>
                        </tr>
                        <tr>
                            <td colspan="4" height="10"></td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <table class="particulars">
                                    <tr>
                                        <td width="15%" align="left" valign="middle">Code</td>
                                        <td align="left" valign="middle">Particulars</td>
                                        <td width="15%" align="right" valign="middle">Debit</td>
                                        <td width="15%" align="right" valign="middle">Credit</td>
                                    </tr>
                                    <?php
                                        $debit_amount = 0;
                                        $credit_amount = 0;
                                    ?>
                                    @foreach($voucher as $v)
                                    <?php
                                        //Debit Credit
                                        $debit_amount += $v->debit_amount;
                                        $credit_amount += $v->credit_amount;
                                    ?>
                                        <tr>
                                            <td>{{$v->account_code}}</td>
                                            <td>{{$v->account_head}}</td>
                                            <td align="right" valign="middle">{{number_format($v->debit_amount, 2)}}</td>
                                            <td align="right" valign="middle">{{number_format($v->credit_amount, 2)}}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td align="right" colspan="2"><strong>Total:</strong></td>
                                        <td align="right" valign="middle"><strong>{{number_format($debit_amount, 2)}}</strong></td>
                                        <td align="right" valign="middle"><strong>{{number_format($credit_amount, 2)}}</strong></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">&nbsp;</td>
                        </tr>
                        <tr>
                            <?php
                                $text_format = new \NumberFormatter( locale_get_default(), \NumberFormatter::SPELLOUT );
                                $amount_in_word = $text_format->format($debit_amount);
                            ?>
                            <td colspan="4" style="text-transform: capitalize;"><strong>In Word: {{$amount_in_word}} Taka Only</strong></td>
                        </tr>
                        <tr>
                            <td colspan="4"><strong>Particulars:</strong> {{@$voucher[0]->remarks}}</td>
                        </tr>
                        <tr>
                            <td colspan="4" height="50"></td>
                        </tr>
                        <tr class="voucher_footer">
                            <td width="16%" align="left" valign="middle">Prepared by</td>
                            <td width="34%" align="center" valign="middle">Accountant</td>
                            <td width="25%" align="center" valign="middle">Amount Received by</td>
                            <td width="25%" align="right" valign="middle">Approved by</td>
                        </tr>
                    </table>
                </div>
            @endforeach
        @else
            <div class="voucher">
                <p class="text-center">Data not found..</p>
            </div>
        @endif
    </body>
    <script type="text/javascript">
        function printDocument() {
            window.print();
        }
    </script>
</html>

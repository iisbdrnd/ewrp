<html moznomarginboxes mozdisallowselectionprint>
    <head>
        <!--<meta charset="utf-8">-->
        <meta http-equiv="X-UA-Compatible" content="IE-edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>Report Preview</title>
        <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&amp;lang=en" />
        <!-- <link type="text/css" rel="stylesheet" href="{!! asset('public/css/bootstrap.css') !!}" />
        <link rel="stylesheet" href="{!! asset('public/css/font-awesome.min.css') !!}"> -->
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="{!! asset('public/css/print-page.css') !!}">
        <style type="text/css">
            <?php
                $table_font = (isset($pdf)) ? '9px' : '12px';
            ?>
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
                font-size: 16px;
            }
            .border-none{
                border: none!important;
            }
            table td {
                font-size: {{$table_font}}
            }
            table tr:last-child {
               border: none !important;
            }
            table tr:last-child td {
               border: none !important;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="print_button">
                <button class="btn btn-default" onclick="printDocument()"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
                <a href="{{$pdf_url}}" class="btn btn-default" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>
            </div>
            <div class="header">
                <?php echo Helper::companyDetails(); ?>
                <p class="project">Project: {{Helper::projectName(@$project_id)}}</p>
                <p class="title">Transaction Posting @if($date_range==1) {{' ('.$from_date.' - '.$to_date.')'}} @endif </p>
            </div>
            <div class="report-data">
                <table class="table table-responsive">
                    <tr>
                        <td width="8%"><strong>Date</strong></td>
                        <td width="8%"><strong>Tr No.</strong></td>
                        <td width="8%" class="text-center"><strong>Instrument</strong></td>
                        <td width="10%"><strong>Code</strong></td>
                        <td><strong>Accounts</strong></td>
                        <td width="15%" class="text-right"><strong>Debit(Tk.)</strong></td>
                        <td width="15%" class="text-right"><strong>Credit(Tk.)</strong></td>
                    </tr>
                    @if(count($transactions)>0)
                        <?php
                            $debit_amount = 0;
                            $credit_amount = 0;
                            $prev_transaction = $transactions[0]->transaction_no;
                        ?>
                        @foreach($transactions as $transaction)
                            <?php
                                //Debit Credit
                                $debit_amount += $transaction->debit_amount;
                                $credit_amount += $transaction->credit_amount;
                                $curr_transaction = $transaction->transaction_no;
                            ?>
                            <?php
                            if($curr_transaction != $prev_transaction) {
                                echo '<tr class="border-none"><td colspan="7" border="0" class="border-none" height="15"></td></tr>';
                                $prev_transaction = $curr_transaction;
                            } ?>
                            <tr>
                                <td>{{DateTime::createFromFormat('Y-m-d', $transaction->transaction_date)->format('d/m/Y')}}</td>
                                <td>{{$transaction->transaction_no}}</td>
                                <td class="text-center">{{$transaction->voucher_type.'-'.$transaction->instrument_no}}</td>
                                <td>{{$transaction->account_code}}</td>
                                <td>{{$transaction->account_head}}</td>
                                <td class="text-right">{{number_format($transaction->debit_amount, 2)}}</td>
                                <td class="text-right">{{number_format($transaction->credit_amount, 2)}}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="5" class="text-right"><strong>Grand Total</strong></td>
                            <td class="text-right"><strong>{{number_format($debit_amount, 2)}}</strong></td>
                            <td class="text-right"><strong>{{number_format($credit_amount, 2)}}</strong></td>
                        </tr>
                    @else
                        <tr style="border: 1px solid #ccc !important;">
                            <td colspan="7" class="text-center" height="50" style="line-height: 50px;">Data not found!</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </body>
    <script type="text/javascript">
        function printDocument() {
            window.print();
        }
    </script>
</html>
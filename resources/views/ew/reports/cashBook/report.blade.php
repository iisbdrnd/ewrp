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
                $table_font = (isset($pdf)) ? '10px' : '11px';
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
                <p class="project">Project: {{Helper::projectName($project_id)}}</p>
                <p class="title">Cash Book of {{$account->account_head}} @if(isset($opening_date)) {{' ('.$from_date.' - '.$to_date.')'}} @endif </p>
            </div>
            <div class="report-data">
                <?php
                    $balance = 0;
                    $debit_amount = 0;
                    $credit_amount = 0;
                    if(isset($opening_balance)) {
                        $debit_amount = $opening_balance->total_debit;
                        $credit_amount = $opening_balance->total_credit;
                        if($account->main_code==2 || $account->main_code==4) {
                            $op_balance = ($debit_amount-$credit_amount);
                        } else {
                            $op_balance = ($credit_amount-$debit_amount);
                        }
                        $balance = $op_balance;
                    }
                    foreach($ledgers as $ledger) {
                        $debit_amount += $ledger->debit_amount;
                        $credit_amount += $ledger->credit_amount;
                        if($account->main_code==2 || $account->main_code==4) {
                            $balance += ($ledger->debit_amount-$ledger->credit_amount);
                        } else {
                            $balance += ($ledger->credit_amount-$ledger->debit_amount);
                        }
                    }
                ?>
                <table class="table table-responsive" style="margin-bottom:0;">
                    <tr>
                        <td colspan="5" class="text-right"><strong>Grand Total</strong></td>
                        <td width="12%" class="text-right"><strong>{{number_format($debit_amount)}}</strong></td>
                        <td width="12%" class="text-right"><strong>{{number_format($credit_amount)}}</strong></td>
                        <td width="12%" class="text-right"><strong>{{number_format($balance)}}</strong></td>
                    </tr>
                    <tr>
                        <td colspan="8" style="height: 10px;"></td>
                    </tr>
                </table>

                <table class="table table-responsive" style="overflow: scroll;" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="8%"><strong>Code</strong></td>
                        <td width="4%" class="text-center"><strong>Tr No.</strong></td>
                        <td width="8%"><strong>Date</strong></td>
                        <td width="8%" class="text-center"><strong>Instrument</strong></td>
                        <td><strong>Particulars</strong></td>
                        <td width="6%" class="text-right"><strong>Debit(Tk.)</strong></td>
                        <td width="6%" class="text-right"><strong>Credit(Tk.)</strong></td>
                        <td width="12%" class="text-right"><strong>Balance</strong></td>
                    </tr>
                    <?php $balance = 0; ?>
                    @if(isset($opening_balance))
                        <?php $balance = $op_balance; ?>
                        <tr>
                            <td>{{$account->account_code}}</td>
                            <td> - </td>
                            <td>{{DateTime::createFromFormat('Y-m-d', $opening_date)->format('d/m/Y')}}</td>
                            <td class="text-center"> - </td>
                            <td>Opening Balance</td>
                            <td class="text-right">{{number_format($opening_balance->total_debit)}}</td>
                            <td class="text-right">{{number_format($opening_balance->total_credit)}}</td>
                            <td class="text-right">{{number_format($op_balance)}}</td>
                        </tr>
                    @endif
                    @foreach($ledgers as $ledger)
                        <?php
                            $particulars = $ledger->remarks;
                            if($account->main_code==2 || $account->main_code==4) {
                                $balance += ($ledger->debit_amount-$ledger->credit_amount);
                            } else {
                                $balance += ($ledger->credit_amount-$ledger->debit_amount);
                            }
                        ?>
                        <tr>
                            <td>{{$ledger->account_code}}</td>
                            <td class="text-center">{{$ledger->transaction_no}}</td>
                            <td>{{DateTime::createFromFormat('Y-m-d', $ledger->transaction_date)->format('d/m/Y')}}</td>
                            <td class="text-center">{{$ledger->voucher_type.'-'.$ledger->instrument_no}}</td>
                            <td style="word-wrap:break-word;">{{ wordwrap($particulars,40,"\n", true)}}</td>
                            <td class="text-right">{{number_format($ledger->debit_amount)}}</td>
                            <td class="text-right">{{number_format($ledger->credit_amount)}}</td>
                            <td class="text-right">{{number_format($balance)}}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="5" class="text-right"><strong>Grand Total</strong></td>
                        <td class="text-right"><strong>{{number_format($debit_amount)}}</strong></td>
                        <td class="text-right"><strong>{{number_format($credit_amount)}}</strong></td>
                        <td class="text-right"><strong>{{number_format($balance)}}</strong></td>
                    </tr>
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

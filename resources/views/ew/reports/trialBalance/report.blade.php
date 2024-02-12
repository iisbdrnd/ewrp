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
                <p class="title">Trial Balance @if($date_range==1) {{' ('.$from_date.' - '.$to_date.')'}} @endif </p>
            </div>
            <div class="report-data">
                <table class="table table-responsive">
                    <tr>
                        <td width="10%"><strong>Code</strong></td>
                        <td><strong>Particulars</strong></td>
                        <td width="15%" class="text-right"><strong>Debit(Tk.)</strong></td>
                        <td width="15%" class="text-right"><strong>Credit(Tk.)</strong></td>
                    </tr>
                    <?php
                        $debit_amount = 0;
                        $credit_amount = 0;
                    ?>
                    @foreach($trial_balances as $balance)
                        <?php
                            //Acc Detail
                            if($account_level==1) {
                                $account_code = $balance->main_code.'000000000';
                                $account_head = $accHead[$account_code]->account_head;
                            } else if($account_level==2) {
                                $account_code = $balance->main_code.$balance->classified_code.'00000000';
                                $account_head = $accHead[$account_code]->account_head;
                            } else if($account_level==3) {
                                $account_code = $balance->main_code.$balance->classified_code.$balance->control_code.'000000';
                                $account_head = $accHead[$account_code]->account_head;
                            } else {
                                $account_code = $balance->account_code;
                                $account_head = $balance->account_head;
                            }

                            //Debit Credit
                            $debit_amount += $balance->debit_amount;
                            $credit_amount += $balance->credit_amount;
                        ?>
                        <tr>
                            <td>{{$account_code}}</td>
                            <td>{{$account_head}}</td>
                            <td class="text-right">{{number_format($balance->debit_amount, 2)}}</td>
                            <td class="text-right">{{number_format($balance->credit_amount, 2)}}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="2" class="text-right"><strong>Grand Total</strong></td>
                        <td class="text-right"><strong>{{number_format($debit_amount, 2)}}</strong></td>
                        <td class="text-right"><strong>{{number_format($credit_amount, 2)}}</strong></td>
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

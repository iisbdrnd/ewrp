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
            .border-none{
                border: 0px!important;
            }
            table td {
                font-size: {{$table_font}}
            }
            body,td,th {
                font-size: 12px;
            }
            .can-info-left tr td{
                font-weight: normal!important;
            }

            .can-info-right tr td{
                font-weight: normal!important;
            }

            .td-talign-r{
                text-align: right;
            }

            .table-border{
                border: 1px solid #ccc;
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
                <p class="title">Amount Less Report</p>
            </div>
            <div class="report-data" style="margin-top: 10px; font-size:10px;">
                <table class="table table-responsive">
                    <tr class="reportHead">
                        <td width="5%" class="text-center"><strong>Id</strong></td>
                        <td width="8%" class="text-center"><strong>NAME</strong></td>
                        <td width="8%" class="text-center"><strong>FATHER'S NAME</strong></td>
                        <td width="8%" class="text-center"><strong>PASSPORT</strong></td>
                        <td width="8%" class="text-center"><strong>TRADE</strong></td>
                        <td width="8%" class="text-center"><strong>REFERENCE</strong></td>
                        <td width="9%" class="text-center"><strong>PROJECT</strong></td>
                        <td width="10%" class="text-center"><strong>CANDIDATE ACCOUNT</strong></td>
                        <td width="10%" class="text-center"><strong>TR. NO</strong></td>
                        <td width="8%" class="text-center"><strong>TR. DATE</strong></td>
                        <td width="8%" class="text-center"><strong>LESS</strong></td>
                    </tr>
                    <?php
                        $grandLess = 0;
                    ?>
                    @foreach($candidateTransaction as $key=>$canTransactionArray)
                    <?php
                        $candidateDetails = $candidate[$key];
                        $date = '';
                        $account_head = '';
                        $transaction_no = '';
                        $less_amount = '';
                        $totalLess = 0;

                        foreach($canTransactionArray as $canTransaction) {
                            $date .= '<p>'.DateTime::createFromFormat('Y-m-d', $canTransaction->transaction_date)->format('d/m/Y').'</p>';
                            $account_head .= '<p>'.$canTransaction->account_head.'</p>';
                            $transaction_no .= '<p>'.$canTransaction->transaction_no.'</p>';
                            $less_amount .= '<p>'.number_format($canTransaction->less_amount,2).'</p>';
                            $totalLess += ($canTransaction->less_amount);
                        }
                        $grandLess += $totalLess;
                    ?>
                    <tr>
                        <td>{{$candidateDetails->candidate_id}}</td>
                        <td>{{$candidateDetails->candidate_name}}</td>
                        <td>{{$candidateDetails->father_name}}</td>
                        <td>{{$candidateDetails->passport_number}}</td>
                        <td>{{$candidateDetails->trade_name}}</td>
                        <td>{{$candidateDetails->reference_name}}</td>
                        <td>{{$candidateDetails->project_name}}</td>
                        <td><?php echo $account_head; ?></td>
                        <td class="text-center"><?php echo $transaction_no; ?></td>
                        <td><?php echo $date; ?></td>
                        <td class="text-right"><?php echo $less_amount; ?></td>
                    </tr>
                    <tr style="font-weight:bold;">
                        <td colspan="10" style="text-align: right;" >Sub Total = &nbsp;</td>
                        <td class="text-right">{{number_format($totalLess,2)}}</td>
                    </tr>
                    @endforeach
                    <tr style="font-weight:bold;">
                        <td colspan="10" style="text-align: right;"> Grand Total = &nbsp;</td>
                        <td class="text-right">{{number_format($grandLess,2)}}</td>
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

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
                margin: 20px auto;
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
                <p class="title">Reference Ledger</p>
            </div>
            <div class="report-data" style="margin-top: 10px;">
                <table class="table table-responsive">
                    <tr class="reportHead">
                        <td width="5%" class="text-center"><strong>Id</strong></td>
                        <td width="10%" class="text-center"><strong>NAME</strong></td>
                        <td width="10%" class="text-center"><strong>FATHER'S NAME</strong></td>
                        <td width="10%" class="text-center"><strong>PASSPORT</strong></td>
                        <td width="10%" class="text-center"><strong>TRADE</strong></td>
                        <td width="10%" class="text-center"><strong>PROJECT</strong></td>
                        <td width="10%" class="text-center"><strong>TR. DATE</strong></td>
                        <td width="10%" class="text-center"><strong>TR. NO</strong></td>
                        <td width="10%" class="text-center"><strong>CANDIDATE ACCOUNT</strong></td>
                        <td width="10%" class="text-center"><strong>RECEIVED</strong></td>
                        <td width="10%" class="text-center"><strong>PAYMENT</strong></td>
                        <td width="10%" class="text-center"><strong>BALANCE</strong></td>
                        <td width="10%" class="text-center"><strong>REMARKS</strong></td>
                    </tr>
                    <?php
                        $undeployedTotalReceived = 0;
                        $undeployedTotalPaid = 0;
                    ?>
                    @foreach($candidate as $candidateDetails)
                    <?php
                        $isTransaction = array_key_exists($candidateDetails->id, $candidateTransaction);
                        $rowspan = ($isTransaction) ? count($candidateTransaction[$candidateDetails->id])+1 : 2;
                    ?>
                    <tr>
                        <td rowspan="{{$rowspan}}">{{$candidateDetails->candidate_id}}</td>
                        <td rowspan="{{$rowspan}}">{{$candidateDetails->candidate_name}}</td>
                        <td rowspan="{{$rowspan}}">{{$candidateDetails->father_name}}</td>
                        <td rowspan="{{$rowspan}}">{{$candidateDetails->passport_number}}</td>
                        <td rowspan="{{$rowspan}}">{{$candidateDetails->trade_name}}</td>
                        <td rowspan="{{$rowspan}}">{{$candidateDetails->project_name}}</td>
                        <td style="height:30px;" colspan="7"></td>
                    </tr>
                    <?php
                        $balanceSum = 0;
                        $totalReceived = 0;
                        $totalPaid = 0;
                    ?>
                    @if($isTransaction)
                        @foreach($candidateTransaction[$candidateDetails->id] as $canTransaction)
                        <?php
                            $balanceSum = ($balanceSum+($canTransaction->received_amount-$canTransaction->paid_amount));
                            $totalReceived += ($canTransaction->received_amount);
                            $totalPaid += ($canTransaction->paid_amount);
                        ?>
                        <tr>
                            <td>{{DateTime::createFromFormat('Y-m-d', $canTransaction->transaction_date)->format('d/m/Y')}}</td>
                            <td class="text-center">{{$canTransaction->account_transaction_no}}</td>
                            <td>{{$canTransaction->account_head}}</td>
                            <td class="text-right">{{number_format(($canTransaction->received_amount),2)}}</td>
                            <td class="text-right">{{number_format($canTransaction->paid_amount,2)}}</td>
                            <td class="text-right">{{number_format($balanceSum,2)}}</td>
                            <td>{{$canTransaction->remarks}}</td>
                        </tr>
                        @endforeach
                        <?php
                            if($candidateDetails->flight_status==0) {
                                $undeployedTotalReceived += $totalReceived;
                                $undeployedTotalPaid += $totalPaid;
                            }
                        ?>
                    @else
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @endif
                    <tr style="font-weight:bold;">
                        <td colspan="9" style="text-align: right;" >Total = &nbsp;</td>
                        <td class="text-right">{{number_format($totalReceived,2)}}</td>
                        <td class="text-right">{{number_format($totalPaid,2)}}</td>
                        <td class="text-right"></td>
                        <td class="text-right">@if($candidateDetails->flight_status==1){{'Deployed-D '.(DateTime::createFromFormat('Y-m-d', $candidateDetails->candidate_flight_date)->format('d/m/Y'))}}@endif</td>
                    </tr>
                    @endforeach
                    <tr style="font-weight:bold;">
                        <td colspan="9" style="text-align: right;">Undeployed Total = &nbsp;</td>
                        <td class="text-right">{{number_format($undeployedTotalReceived,2)}}</td>
                        <td class="text-right">{{number_format($undeployedTotalPaid,2)}}</td>
                        <td class="text-right">{{number_format($undeployedTotalReceived-$undeployedTotalPaid,2)}}</td>
                        <td class="text-right"></td>
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

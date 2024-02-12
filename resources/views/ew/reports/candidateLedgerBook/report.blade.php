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
                <p class="title">Candidate Ledger</p>
            </div>
            <table width="100%" class="border-none" style="border: none;">
                <tr>
                    <td width="55%" class="border-none" style="border: none;">
                        <table class="can-info-left" width="100%" style="border: 1px solid #ccc;">
                            <tr>
                                <?php
                                    $deploymentDate = ($candidateDetails->flight_status==1)?DateTime::createFromFormat('Y-m-d', $candidateDetails->candidate_flight_date)->format('d/m/Y'):"N/A";

                                    $trDate = ($candidateDetails->flight_status==1)?DateTime::createFromFormat('Y-m-d', $candidateDetails->flight_transaction_date)->format('d/m/Y'):"N/A";
                                ?>
                                <td width="20%" class="border-none" style="border: none;"><b>Candidate Id No</b></td>
                                <td width="40%" class="border-none" style="border: none;">: {{$candidateDetails->candidate_id}}</td>
                                <td width="20%" class="border-none" style="border: none;"><b>Trade</b></td>
                                <td width="20%" class="border-none" style="border: none;">: {{$candidateDetails->trade_name}}</td>
                            </tr>
                            <tr>
                                <td class="border-none" style="border: none;"><b>Name of Candidate</b></td>
                                <td class="border-none" style="border: none;">: {{$candidateDetails->candidate_name}}</td>
                                <td class="border-none" style="border: none;"><b>Deployment Date</b></td>
                                <td class="border-none" style="border: none;">: {{$deploymentDate}}</td>
                            </tr>
                            <tr>
                                <td class="border-none" style="border: none;"><b>Father's Name</b></td>
                                <td class="border-none" style="border: none;">: {{$candidateDetails->father_name}}</td>
                                <td class="border-none" style="border: none;"><b>Passport Number</b></td>
                                <td class="border-none" style="border: none;">: {{$candidateDetails->passport_number}}</td>
                            </tr>
                            <tr>
                                <td class="border-none" style="border: none;"><b>Flight Tr. No</b></td>
                                <td class="border-none" style="border: none;">: @if($candidateDetails->flight_status==1){{$candidateDetails->flight_transaction_no}}@else{{'N/A'}}@endif</td>
                                <td class="border-none" style="border: none;"><b>Flight Tr. Date</b></td>
                                <td class="border-none" style="border: none;">: {{$trDate}}</td>
                            </tr>
                        </table>
                    </td>
                    <td class="border-none" style="border: none;">&nbsp;</td>
                    <td width="44%" class="border-none" style="border: none;">
                        <table class="can-info-right" width="100%" style="border: 1px solid #ccc;">
                            <tr>
                                <td width="41%" class="border-none" style="border: none;"><b>Reference</b></td>
                                <td width="59%" class="border-none" style="border: none;">: {{$candidateDetails->reference_name}}</td>
                            </tr>
                            <tr>
                                <td class="border-none" style="border: none;"><b>Project</b></td>
                                <td class="border-none" style="border: none;">: {{$candidateDetails->project_name}}</td>
                            </tr>
                            <tr>
                                <td class="border-none" style="border: none;"><b>Total Receivable Amount</b></td>
                                <td class="border-none" style="border: none;">: <b>{{$receivable->receivable_amount}}</b></td>
                            </tr>
                             <tr>
                                <td colspan="2" class="border-none" style="border: none;">&nbsp;</td>
                            </tr>
                         </table>
                    </td>
                </tr>
            </table>
            <div class="report-data" style="margin-top: 10px;">
                <table class="table table-responsive">
                    <tr class="reportHead">
                        <td width="8%" class="text-center"><strong>DATE</strong></td>
                        <td width="15%" class="text-center"><strong>CANDIDATE ACCOUNT</strong></td>
                        <td width="12%" class="text-center"><strong>TR. NO</strong></td>
                        <td width="12%" class="text-center"><strong>RECEIVED</strong></td>
                        <td width="11%" class="text-center"><strong>PAYMENT</strong></td>
                        <td width="15%" class="text-center"><strong>BALANCE</strong></td>
                        <td width="20%" class="text-center"><strong>REMARKS</strong></td>
                    </tr>
                    <?php $total = array(); $balanceSum = 0; $totalReceive = 0; $totalPayment = 0; ?>
                    @foreach($candidateTransactionHis as $canTransaction)
                    <tr>
                        <td>{{DateTime::createFromFormat('Y-m-d', $canTransaction->transaction_date)->format('d/m/Y')}}</td>
                        <td>{{$canTransaction->account_head}}</td>
                        <td class="text-center">{{$canTransaction->account_transaction_no}}</td>
                        <td class="text-right">{{number_format($canTransaction->received_amount,2)}}</td>
                        <td class="text-right">{{number_format($canTransaction->paid_amount,2)}}</td>
                        <td class="text-right"><?php $balanceSum += ($canTransaction->received_amount-$canTransaction->paid_amount); echo number_format($balanceSum,2); ?></td>
                        <td class="text-center">{{$canTransaction->remarks}}</td>
                        <?php $totalReceive += ($canTransaction->received_amount); ?>
                        <?php $totalPayment += $canTransaction->paid_amount; ?>
                    </tr>
                    @endforeach
                    <tr style="font-weight:bold;">
                        <td colspan="3" style="text-align: right;">TOTAL= </td>
                        <td class="text-right">{{number_format($totalReceive,2)}}</td>
                        <td class="text-right">{{number_format($totalPayment,2)}}</td>
                        <td class="text-right"></td>
                        <td></td>
                    </tr>
                </table>
                <table class="table table-responsive balance_tab">
                    <tr style="font-weight:bold;">
                        <td width="37%" class="text-right border-none" style="font-size: 16px;">Balance: </td>
                        <td width="63%" class="text-left border-none" style="font-size: 16px;"><u>{{number_format($receivable->receivable_amount-$balanceSum,2)}}</u></td>
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

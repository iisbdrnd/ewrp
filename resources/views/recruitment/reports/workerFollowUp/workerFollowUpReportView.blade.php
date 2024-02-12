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
            .sub_title{
                font-size:14px;
                margin-top:1%;
            }
            table td {
                font-size: {{$table_font}}
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
                <p class="title">Worker Follow-UP SHEET</p>

                <div class="row">
                    <div class="col-md-12 sub_title"><b>Project:</b> {{ @Helper::projectName($byProjectId) }}</div>
                    <div class="col-md-12 sub_title"><b>Dealer:</b> @if($byDealerId > 0) {{ @Helper::getDealerName($byDealerId) }} @else ALL Dealer @endif</div>

                </div>
            </div>


            <div class="report-data">

                <?php 
                    //echo "<pre>";
                    //print_r($candidateCvDetails); exit();

                ?>

                <table class="table table-responsive table-bordered tradeDetailsTable">
                    <tr>
                        <td width="5%" class="text-center"><strong>Sl.</strong></td>
                        <td width="15%" class="text-center"><strong>TRADE</strong></td>
                        <td width="10%" class="text-center"><strong>SALARY</strong></td>
                        <td width="15%" class="text-center"><strong>OTHERS</strong></td>
                        <td width="10%" class="text-center"><strong>REQ</strong></td>
                        <td width="15%" class="text-center"><strong>APV</strong></td> 
                        <td width="15%" class="text-center"><strong>Medical Status</strong></td> 
                    </tr>
                    @if(count($tradeDetails)>0)

                        <?php 
                            $total_trade_qty = 0;
                            $total_candidateApvQty = 0;
                            $total_candidateFitQty = 0;
                        ?>
                        @foreach($tradeDetails as $trade)

                        <?php 
   

                            $trade_qty = $trade->trade_qty;
                            $candidatApvQty = @Helper::candidateAPVQuantity($trade->ew_project_id, $trade->trade_id);
                            $candidateFitQty = @Helper::candidateFitQuantity($trade->ew_project_id, $trade->trade_id);

                            $total_trade_qty+= $trade_qty;
                            $total_candidateApvQty+=$candidatApvQty;
                            $total_candidateFitQty+=$candidateFitQty;
                        ?>

                        <tr>
                            <td class="text-center">{{$sn++}}</td>
                            <td class="text-center">{{ @Helper::getTradeName($trade->trade_id) }}</td>
                            <td class="text-center">{{ $trade->trade_salary }}</td>
                            <td class="text-center">{{ $trade->trade_others }}</td>
                            <td class="text-center">{{ $trade_qty }}</td>
                            <td class="text-center">{{ $candidatApvQty }}</td>
                            <td class="text-center">{{ $candidateFitQty }}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td class="text-center" colspan="4"><b>Grand Total</b></td>
                            <td class="text-center"><b>{{ $total_trade_qty }}</b></td>
                            <td class="text-center"><b>{{ $total_candidateApvQty }}</b></td>
                            <td class="text-center"><b>{{ $total_candidateFitQty }}</b></td>    
                        </tr>
                    @else
                        <tr>
                            <td colspan="6" class="text-center">Data not found!</td>
                        </tr>
                    @endif
                </table>



                <table class="table table-responsive table-bordered workerDetailsTable">
                    <tr>
                        <td width="3%" class="text-center"><strong>Sl.</strong></td>
                        <td width="10%" class="text-center"><strong>WORKER NAME</strong></td>
                        <td width="7%" class="text-center"><strong>PASSPORT NO</strong></td>
                        <td width="10%" class="text-center"><strong>TRADE</strong></td>
                        <td width="10%" class="text-center"><strong>REFERENCE</strong></td>
                        <td width="6%" class="text-center"><strong>Approval</strong></td>
                        <td width="6%" class="text-center"><strong>VAC</strong></td>
                        <td width="6%" class="text-center"><strong>M. Sent</strong></td>
                        <td width="6%" class="text-center"><strong>M. Status</strong></td>
                        <td width="6%" class="text-center"><strong>MOFA</strong></td>
                        <td width="6%" class="text-center"><strong>PCC Rcv</strong></td>
                        <td width="6%" class="text-center"><strong>GTC Rcv</strong></td>
                        <td width="6%" class="text-center"><strong>VISA Stamp</strong></td>
                        <td width="6%" class="text-center"><strong>Smart Card</strong></td>
                        <td width="6%" class="text-center"><strong>FLT. Status</strong></td>
                    </tr>
                    @if(count($workerDeatails)>0)
                        @foreach($workerDeatails as $workerKey => $worker)
                        <tr>
                            <td class="text-center">{{$workerKey+1}}</td>
                            <td class="text-center">{{ $worker->full_name }}</td>
                            <td class="text-center">{{ $worker->passport_no }}</td>
                            <td class="text-center">{{ @Helper::getTradeName($worker->selected_trade) }}</td>
                            <td class="text-center">{{ @Helper::getReference($worker->reference_id)->reference_name }}</td>
                            <td class="text-center">
                                <?php 
                                    if($worker->approved_date !='0000-00-00 00:00:00'){
                                        echo date("d/m/Y",strtotime($worker->approved_date));
                                    }
                                ?> 
                            </td>
                            <td class="text-center"></td>
                            <td class="text-center">
                                <?php 
                                    if($worker->medical_sent_date !='0000-00-00 00:00:00'){
                                        echo date("d/m/Y",strtotime($worker->medical_sent_date));
                                    }
                                ?>
                            </td>
                            <td class="text-center">
                                <?php 
                                    if($worker->medical_status_date !='0000-00-00 00:00:00'){
                                        echo date("d/m/Y",strtotime($worker->medical_status_date));
                                    }
                                ?>
                            </td>
                            <td class="text-center">
                                <?php 
                                    if($worker->mofa_date !='0000-00-00 00:00:00'){
                                        echo date("d/m/Y",strtotime($worker->mofa_date));
                                    }
                                ?>
                            </td>
                            <td class="text-center">
                                <?php 
                                    if($worker->pcc_received_date !='0000-00-00 00:00:00'){
                                        echo date("d/m/Y",strtotime($worker->pcc_received_date));
                                    }
                                ?>
                            </td>
                            <td class="text-center">
                                <?php 
                                    if($worker->gttc_received_date !='0000-00-00 00:00:00'){
                                        echo date("d/m/Y",strtotime($worker->gttc_received_date));
                                    }
                                ?>
                            </td>
                            <td class="text-center">
                                <?php 
                                    if($worker->visa_attached_date !='0000-00-00 00:00:00'){
                                        echo date("d/m/Y",strtotime($worker->visa_attached_date));
                                    }
                                ?>
                            </td>
                            <td class="text-center">
                                <?php 
                                    if($worker->smartcard_date !='0000-00-00 00:00:00'){
                                        echo date("d/m/Y",strtotime($worker->smartcard_date));
                                    }
                                ?>
                            </td>
                            <td class="text-center">
                                <?php 
                                    if($worker->flight_date !='0000-00-00 00:00:00'){
                                        echo date("d/m/Y",strtotime($worker->flight_date));
                                    }
                                ?>
                            </td>

                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="14" class="text-center">Data not found!</td>
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

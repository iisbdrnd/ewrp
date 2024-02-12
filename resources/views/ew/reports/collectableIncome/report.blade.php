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
            .table {
                width: 100%;
                max-width: 100%;
                margin-bottom: 20px;
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
            .reportGroup {
                font-size: 12px;
                margin-bottom: 2px;
                text-transform: uppercase;
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
                <p class="project">Project: {{Helper::projectName($project)}}</p>
                <p class="title">Collectable Income @if($date_range==1){{'upto '.$date}}@elseif($date_range==2){{'from '.$from_date.' to '.$to_date}}@endif</p>
            </div>
            <div class="report-data">
                @if($group_report==1)
                    <?php $grand_total_amount = 0; $i=0; ?>
                    @if(array_key_exists(0, $ledgers))
                    <p class="reportGroup">Head Office</p>
                    <table class="table table-responsive">
                        <tr>
                            <td><strong>Collectable Account</strong></td>
                            <td width="12%" class="text-center"><strong>Amount</strong></td>
                        </tr>
                        <?php $total_amount = 0; ?>
                        @foreach($ledgers[0] as $ledger)
                            <?php
                                $total_amount += $ledger->incAmount;
                            ?>
                            <tr>
                                <td>{{$ledger->account_head}}</td>
                                <td class="text-right">{{number_format($ledger->incAmount,2)}}</td>
                            </tr>
                        @endforeach
                        <?php $grand_total_amount += $total_amount; $i++; ?>
                        <tr>
                            <td class="text-right"><strong>Total</strong></td>
                            <td class="text-right"><strong>{{number_format($total_amount,2)}}</strong></td>
                        </tr>
                    </table>
                    @endif

                    @foreach($projects as $project)
                    @if(array_key_exists($project->id, $ledgers))
                    <p class="reportGroup">{{$project->project_name}}</p>
                    <table class="table table-responsive">
                        <tr>
                            <td><strong>Collectable Account</strong></td>
                            <td width="12%" class="text-center"><strong>Amount</strong></td>
                        </tr>
                        <?php $total_amount = 0; ?>
                        @foreach($ledgers[$project->id] as $ledger)
                            <?php
                                $total_amount += $ledger->incAmount;
                            ?>
                            <tr>
                                <td>{{$ledger->account_head}}</td>
                                <td class="text-right">{{number_format($ledger->incAmount,2)}}</td>
                            </tr>
                        @endforeach
                        <?php $grand_total_amount += $total_amount; $i++; ?>
                        <tr>
                            <td class="text-right"><strong>Total</strong></td>
                            <td class="text-right"><strong>{{number_format($total_amount,2)}}</strong></td>
                        </tr>
                    </table>
                    @endif
                    @endforeach

                    @if($i==0)
                    <table class="table table-responsive">
                        <tr>
                            <td class="text-center">Data not found!</td>
                        </tr>
                        <tr>
                            <td class="text-center">&nbsp;</td>
                        </tr>
                    </table>
                    @else
                    <table class="table table-responsive">
                        <tr>
                            <td class="text-right"><strong>Grand Total</strong></td>
                            <td width="12%" class="text-right"><strong>{{number_format($grand_total_amount,2)}}</strong></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-center">&nbsp;</td>
                        </tr>
                    </table>
                    @endif
                @else
                <table class="table table-responsive">
                    <tr>
                        <td><strong>Collectable Account</strong></td>
                        <td width="12%" class="text-center"><strong>Amount</strong></td>
                    </tr>
                    <?php $total_amount = 0; $i=0; ?>
                    @foreach($ledgers as $ledger)
                        <?php
                            $total_amount += $ledger->incAmount;
                            $i++;
                        ?>
                        <tr>
                            <td>{{$ledger->account_head}}</td>
                            <td class="text-right">{{number_format($ledger->incAmount,2)}}</td>
                        </tr>
                    @endforeach
                    @if($i==0)
                    <tr>
                        <td colspan="2" class="text-center">Data not found!</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="text-right"><strong>Total</strong></td>
                        <td class="text-right"><strong>{{number_format($total_amount,2)}}</strong></td>
                    </tr>
                </table>
                @endif
            </div>
        </div>
    </body>
    <script type="text/javascript">
        function printDocument() {
            window.print();
        }
    </script>
</html>

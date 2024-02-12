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
                font-size: 11px;
            }
            .contact {
                font-size: 11px;
            }
            .title {
                font-size: 16px;
            }
            .border-none{
                border: none!important;
            }
            .reportGroup{
                font-size: 12px;
                margin-bottom: 2px;
                text-transform: uppercase;
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
                <p class="title">FLIGHT REPORT OF @if(!empty($companyInfo)){{$companyInfo->project_name}}@else{{"CANDIDATES"}}@endif @if(($date_range==1)){{'('.$from_date.' - '.$to_date.')'}} @endif</p>
            </div>
            <div class="report-data">
                <table class="table table-responsive">
                    <tr>
                        <td width="4%" class="text-center"><strong>Sl.</strong></td>
                        <td width="6%" class="text-center"><strong>ID No</strong></td>
                        <td width="10%"><strong>Name</strong></td>
                        <td width="10%"><strong>Father's Name</strong></td>
                        <td width="10%" class="text-center"><strong>Reference</strong></td>
                        <td width="10%" class="text-center"><strong>Trade</strong></td>
                        <td width="8%"><strong>Passport</strong></td>
                        <td width="5%"><strong>Tr. No.</strong></td>
                        <td width="8%"><strong>Tr. Date</strong></td>
                        <td width="8%"><strong>Flight No.</strong></td>
                        <td width="8%"><strong>Flight Date</strong></td>
                        <td width="13%"><strong>Remarks</strong></td>
                    </tr>
                    @if(count($flightReports)>0)
                        <?php $prev = NULL; ?>
                        @foreach($flightReports as $flightReport)
                        <?php
                            $curr = $flightReport->candidate_flight_date;
                         ?>
                        <tr>
                            <?php
                                $flightDate = DateTime::createFromFormat('Y-m-d', $flightReport->candidate_flight_date);
                                $flight_date = $flightDate->format('d/m/Y');

                                $trDate = DateTime::createFromFormat('Y-m-d', $flightReport->flight_transaction_date);
                                $tr_date = $trDate->format('d/m/Y');
                            ?>
                            <td class="text-center">{{$sn++}}</td>
                            <td class="text-center">{{$flightReport->candidate_id}}</td>
                            <td class="ttp">{{$flightReport->candidate_name}}</td>
                            <td class="ttp">{{$flightReport->father_name}}</td>
                            <td class="text-center">{{$flightReport->reference_name}}</td>
                            <td class="text-center">{{$flightReport->trade_name}}</td>
                            <td>{{$flightReport->passport_number}}</td>
                            <td>{{$flightReport->flight_transaction_no}}</td>
                            <td>{{$tr_date}}</td>
                            <td>{{$flightReport->flight_no}}</td>
                            <td>{{$flight_date}}</td>
                            <td>{{$flightReport->flight_remarks}}</td>
                        </tr>
                        <?php
                            if ($curr != $prev) {
                                echo '<tr class="border-none"><td colspan="10" border="0" class="border-none">&nbsp;</td></tr>';
                                $prev = $curr;
                            } ?>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="12" class="text-center">Data not found!</td>
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

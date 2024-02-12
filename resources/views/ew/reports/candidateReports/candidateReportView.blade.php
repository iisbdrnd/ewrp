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
                <p class="title">@if($previewType==2){{"PROJECT WISE"}} @elseif($previewType==3){{"REFERENCE WISE"}}@elseif($previewType==4){{"TRADE WISE"}}@endif LIST OF CANDIDATES</p>
            </div>
            <div class="report-data">
                @if($previewType==1)
                    <table class="table table-responsive table-bordered">
                        <tr>
                            <td width="5%" class="text-center"><strong>Sl.</strong></td>
                            <td width="10%" class="text-center"><strong>ID No</strong></td>
                            <td width="27%"><strong>Student's Name/Father's Name</strong></td>
                            <td width="15%" class="text-center"><strong>Reference</strong></td>
                            <td width="10%" class="text-center"><strong>Trade</strong></td>
                            <td width="15%" class="text-center"><strong>Project</strong></td>
                            <td width="10%"><strong>Passport</strong></td>
                            <td width="8%"><strong>Flight Date</strong></td>
                        </tr>
                        @if(count($candidateReports)>0)
                            @foreach($candidateReports as $candidateReport)
                            <tr>
                                <td class="text-center">{{$sn++}}</td>
                                <td class="text-center">{{$candidateReport->candidate_id}}</td>
                                <td class="ttp">{{$candidateReport->candidate_name.' / '.$candidateReport->father_name}}</td>
                                <td class="text-center">{{$candidateReport->reference_name}}</td>
                                <td class="text-center">{{$candidateReport->trade_name}}</td>
                                <td class="text-center">{{$candidateReport->project_name}}</td>
                                <td>{{$candidateReport->passport_number}}</td>
                                <?php
                                    if($candidateReport->candidate_flight_date!='0000-00-00'){
                                        $flightDate = DateTime::createFromFormat('Y-m-d', $candidateReport->candidate_flight_date);
                                        $flight_date = $flightDate->format('d/m/Y');
                                    }else{
                                        $flight_date = "";
                                    }
                                ?>
                                <td>{{$flight_date}}</td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="10" class="text-center">Data not found!</td>
                            </tr>
                        @endif
                    </table>
                @else
                    @if(count($candidateReports)>0)
                        @foreach($candidateReports as $groupKey => $candidateGroup)
                            <p class="reportGroup"><?php
                                if($previewType==2) echo $ewProjects[$groupKey]->project_name;
                                else if($previewType==3) echo $references[$groupKey]->reference_name;
                                else if($previewType==4) echo $trades[$groupKey]->trade_name;
                            ?></p>
                            <table class="table table-responsive table-bordered">
                                <tr>
                                    <td width="5%" class="text-center"><strong>Sl.</strong></td>
                                    <td width="5%" class="text-center"><strong>ID No</strong></td>
                                    <td width="27%"><strong>Student's Name/Father's Name</strong></td>
                                    @if($previewType!=3)<td width="20%" class="text-center"><strong>Reference</strong></td>@endif
                                    @if($previewType!=4)<td width="10%" class="text-center"><strong>Trade</strong></td>@endif
                                    @if($previewType!=2)<td width="15%" class="text-center"><strong>Project</strong></td>@endif
                                    <td width="10%"><strong>Passport</strong></td>
                                    <td width="8%"><strong>Flight Date</strong></td>
                                </tr>
                                @foreach($candidateGroup as $candidateReport)
                                <tr>
                                    <td class="text-center">{{$sn++}}</td>
                                    <td class="text-center">{{$candidateReport->candidate_id}}</td>
                                    <td class="ttp">{{$candidateReport->candidate_name.' / '.$candidateReport->father_name}}</td>
                                    @if($previewType!=3)<td class="text-center">{{$candidateReport->reference_name}}</td>@endif
                                    @if($previewType!=4)<td class="text-center">{{$candidateReport->trade_name}}</td>@endif
                                    @if($previewType!=2)<td class="text-center">{{$candidateReport->project_name}}</td>@endif
                                    <td>{{$candidateReport->passport_number}}</td>
                                    <?php
                                        if($candidateReport->candidate_flight_date!='0000-00-00'){
                                            $flightDate = DateTime::createFromFormat('Y-m-d', $candidateReport->candidate_flight_date);
                                            $flight_date = $flightDate->format('d/m/Y');
                                        }else{
                                            $flight_date = "";
                                        }
                                    ?>
                                    <td>{{$flight_date}}</td>
                                </tr>
                                @endforeach
                            </table>
                        @endforeach
                    @else
                    <table class="table table-responsive table-bordered">
                        <tr>
                            <td width="5%" class="text-center"><strong>Sl.</strong></td>
                            <td width="5%" class="text-center"><strong>ID No</strong></td>
                            <td width="27%"><strong>Student's Name/Father's Name</strong></td>
                            @if($previewType!=3)<td width="20%" class="text-center"><strong>Reference</strong></td>@endif
                            @if($previewType!=4)<td width="10%" class="text-center"><strong>Trade</strong></td>@endif
                            @if($previewType!=2)<td width="15%" class="text-center"><strong>Project</strong></td>@endif
                            <td width="10%"><strong>Passport</strong></td>
                            <td width="8%"><strong>Flight Date</strong></td>
                        </tr>
                        <tr>
                            <td colspan="10" class="text-center">Data not found!</td>
                        </tr>
                    </table>
                    @endif
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

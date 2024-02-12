<html moznomarginboxes mozdisallowselectionprint>
  <head>
    <!--<meta charset="utf-8">-->
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Candidate CV Report</title>
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&amp;lang=en" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        table td{
            border-right: hidden!important;
            border-left: hidden!important;
        }
        table thead tr th{
            border-bottom: 1px solid #000;
            border-top: 2px solid #000;
            font-size: 13px;
        }
        table tbody tr td{
            text-align: right;
        }
        .show-room{
            margin-top: 20px;
        }
        .show-room p span{
            border: 1px solid black; 
            margin-left: 10px; 
            padding: 2px 15px;
        }
    </style>
  </head>
    
  <body>
    <div class="container">
        <div class="header">
             <p class="title" style="text-align:center">Candidate Cv Report</p>
        </div>
        <!-- get grand total variable -->
        <!-- end grand total variable -->
        <table cellspacing="0" class="responsive table table-striped table-bordered" id="candidateCvData">
            <thead>
            <tr>
                <th style="text-align:center;">SL.No</th>
                <th width="10%" style="text-align:center;">CV No.</th>
                <th width="15%" style="text-align:center;">Name</th>
                <th width="10%" style="text-align:center;">PP.No.</th>
                <th width="10%" style="text-align:center;">Project</th>
                <th width="8%" style="text-align:center;">Trade</th>
                <th width="8%" style="text-align:center;">Reference</th>
                <th width="8%" style="text-align:center;">Dealer</th>
                <th width="8%" style="text-align:center;">Contact No</th>
                <th width="3%" style="text-align:center;">Home Exp</th>
                <th width="3%" style="text-align:center;">Ovr Exp</th>
                <th width="8%" style="text-align:center;">CV Status</th>
            </tr>
            </thead>
            <tbody>
                @if (count($candidateCVs)>0)

                    @foreach ($candidateCVs as $key =>  $candidateCV)

                    <tr>
                        <td style="text-align:center;">{{$key + 1}}</td>
                        <?php $trades = 1; ?>
                        <td style="text-align:center;">{{ $candidateCV->cv_number }}</td>
                        <td style="text-align:center;">{{ $candidateCV->full_name }}</td>
                        <td style="text-align:center;">
                            {{ $candidateCV->passport_no }} {!! @Helper::passportExpired($candidateCV->id) !!}
                            <p style="margin-bottom: 0;">NID:</p> 
                            @if(!empty($candidateCV->national_id)) {{$candidateCV->national_id}} @else N/A @endif
                        </td>
                        <td style="text-align:center;">@if(!empty($candidateCV->ew_project_id)) {{ @Helper::projects($candidateCV->ew_project_id)->project_name }} @else CV Bank @endif</td>
                        <td style="text-align:center;">

                            @if (!empty($candidateCV->selectedTrade))
                                {{ $candidateCV->selectedTrade }}
                            @else
                                <?php 
                                    $trades = (object)json_decode($candidateCV->trade, true);
                                    $i=1;
                                    $totalTrades = count((array)$trades);
                                ?>

                                @foreach ($trades as $trade)
                                    {{Helper::singleTrade($trade)->trade_name}}

                                    <?php if ($i<$totalTrades) { echo ","; } $i++;?>
                                @endforeach

                            @endif

                        </td>
                        <td style="text-align:center;">{{ @Helper::reference($candidateCV->reference_id)->reference_name }}</td>
                        <td style="text-align:center;">
                            {{ @Helper::dealer($candidateCV->dealer)->name }}
                        </td>
                        <td style="text-align:center;">{{ $candidateCV->contact_no }}</td>
                        <td style="text-align:center;">
                            <?php 
                                $homeExp = (object)json_decode($candidateCV->total_home_exp, true);
                                $totalHomeExp = 0;
                                foreach($homeExp as $hExp){
                                $totalHomeExp+= $hExp;
                                }
                                echo $totalHomeExp;
                            ?>
                        </td>
                        <td style="text-align:center;">
                            <?php 
                                $OvrExp = (object)json_decode($candidateCV->total_overs_exp, true);
                                $totalOvrExp = 0;
                                foreach($OvrExp as $OExp){
                                    $totalOvrExp+= $OExp;
                                }
                                echo $totalOvrExp;
                            ?>
                        </td>
                        <td style="text-align:center;">
                            <?php 
                                $deployDate = @Helper::deployDate($candidateCV->ew_project_id,$candidateCV->id);
                            ?>

                            {!! @Helper::flightCompleted($candidateCV->ew_project_id,$candidateCV->id) == 1 &&
                            $candidateCV->approved_status == 1
                            ? '<span class="text-danger"><b style="font-size:15px">Deployed</b></span></br><span style="color:green"><b>'.$deployDate.'</b></span>'
                            : ($candidateCV->approved_status == 1
                            ? '<span class="text-primary"><strong>Work In Progress</strong></span>'
                            : '<span class="text-primary"><strong>Mobilize is Not Started!</strong></span>') !!}
                            <hr>
                            <span>
                                {!! $candidateCV->result == 1
                                ? '<strong class="text-success"><b>Pass</b></strong>'
                                : ($candidateCV->result == 2
                                ? '<strong class="text-warning">Fail</strong>'
                                : ($candidateCV->result == 3
                                ? '<strong class="text-danger">Waiting</strong>'
                                : ($candidateCV->result == 4
                                ? '<strong class="text-info">Hold</strong>'
                                : ($candidateCV->result == 5
                                ? '<strong class="text-danger">Decline</strong>'
                                : '')))) !!}
                            </span>
                        </td>
                    </tr>

                    @endforeach

                    @else

                    <tr>
                        <td colspan="13" class="emptyMessage">Empty</td>
                    </tr>

                @endif

            </tbody>
        </table>

        
    </div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript">
    function printDocument() {
        window.print();
    }

</script>
</html>
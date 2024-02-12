<style>
  .btn-success{display: none;}
</style>
<html moznomarginboxes mozdisallowselectionprint>
    <head>
        <!--<meta charset="utf-8">-->
        <meta http-equiv="X-UA-Compatible" content="IE-edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>Report Preview</title>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"/>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css"/>


<style>
table, thead,tbody, tr, th, td{
    font-size: 10px;
}
/* DataTable print, excel, csv and pdf button customizing design */
/* div.dt-buttons {

position: absolute !important;
margin-left: 42% !important;

}
label{
  margin-bottom: 9px;
}
a.buttons-copy{
  background: #36A9CB;
  color: #fff;
  border:1px solid #36A9CA !important;
}
a.buttons-excel{
  background: #1C6C40;
color: #fff;
border:1px solid #1C6C49 !important;
}
a.buttons-csv{
  background: #056E11;
  color: #fff;
  border:1px solid #056E18 !important;
}
a.buttons-pdf{
  background: #D60B0B;
  color: #fff;
  border:1px solid #D60B0A !important;
}
a.buttons-print{
  background: #0F5BA1;
  color: #fff;
  border:1px solid #0F5BA1 !important;
}
a.buttons-copy:hover{
  background: #36A9CA !important;
  color: #fff !important;
  border:1px solid !important;
}
a.buttons-excel:hover{
  background: #1C6C49 !important;
color: #fff !important;
border:1px solid !important;
}
a.buttons-csv:hover{
  background: #056E18 !important;
  color: #fff !important;
  border:1px solid !important;
}
a.buttons-pdf:hover{
  background: #D60B0A !important;
  color: #fff !important;
  border:1px solid !important;
}
a.buttons-print:hover{
  background: #0F5BA1 !important;
  color: #fff !important;
  border:1px solid !important;
} */
</style>
    </head>
    <body>
        <div class="container-fluid">
            <div class="header text-center">
                {!!@Helper::companyDetails() !!}
                <strong class="title" style="margin-top:10px;">Project Name: {{ @Helper::projects($projectId)->project_name }}</strong>

                <input type="hidden" id="project_name" value="{{ @Helper::projects($projectId)->project_name }}">
            </div>
           
    <div class="report-data mt10">
    <table id="myTable" class="table table-bordered table-condensed" width="100%" border="1">
       <thead>
            <tr>
                <th width="1%">No.</th>
               {{-- @foreach($cvLabels as $cvLabel)
               <th>{{ $cvLabel }}</th>
               @endforeach --}}
               
                @foreach($labels as $label)
               <th>{{ $label }}</th>
               @endforeach
               
           </tr>
        </thead>
      <tbody>
          <?php $sn= 1;?>
          @foreach($reports as $report) 
          <tr>
              <td>{{ $sn++ }}</td>
              @foreach($objectNames as $objectName)
                <td> 
                    @if($objectName == 'passport_status') 
                    {!!  @Helper::passportStatus($report->$objectName) !!}
                    
                    @elseif ($objectName == 'home_experience')

                     @if (!empty($report->$objectName))
                    {{ $report->$objectName >1?$report->$objectName.' Years':'Year' }}
                    @endif
                   
                    @elseif ($objectName == 'oversease_experience') 

                     @if (!empty($report->$objectName))
                    {{ $report->$objectName >1?$report->$objectName.' Years':'Year' }}
                    @endif
                    
                    @elseif ($objectName == 'total_years_of_experience') 

                     @if (!empty($report->$objectName))
                    {{ $report->$objectName >1?$report->$objectName.' Years':'Year' }}
                    @endif
                    
                    @elseif ($objectName == 'medical_actual_status')
                    {!! @Helper::medicalActualStatus($report->$objectName) !!}

                    @elseif ($objectName == 'medical_expire_date')
                        @if($report->$objectName == '0000-00-00')
                          {{'No Date'}}
                        @else
                          {{Carbon\Carbon::parse($report->$objectName)->format('d-m-Y')}}
                        @endif

                    @elseif ($objectName == 'result')
                     <span>
                        {!! $report->$objectName == 1 
                        ? '<strong class="text-success">Pass</strong>'
                        : ($report->$objectName == 2 
                        ? '<strong class="text-warning">Fail</strong>' 
                        : ($report->$objectName == 3 
                        ? '<strong class="text-danger">Waiting</strong>' 
                        : ($report->$objectName == 4 
                        ? '<strong class="text-info">Hold</strong>'
                        : ($report->$objectName == 5 
                        ? '<strong class="text-danger">Decline</strong>'
                        : '')))) !!} 
                    </span>
                    
                    @elseif ($objectName == 'date_of_birth')
                        @if($report->$objectName == '0000-00-00 00:00:00')
                          {{'No Date'}}
                        @else
                          {{Carbon\Carbon::parse($report->$objectName)->format('d-m-Y')}}
                        @endif
                    @elseif ($objectName == 'home_experience_details')
                    
                    @foreach(json_decode($report->$objectName, true) as $hed) 
                        {{$hed}}
                    @endforeach

                    @elseif ($objectName == 'from_home_exp')
                    
                    @foreach(json_decode($report->$objectName, true) as $from_home_exp) 
                        {{$from_home_exp}}
                    @endforeach
                   
                    @elseif ($objectName == 'to_home_exp')
                    
                    @foreach(json_decode($report->$objectName, true) as $to_home_exp) 
                        {{$to_home_exp}}
                    @endforeach
                   
                    @elseif ($objectName == 'total_home_exp')
                    
                    @foreach(json_decode($report->$objectName, true) as $total_home_exp)

                    @if (!empty($total_home_exp))
                    {{ $total_home_exp >1?$total_home_exp.' Years':'Year' }}
                    @endif

                    @endforeach
                   
                    @elseif ($objectName == 'oversease_experience_details')
                    <?php $i = 1;?>
                    @foreach(json_decode($report->$objectName, true) as $oed)

                        @if (!empty($oed))
                        {{$i++}}. {{$oed}}<br>
                        @endif

                    @endforeach
                   
                    @elseif ($objectName == 'from_overs_exp')
                   
                    @foreach(json_decode($report->$objectName, true) as $from_overs_exp) 
                        {{$from_overs_exp}}
                    @endforeach
                   
                    @elseif ($objectName == 'to_overs_exp')
                    
                    @foreach(json_decode($report->$objectName, true) as $to_overs_exp) 
                        {{$to_overs_exp}}
                    @endforeach

                    @elseif ($objectName == 'total_overs_exp')
                    
                    @foreach(json_decode($report->$objectName, true) as $total_overs_exp)

                    @if (!empty($total_overs_exp))
                         {{ $total_overs_exp >1?$total_overs_exp.' Years':'Year' }}
                    @endif

                    @endforeach
                    
                    @elseif ($objectName == 'oversease_country')
                    
                    @if (is_array(json_decode($report->$objectName, true)))
                    @foreach(json_decode($report->$objectName, true) as $oversease_country) 
                        {{@Helper::country($oversease_country)}}
                    @endforeach
                    @else
                    <?php 
                    $arr = array();
                    $val = str_replace('"', '', $report->$objectName);
                    $val_convert_to_int = (int)$val;
                    ?>
                    {{@Helper::country($val_convert_to_int)}}
                    @endif

                    @elseif ($objectName == 'passport_expired_date')
                    {{Carbon\Carbon::parse($report->$objectName)->format('d-m-Y')}}

                    @elseif ($objectName == 'trade')
                    <?php 
                       if(isset($report->$objectName))
                        {
                          $tradeName = array();
                          if(json_decode($report->$objectName, true) != null){
                             $tradeName = json_decode($report->$objectName, true);
                          }
                        }else{
                            $tradeName = "";
                        }
                        $t=1;
                        $totaltrades = count($tradeName);
                    ?>    
                    @if(!empty($tradeName))
                      @foreach($tradeName as $tradeId)
                        {{ @Helper::getTradeName($tradeId) }}
                        <?php if ($t<$totaltrades) { echo ","; } $t++; ?>
                      @endforeach
                    @endif


                    @elseif ($objectName == 'trade_applied')
                    {{ @Helper::singleTrade($report->$objectName)->trade_name }}
                   
                    @elseif ($objectName == 'fingerprint_status')
                    {!! @Helper::fingerPrintStatus($report->$objectName) !!}
                   
                    @elseif ($objectName == 'process')
                    @if ($report->$objectName == 1) 
                    <span>SMAW</span>
                    @else 
                    <span>GTAW + SMAW</span>
                    @endif

                    @elseif ($objectName == 'interview_attend')
                    
                    @if ($report->$objectName == 1) 
                    <span>Yes</span>
                    @else 
                    <span>No</span>
                    @endif

                    @elseif ($objectName == 'wqrt_test_report')
                    
                    @if ($report->$objectName == 1) 
                    <span>Accepted</span>
                    @else 
                    <span>Denied</span>
                    @endif

                    @elseif ($objectName == 'rt_test_result')
                    
                    @if ($report->$objectName == 1) 
                    <span>Accepted</span>
                    @else 
                    <span>Rejected</span>
                    @endif

                    @elseif ($objectName == 'interview_selected_status')
                    
                    @if ($report->$objectName == 1) 
                    <span>Yes</span>
                    @else 
                    <span>No</span>
                    @endif

                    @elseif ($objectName == 'salary_ad')
                    
                    @if ($report->$objectName == 1) 
                    <span>Accepted</span>
                    @else 
                    <span>Not Accepted</span>
                    @endif

                    @elseif ($objectName == 'food')
                    
                    @if ($report->$objectName == 1) 
                    <span>Company</span>
                    @else 
                    <span>Self</span>
                    @endif

                    @elseif ($objectName == 'ot')
                    
                    @if ($report->$objectName == 1) 
                    <span>Yes</span>
                    @else 
                    <span>No</span>
                    @endif

                    @elseif ($objectName == 'selected_trade')
                    {{ @Helper::singleTrade($report->$objectName)->trade_name }}
                    
                    @elseif ($objectName == 'reference_id')
                    {{ @Helper::reference($report->$objectName)->reference_name }}

                    @elseif ($objectName == 'agency_name')
                    {{ $report->$objectName }}

                    @elseif ($objectName == 'recruiting_licence_no')
                    {{ $report->$objectName }}
                   
                    @elseif ($objectName == 'country_id')
                    {{ @Helper::country($report->$objectName) }}

                    @elseif ($objectName == 'interview_date')
                        @if($report->$objectName == '0000-00-00 00:00:00')
                          {{'No Date'}}
                        @else
                          {{Carbon\Carbon::parse($report->$objectName)->format('d-m-Y')}}
                        @endif
                    @elseif ($objectName == 'medical_call_date')
                        @if($report->$objectName == '0000-00-00 00:00:00')
                          {{'No Date'}}
                        @else
                          {{Carbon\Carbon::parse($report->$objectName)->format('d-m-Y')}}
                        @endif
                    @elseif ($objectName == 'qvc_appointment_date')
                        @if($report->$objectName == '0000-00-00 00:00:00')
                          {{'No Date'}}
                        @else
                          {{Carbon\Carbon::parse($report->$objectName)->format('d-m-Y')}}
                        @endif

                    @elseif ($objectName == 'approved_date')
                        @if($report->$objectName == '0000-00-00 00:00:00')
                           NO DATE
                        @else
                          {{Carbon\Carbon::parse($report->$objectName)->format('d-m-Y')}}
                        @endif
                    @elseif ($objectName == 'qvc_appointment_completed')
                    <?php switch ($report->$objectName) {
                        case 1:
                            echo "Yes";
                            break;
                        
                        default:
                            echo "No";
                            break;
                    } ?>

                    @elseif ($objectName == 'medical_completed')
                    <?php switch ($report->$objectName) {
                        case 1:
                            echo "Yes";
                            break;
                        
                        default:
                            echo "No";
                            break;
                    } ?>
                    @elseif ($objectName == 'dealer')
                    <?php 
                       if(isset($report->$objectName))
                        {
                            $delearName = array();
                            if(json_decode($report->$objectName, true) != null){
                               $delearName = json_decode($report->$objectName, true);
                            }
                        }else{
                            $delearName = "";
                        }
                        $t=1;
                        $totalDealers = count($delearName);
                    ?>    
                    @if(!empty($delearName))
                        @foreach($delearName as $dealerId)
                            {{ @Helper::getDealerName($dealerId) }}
                            <?php if ($t<$totalDealers) { echo ","; } $t++;?>
                        @endforeach
                    @endif

                    @elseif ($objectName == 'medical_sent_date')
                        @if($report->$objectName == '0000-00-00 00:00:00')
                          {{'No Date'}}
                        @else
                          {{Carbon\Carbon::parse($report->$objectName)->format('d-m-Y')}}
                        @endif

                    @elseif ($objectName == 'gttc_date')
                        @if($report->$objectName == '0000-00-00 00:00:00')
                          {{'No Date'}}
                        @else
                          {{Carbon\Carbon::parse($report->$objectName)->format('d-m-Y')}}
                        @endif
                    @elseif ($objectName == 'pcc_date')
                        @if($report->$objectName == '0000-00-00 00:00:00')
                          {{'No Date'}}
                        @else
                          {{Carbon\Carbon::parse($report->$objectName)->format('d-m-Y')}}
                        @endif

                    @elseif ($objectName == 'medical_slip_date')
                        @if($report->$objectName == '0000-00-00 00:00:00')
                          {{'No Date'}}
                        @else
                          {{Carbon\Carbon::parse($report->$objectName)->format('d-m-Y')}}
                        @endif

                    @elseif ($objectName == 'medical_gone_date')
                        @if($report->$objectName == '0000-00-00 00:00:00')
                          {{'No Date'}}
                        @else
                          {{Carbon\Carbon::parse($report->$objectName)->format('d-m-Y')}}
                        @endif

                    @elseif ($objectName == 'gttc_received_date')
                        @if($report->$objectName == '0000-00-00 00:00:00')
                          {{'No Date'}}
                        @else
                          {{Carbon\Carbon::parse($report->$objectName)->format('d-m-Y')}}
                        @endif

                    @elseif ($objectName == 'pcc_received_date')
                        @if($report->$objectName == '0000-00-00 00:00:00')
                          {{'No Date'}}
                        @else
                          {{Carbon\Carbon::parse($report->$objectName)->format('d-m-Y')}}
                        @endif

                    @elseif ($objectName == 'mofa_date')
                        @if($report->$objectName == '0000-00-00 00:00:00')
                          {{'No Date'}}
                        @else
                          {{Carbon\Carbon::parse($report->$objectName)->format('d-m-Y')}}
                        @endif

                    @elseif ($objectName == 'medical_online_date')
                        @if($report->$objectName == '0000-00-00 00:00:00')
                          {{'No Date'}}
                        @else
                          {{Carbon\Carbon::parse($report->$objectName)->format('d-m-Y')}}
                        @endif

                    @elseif ($objectName == 'embassy_submission_date')
                        @if($report->$objectName == '0000-00-00 00:00:00')
                          {{'No Date'}}
                        @else
                          {{Carbon\Carbon::parse($report->$objectName)->format('d-m-Y')}}
                        @endif

                    @elseif ($objectName == 'visa_attached_date')
                        @if($report->$objectName == '0000-00-00 00:00:00')
                          {{'No Date'}}
                        @else
                          {{Carbon\Carbon::parse($report->$objectName)->format('d-m-Y')}}
                        @endif

                    @elseif ($objectName == 'visa_attach_expiry_date')
                        @if($report->$objectName == '0000-00-00')
                          {{'No Date'}}
                        @else
                          {{Carbon\Carbon::parse($report->$objectName)->format('d-m-Y')}}
                        @endif

                    @elseif ($objectName == 'visa_print_date')
                        @if($report->$objectName == '0000-00-00 00:00:00')
                          {{'No Date'}}
                        @else
                          {{Carbon\Carbon::parse($report->$objectName)->format('d-m-Y')}}
                        @endif

                    @elseif ($objectName == 'selection_date')
                        @if($report->$objectName == '0000-00-00 00:00:00')
                          {{'No Date'}}
                        @else
                          {{Carbon\Carbon::parse($report->$objectName)->format('d-m-Y')}}
                        @endif

                    @elseif ($objectName == 'visa_online_completed')
                   <?php
                    switch ($report->$objectName) {
                        case 1:
                           echo "Yes";
                            break;
                        
                        default:
                            echo "No";
                            break;
                    }
                    ?>

                    @elseif ($objectName == 'visa_print_completed')
                    
                   <?php
                    switch ($report->$objectName) {
                        case 1:
                           echo "Yes";
                            break;
                        
                        default:
                            echo "No";
                            break;
                    }
                    ?>             
                    @elseif ($objectName == 'visa_print_expiry_date')
                        @if($report->$objectName == '0000-00-00')
                          {{'No Date'}}
                        @else
                          {{Carbon\Carbon::parse($report->$objectName)->format('d-m-Y')}}
                        @endif

                    @elseif ($objectName == 'fingerprint_date')
                        @if($report->$objectName == '0000-00-00 00:00:00')
                          {{'No Date'}}
                        @else
                          {{Carbon\Carbon::parse($report->$objectName)->format('d-m-Y')}}
                        @endif

                    @elseif ($objectName == 'bmet_date')
                        @if($report->$objectName == '0000-00-00 00:00:00')
                          {{'No Date'}}
                        @else
                          {{Carbon\Carbon::parse($report->$objectName)->format('d-m-Y')}}
                        @endif

                    @elseif ($objectName == 'smartcard_date')
                        @if($report->$objectName == '0000-00-00 00:00:00')
                          {{'No Date'}}
                        @else
                          {{Carbon\Carbon::parse($report->$objectName)->format('d-m-Y')}}
                        @endif

                    @elseif ($objectName == 'pta_request_date')
                        @if($report->$objectName == '0000-00-00 00:00:00')
                          {{'No Date'}}
                        @else
                          {{Carbon\Carbon::parse($report->$objectName)->format('d-m-Y')}}
                        @endif

                    @elseif ($objectName == 'flight_date')
                        @if($report->$objectName == '0000-00-00 00:00:00')
                          {{'No Date'}}
                        @else
                          {{Carbon\Carbon::parse($report->$objectName)->format('d-m-Y')}}
                        @endif

                    @elseif ($objectName == 'pta_received_date')
                        @if($report->$objectName == '0000-00-00 00:00:00')
                          {{'No Date'}}
                        @else
                          {{Carbon\Carbon::parse($report->$objectName)->format('d-m-Y')}}
                        @endif

                    @elseif ($objectName == 'flight_briefing_date')
                        @if($report->$objectName == '0000-00-00 00:00:00')
                          {{'No Date'}}
                        @else
                          {{Carbon\Carbon::parse($report->$objectName)->format('d-m-Y')}}
                        @endif

                    @elseif ($objectName == 'medical_call_completed')
                    @if ($report->$objectName == 1) 
                    <span>Yes</span>
                    @else
                    <span>No</span>
                    @endif

                    @elseif ($objectName == 'medical_sent_completed')

                    @if ($report->$objectName == 1) 
                    <span>Yes</span>
                    @else
                    <span>No</span>
                    @endif

                    @elseif ($objectName == 'gttc_completed')

                    @if ($report->$objectName == 1) 
                    <span>Yes</span>
                    @else
                    <span>No</span>
                    @endif

                    @elseif ($objectName == 'pcc_completed')

                    @if ($report->$objectName == 1) 
                    <span>Yes</span>
                    @else
                    <span>No</span>
                    @endif

                    @elseif ($objectName == 'medical_slip_completed')

                    @if ($report->$objectName == 1) 
                    <span>Yes</span>
                    @else
                    <span>No</span>
                    @endif

                    @elseif ($objectName == 'gttc_received_completed')

                    @if ($report->$objectName == 1) 
                    <span>Yes</span>
                    @else
                    <span>No</span>
                    @endif

                    @elseif ($objectName == 'pcc_received_completed')

                    @if ($report->$objectName == 1) 
                    <span>Yes</span>
                    @else
                    <span>No</span>
                    @endif

                    @elseif ($objectName == 'mofa_completed')

                    @if ($report->$objectName == 1) 
                    <span>Yes</span>
                    @else
                    <span>No</span>
                    @endif

                    @elseif ($objectName == 'medical_online_completed')

                    @if ($report->$objectName == 1) 
                    <span>Yes</span>
                    @else
                    <span>No</span>
                    @endif

                    @elseif ($objectName == 'embassy_submission_completed')

                    @if ($report->$objectName == 1) 
                    <span>Yes</span>
                    @else
                    <span>No</span>
                    @endif

                    @elseif ($objectName == 'visa_attached_completed')

                    @if ($report->$objectName == 1) 
                    <span>Yes</span>
                    @else
                    <span>No</span>
                    @endif

                    @elseif ($objectName == 'fingerprint_completed')

                    @if ($report->$objectName == 1) 
                    <span>Yes</span>
                    @else
                    <span>No</span>
                    @endif

                    @elseif ($objectName == 'bmet_completed')

                    @if ($report->$objectName == 1) 
                    <span>Yes</span>
                    @else
                    <span>No</span>
                    @endif

                    @elseif ($objectName == 'smartcard_completed')

                    @if ($report->$objectName == 1) 
                    <span>Yes</span>
                    @else
                    <span>No</span>
                    @endif

                    @elseif ($objectName == 'pta_request_completed')

                    @if ($report->$objectName == 1) 
                    <span>Yes</span>
                    @else
                    <span>No</span>
                    @endif

                    @elseif ($objectName == 'pta_received_completed')

                    @if ($report->$objectName == 1) 
                    <span>Yes</span>
                    @else
                    <span>No</span>
                    @endif

                    @elseif ($objectName == 'flight_briefing_completed')

                    @if ($report->$objectName == 1) 
                    <span>Yes</span>
                    @else
                    <span>No</span>
                    @endif

                    @elseif ($objectName == 'flight_completed')

                    @if ($report->$objectName == 1) 
                    <span>Deployed</span>
                    @else
                    <span>No</span>
                    @endif

                @else
                     {{ $report->$objectName }}  
                @endif
            </td>
            @endforeach
        </tr>
        @endforeach   
      </tbody>
    </table>
    </div>
</div>
</body>

<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>


<script>
  $(document).ready(function() {
    var project_name = $('#project_name').val();
    var data = [];
    // for ( var i=0 ; i<50000 ; i++ ) {
    //     data.push( [ i, i, i, i, i,i,i,i,i,i,i,i,i,i,i,i,i,i,i,i,i,i,i,i,i,i,i,i, ] );
    // }    
    $('#myTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {   extend: 'excelHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                filename: project_name
            },
            {   extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                filename: project_name
            },
            {   extend: 'print',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                filename: project_name
            },
        ]
    });
  });
</script>
<script type="text/javascript">
    function printDocument() {
        window.print();
    }
</script>
</html>
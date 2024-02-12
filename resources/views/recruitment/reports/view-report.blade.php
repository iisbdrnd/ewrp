<style>
  .btn-success{
    display: none;
  }
</style>      
<ul class="list-group">
<div class="col-md-6">
@foreach($labels as $label)
<li class="list-group-item" style="border-right:0px; padding-right: 0px;margin-right: 0px;"><strong>{{ $label }}</strong></li>
@endforeach
</div>
<div class="col-md-6">
<?PHP $i= 1;?>
@foreach($reports as $report) 
    @foreach($masterObjectNames as $objectName)
        <li class="list-group-item" style="border-left: 0px; padding-left:0px; margin-left: -20px; "><b>:</b>&nbsp;
            @if($objectName == 'passport_status') 
            {!!  @Helper::passportStatus($report->$objectName) !!}
            @elseif($objectName == 'home_experience') 
            {{ $report->$objectName >1?$report->$objectName.' Years':'Year' }}
            @elseif($objectName == 'oversease_experience') 
            {{ $report->$objectName >1?$report->$objectName.' Years':'Year' }} 
            @elseif($objectName == 'total_years_of_experience') 
            {{ $report->$objectName >1?$report->$objectName.' Years':'Year' }}
            @elseif($objectName == 'medical_actual_status')
            {!! @Helper::medicalActualStatus($report->$objectName) !!}
            @elseif($objectName == 'salary_ad')
            {!! @Helper::salarayAD($report->$objectName) !!}
            @elseif($objectName == 'fingerprint_status')
            {!! @Helper::fingerPrintStatus($report->$objectName) !!}
            @elseif($objectName == 'visa_online_job_category_id')
            {{ @Helper::singleJobCategory($report->$objectName)->job_category_name }}
             @elseif($objectName == 'selected_trade')
            {{ @Helper::singleTrade($report->$objectName)->trade_name }}
            @elseif($objectName == 'reference_id')
            {{ @Helper::reference($report->$objectName)->reference_name }}
            @elseif($objectName == 'country_id')
            {{ @Helper::country($report->$objectName) }}
            @else  
                 {{ empty($report->$objectName)?'Not Found':$report->$objectName }}
                
            @endif
        </li>
    @endforeach
@endforeach   
</div>
</ul>
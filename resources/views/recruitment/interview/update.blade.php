<style>
.from_overs_exp{
  padding: 0px 8px !important;
}
.to_overs_exp{
  padding: 0px 8px !important;
}
</style>
<?php $panelTitle = "Candidate CV Create"; ?>
<form type="update" panelTitle="{{$panelTitle}}" class="form-load form-horizontal" callback="accountFormRefresh" data-fv-excluded="">
  {{csrf_field()}}
  <div class="panel panel-default chart mt20">
    <div class="panel-body">
      <div class="col-lg-4 col-md-4">
        <input type="hidden" name="project_country_id" id="project_country_id" value="{{ @Helper::projects($editCandidateCvs->ew_project_id)->project_country_id }}">
        <input type="hidden" name="project_id" value="{{ @$editCandidateCvs->ew_project_id }}">
        <div class="form-group">
          <input type="hidden">
          <label class="col-lg-3 col-md-3 control-label">Interview Date</label>
          <div class="col-lg-9 col-md-9">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
              <input type="hidden" name="date" class="form-control keypressOff">
              <input autocomplete="off" autofocus id="interview_date" name="interview_date" class="form-control dateTimeFormat keypressOff"
                placeholder="e.g. 15-12-2019" value="{{ Carbon\Carbon::parse($editCandidateCvs->interview_date)->format('d-m-Y') }}">
            </div>
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-3 col-md-3 control-label required">Full Name</label>
          <div class="col-lg-9 col-md-9">
            <input type="hidden" class="form-control">
            <input autofocus required name="full_name" value="{{ @$editCandidateCvs->full_name }}" id="full_name"
              placeholder="Full Name" class="form-control">
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-3 col-md-3 control-label">Father's Name</label>
          <div class="col-lg-9 col-md-9">
            <input name="father_name" id="father_name" value="{{ @$editCandidateCvs->father_name }}" placeholder="Father's Name"
              class="form-control">
          </div>
        </div>
        {{-- <div class="form-group">
          <label class="col-lg-3 col-md-3 control-label ">National ID</label>
          <div class="col-lg-9 col-md-9">
            <input autofocus name="national_id" value="{{ @$editCandidateCvs->national_id }}" id="national_id"
              placeholder="e.g. 28993268881" class="form-control">
          </div>
        </div> --}}
        <div class="form-group">
          <label class="col-lg-3 col-md-3 control-label required ">Passport No.</label>
          <div class="col-lg-4 col-md-4">
            <input class="form-control" id="passport_no" required name="passport_no" value="{{ @$editCandidateCvs->passport_no }}"
              placeholder="e.g. 0889382">
          </div>
          <label class="col-lg-2 col-md-2 control-label">Status</label>
          <div class="col-lg-3 col-md-3">
            <select name="passport_status" id="passport_status" class="form-control select2" placeholder="Status">
              <option {{ @$editCandidateCvs->passport_status == 2?'selected=selected':'' }} value="2">Yes</option>
              <option {{ @$editCandidateCvs->passport_status == 3?'selected=selected':'' }} value="3">No</option>
              <option {{ @$editCandidateCvs->passport_status == 1?'selected=selected':'' }} value="1">In Office</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-3 col-md-3 control-label ">PP Exp Date</label>
          <div class="col-lg-9 col-md-9">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
              <input id="passport_expired_date" name="passport_expired_date" value="{{ Carbon\Carbon::parse(@$editCandidateCvs->passport_expired_date)->format('d-m-Y') }}"
                class="form-control dateTimeFormat keypressOff" placeholder="e.g. 2019-12-15" autocomplete="off">
            </div>
          </div>
        </div>
        <div class="form-group">
            <!-- Start .row -->
              <label class="col-lg-3 col-md-3 control-label">Date Of Birth</label>
              <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                  <input autocomplete="off" id="date_of_birth" name="date_of_birth" value="{{ Carbon\Carbon::parse(@$editCandidateCvs->date_of_birth)->format('d-m-Y') }}"
                    class="form-control dateTimeFormat keypressOff" placeholder="e.g. 1990-10-18">
                </div>
              </div>
              <label class="col-lg-1 col-md-1 control-label ">Age</label>
              <div class="col-lg-3 col-md-3 col-sm-2 col-xs-12">
                <input class="form-control keypressoff-only-age" id="age" name="age"
                  value="{{ @$editCandidateCvs->age }}" placeholder="e.g. 34" readonly>
              </div>
            <!-- End .row -->
        </div>
        <div class="form-group">
          <label class="col-lg-3 col-md-3 control-label required">Contact No.</label>
          <div class="col-lg-9 col-md-9">
            <input autofocus required name="contact_no" value="{{ @$editCandidateCvs->contact_no }}" id="contact_no"
              placeholder="e.g. 01759xxxxxx" class="form-control">
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-3 col-md-3 control-label required">Reference</label>
          <div class="col-lg-9 col-md-9">
            <select name="reference_id" id="reference_id" class="form-control select2" palceholder="Select Reference">
              <option></option>
              @foreach($references as $reference)
              <option {{ @$editCandidateCvs->reference_id == $reference->id? 'selected=selected':'' }} value="{{ $reference->id }}">{{
                $reference->reference_name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-3 col-md-3 control-label ">Dealer:</label>
          <div style="margin-top: -17px;" class="col-lg-7 col-md-7 col-md-offset-3" id="dealer">
            <?php 
              //$dealers = json_decode($singgleRefs->dealer);
              $dealers = $singgleRefs->dealer;
            ?>
            @if(!empty($dealers))
              {{@Helper::dealer($singgleRefs->dealer)->name}}
              {{-- @foreach($dealers as $dealerId)
              {{ @Helper::dealer($dealerId)->name.',' }}
              @endforeach --}}
            @endif
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-3 col-md-3 control-label">Agency</label>
          <div class="col-lg-9 col-md-9">
            <select name="agency_id" id="agency_id" class="form-control select2" palceholder="Select Reference">
              <option></option>
              @foreach($agency as $agency)
              <option {{ @$editCandidateCvs->selected_agency == $agency->id? 'selected=selected':'' }} value="{{ $agency->id }}">{{
                $agency->agency_name }}</option>
              @endforeach
            </select>
            <span id="agency_stock_feedback" style="color: red"></span>
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-3 col-md-3 control-label ">Licence No:</label>
            <div style="margin-top: -17px;" class="col-lg-7 col-md-7 col-md-offset-3" id="agency_licence">
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-3 col-md-3 control-label ">Availabe Quantity:</label>
            <div style="margin-top: -17px;" class="col-lg-7 col-md-7 col-md-offset-3" id="agency_quantity">
          </div>
        </div>
      </div>
      <!--col-6 End-->
      <div class="col-lg-8 col-md-8 col-sm-12">
        <div class="form-group">
          <label class="col-lg-2 col-md-2 control-label">Trade Applied</label>
          <div class="col-lg-4 col-md-4">
            <select class="form-control select2" id="trade_applied" name="trade_applied" placeholder="Select Trade">
              <option></option>
              @foreach($projectTrades as $projectTrade)
              <option {{ $tradeApplied !==null && $tradeApplied == $projectTrade->trade_id?'selected=selected':''    }}
                value="{{  $projectTrade->trade_id }}">{{ $projectTrade->trade_name}}</option>
              @endforeach
            </select>
          </div>
          <label class="col-lg-2 col-md-2 control-label required">Trade Selection</label>
          <div class="col-lg-4 col-md-4">
            <select class="form-control select2" required id="selected_trade" name="selected_trade" placeholder="Selected Trade">
              <option></option>
              @foreach($projectTrades as $pTrade)
              <option {{ $tradeSelected !==null && $tradeSelected == $pTrade->trade_id?'selected=selected':'' }} value="{{  $pTrade->trade_id }}">{{$pTrade->trade_name}}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-2 col-md-2 control-label ">Grade</label>
          <div class="col-lg-4 col-md-4">
            <select name="grade" id="grade" class="select2 form-control" placeholder="Select Grade">
              <option value="0">--Select Grade--</option>
              <option value="A+" {{ $editCandidateCvs->grade =="A+"?'selected=selected':'' }}>A+</option>
              <option value="A" {{ $editCandidateCvs->grade =="A"?'selected=selected':'' }}>A</option>
              <option value="B+" {{ $editCandidateCvs->grade =="B+"?'selected=selected':'' }}>B+</option>
              <option value="B" {{ $editCandidateCvs->grade =="B"?'selected=selected':'' }}>B</option>
              <option value="C+" {{ $editCandidateCvs->grade =="C+"?'selected=selected':'' }}>C+</option>
              <option value="C" {{ $editCandidateCvs->grade =="C"?'selected=selected':'' }}>C</option>
              <option {{ $editCandidateCvs->grade =="Excellent" }} value="Excellent">Excellent</option>
              <option {{ $editCandidateCvs->grade =="Very Good" }} value="Very Good">Very Good</option>
              <option {{ $editCandidateCvs->grade =="Good" }} value="Good">Good</option>
              <option {{ $editCandidateCvs->grade =="Average" }} value="Average">Average</option>
              <option {{ $editCandidateCvs->grade =="Poor" }} value="Poor">Poor</option>
            </select>
          </div>
          <label class="col-lg-2 col-md-2 control-label">Score</label>
          <div class="col-lg-4 col-md-4">
          <input type="text" name="score" id="score" value="{{ $editCandidateCvs->score }}" class="form-control" placeholder="e.g. 75">
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-2 col-md-2 control-label">Salary</label>
          <div class="col-lg-4 col-md-4">
            <input autofocus name="salary" id="salary" placeholder="e.g. 1200" class="form-control" value="{{  @$editCandidateCvs->salary }}">
          </div>
          {{-- <label class="col-lg-1 col-md-1 control-label">Food</label> --}}
          <div class="col-lg-2 col-md-2">
            <select name="food" id="food" class="form-control select2" placeholder="Food">
              <option {{ @$editCandidateCvs->food == 1?'selected':'' }} value="1">Company</option>
              <option {{ @$editCandidateCvs->food == 2?'selected':'' }} value="2">Self</option>
            </select>
          </div>
          {{-- <label class="col-lg-1 col-md-1 control-label">OT</label> --}}
          <div class="col-lg-2 col-md-2">
            <select name="ot" id="ot" class="form-control select2" placeholder="OT">
              <option {{ @$editCandidateCvs->ot == 1?'selected':'' }} value="1">Yes</option>
              <option {{ @$editCandidateCvs->ot == 2?'selected':'' }} value="0">No</option>
            </select>
          </div>
          {{-- <label class="col-lg-3 col-md-3 control-label">Salary A/D</label> --}}
          <div class="col-lg-2 col-md-2">
            <select name="salary_ad" id="salary_ad" class="form-control select2" placeholder="A/D">
              <option></option>
              <option {{  @$editCandidateCvs->salary_ad == 1? 'selected=selected':'' }} value="1">Accpted</option>
              <option {{  @$editCandidateCvs->salary_ad == 2? 'selected=selected':'' }} value="2">Not Accpted</option>
            </select>
          </div>
        </div> 
        <div class="form-group">
          <label class="col-lg-2 col-md-2 control-label">Result</label>
          <div class="col-lg-4 col-md-4">
            <select name="result" id="result" class="form-control select2" placeholder="Result">
                <option 
                {{ @$editCandidateCvs->result == 1
                ?'selected=selected'
                :'' }} value="1">Pass</option>
                <option 
                {{ @$editCandidateCvs->result == 2
                ?'selected=selected'
                :'' }} value="2">Fail</option>
                <option 
                {{ @$editCandidateCvs->result == 3
                ?'selected=selected'
                :'' }} value="3">Waiting</option>
                <option 
                {{ @$editCandidateCvs->result == 4
                ?'selected=selected'
                :'' }} value="4">Hold</option>
                <option 
                {{ @$editCandidateCvs->result == 5
                ?'selected=selected'
                :'' }} value="5">Decline</option>
              </select>
          </div>
          <label class="col-lg-2 col-md-2 control-label">Education</label>
          <div class="col-lg-4 col-md-4">
            <input autofocus name="education" id="education" value="{{ @$editCandidateCvs->education }}" placeholder="e.g. S.S.C" class="form-control">
          </div>
        </div>
        <div class="form-group" id="homeExpDiv">
          <label class="col-lg-2 col-md-2 control-label ">Home Exp.</label>
          <?php 
              $lastIndex      = count(json_decode($editCandidateCvs->home_experience_details, true)) - 1;
              $from_home_exp  = json_decode($editCandidateCvs->from_home_exp, true);
              $to_home_exp    = json_decode($editCandidateCvs->to_home_exp, true);
              $total_home_exp = json_decode($editCandidateCvs->total_home_exp, true);
          ?>

          @if (!empty($editCandidateCvs->home_experience_details))

          @foreach (json_decode($editCandidateCvs->home_experience_details, true) as $index => $ans)
          <div class="col-lg-10 col-md-10 @if($lastIndex>0) col-lg-offset-2 col-md-offset-2 @endif" id="answer_view_2_3">
              <div class="row">
                  <div class="col-md-4 pr0 mb10">
                      <input name="home_experience_details[]"  class="form-control" value="{{$ans}}" placeholder="Company Name">
                  </div>
                  <div class="col-md-2 pr0 mb10">
                      <input type="text"  name="from_home_exp[]" id="from_home_exp{{ $index }}" value="{{ @$from_home_exp[$index] }}" serialId="{{ $index }}" class="form-control  from_home_exp" placeholder="yyyy">
                  </div>
                  <div class="col-md-2 pr0 mb10">
                      <input type="text" name="to_home_exp[]" serialId="{{ $index }}" id="to_home_exp{{ $index }}" value="{{ $to_home_exp[$index] }}" class="form-control  to_home_exp" placeholder="yyyy">
                  </div>
                  <div class="col-md-2 pr0 mb10">
                      <input name="total_home_exp[]" placeholder="y" readonly id="total_home_exp{{ $index }}" class="form-control total_home_exp" value="{{ @$total_home_exp[$index] }}">
                      <input type="hidden" name="home_experience[]"  class="form-control trueAnswer" value="{{ $index+1 }}">
                  </div>
                  <div class="col-md-2 btnView pr0">
                      <?php if($lastIndex>0) { ?><button id="answer_remove" class="btn btn-danger ml5" type="button"><i class="glyphicon glyphicon-remove"></i></button><?php } if($index==$lastIndex) { ?><button id="answer_add" class="btn btn-success ml5" type="button"><i class="glyphicon glyphicon-plus"></i></button><?php } ?>
                  </div>
              </div>
          </div>
          @endforeach

          @else 
         
          @endif

        </div>
        <div class="form-group" id="overExpDiv">
          <label class="col-lg-2 col-md-2 control-label ">Overs Exp.</label>
          <?php 
              $overLastIndex = count(json_decode($editCandidateCvs->oversease_experience_details, true))-1;
              $oversease_from_year = json_decode($editCandidateCvs->from_overs_exp, true);
              $oversease_to_year = json_decode($editCandidateCvs->to_overs_exp, true);
              $oversease_total_year = json_decode($editCandidateCvs->total_overs_exp, true);
              $oversease_country = json_decode($editCandidateCvs->oversease_country, true);
          ?>
          @if(!empty($editCandidateCvs->oversease_experience_details))
          @foreach(json_decode($editCandidateCvs->oversease_experience_details, true) as $index => $ans)
          <div class="col-lg-10 col-md-10 @if($overLastIndex>0) col-lg-offset-2 col-md-offset-2 @endif" id="answer_view_2_3_1">
            <div class="row">
              <div class="col-md-4 pr0 mb10">
                <input name="oversease_experience_details[]"  class="form-control" value="{{$ans}}" placeholder="Company Name">
              </div>
              <div class="col-md-3 col-lg-3 pr0 mb10">
                <select name="oversease_country[]" id="oversease_country" class="form-control">
                  <option value="0">Select Country</option>
                    @foreach($expCountries as $expCountry)
                    <option {{ @$oversease_country[$index] ==  $expCountry->id?'selected':'' }} value="{{ $expCountry->id }}">{{ $expCountry->name }}</option>
                    @endforeach
                 </select>
              </div>
              <div class="col-md-1 pr0 mb10">
                  <input type="text" name="from_overs_exp[]" serialId="{{ $index }}" id="from_overs_exp{{ $index }}" class="form-control  from_overs_exp" style="direction: rtl;" value="{{ @$oversease_from_year[$index] }}" placeholder="yyyy">
              </select>
              </div>
              <div class="col-md-1 pr0 mb10">
                  <input type="text" name="to_overs_exp[]" serialId="{{ $index }}" id="to_overs_exp{{ $index }}" class="form-control  to_overs_exp" style="direction: rtl;" value="{{ @$oversease_to_year[$index] }}" placeholder="yyyy">
              </select>
              </div>
              <div class="col-md-1 pr0 mb10">
                <input name="total_overs_exp[]" id="total_overs_exp{{ $index }}" readonly placeholder="y" class="form-control total_overs_exp" value="{{ @$oversease_total_year[$index] }}">
                <input type="hidden" name="over_experience[]" class="form-control trueAnswer2"
                  value="{{ $index+1 }}">
              </div>
              <div class="col-md-2 btnView pr0">
                <?php if(@$overLastIndex>0) { ?><button id="answer_remove2" class="btn btn-danger ml5" type="button"><i
                    class="glyphicon glyphicon-remove"></i></button>
                <?php } if($index==$overLastIndex) { ?><button id="answer_add2" class="btn btn-success ml5" type="button"><i
                    class="glyphicon glyphicon-plus"></i></button>
                <?php } ?>
              </div>
            </div>
          </div>
          @endforeach
          @else
        
          @endif
        </div>
        <div class="form-group">
          <label class="col-lg-2 col-md-2 control-label">Process</label>
          <div class="col-lg-4 col-md-4">
              <select name="process" id="process" class="form-control select2" placeholder="Process">
                <option {{ @$editCandidateCvs->process == 1?'selected':'' }} value="1">SMAW</option>
                <option {{ @$editCandidateCvs->process == 2?'selected':'' }} value="2">GTAW + SMAW</option>
              </select>
            </div>
          <div class="col-lg-2 col-md-2">
            <input name="wqrt_no" placeholder="e.g. 12021" value="{{ @$editCandidateCvs->wqrt_no }}" class="form-control">
          </div>
          {{-- <label class="col-lg-2 col-md-2 control-label">WQRT Test Rpt.</label> --}}
          <div class="col-lg-2 col-md-2">
            <select name="wqrt_test_report" id="wqrt_test_report" class="form-control select2" placeholder="WQRT TEST">
              <option {{ @$editCandidateCvs->wqrt_test_report == 0?'selected':'' }} value="0"> WQRT Test</option>
              <option {{ @$editCandidateCvs->wqrt_test_report == 1?'selected':'' }} value="1">Accepted</option>
              <option {{ @$editCandidateCvs->wqrt_test_report == 2?'selected':'' }} value="2">Denied</option>
            </select>
          </div>
          <div class="col-lg-2 col-md-2">
            <select name="rt_test_result" id="rt_test_result" class="form-control select2" placeholder="RT TEST">
              <option {{ @$editCandidateCvs->rt_test_result == 1?'selected':'' }} value="1">Accept</option>
              <option {{ @$editCandidateCvs->rt_test_result == 2?'selected':'' }} value="2">Reject</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-2 col-md-2 control-label">Selection Date</label>
          <div class="col-lg-10 col-md-10">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
              <input id="selection_date" name="selection_date" value="{{ Carbon\Carbon::parse(@$editCandidateCvs->selection_date)->format('d-m-Y') }}" class="form-control dateTimeFormat keypressOff"
                placeholder="e.g. 15-12-2019" autocomplete="off">
            </div>
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-2 col-md-2 control-label">Remarks</label>
          <div class="col-lg-10 col-md-10">
            <input autofocus name="remarks" value="{{ @$editCandidateCvs->remarks }}" id="remarks" placeholder="e.g. This is candidate fit for visa processing"
              class="form-control">
          </div>
        </div>
        {{-- <div class="form-group">
          <div class="col-lg-2 col-md-2" style="display:none;"></div>
          <div class="col-lg-10 col-md-10">
            <input type="checkbox" checked {{ @$editCandidateCvs->cv_completeness_status == 1?'checked':'' }} name="cv_completeness_status"
              value="1">
            <label>Is this interview information completed?</label>
          </div>
        </div> --}}
      </div>
    </div>
    <!--panel-body end-->
  </div>
</form>

<script type="text/javascript" src="{{ asset('public/js/moment.min.js') }}"></script>
<script type="text/javascript">
  $('#reference_id').on('change', function () {
    console.log(this.value);
    $.post('{{ route('recruit.dealer') }}', {
        'dealerId': this.value,
        '_token': $('input[name=_token]').val()
      },
      function (response) {
        console.log(typeof (response.dealer));
        if (response.dealer == undefined) {
          $('#dealer').html('No dealer found!');
        } else {
          $('#dealer').html(response.dealer);
        }

      })
  });
  $("select.select2").select2({
    placeholder: "Select"
  });

 
  var i = 99999999;
  /*----------------------------------------------------------------------------------
                                                  HOME EXPERIENCE
   ----------------------------------------------------------------------------------*/
  function agency_change(agency_id,type)
  {
    var projectId = "{{$editCandidateCvs->ew_project_id}}";
    $.get('{{ route('recruit.agency_details') }}', {
        'agencyId': agency_id,
        'projectId': projectId,
        '_token': $('input[name=_token]').val()
      },
      function (response) {
        console.log(response);
          $('#agency_licence').html(response.licence);
          $('#agency_quantity').html(response.qty);
          if(response.qty == 0 && type == 1)
          {
            $('#agency_stock_feedback').html('out of stock');
            $(".modal-footer .btn-success").prop('disabled', true);
          }
          else
          {
            $('#agency_stock_feedback').html("");
            $(".modal-footer .btn-success").prop('disabled', false);
          }
      });
  }

  $('#agency_id').on('change', function () {
      agency_change(this.value,1);
  });

  $(document).ready(function () {
    var agency_id = $('#agency_id').val();
    if(agency_id)
    {
      agency_change(agency_id,0);
    }


    //Start Home Exprience
    $("#homeExpDiv").on("click", "#answer_add", function () {
      i++;
      $("#homeExpDiv").find("#answer_add").remove();
      var trueAnswer = $("#answer_view_2_3").find(".trueAnswer:last").val();
      $("#homeExpDiv").append(
        '<div class="col-lg-10 col-md-10 col-lg-offset-2 col-md-offset-2" id="answer_view_2_3"><div class="row"><div class="col-md-4 pr0 mb10"><input name="home_experience_details[]" class="form-control" placeholder="Company Name"></div><div class="col-md-2 pr0 mb10"><input type="text"  name="from_home_exp[]" serialId="'+i+'" id="from_home_exp'+i+'" class="form-control from_home_exp" placeholder="yyyy"></div><div class="col-md-2 pr0 mb10"><input type="text" name="to_home_exp[]" serialId="'+i+'" id="to_home_exp'+i+'" class="form-control to_home_exp " placeholder="yyyy"><input type="hidden" name="home_experience[]" class="form-control trueAnswer" value="' +
        (parseInt(trueAnswer) + 1) +
        '"></div><div class="col-md-2 pr0 mb10"><input name="total_home_exp[]" readonly placeholder="y" id="total_home_exp'+i+'" class="form-control total_home_exp"></div><div class="col-md-2 btnView pr0"><button id="answer_remove" class="btn btn-danger ml5" type="button"><i class="glyphicon glyphicon-remove"></i></button><button id="answer_add" class="btn btn-success ml5" type="button"><i class="glyphicon glyphicon-plus"></i></button></div></div></div>'
      );

      if ($("#homeExpDiv").find(".btnView:first").find("#answer_remove").length <= 0) {
        $("#homeExpDiv").find(".btnView:first").append(
          '<button id="answer_remove" class="btn btn-danger ml5" type="button"><i class="glyphicon glyphicon-remove"></i></button>'
        );
      }
    });

    $("#homeExpDiv").on("click", "#answer_remove", function () {
      $(this).parents(".row").first().remove();
      if ($("#homeExpDiv").find("#answer_add").length <= 0) {
        $("#homeExpDiv").find(".btnView:last").append(
          '<button id="answer_add" class="btn btn-success ml5" type="button"><i class="glyphicon glyphicon-plus"></i></button>'
        );
      }
      if ($("#homeExpDiv").find(".btnView").length == 1) {
        $("#homeExpDiv").find(".btnView").find("#answer_remove").remove();
      }
      $("#answer_view_2_3").find(".trueAnswer").each(function (index) {
        $(this).val(index);
      });
    });
    //End Home Exprience
    $("#homeExpDiv").on('change', '.from_home_exp', function(){
      var serialId = $('#'+this.id).attr('serialId');
      totalHomeExperience(serialId);
    });
    $("#homeExpDiv").on('change', '.to_home_exp', function(){
      var serialId = $('#'+this.id).attr('serialId');
      totalHomeExperience(serialId);
    });

    function totalHomeExperience(serialId){
      var from_home_exp = $('#from_home_exp'+Number(serialId)).val();
      var to_home_exp = $('#to_home_exp'+Number(serialId)).val();
      var totalExp =Number(to_home_exp) - Number(from_home_exp);
     
      $('input#total_home_exp'+Number(serialId)).val(totalExp+1);
      
    }

    //Start Oversease Exprience
    i = 99999999;
    $("#overExpDiv").on("click", "#answer_add2", function () {
      i++;
      $("#overExpDiv").find("#answer_add2").remove();
      var trueAnswer = $("#answer_view_2_3_1").find(".trueAnswer:last").val();
      $("#overExpDiv").append(
        '<div class="col-lg-10 col-md-10 col-lg-offset-2 col-md-offset-2" id="answer_view_2_3_1"><div class="row"><div class="col-md-4 pr0 mb10"><input name="oversease_experience_details[]" class="form-control" placeholder="Company Name"></div><div class="col-md-3 col-lg-3 pr0 mb10"><select name="oversease_country[]" id="oversease_country" class="form-control"><option value="0">Select Country</option>@foreach($expCountries as $expCountry)<option value="{{ $expCountry->id }}">{{ $expCountry->name }}</option>@endforeach</select></div><div class="col-md-1 pr0 mb10"><input type="text" name="from_overs_exp[]" id="from_overs_exp'+i+'" serialId="'+i+'" class="form-control  from_overs_exp" style="direction: rtl;" placeholder="yyyy"></div><div class="col-md-1 pr0 mb10"><input type="text" name="to_overs_exp[]" id="to_overs_exp'+i+'" serialId="'+i+'" class="form-control  to_overs_exp" style="direction: rtl;" placeholder="yyyy"><input type="hidden" name="overs_experience[]" class="form-control trueAnswer2" value="' +
        (parseInt(trueAnswer) + 1) +
        '"></div><div class="col-md-1 pr0 mb10"><input name="total_overs_exp[]" id="total_overs_exp'+i+'" readonly placeholder="y" class="form-control total_overs_exp"></div><div class="col-md-2 btnView pr0"><button id="answer_remove2" class="btn btn-danger ml5" type="button"><i class="glyphicon glyphicon-remove"></i></button><button id="answer_add2" class="btn btn-success ml5" type="button"><i class="glyphicon glyphicon-plus"></i></button></div></div></div>'
      );

      if ($("#overExpDiv").find(".btnView:first").find("#answer_remove2").length <= 0) {
        $("#overExpDiv").find(".btnView:first").append(
          '<button id="answer_remove2" class="btn btn-danger ml5" type="button"><i class="glyphicon glyphicon-remove"></i></button>'
        );
      }
    });

    $("#overExpDiv").on("click", "#answer_remove2", function () {
      $(this).parents(".row").first().remove();
      if ($("#overExpDiv").find("#answer_add2").length <= 0) {
        $("#overExpDiv").find(".btnView:last").append(
          '<button id="answer_add2" class="btn btn-success ml5" type="button"><i class="glyphicon glyphicon-plus"></i></button>'
        );
      }
      if ($("#overExpDiv").find(".btnView").length == 1) {
        $("#overExpDiv").find(".btnView").find("#answer_remove2").remove();
      }
      $("#answer_view_2_3_1").find(".trueAnswer").each(function (index) {
        $(this).val(index);
      });
    });
    //End Over Exprience
    $("#overExpDiv").on('change', '.from_overs_exp', function(){
      var serialId = $('#'+this.id).attr('serialId');
      totalOversExperience(serialId);
    });
    $("#overExpDiv").on('change', '.to_overs_exp', function(){
      var serialId = $('#'+this.id).attr('serialId');
      totalOversExperience(serialId);
    });

    function totalOversExperience(serialId){
      var from_overs_exp = $('#from_overs_exp'+Number(serialId)).val();
      var to_overs_exp   = $('#to_overs_exp'+Number(serialId)).val();
      var totalExp       = Number(to_overs_exp) - Number(from_overs_exp);
     
      $('#total_overs_exp'+Number(serialId)).val(totalExp+1);
      console.log(typeof(from_overs_exp.length));
     
    }
  });


  /*---------------------------------
  DATE PICKER FOR ALL CALENDAR
  -----------------------------------*/
  $("select.select2").select2({
    placeholder: "Select"
  });

  $('.keypressOff').keypress(function (e) {
    return false
  });

  $('.keypressoff-only-age').keypress(function (e) {
    return false
  });

  $('.dateTimeFormat').datepicker({
    format: "dd-mm-yyyy"
  });

  $('#date_of_birth').on('change', function () {
    var dateOfBirth = moment($(this).val(), 'DD-MM-YYYY').format('DD-MMM-YYYY');;
    var currentDate = moment();
    var age = currentDate.diff(dateOfBirth, 'year');
    $('#age').val(age);
  });

  $('input').keyup(function(e) {
    
    var code = e.keyCode || e.which;
    if (code == '9') {
      $(this).css('border', '2px solid #87C540');
    }
 });

  $('button').keyup(function(e) {
    
    var code = e.keyCode || e.which;
    if (code == '9') {
      $(this).css({'border':'2px solid #C12E2A'});
    }
 });
  $('button').focusout(function(e) {
    $(this).css('border', '1px solid #c4c4c4');
 });

  $('select').keyup(function(e) {
    
    var code = e.keyCode || e.which;
    if (code == '9') {
      $(this).css('border', '2px solid #87C540');
    }
 });

  $('select').focusout(function(e) {
      $(this).css('border', '1px solid #c4c4c4');    
 });

   $('.select2-container').keyup(function(e) {
    
    var code = e.keyCode || e.which;
    if (code == '9') {
      $(this).css('border', '2px solid #87C540');
    }
 });
 $('input').focusout(function(){
  $(this).css('border', '1px solid #c4c4c4'); 
 });
 $('.select2-container').focusout(function(){
  $(this).css('border', '1px solid #c4c4c4'); 
 });
//  $('.modal-title').text(" ");
$('.modal-title').text("Update Interview CV");
$('.modal-footer button.btn-default').text('Back To List');

// $('.modal-footer button.btn-default').on('click', function(){
  // $('.panel-refresh').trigger('click');
//   setTimeout(function() {
//     $('.panel-refresh').trigger('click');
//   }, 50);
// });
function accountFormRefresh(data) {
  $('.panel-refresh').trigger('click'); 
}

// 

  //var selectedTrade = $('#selected_trade').val();
  //var projectId = "{{$editCandidateCvs->ew_project_id}}";


  //if(selectedTrade > 0){
     // $.get('{{ route('recruit.tradeDetails') }}', {
     /// 'tradeId': selectedTrade,
     // 'projectId': projectId,
     // '_token': $('input[name=_token]').val()
    //},
    //function (response) {
      //  console.log(response);
       // $('#salary').val(response.details.trade_salary);
    //})

 // }

  //$('#selected_trade').on('change', function () {
   // $.get('{{ route('recruit.tradeDetails') }}', {
      //  'tradeId': this.value,
      //  'projectId': projectId,
      //  '_token': $('input[name=_token]').val()
      //},
      //function (response) {
       //   console.log(response);
       //   $('#salary').val(response.details.trade_salary);
     // })
  //});

//

$('.modal-footer').append('<a class="btn btn-primary btn-md pull-left" target="_blank" href="{{ url('/recruitment#mobilization/mobilization-room/'.$editCandidateCvs->ew_project_id.'/'.$editCandidateCvs->project_country_id) }}">Go To Selection List</a>');   
</script>
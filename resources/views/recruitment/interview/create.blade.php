<style>
    .badge {
      padding: 2px 4px !important;
  }
  .list-group-item{
      padding: 3px 3px;
      margin-top:3px; 
      /* border-bottom:0.1px solid #ABABA9 !important;*/
      border-top:0px !important;
      border-left: 0px !important;
      border-right: 0px !important;
      border-radius: 0px !important;
      /* border:0px !important; */
  }
.from_overs_exp{
  padding: 0px 8px !important;
}
.to_overs_exp{
  padding: 0px 8px !important;
}
  /* button.btn-success{
    display:none;
  } */
  </style>
  <?php $panelTitle = "Candidate CV Create"; ?>

  <form type="create" id="interviewForm" panelTitle="{{$panelTitle}}" class="form-load form-horizontal" data-fv-excluded="" callback="refresh_interview_cv">
    {{csrf_field()}}
    <?php $currentDate = date('d-m-Y'); ?>
    <input type="hidden" name="ew_project_id" id="ew_project_id" value="{{$project_id}}">
    <div class="panel panel-default chart mt20">
      <div class="panel-body">
        <div class="col-lg-4 col-md-4">
          <input type="hidden" name="nothing" class="form-control">
          <input type="hidden" name="project_country_id" id="project_country_id" value="{{ Helper::projects($project_id)->project_country_id }}">
          <div class="form-group">
            <label class="col-lg-3 col-md-3 control-label">Interview Date</label>
            <div class="col-lg-9 col-md-9">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <input type="text" id="interview_date" name="interview_date" class="form-control dateTimeFormat"
                  placeholder="e.g. 15-12-2019" autocomplete="off" value="{{$currentDate}}">
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 col-md-3 control-label required">Full Name</label>
            <div class="col-lg-9 col-md-9">
              <input autofocus required name="full_name" id="full_name" placeholder="Full Name" class="form-control">
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 col-md-3 control-label">Father Name</label>
            <div class="col-lg-9 col-md-9">
              <input name="father_name" id="father_name" placeholder="Father's Name" class="form-control">
            </div>
          </div>
          {{-- <div class="form-group">
            <label class="col-lg-3 col-md-3 control-label ">National ID</label>
            <div class="col-lg-9 col-md-9">
              <input autofocus name="national_id" id="national_id" placeholder="e.g. 2893887481" class="form-control">
            </div>
          </div> --}}
          <div class="form-group">
            <label class="col-lg-3 col-md-3 control-label required">PP. No.</label>
            <div class="col-lg-4 col-md-4">
              <input class="form-control" id="passport_no" name="passport_no" placeholder="e.g. 4355645" required>
            </div>
            <label class="col-lg-2 col-md-2 control-label">Status</label>
            <div class="col-lg-3 col-md-3">
              <select name="passport_status" id="passport_status" class="form-control select2">
                <option value="2">Yes</option>
                <option value="3">No</option>
                <option value="1">In Office</option>
              </select>
            </div>
            <div id="candidateDetails">
             
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 col-md-3 control-label ">PP Exp Date</label>
            <div class="col-lg-9 col-md-9">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <input type="text" id="passport_expired_date" name="passport_expired_date" class="form-control dateTimeFormat keypressOff"
                  placeholder="e.g. 15-12-2019" autocomplete="off">
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 col-md-3 control-label ">Date Of Birth</label>
            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <input autocomplete="off" id="date_of_birth" name="date_of_birth" class="form-control dateTimeFormat keypressOff"
                  placeholder="e.g. 15-12-2019">
              </div>
            </div>
            <label class="col-lg-1 col-md-1 control-label ">Age</label>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
              <input  class="form-control keypressoff-only-age" id="age" name="age"
                placeholder="e.g. 34" readonly>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 col-md-3 control-label required">Contact No.</label>
            <div class="col-lg-9 col-md-9">
              <input autofocus required name="contact_no" id="contact_no" placeholder="e.g. 01749xxxxx" class="form-control">
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 col-md-3 control-label required">Reference</label>
            <div class="col-lg-9 col-md-9">
              <select name="reference_id" id="reference_id" required class="form-control select2" placeholder="Select Reference">
                <option></option>
                @foreach($references as $reference)
                <option value="{{ $reference->id }}">{{ $reference->reference_name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 col-md-3 control-label ">Dealer:</label>
            <div style="margin-top: -17px;" class="col-lg-7 col-md-7 col-md-offset-3" id="dealer">
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 col-md-3 control-label">Agency</label>
            <div class="col-lg-9 col-md-9">
              <select name="agency_id" id="agency_id" class="form-control select2" placeholder="Select Agency">
                <option></option>
                @foreach($agency as $agency)
                <option value="{{ $agency->id }}">{{ $agency->agency_name }}</option>
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
        <!--col-6 END-->
        <div class="col-lg-8 col-md-8 col-sm-6">
          <div class="form-group">
            <label class="col-lg-2 col-md-2 control-label">Trade Applied</label>
            <div class="col-lg-4 col-md-4">
              <select class="form-control select2" id="trade_applied" name="trade_applied" placeholder="Select Trade">
                <option></option>
                @foreach($projectTrades as $projectTrade)
                <option value="{{  $projectTrade->trade_id }}">{{ $projectTrade->trade_name}}</option>
                @endforeach
              </select>
            </div>
            <label class="col-lg-2 col-md-2 control-label required">Trade Selection</label>
            <div class="col-lg-4 col-md-4">
              <select class="form-control select2" required id="selected_trade"  name="selected_trade" placeholder="Select Trade">
                <option></option>
                @foreach($projectTrades as $pTrade)
                <option value="{{  $pTrade->trade_id }}">{{ $pTrade->trade_name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 col-md-2 control-label ">Grade</label>
            <div class="col-lg-4 col-md-4">
              <select name="grade" id="grade" class="select2 form-control" placeholder="Select Grade">
                <option value="0">--Select Grade--</option>
                <option value="A+">A+</option>
                <option value="A">A</option>
                <option value="B+">B+</option>
                <option value="B">B</option>
                <option value="C+">C+</option>
                <option value="C">C</option>
                <option value="Excellent">Excellent</option>
                <option value="Very Good">Very Good</option>
                <option value="Good">Good</option>
                <option value="Average">Average</option>
                <option value="Poor">Poor</option>
              </select>
            </div>
            <label class="col-lg-2 col-md-2 control-label">Score</label>
            <div class="col-lg-4 col-md-4">
              <input type="text" name="score" id="score" class="form-control" placeholder="e.g. 75">
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 col-md-2 control-label">Salary</label>
            <div class="col-lg-4 col-md-4">
              <input autofocus name="salary" id="salary_and_others" placeholder="e.g. 1200" class="form-control">
            </div>
            {{-- <label class="col-lg-1 col-md-1 control-label">Food</label> --}}
            <div class="col-lg-2 col-md-2">
              <select name="food" id="food" class="form-control select2" placeholder="Food">
                <option></option>
                <option value="1">Company</option>
                <option value="2">Self</option>
              </select>
            </div>
            {{-- <label class="col-lg-1 col-md-1 control-label">OT</label> --}}
            <div class="col-lg-2 col-md-2">
              <select name="ot" id="ot" class="form-control select2" placeholder="OT">
                <option></option>
                <option value="1">Yes</option>
                <option value="0">No</option>
              </select>
            </div>
            {{-- <label class="col-lg-3 col-md-3 control-label">Salary A/D</label> --}}
            <div class="col-lg-2 col-md-2">
              <select name="salary_ad" id="salary_ad" class="form-control select2" placeholder="A\D">
                <option></option>
                <option value="1">Accpted</option>
                <option value="2">Not Accpted</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 col-md-2 control-label">Result</label>
            <div class="col-lg-4 col-md-4">
              <select name="result" id="result" class="form-control select2" placeholder="Select Result">
                <option selected value="1">Pass</option>
                <option value="2">Fail</option>
                <option value="3">Waiting</option>
                <option value="4">Hold</option>
                <option value="5">Decline</option>
              </select>
            </div>
            <label class="col-lg-2 col-md-2 control-label">Education</label>
            <div class="col-lg-4 col-md-4">
              <input autofocus name="education" id="education" placeholder="e.g. S.S.C" class="form-control">
            </div>
          </div>
          <div class="form-group" id="homeExpDiv">
              <label class="col-lg-2 col-md-2 control-label ">Home Exp.</label>
              <div class="col-lg-10 col-md-10" id="answer_view_2_3">
                <div class="row">
                  <div class="col-md-4 pr0 mb10">
                    <input type="text" name="home_experience_details[]" id="home_experience_details0"  class="form-control home_experience_details" placeholder="Company Name">
                  </div>
                  <div class="col-md-2 pr0 mb10">
                    <input type="text" name="from_home_exp[]" placeholder="yyyy" maxlength="4" serialId="0" id="from_home_exp0" class="form-control from_home_exp">
                  </div>
                  <div class="col-md-2 pr0 mb10">
                    <input type="text" name="to_home_exp[]" placeholder="yyyy" maxlength="4" serialId="0" id="to_home_exp0" class="form-control to_home_exp">
                  </div>
                  <div class="col-md-2 pr0 mb10">
                    <input name="total_home_exp[]" placeholder="y" id="total_home_exp0" class="form-control to_home_exp" readonly>
                  </div>
                  <div class="col-md-2 btnView pr0">
                    <button id="answer_add" class="btn btn-success ml5" type="button"><i class="glyphicon glyphicon-plus"></i></button></div>
                </div>
              </div>
            </div>
            <div class="form-group" id="overExpDiv">
              <label class="col-lg-2 col-md-2 control-label ">Overs Exp.</label>
              <div class="col-lg-10 col-md-10" id="answer_view_2_3_1">
                <div class="row">
                  <div class="col-md-4 pr0 mb10">
                    <input name="oversease_experience_details[]"  class="form-control" placeholder="Company Name">
                  </div>
                  <div class="col-md-3 col-lg-3 pr0 mb10">
                   <select name="oversease_country" id="oversease_country" class="form-control">
                     <option value="0">Select Country</option>
                      @foreach($expCountries as $expCountry)
                      <option value="{{ $expCountry->id }}">{{ $expCountry->name }}</option>
                      @endforeach
                   </select>
                  </div>
                  <div class="col-md-1 pr0 mb10">
                    <input type="text" name="from_overs_exp[]" placeholder="yyyy" serialId="0" id="from_overs_exp0" class="form-control from_overs_exp" style=" direction: rtl;">
                  </div>
                  <div class="col-md-1 pr0 mb10">
                    <input type="text" name="to_overs_exp[]" placeholder="yyyy" id="to_overs_exp0" serialId="0" class="form-control to_overs_exp" style=" direction: rtl;">
                  </div>
                  <div class="col-md-1 pr0 mb10">
                    <input name="total_overs_exp[]" placeholder="y" id="total_overs_exp0" readonly class="form-control total_overs_exp">
                  </div>
                  <div class="col-md-1 btnView pr0">
                    <button id="answer_add2" class="btn btn-success ml5" type="button"><i class="glyphicon glyphicon-plus"></i></button></div>
                </div>
              </div>
            </div>
          <div class="form-group">
            <label class="col-lg-2 col-md-2 control-label">Process</label>
            <div class="col-lg-4 col-md-4">
              <select name="process" id="process" class="form-control select2" placeholder="Process">
                <option></option>
                <option value="1">SMAW</option>
                <option value="2">GTAW + SMAW</option>
              </select>
            </div>
            <div class="col-lg-2 col-md-2">
              <input name="wqrt_no" placeholder="WQRT No." class="form-control">
            </div>
            {{-- <label class="col-lg-2 col-md-2 control-label">WQRT Test Rpt.</label> --}}
            <div class="col-lg-2 col-md-2">
              <select name="wqrt_test_report" id="wqrt_test_report" class="form-control select2" placeholder="WQRT Report">
                <option></option>
                <option value="1">Accepted</option>
                <option value="2">Denied</option>
              </select>
            </div>
            <div class="col-lg-2 col-md-2">
              <select name="rt_test_result" id="rt_test_result" class="form-control select2" placeholder="R.T Test">
                <option></option>
                <option value="1">Accept</option>
                <option value="2">Reject</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 col-md-2 control-label">Selection Date</label>
            <div class="col-lg-10 col-md-10">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <input id="selection_date" name="selection_date" class="form-control dateTimeFormat keypressOff"
                  placeholder="e.g. 15-12-2019" autocomplete="off" value="{{$currentDate}}">
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 col-md-2 control-label">Remarks</label>
            <div class="col-lg-10 col-md-10">
              <input autofocus name="remarks" id="remarks" placeholder="e.g. This is candidate fit for visa processing"
                class="form-control">
            </div>
        </div>
      </div>
    </div>
  </form>
  <script type="text/javascript" src="{{ asset('public/js/moment.min.js') }}"></script>
  <script type="text/javascript">

  $('#passport_no').on('keyup', function() {
    var passport_no = $(this).val();
    console.log(passport_no);
     $.ajax({
      url:'{{ route('recruit.getPassportFormData') }}',
      type:'GET',
      data:{passport_no:passport_no},
      processData:true,
      contentType:false,
      success:function(res){
        console.log(res);
        if (jQuery.isEmptyObject(res)){
        
        } else {
        $('#full_name').val(res.full_name);
        $('#father_name').val(res.father_name);
        $('#date_of_birth').val(moment(res.date_of_birth).format('DD-MM-YYYY'));
        $('#passport_expired_date').val(moment(res.passport_expired_date).format('DD-MM-YYYY'));
        $('#contact_no').val(res.emergency_contact);
        $('#age').val(res.age);
        }
      }
    }) 
  });

// $('.modal-footer button.btn-success').on('click', function(){
//   var passport_no = $('#passport_no').val();
//   console.log(passport_no);
//   $.ajax({
//     url:'{{ route('recruit.passportChecker') }}',
//     type:'GET',
//     data:{passport_no:passport_no},
//     processData:true,
//     contentType:false,
//     success:function(response){
//       console.log(response);
//       $('#errorMessage').text('This is '+response.candidateName.full_name+"'s passport. Assigned Project : "+response.projectName+". Please, Try another.");
//     }

//   });
// });


var i = 99999999;

$(document).ready(function () {
    //Start Home Exprience
    $("#homeExpDiv").on("click", "#answer_add", function () {
      i++;
      var answer_type = $("#answer_type").val();
      $("#answer_view_2_3").find("#answer_add").remove();
      var trueAnswer = $("#answer_view_2_3").find(".trueAnswer:last").val();
      $("#answer_view_2_3").append(
        '<div class="row "><div class="col-md-4 pr0 mb10"><input name="home_experience_details[]" class="form-control" placeholder="Company Name"></div><div class="col-md-2 pr0 mb10"><input type="text" name="from_home_exp[]" serialId="'+i+'" id="from_home_exp'+i+'" class="form-control from_home_exp" placeholder="yyyy"></div><div class="col-md-2 pr0 mb10"><input type="text" name="to_home_exp[]" id="to_home_exp'+i+'" serialId="'+i+'" class="form-control to_home_exp" placeholder="yyyy"></div><div class="col-md-2 pr0 mb10"><input name="total_home_exp[]" placeholder="y" readonly id="total_home_exp'+i+'" class="form-control total_home_exp"></div><div class="col-md-2 btnView pr0"><button id="answer_remove" class="btn btn-danger" type="button"><i class="glyphicon glyphicon-remove"></i></button><button id="answer_add" class="btn btn-success ml5" type="button"><i class="glyphicon glyphicon-plus"></i></button></div></div>'
      );

      if ($("#answer_view_2_3").find(".btnView:first").find("#answer_remove").length <= 0) {
        $("#answer_view_2_3").find(".btnView:first").append(
          '<button id="answer_remove" class="btn btn-danger" type="button"><i class="glyphicon glyphicon-remove"></i></button>'
        );
      }
    });


    $("#homeExpDiv").on("click", "#answer_remove", function () {
      $(this).parents(".row").first().remove();
      if ($("#answer_view_2_3").find("#answer_add").length <= 0) {
        $("#answer_view_2_3").find(".btnView:last").append(
          '<button id="answer_add" class="btn btn-success ml5" type="button"><i class="glyphicon glyphicon-plus"></i></button>'
        );
      }
      if ($("#answer_view_2_3").find(".btnView").length == 1) {
        $("#answer_view_2_3").find(".btnView").find("#answer_remove").remove();
      }
      $("#answer_view_2_3").find(".trueAnswer").each(function (index) {
        $(this).val(index);
      });
    });

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
      
      $('#total_home_exp'+Number(serialId)).val(totalExp+1);
      
    }
      i = 99999999;
    //Start Oversease Exprience
    $("#overExpDiv").on("click", "#answer_add2", function () {
      i++;
      $("#answer_view_2_3_1").find("#answer_add2").remove();
      var trueAnswer = $("#answer_view_2_3_1").find(".trueAnswer:last").val();
      $("#answer_view_2_3_1").append(
        '<div class="row"><div class="col-md-4 pr0 mb10"><input name="oversease_experience_details[]" class="form-control" placeholder="Company Name"></div><div class="col-md-3 col-lg-3 pr0 mb10"><select name="oversease_country" id="oversease_country" class="form-control"><option value="0">Select Country</option>@foreach($expCountries as $expCountry)<option value="{{ $expCountry->id }}">{{ $expCountry->name }}</option>@endforeach</select></div><div class="col-md-1 pr0 mb10"><input type="text" name="from_overs_exp[]" id="from_overs_exp'+i+'" serialId="'+i+'" class="form-control  from_overs_exp" style=" direction: rtl;" placeholder="yyyy"></div><div class="col-md-1 pr0 mb10"><input type="text" name="to_overs_exp[]" id="to_overs_exp'+i+'" serialId="'+i+'" class="form-control  to_overs_exp" style=" direction: rtl;" placeholder="yyyy"></div><div class="col-md-1 pr0 mb10"><input name="total_overs_exp[]" placeholder="y" readonly id="total_overs_exp'+i+'" class="form-control total_overs_exp"></div><div class="col-md-2 btnView pr0"><button id="answer_remove2" class="btn btn-danger" type="button"><i class="glyphicon glyphicon-remove"></i></button><button id="answer_add2" class="btn btn-success ml5" type="button"><i class="glyphicon glyphicon-plus"></i></button></div></div>'
      );

      if ($("#answer_view_2_3_1").find(".btnView:first").find("#answer_remove2").length <= 0) {
        $("#answer_view_2_3_1").find(".btnView:first").append(
          '<button id="answer_remove2" class="btn btn-danger" type="button"><i class="glyphicon glyphicon-remove"></i></button>'
        );
      }
    });

    $("#overExpDiv").on("click", "#answer_remove2", function () {
      $(this).parents(".row").first().remove();
      if ($("#answer_view_2_3_1").find("#answer_add2").length <= 0) {
        $("#answer_view_2_3_1").find(".btnView:last").append(
          '<button id="answer_add2" class="btn btn-success ml5" type="button"><i class="glyphicon glyphicon-plus"></i></button>'
        );
      }
      if ($("#answer_view_2_3_1").find(".btnView").length == 1) {
        $("#answer_view_2_3_1").find(".btnView").find("#answer_remove2").remove();
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
      
    } 
});

    /*---------------------------------
     DATE PICKER FOR ALL CALENDAR
     -----------------------------------*/
    $('.keypressOff').keypress(function (e) {
      return true
    });
  
    $('.keypressoff-only-age').keypress(function (e) {
      return false
    });
  
    $('.dateTimeFormat').datepicker({
      format: "dd-mm-yyyy"
    });
  
    $('#date_of_birth').on('change', function () {
      var dateOfBirth = moment($(this).val(), 'DD-MM-YYYY').format('DD-MMM-YYYY');
      var currentDate = moment();
      var age = currentDate.diff(dateOfBirth, 'year');
      $('#age').val(age);
      console.log(dateOfBirth + " " + currentDate);
    });
  
    $("select.select2").select2({
      placeholder: "Select"
    });
  
    // $('#full_name').keypress(function(){
    //   $(this).css('border', '1px solid green');
    // });
    // $('#father_name').keypress(function(){
    //   $(this).css('border', '1px solid green');
    // });
  
    $('input').keyup(function (e) {
      console.log('keyup called');
      var code = e.keyCode || e.which;
      if (code == '9') {
        $(this).css('border', '2px solid #87C540');
      }
    });
    $('.select2-container').keyup(function (e) {
      console.log('keyup called');
      var code = e.keyCode || e.which;
      if (code == '9') {
        $(this).css('border', '2px solid #87C540');
      }
    });
    $('input').focusout(function () {
      $(this).css('border', '1px solid #c4c4c4');
    });
    $('.select2-container').focusout(function () {
      $(this).css('border', '1px solid #c4c4c4');
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

$('.modal-title').text("Create Interview CV");
$('.modal-footer button.btn-default').text('Back To List');

//Footer Back to list button
$('.modal-footer button.btn-default').on('click', function(){
  setTimeout(function() {
    $('.panel-refresh').trigger('click');
  }, 40);
});

//Header close button
$('.modal-header button.bootbox-close-button').on('click', function(){
  setTimeout(function() {
    $('.panel-refresh').trigger('click');
  }, 40);
});

$('.modal-footer').append('<a class="btn btn-primary btn-md pull-left" target="_blank" href="{{ url('/recruitment#mobilization/mobilization-room/'.$project_id.'/'.$projectCountryId) }}">Go To Selection List</a>');  


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

$('#agency_id').on('change', function () {
  var projectId = "{{$project_id}}";
  $.get('{{ route('recruit.agency_details') }}', {
      'agencyId': this.value,
      'projectId': projectId,
      '_token': $('input[name=_token]').val()
    },
    function (response) {
        $('#agency_licence').html(response.licence);
        $('#agency_quantity').html(response.qty);
        if(response.qty == 0)
        {
          $('#agency_stock_feedback').html('out of stock');
          $(".modal-footer .btn-success").prop('disabled', true);
        }
        else
        {
          $('#agency_stock_feedback').html('');
          $(".modal-footer .btn-success").prop('disabled', false);
        }
    })
});

/* SHOWING PASSPORT DETAILS */
$('#passport_no').on('keyup', function(){
    var projectId = "{{$project_id}}";
    // console.log($(this).val());
    var passport = $(this).val();
    if (passport.length >= 9) {
      $.ajax({
      type:'GET',
      url:"{{route('recruit.getPassportDetails')}}",
      data:{projectId : projectId, passport : passport},
      processData:true,
      contentType:false,
      success:function(response){
        console.log(response.message);
        if (jQuery.isEmptyObject(response.candidatesName)) {
          $('.btn-success').prop('disabled', false);
          $('#candidateDetails').html(" ");
        } else {
          var html = "<p class='text-danger' style='margin-left:127px;'>"+response.message+"</p><ol class='text-danger' style='margin-left:100px;'><li>Project Name: "+response.projectName+"</li><li>Candidate Name: "+response.candidatesName+"</li><li>Mobilize Stage: "+response.mobilizeName+"</li></ol>";
        $('#candidateDetails').html(html);
         $('.btn-success').prop('disabled', true);
        }
        
      }
    });
  }

});

var selectedFood;
var selectedOt;
    
$('#interviewForm').submit(function(e) {
    e.preventDefault();
    var foodVal = $('#food option:selected').val();
    var otVal = $('#ot option:selected').val();
    selectedFood = foodVal;
    selectedOt = otVal;
});

function refresh_interview_cv(){
  console.log("it's callback function");
  $('#food').val(selectedFood).trigger('change');
  $('#ot').val(selectedOt).trigger('change');
}

// $('#passport_no').on('keyup', function(){
//   var passport_no = $('#passport_no').val();
//   // console.log(passport_no);
//   $.ajax({
//     url:'{{ route('recruit.passportChecker') }}',
//     type:'GET',
//     data:{passport_no:passport_no},
//     processData:true,
//     contentType:false,
//     success:function(response){
//       console.log(response);
//       if(jQuery.isEmptyObject(response.candidateName)){
//         $('#save-cv-form').prop('disabled', false);
//         $('#candidateDetails').text("");
//       }else{
//         $('#save-cv-form').prop('disabled', true);
//         $('#candidateDetails').text('This is '+response.candidateName.full_name+"'s passport. Assigned Project : "+response.projectName+". Please, Try another.");
//       }
//     }
//   });
// });
  </script>
  
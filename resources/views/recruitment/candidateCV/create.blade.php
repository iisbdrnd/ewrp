<button type="button" class="back-btn btn btn-default pull-right">Back to List</button>
<?php $panelTitle = "Create New CV"; ?>
<form type="create" panelTitle="{{$panelTitle}}" class="form-load form-horizontal panelRefresh pull-left" data-fv-excluded="" callback="refreshArea">
  {{csrf_field()}}
  <?php $currentDate = date('d-m-Y'); ?>
  <div class="panel panel-default chart mt20">
    <div class="panel-body">
      <div class="form-group">
        <label class="col-lg-3 col-md-3 control-label">Assume CV No.</label>
        <div class="col-lg-7 col-md-7">
          <input autofocus value="{{$genCvNumber}}" class="form-control" readonly>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 col-md-3 control-label required">Full Name</label>
        <div class="col-lg-7 col-md-7">
          <input required autofocus name="full_name" id="full_name" placeholder="Full Name" class="form-control">
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 col-md-3 control-label ">Father's Name</label>
        <div class="col-lg-7 col-md-7">
          <input name="father_name" id="father_name" placeholder="Father's Name" class="form-control">
        </div>
      </div>
      <div class="form-group">
        <div class="col-lg-12">
          <!-- Start .row --> 
          <div class="row">
            <label class="col-lg-3 col-md-3 control-label required ">Passport No. </label>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
              <input class="form-control" id="passport_no" required name="passport_no" placeholder="Passport No.">
              <strong id="errorMessage" class="text-danger" style="border-bottom: 1px solid red;"></strong>
            </div>
            <label class="col-lg-1 col-md-1 control-label">PP Expired</label>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">

              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <input id="passport_expired_date" name="passport_expired_date"
                  class="form-control dateTimeFormat" placeholder="Passport Expired Date" autocomplete="off"
                  value="{{$currentDate}}">
              </div>
            </div>
          </div>
          <!-- End .row -->
        </div>
      </div>
      <div class="form-group">
        <div class="col-lg-12">
          <!-- Start .row -->
          <div class="row">
            <label class="col-lg-3 col-md-3 control-label ">Date Of Birth(DOB) </label>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <input autocomplete="off" id="date_of_birth" name="date_of_birth"
                  class="form-control dateTimeFormat" placeholder="Date of birth" value="{{$currentDate}}">
              </div>
            </div>
            <label class="col-lg-1 col-md-1 control-label ">Age </label>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
              <input class="form-control" id="age" name="age" placeholder="Age">
            </div>
          </div>
          <!-- End .row -->
        </div>
      </div>

      {{-- address --}}
      <div class="form-group">
        <div class="col-md-12">
          <div class="row">
            <label class="col-lg-3 col-md-3 control-label "><b>Permanent Address: </b></label>
          </div>
        </div>

        <div class="col-lg-12">
          <!-- Start .row -->
          <div class="row">
            <label class="col-lg-3 col-md-3 control-label ">Village </label>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <input class="form-control" id="village" name="village" placeholder="Village">
            </div>
            <label class="col-lg-1 col-md-1 control-label ">P.O </label>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
              <input class="form-control" id="po" name="po" placeholder="P.O">
            </div>
          </div>
          <!-- End .row -->
          <!-- Start .row -->
          <div class="row mt10">
            <label class="col-lg-3 col-md-3 control-label ">Upazila </label>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
              <input class="form-control" id="upazila" name="upazila" placeholder="Upazila">
            </div>
            <label class="col-lg-1 col-md-1 control-label ">District </label>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
              <input class="form-control" id="district" name="district" placeholder="District">
            </div>
          </div>
          <!-- End .row -->
        </div>
      </div>
      {{-- end address --}}

      <div class="form-group">
        <div class="col-lg-12">
          <!-- Start .row -->
          <div class="row">

            <label class="col-lg-3 col-md-3 control-label required">Contact No.</label>
            <div class="col-lg-3 col-md-3">
              <input autofocus required name="contact_no" id="contact_no" placeholder="Contact No." class="form-control">
            </div>
            
            <label class="col-lg-1 col-md-1 control-label">National ID</label>
            <div class="col-lg-3 col-md-3">
              <input autofocus name="national_id" id="national_id" placeholder="National ID" class="form-control">
            </div>

          </div>
        </div>
      </div>

      <div class="form-group">
        <div class="col-lg-12">
          <!-- Start .row -->
          <div class="row">

              <label class="col-lg-3 col-md-3 control-label required">Reference</label>
              <div class="col-lg-3 col-md-3">
                <select name="reference_id" id="reference_id" class="form-control select2" required placeholder="Select Reference">
                  <option></option>
                  @foreach($references as $reference)
                  <option value="{{ $reference->id }}">{{ $reference->reference_name }}</option>
                  @endforeach
                </select>
              </div>

              <label class="col-lg-1 col-md-1 control-label ">Dealer:</label>
              <div
                style="margin-top: 0px; padding: 5px 15px !important; overflow: hidden; margin-left: 10px; padding-right: 0px; width: 23.5%;"
                class="col-lg-3 col-md-3 list-group-item" id="dealer">
                No dealer found!
              </div>

          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 col-md-3 control-label">Trade</label>
        <div class="col-lg-7 col-md-7">
          <select name="trade[]" id="trade" class="form-control select2" multiple>
            <option value="0">Select trade</option>
            @foreach($trades as $trade)
            <option value="{{ $trade->id }}">{{ $trade->trade_name }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 col-md-3 control-label">Education</label>
        <div class="col-lg-7 col-md-7">
          <select name="education" id="education" class="form-control select2">
            <option value="0">Select Education</option>
            <option value="PSC">PSC</option>
            <option value="JSC">JSC</option>
            <option value="CLASS 10">CLASS 10</option>
            <option value="SSC">SSC</option>
            <option value="HSC">HSC</option>
            <option value="DIPLOMA">DIPLOMA</option>
            <option value="DEGREE">DEGREE</option>
            <option value="HONOURS">HONOURS</option>
            <option value="MASTERS">MASTERS</option>
          </select>
        </div>
      </div>
      <div class="form-group" id="homeExpDiv">
        <label class="col-lg-3 col-md-3 control-label">Home Exp.</label>
        <div class="col-lg-9 col-md-9" id="answer_view_2_3">
          <div class="row">
            <div class="col-md-3 pr0 mb10">
              <input name="home_experience_details[]" placeholder="Company Name" class="form-control">
            </div>
            <div class="col-md-2 pr0 mb10">
              <input type="text" name="from_home_exp[]" serialId="0" id="from_home_exp0"
              class="form-control from_home_exp" placeholder="yyyy">
            </div>
            <div class="col-md-2 pr0 mb10">
             <input type="text" name="to_home_exp[]" serialId="0" id="to_home_exp0" class="form-control to_home_exp" placeholder="yyyy">
            </div>
            <div class="col-md-1 pr0 mb10">
              <input name="total_home_exp[]" readonly placeholder="y" id="total_home_exp0" class="form-control to_home_exp">
            </div>
            <div class="col-md-2 btnView">
              <button id="answer_add" class="btn btn-success ml5" type="button"><i
                  class="glyphicon glyphicon-plus"></i></button></div>
          </div>
        </div>
      </div>
      <div class="form-group" id="overExpDiv">
        <label class="col-lg-3 col-md-3 control-label">Oversease Exp.</label>
        <div class="col-lg-9 col-md-9" id="answer_view_2_3_1">
          <div class="row">
            <div class="col-md-3 pr0 mb10">
              <input name="oversease_experience_details[]" placeholder="Company Name" class="form-control">
            </div>
            <div class="col-md-2 col-lg-2 pr0 mb10">
              <select name="oversease_country[]" id="oversease_country" class="form-control">
                <option value="0">Select Country</option>
                @foreach($expCountries as $expCountry)
                <option value="{{ $expCountry->id }}">{{ $expCountry->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-1 pr0 mb10">
              <input type="text" name="from_overs_exp[]" serialId="0" id="from_overs_exp0"
              class="form-control from_overs_exp" placeholder="yyyy">
            </div>
            <div class="col-md-1 pr0 mb10">
              <input type="text" name="to_overs_exp[]" id="to_overs_exp0" serialId="0" class="form-control  to_overs_exp" placeholder="yyyy">
            </div>
            <div class="col-md-1 pr0 mb10">
              <input name="total_overs_exp[]" readonly placeholder="y" id="total_overs_exp0"
                class="form-control total_overs_exp">
            </div>
            <div class="col-md-2 btnView">
              <button id="answer_add2" class="btn btn-success ml5" type="button"><i
                  class="glyphicon glyphicon-plus"></i></button></div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 col-md-3 control-label">Process</label>
        <div class="col-lg-7 col-md-7">
          <select name="process" id="process" class="form-control select2">
            <option value="0">--Process--</option>
            <option value="1">SMAW</option>
            <option value="2">GTAW + SMAW</option>
          </select>
        </div>
      </div>
    </div>
    <div class="panel-footer">
      <div class="form-group">
        <div class="col-md-3 lg-3"></div>
        <div class="col-md-7 lg-7">
          <button type="submit" id="save-cv-form" class="btn btn-md btn-success">Save</button>
        </div>
      </div>
    </div>
  </div>
</form>
<br>
<script type="text/javascript" src="{{ asset('public/js/moment.min.js') }}"></script>
<script type="text/javascript">
  $('#passport_no').on('keyup', function() {
    //var passport_no = $(this).val();
    //console.log(passport_no);
     //$.ajax({
      //url:'{{ route('recruit.getPassportFormData') }}',
      //type:'GET',
      //data:{passport_no:passport_no},
      //processData:true,
      //contentType:false,
      //success:function(res){
        //$('#full_name').val(res.full_name);
       // $('#father_name').val(res.father_name);
        //$('#date_of_birth').val(moment(res.date_of_birth).format('DD-MM-YYYY'));
      //  $('#passport_expired_date').val(moment(res.passport_expired_date).format('DD-MM-YYYY'));
       // $('#contact_no').val(res.emergency_contact);
       // $('#age').val(res.age);
     // }
    //}) 
  });
  
// $('#save-cv-form').on('click', function(){
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

  $('#passport_no').on('keyup', function(){
  var passport_no = $('#passport_no').val();
  // console.log(passport_no);
if (passport_no.length >= 9) {
    $.ajax({
      url:'{{ route('recruit.passportChecker') }}',
      type:'GET',
      data:{passport_no:passport_no},
      processData:true,
      contentType:false,
      success:function(response){
        console.log(response);
        if(jQuery.isEmptyObject(response.candidateName)){
          $('#save-cv-form').prop('disabled', false);
          $('#errorMessage').text("");
        }else{
          $('#save-cv-form').prop('disabled', true);
          $('#errorMessage').text('This is '+response.candidateName.full_name+"'s passport. Assigned Project : "+response.projectName+". Please, Try another.");
        }
      }
    });
  }
});

$('#reference_id').on('change', function (e) {
  e.preventDefault();
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

  // $('#save-cv-form').on('click', function(){
  //     $('.panel-refresh').trigger('click');
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
        '<div class="row "><div class="col-md-3 pr0 mb10"><input name="home_experience_details[]" placeholder="Company Name" class="form-control"></div><div class="col-md-2 pr0 mb10"><input type="text" name="from_home_exp[]" serialId="' +
        i + '" id="from_home_exp' + i +
        '" class="form-control   from_home_exp" placeholder="yyyy"></div><div class="col-md-2 pr0 mb10"><input type="text" name="to_home_exp[]" id="to_home_exp' +
        i + '" serialId="' + i +
        '" class="form-control to_home_exp " placeholder="yyyy"></div><div class="col-md-1 pr0 mb10"><input name="total_home_exp[]" readonly placeholder="y" id="total_home_exp' +
        i +
        '" class="form-control total_home_exp"></div><div class="col-md-3 btnView"><button id="answer_remove" class="btn btn-danger ml5" type="button"><i class="glyphicon glyphicon-remove"></i></button><button id="answer_add" class="btn btn-success ml5" type="button"><i class="glyphicon glyphicon-plus"></i></button></div></div>'
      );

      if ($("#answer_view_2_3").find(".btnView:first").find("#answer_remove").length <= 0) {
        $("#answer_view_2_3").find(".btnView:first").append(
          '<button id="answer_remove" class="btn btn-danger ml5" type="button"><i class="glyphicon glyphicon-remove"></i></button>'
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

    $("#homeExpDiv").on('change', '.from_home_exp', function () {
      var serialId = $('#' + this.id).attr('serialId');
      totalHomeExperience(serialId);
    });
    $("#homeExpDiv").on('change', '.to_home_exp', function () {
      var serialId = $('#' + this.id).attr('serialId');
      totalHomeExperience(serialId);
    });

    function totalHomeExperience(serialId) {
      var from_home_exp = $('#from_home_exp' + Number(serialId)).val();
      var to_home_exp = $('#to_home_exp' + Number(serialId)).val();
      var totalExp = Number(to_home_exp) - Number(from_home_exp);

      $('#total_home_exp' + Number(serialId)).val(totalExp + 1);

    }
    i = 99999999;
    //Start Oversease Exprience
    $("#overExpDiv").on("click", "#answer_add2", function () {
      i++;
      $("#answer_view_2_3_1").find("#answer_add2").remove();
      var trueAnswer = $("#answer_view_2_3_1").find(".trueAnswer:last").val();
      $("#answer_view_2_3_1").append(
        '<div class="row"><div class="col-md-3 pr0 mb10"><input name="oversease_experience_details[]" placeholder="Country Name" class="form-control"></div><div class="col-md-2 col-lg-2 pr0 mb10"><select name="oversease_country[]" id="oversease_country" class="form-control"> @foreach($expCountries as $expCountry)<option value="{{ $expCountry->id }}">{{ $expCountry->name }}</option>@endforeach</select></div><div class="col-md-1 pr0 mb10"><input type="text" name="from_overs_exp[]" id="from_overs_exp' +
        i + '" serialId="' + i +
        '" class="form-control from_overs_exp" placeholder="yyyy"></div><div class="col-md-1 pr0 mb10"><input type="text" name="to_overs_exp[]" id="to_overs_exp' +
        i + '" serialId="' + i +
        '" class="form-control to_overs_exp" placeholder="yyyy"></div><div class="col-md-1 pr0 mb10"><input name="total_overs_exp[]" readonly placeholder="y" id="total_overs_exp' +
        i +
        '" class="form-control total_overs_exp"></div><div class="col-md-2 btnView"><button id="answer_remove2" class="btn btn-danger ml5" type="button"><i class="glyphicon glyphicon-remove"></i></button><button id="answer_add2" class="btn btn-success ml5" type="button"><i class="glyphicon glyphicon-plus"></i></button></div></div>'
      );

      if ($("#answer_view_2_3_1").find(".btnView:first").find("#answer_remove2").length <= 0) {
        $("#answer_view_2_3_1").find(".btnView:first").append(
          '<button id="answer_remove2" class="btn btn-danger ml5" type="button"><i class="glyphicon glyphicon-remove"></i></button>'
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
    $("#overExpDiv").on('change', '.from_overs_exp', function () {
      var serialId = $('#' + this.id).attr('serialId');
      totalOversExperience(serialId);
    });
    $("#overExpDiv").on('change', '.to_overs_exp', function () {
      var serialId = $('#' + this.id).attr('serialId');
      totalOversExperience(serialId);
    });

    function totalOversExperience(serialId) {
      var from_overs_exp = $('#from_overs_exp' + Number(serialId)).val();
      var to_overs_exp = $('#to_overs_exp' + Number(serialId)).val();
      var totalExp = Number(to_overs_exp) - Number(from_overs_exp);

      $('#total_overs_exp' + Number(serialId)).val(totalExp + 1);

    }
  });

  /*---------------------------------
      DATE PICKER FOR ALL CALENDAR
  -----------------------------------*/
  $('.keypressOff').keypress(function (e) {
    return false;
  });


  $('.dateTimeFormat').datepicker({
    format: "dd-mm-yyyy"
  });

  function refreshArea(data){
    $('.panel-refresh').trigger('click');
  }

  $('#date_of_birth').on('change', function () {
    // var check = moment($(this).val(), 'YYYyY/MM/DD');

    // var month = check.format('M');
    // var day   = check.format('D');
    // var year  = check.format('YYYyY');
    // console.log(month+" "+day+" "+year);

    var dateOfBirth = moment($(this).val());
    var currentDate = moment();
    var age = currentDate.diff(dateOfBirth, 'year');
    $('#age').val(age);

  });

  $('.keypressOff').keypress(function (e) {
    return false;
  });

  $("select.select2").select2({
    placeholder: "Select"
  });

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

</script>
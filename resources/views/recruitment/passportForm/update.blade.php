<?php $panelTitle = "Create Passport"; ?>
<form type="update" panelTitle="{{$panelTitle}}" class="form-load form-horizontal panelRefresh" data-fv-excluded="">
  {{csrf_field()}}
  <?php $currentDate = date('Y-m-d'); ?>
  <div class="panel panel-default chart mt20">
    <div class="panel-body">
      <div class="col-md-6">
        <div class="form-group">
        <label class="col-lg-3 col-md-3 control-label required">Full Name</label>
        <div class="col-lg-7 col-md-7">
          <input required autofocus name="full_name" id="full_name" placeholder="Full Name" class="form-control" value="{{ $editpassportForms->full_name }}">
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 col-md-3 control-label ">Father's Name</label>
        <div class="col-lg-7 col-md-7">
          <input name="father_name" id="father_name" placeholder="Father's Name" class="form-control" value="{{ $editpassportForms->father_name }}">
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 col-md-3 control-label ">Mother's Name</label>
        <div class="col-lg-7 col-md-7">
          <input name="mother_name" id="mother_name" placeholder="Mother's Name" class="form-control" value="{{ $editpassportForms->mother_name }}">
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 col-md-3 control-label ">Spouse's Name</label>
        <div class="col-lg-7 col-md-7">
          <input name="spouse_name" id="spouse_name" placeholder="Spouse's Name" class="form-control" value="{{ $editpassportForms->spouse_name }}">
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 col-md-3 control-label ">Permanent Address</label>
        <div class="col-lg-7 col-md-7">
         <input type="text" name="permanent_address" id="permanent_address" class="form-control" placeholder="Permanent Address" value="{{ $editpassportForms->permanent_address }}">
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 col-md-3 control-label ">Emergency Contact</label>
        <div class="col-lg-7 col-md-7">
          <input name="emergency_contact" id="emergency_contact" placeholder="e.g. +8801749738261" class="form-control" value="{{ $editpassportForms->emergency_contact }}">
        </div>
      </div>
       <div class="form-group">
        <label class="col-lg-3 col-md-3 control-label ">Telephone No.</label>
        <div class="col-lg-7 col-md-7">
          <input name="telephone_no" id="telephone_no" placeholder="e.g. +8809968733" class="form-control" value="{{ $editpassportForms->telephone_no }}">
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 col-md-3 control-label ">Passport Type</label>
        <div class="col-lg-7 col-md-7">
          <select name="passport_type" id="passport_type" class="select2 form-control">
            <option value="0">Select Type</option>
            <option {{ $editpassportForms->passport_type == 1?"selected":"" }} value="1">Ordinary</option>
            <option {{ $editpassportForms->passport_type == 2?"selected":"" }} value="2">Official</option>
            <option {{ $editpassportForms->passport_type == 3?"selected":"" }} value="3">Diplomatic</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 col-md-3 control-label ">Country Code</label>
        <div class="col-lg-7 col-md-7">
         <input type="text" name="country_code" id="country_code" class="form-control" placeholder="e.g. +88" value="{{ $editpassportForms->country_code }}">
        </div>
      </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
        <label class="col-lg-3 col-md-3 control-label required">Surname</label>
        <div class="col-lg-7 col-md-7">
          <input required autofocus name="surname" id="surname" placeholder="Surname" class="form-control" value="{{ $editpassportForms->surname }}">
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 col-md-3 control-label ">Nationality</label>
        <div class="col-lg-7 col-md-7">
          <select name="nationality" id="nationality" class="select2 form-control">
            <option value="1" selected>Bangladesh</option>
          </select>
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
                  class="form-control dateTimeFormat keypressOff" placeholder="e.g. 30-02-2019" value="{{ Carbon\Carbon::parse($editpassportForms->date_of_birth)->format('d-m-Y') }}">
              </div>
            </div>
            <label class="col-lg-1 col-md-1 control-label ">Age </label>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
              <input class="form-control keypressOff" id="age" name="age" placeholder="Age" value="{{ $editpassportForms->age }}">
            </div>
          </div>
          <!-- End .row -->
        </div>
      </div>
      <div class="form-group">
         <label class="col-lg-3 col-md-3 control-label ">Date of issue</label>
          <div class="col-lg-7 col-md-7 col-xs-12">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
              <input autocomplete="off" id="date_of_issue" name="date_of_issue"
                class="form-control dateTimeFormat keypressOff" placeholder="e.g. 30-02-2019"  value="{{ Carbon\Carbon::parse($editpassportForms->date_of_issue)->format('d-m-Y') }}">
            </div>
          </div>
      </div>
       <div class="form-group">
          <label class="col-lg-3 col-md-3 control-label">Sex</label>
          <div class="col-lg-7 col-md-7 col-xs-12">
            <div class="radio-custom radio-inline"><input type="radio" {{ $editpassportForms->gender == 1? "checked":"" }} name="gender" value="1" id="gender"><label for="gender">Male</label></div>
            <div class="radio-custom radio-inline"><input type="radio" {{ $editpassportForms->gender == 2? "checked":"" }} name="gender" value="2" id="gender"><label for="gender">Female</label></div>
          </div>
      </div>
       <div class="form-group">
        <label class="col-lg-3 col-md-3 control-label ">Place of birth</label>
        <div class="col-lg-7 col-md-7">
         <input type="text" name="place_of_birth" id="place_of_birth" class="form-control" placeholder="Place of birth" value="{{ $editpassportForms->place_of_birth }}">
        </div>
      </div>
      <div class="form-group">
        <div class="col-lg-12">
          <!-- Start .row -->
          <div class="row">
            <label class="col-lg-3 col-md-3 control-label">Passport No. </label>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
              <input class="form-control" id="passport_no" name="passport_no" placeholder="Passport No." value="{{ $editpassportForms->passport_no }}">
            </div>
            <label class="col-lg-1 col-md-1 control-label">PP Expired</label>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <input id="passport_expired_date" name="passport_expired_date"
                  class="form-control dateTimeFormat keypressOff" placeholder="Passport Expired Date" autocomplete="off"
                  value="{{ Carbon\Carbon::parse($editpassportForms->passport_expired_date)->format('d-m-Y') }}">
              </div>
            </div>
          </div>
          <!-- End .row -->
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 col-md-3 control-label">National ID</label>
        <div class="col-lg-7 col-md-7">
          <input autofocus name="national_id" id="national_id" placeholder="National ID" class="form-control" value="{{ $editpassportForms->national_id }}">
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 col-md-3 control-label ">Issue of authority</label>
        <div class="col-lg-7 col-md-7">
         <input type="text" name="issue_of_authority" id="issue_of_authority" class="form-control" placeholder="e.g. DIP Dhaka" value="{{ $editpassportForms->issue_of_authority }}">
        </div>
      </div>
      </div>

    </div>
    <div class="panel-footer">
      <div class="form-group">
        <div class="col-md-3 lg-3"></div>
        <div class="col-md-7 lg-7">
          <button type="submit" id="save-cv-form" class="btn btn-md btn-success">Update</button>
        </div>
      </div>
    </div>
  </div>
</form>
<br>
<script type="text/javascript" src="{{ asset('public/js/moment.min.js') }}"></script>
<script type="text/javascript">
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

  /*---------------------------------
      DATE PICKER FOR ALL CALENDAR
  -----------------------------------*/
  $('.keypressOff').keypress(function (e) {
    return false
  });


  $('.dateTimeFormat').datepicker({
    format: "dd-mm-yyyy"
  });

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
    return false
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
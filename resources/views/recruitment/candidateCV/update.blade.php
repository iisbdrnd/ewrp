<?php $panelTitle = "Candidate CV Create"; ?>
<form type="update" panelTitle="{{$panelTitle}}" class="form-load form-horizontal">
  {{csrf_field()}}
  <div class="panel panel-default chart mt20">
    <div class="panel-body">
      <div class="form-group">
        <label class="col-lg-3 col-md-3 control-label">CV No.</label>
        <div class="col-lg-7 col-md-7">
          <input autofocus value="{{$editCandidateCvs->cv_number}}" class="form-control" readonly>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 col-md-3 control-label required">Full Name</label>
        <div class="col-lg-7 col-md-7">
          <input autofocus required name="full_name" id="full_name" value="{{ $editCandidateCvs->full_name }}"
            placeholder="Full Name" class="form-control">
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 col-md-3 control-label required">Father's Name</label>
        <div class="col-lg-7 col-md-7">
          <input autofocus required name="father_name" id="father_name" value="{{ $editCandidateCvs->father_name }}"
            placeholder="Father's Name" class="form-control">
        </div>
      </div>
      <div class="form-group">
        <div class="col-lg-12">
          <!-- Start .row -->
          <div class="row">
            <label class="col-lg-3 col-md-3 control-label required">Passport No. </label>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
              <input class="form-control" id="passport_no" required name="passport_no" value="{{ $editCandidateCvs->passport_no }}"
                placeholder="Passport No.">
            </div>
            <label class="col-lg-1 col-md-1 control-label">PP Expired</label>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">

              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <input autofocus id="passport_expired_date" name="passport_expired_date" value="{{ Carbon\Carbon::parse($editCandidateCvs->passport_expired_date)->format('d-m-Y') }}"
                  class="form-control passport_expired_date" placeholder="Passport Expired Date">
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
                <input id="date_of_birth" name="date_of_birth" value="{{ $editCandidateCvs->date_of_birth }}" class="form-control date_of_birth"
                  placeholder="Date of birth">
              </div>
            </div>
            <label class="col-lg-1 col-md-1 control-label ">Age </label>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
              <input class="form-control" id="age" name="age" value="{{ $editCandidateCvs->age }}" placeholder="Age">
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
                <input class="form-control" id="village" name="village" placeholder="Village" value="{{ $editCandidateCvs->village }}">
            </div>
            <label class="col-lg-1 col-md-1 control-label ">P.O </label>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
              <input class="form-control" id="po" name="po" placeholder="P.O" value="{{ $editCandidateCvs->po }}">
            </div>
          </div>
          <!-- End .row -->
          <!-- Start .row -->
          <div class="row mt10">
            <label class="col-lg-3 col-md-3 control-label ">Upazila </label>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
              <input class="form-control" id="upazila" name="upazila" placeholder="Upazila" value="{{ $editCandidateCvs->upazila }}">
            </div>
            <label class="col-lg-1 col-md-1 control-label ">District </label>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
              <input class="form-control" id="district" name="district" placeholder="District" value="{{ $editCandidateCvs->district }}">
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
                <input autofocus required name="contact_no" id="contact_no" value="{{ $editCandidateCvs->contact_no }}"
                  placeholder="Contact No." class="form-control">
              </div>

              <label class="col-lg-1 col-md-1 control-label">National ID</label>
              <div class="col-lg-3 col-md-3">
                <input autofocus name="national_id" id="national_id" value="{{ $editCandidateCvs->national_id }}" placeholder="National ID"
                  class="form-control">
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
                <select name="reference_id" id="reference_id" class="form-control select2">
                  <option>Select Reference</option>
                  @foreach($references as $reference)
                  <option {{ $editCandidateCvs->reference_id ==  $reference->id?'selected=selected':'' }} value="{{ $reference->id }}">{{
                    $reference->reference_name }}</option>
                  @endforeach
                </select>
              </div>

              <label class="col-lg-1 col-md-1 control-label ">Dealer:</label>
              <div style="margin-top: 0px; padding: 5px 15px !important; overflow: hidden; margin-left: 10px; padding-right: 0px; width:23.5%;" class="col-lg-3 col-md-3 list-group-item" id="dealer">

                  @if(!empty($singgleRefs->dealer))
                    {{ @Helper::dealer($singgleRefs->dealer)->name }}

                  @foreach(json_decode($singgleRefs->dealer, true) as $dealerId)
                  <?php //$dealerName = @Helper::dealer($dealerId)->name.','; ?>
                    {{-- {{  rtrim(@Helper::dealer($dealerId)->name) }} --}}
                  @endforeach 
                  @else 
                  No dealer found!
                @endif
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
              <option {{ $editCandidateCvs->selected_trade == $trade->id?'selected':'' }} value="{{ $trade->id }}">{{ $trade->trade_name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 col-md-3 control-label ">Education</label>
            <div class="col-lg-7 col-md-7">
              <select name="education" id="education" class="form-control select2">
                <option value="0">Select Education</option>
                <option value="PSC" @if($editCandidateCvs->education == "PSC") selected @endif>PSC</option>
                <option value="JSC" @if($editCandidateCvs->education == "JSC") selected @endif>JSC</option>
                <option value="CLASS 10" @if($editCandidateCvs->education == "CLASS 10") selected @endif>CLASS 10</option>
                <option value="SSC" @if($editCandidateCvs->education == "SSC") selected @endif>SSC</option>
                <option value="HSC" @if($editCandidateCvs->education == "HSC") selected @endif>HSC</option>
                <option value="DIPLOMA" @if($editCandidateCvs->education == "DIPLOMA") selected @endif>DIPLOMA</option>
                <option value="DEGREE" @if($editCandidateCvs->education == "DEGREE") selected @endif>DEGREE</option>
                <option value="HONOURS"  @if($editCandidateCvs->education == "HONOURS") selected @endif>HONOURS</option>
                <option value="MASTERS" @if($editCandidateCvs->education == "MASTERS") selected @endif>MASTERS</option>
              </select>
            </div>
          </div>
        <div class="form-group" id="homeExpDiv">
            <label class="col-lg-3 col-md-3 control-label">Home Exp.</label>
            <?php 
              $lastIndex      = count(json_decode($editCandidateCvs->home_experience_details, true))-1;
              $from_home_exp  = json_decode($editCandidateCvs->from_home_exp, true);
              $to_home_exp    = json_decode($editCandidateCvs->to_home_exp, true);
              $total_home_exp = json_decode($editCandidateCvs->total_home_exp, true);
            ?>
            @if(!empty($editCandidateCvs->home_experience_details))
            @foreach(json_decode($editCandidateCvs->home_experience_details, true) as $index => $ans)
            <div class="col-lg-9 col-md-9 @if($lastIndex>0) col-lg-offset-3 col-md-offset-3 @endif" id="answer_view_2_3">
                <div class="row">
                    <div class="col-md-3 pr0 mb10">
                        <input name="home_experience_details[]" placeholder="Company Name" class="form-control" value="{{$ans}}">
                    </div>
                    <div class="col-md-2 pr0 mb10">
                        <input type="text" name="from_home_exp[]" id="from_home_exp{{ $index }}" serialId="{{ $index }}" class="form-control from_home_exp" value="{{ $from_home_exp[$index] }}" placeholder="yyyy">
                    </div>
                    <div class="col-md-2 pr0 mb10">
                        <input type="text" name="to_home_exp[]" serialId="{{ $index }}" id="to_home_exp{{ $index }}" class="form-control to_home_exp" value="{{ $to_home_exp[$index] }}" placeholder="yyyy">
                    </div>
                    <div class="col-md-1 pr0 mb10">
                        <input name="total_home_exp[]"  id="total_home_exp{{ $index }}" class="form-control total_home_exp" value="{{ $total_home_exp[$index] }}" placeholder="y">
                        <input type="hidden" name="home_experience[]" class="form-control trueAnswer" value="{{ $index+1 }}">
                    </div>
                    <div class="col-md-3 btnView">
                        <?php if($lastIndex>0) { ?><button id="answer_remove" class="btn btn-danger ml5" type="button"><i class="glyphicon glyphicon-remove"></i></button><?php } if($index==$lastIndex) { ?><button id="answer_add" class="btn btn-success ml5" type="button"><i class="glyphicon glyphicon-plus"></i></button><?php } ?>
                    </div>
                </div>
            </div>
            @endforeach
            @else 
           
            @endif
          </div>
          <div class="form-group" id="overExpDiv">
            <label class="col-lg-3 col-md-3 control-label">Oversease Exp.</label>
            <?php 
                $overLastIndex        = count(json_decode($editCandidateCvs->oversease_experience_details, true))-1;
                $oversease_from_year  = json_decode($editCandidateCvs->from_overs_exp, true);
                $oversease_to_year    = json_decode($editCandidateCvs->to_overs_exp, true);
                $oversease_total_year = json_decode($editCandidateCvs->total_overs_exp, true);
                $oversease_country    = json_decode($editCandidateCvs->oversease_country, true);
            ?>
            @if(!empty($editCandidateCvs->oversease_experience_details))
            @foreach(json_decode($editCandidateCvs->oversease_experience_details, true) as $index => $ans)
            <div class="col-lg-9 col-md-9 @if($overLastIndex>0) col-lg-offset-3 col-md-offset-3 @endif" id="answer_view_2_3_1">
              <div class="row">
                <div class="col-md-3 pr0 mb10">
                  <input name="oversease_experience_details[]" placeholder="Country Name" class="form-control"
                    value="{{$ans}}">
                </div>
                <div class="col-md-2 col-lg-2 pr0 mb10">
                  <select name="oversease_country[]" id="oversease_country" class="form-control">
                    <option value="0">Select Country</option>
                      @foreach($expCountries as $expCountry)
                      <option {{ $oversease_country[$index] == $expCountry->id?'selected':'' }} value="{{ $expCountry->id }}">{{ $expCountry->name }}</option>
                      @endforeach
                  </select>
                 </div>
                <div class="col-md-1 pr0 mb10">
                <input type="text" name="from_overs_exp[]" serialId="{{ $index }}" id="from_overs_exp{{ $index }}" class="form-control  from_overs_exp" value="{{ @$oversease_from_year[$index] }}" placeholder="yyyy">
                </div>
                <div class="col-md-1 pr0 mb10">
                    <input type="text" name="to_overs_exp[]" serialId="{{ $index }}" id="to_overs_exp{{ $index }}" class="form-control to_overs_exp" placeholder="yyyy" value="{{ $oversease_to_year[$index] }}">
                </div>
                <div class="col-md-1 pr0 mb10">
                  <input name="total_overs_exp[]" id="total_overs_exp{{ $index }}" placeholder="y" class="form-control total_overs_exp" value="{{ @$oversease_total_year[$index] }}">
                  <input type="hidden" name="over_experience[]" placeholder="" class="form-control trueAnswer2"
                    value="{{ $index+1 }}">
                </div>
                <div class="col-md-2 btnView">
                  <?php if($overLastIndex>0) { ?><button id="answer_remove2" class="btn btn-danger ml5" type="button"><i
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
          <label class="col-lg-3 col-md-3 control-label">Process</label>
          <div class="col-lg-7 col-md-7">
              <select name="process" id="process" class="form-control select2">
                <option value="0">--Process--</option>
                <option {{ $editCandidateCvs->process == 1?'selected':'' }} value="1">SMAW</option>
                <option {{ $editCandidateCvs->process == 2?'selected':'' }} value="2">GTAW + SMAW</option>
              </select>
          </div>
        </div>
    </div>
    <div class="panel-footer">
      <div class="form-group">
        <div class="col-md-3 lg-3"></div>
        <div class="col-md-7 lg-7">
          <button type="submit" class="btn btn-md btn-success">Save</button>
        </div>
      </div>
    </div>
  </div>
</form>
<br>
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

 $(".select2").select2({
      placeholder: "Select"
    });

    var i = 99999999;
  /*----------------------------------------------------------------------------------
                                                  HOME EXPERIENCE
   ----------------------------------------------------------------------------------*/


  $(document).ready(function () {
    //Start Home Exprience
    $("#homeExpDiv").on("click", "#answer_add", function () {
      i++;
      $("#homeExpDiv").find("#answer_add").remove();
      var trueAnswer = $("#answer_view_2_3").find(".trueAnswer:last").val();
      $("#homeExpDiv").append(
        '<div class="col-lg-9 col-md-9 col-lg-offset-3 col-md-offset-3" id="answer_view_2_3"><div class="row"><div class="col-md-3 pr0 mb10"><input name="home_experience_details[]" placeholder="Company Name" class="form-control"></div><div class="col-md-2 pr0 mb10"><input type="text" name="from_home_exp[]" serialId="'+i+'" id="from_home_exp'+i+'" class="form-control  from_home_exp"></div><div class="col-md-2 pr0 mb10"><input type="text"  name="to_home_exp[]" serialId="'+i+'" id="to_home_exp'+i+'" class="form-control to_home_exp "><input type="hidden" name="home_experience[]" class="form-control trueAnswer" value="' +
        (parseInt(trueAnswer) + 1) +
        '"></div><div class="col-md-1 pr0 mb10"><input name="total_home_exp[]" placeholder="y" id="total_home_exp'+i+'" class="form-control total_home_exp"></div><div class="col-md-2 btnView"><button id="answer_remove" class="btn btn-danger ml5" type="button"><i class="glyphicon glyphicon-remove"></i></button><button id="answer_add" class="btn btn-success ml5" type="button"><i class="glyphicon glyphicon-plus"></i></button></div></div></div>'
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
        '<div class="col-lg-9 col-md-9 col-lg-offset-3 col-md-offset-3" id="answer_view_2_3_1"><div class="row"><div class="col-md-3 pr0 mb10"><input name="oversease_experience_details[]" placeholder="Company Name" class="form-control"></div><div class="col-md-2 col-lg-2 pr0 mb10"><select  name="oversease_country[]" id="oversease_country" class="form-control">@foreach($expCountries as $expCountry)<option value="{{ $expCountry->id }}">{{ $expCountry->name }}</option>@endforeach</select></div><div class="col-md-1 pr0 mb10"><input type="text" name="from_overs_exp[]" id="from_overs_exp'+i+'" serialId="'+i+'" class="form-control from_overs_exp" placeholder="yyyy"></div><div class="col-md-1 pr0 mb10"><input type="text" name="to_overs_exp[]" id="to_overs_exp'+i+'" serialId="'+i+'" class="form-control to_overs_exp" placeholder="yyyy"><input type="hidden" name="overs_experience[]" class="form-control trueAnswer2" value="' +
        (parseInt(trueAnswer) + 1) +
        '"></div><div class="col-md-1 pr0 mb10"><input name="total_overs_exp[]" id="total_overs_exp'+i+'" placeholder="y" class="form-control total_overs_exp"></div><div class="col-md-2 btnView pr0"><button id="answer_remove2" class="btn btn-danger ml5" type="button"><i class="glyphicon glyphicon-remove"></i></button><button id="answer_add2" class="btn btn-success ml5" type="button"><i class="glyphicon glyphicon-plus"></i></button></div></div></div>'
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
  $('.date_of_birth').datepicker({
    format: "yyyy-mm-dd"
  });

  $('.passport_expired_date').datepicker({
    format: "dd-mm-yyyy"
  });


  $('#date_of_birth').on('change', function () {
    var dateOfBirth = moment($(this).val()); //small
    // $('#date_of_birth').val(dateOfBirth);
    var currentDate = moment(); //big
    var age = currentDate.diff(dateOfBirth, 'year');

    $('#age').val(age);
    console.log(age);
  });

  $('#country').on('change', function () {
    console.log($(this).val());
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
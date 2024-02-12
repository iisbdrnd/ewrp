<style>
  body {
    position: relative;
  }

  .bootstrap-datetimepicker-widget {
    z-index: 9999 !important;
  }
  #gtc_sent_NA, #gtc_rec_NA
  {
     float: right;
     margin-left: 1%;
    -webkit-transform: scale(1.5); /* Safari and Chrome */
    transform: scale(1.5);
  }
</style>
<form class="form-horizontal" id="mobilizeFormData" method="post" action="">
  {{csrf_field()}}
  <input type="hidden" name="projectId" class="projectId">
  <input type="hidden" name="projectCountryId" class="projectCountryId">
  <input type="hidden" name="candidateId" class="candidateId">
  <input type="hidden" name="mobilizeId" class="mobilizeId">
  <div class="panel panel-default chart">
    <div class="panel-body">
      <!-- MEDICAL OR MEDICAL FIT/UNFIT START-->
      <div class="form-group medicalStatus medical_name">
        <label class="col-lg-3 col-md-4 control-label medicalName">Medical Name</label>
        <div class="col-lg-7 col-md-6">
          <input type="text" name="medical_name" class="form-control" id="medical_name"
            placeholder="e.g. Dhaka Medical">
        </div>
      </div>
      <div class="form-group medicalStatus medical_gone_date">
        <label class="col-lg-3 col-md-4 control-label dateTimeFormat medicalDate">Medical Date</label>
        <div class="col-lg-7 col-md-6">
          <input type="text" name="medical_gone_date" id="medical_gone_date" value="{{ date('m-d-Y') }}"
            class="form-control dateTimeFormat keypressOff" placeholder="e.g. dd-mm-yyyy">
        </div>
      </div>
      <div class="form-group medicalStatus medical_code">
        <label class="col-lg-3 col-md-4 control-label medicalCode">Medical Code</label>
        <div class="col-lg-7 col-md-6">
          <input type="text" name="medical_code" id="medical_code" class="form-control" placeholder="e.g. 764-783">
        </div>
      </div>
      <div class="form-group medicalStatus medical_status">
        <label class="col-lg-3 col-md-4 control-label medicalStatus">Medical Status</label>
        <div class="col-lg-7 col-md-6">
          <select name="medical_actual_status" id="medical_actual_status" class=" form-control">
            <option value="0">---Select Status---</option>
            <option value="1">Fit</option>
            <option value="2">Unfit</option>
            <option value="3">Remedical</option>
            <option value="4">Fit Self</option>
          </select>
        </div>
      </div>
      <div class="form-group medicalStatus medical_status">
        <label class="col-lg-3 col-md-4 control-label medicalStatus">Medical Status Date</label>
        <div class="col-lg-7 col-md-6">
          <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
              <input type="text" id="medical_status_date" name="medical_status_date" class="form-control dateTimeFormat"
                placeholder="e.g. 15-12-2019" autocomplete="off" value="{{ date('d-m-Y') }}">
            </div>
        </div>
      </div>
      <div class="form-group medicalStatus medical_status">
        <label class="col-lg-3 col-md-4 control-label medicalStatus">Medical Expiry Date</label>
        <div class="col-lg-7 col-md-6">
          <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
              <input type="text" id="medical_expire_date" name="m_expire_date" class="form-control dateTimeFormat"
                placeholder="e.g. 15-12-2019" autocomplete="off" value="{{ date('d-m-Y') }}">
            </div>
        </div>
      </div>

      <!--Medical Slip --->
      <div class="form-group medicalSlip">
        <label class="col-lg-3 col-md-4 control-label medicalName">Medical Center</label>
        <div class="col-lg-7 col-md-6">
          <input type="text" name="medical_name_in_slip" class="form-control" id="medical_name_in_slip"
            placeholder="e.g. Dhaka Medical">
        </div>
      </div>
      <div class="form-group medicalSlip">
        <label class="col-lg-3 col-md-4 control-label">Medical Slip No.</label>
        <div class="col-lg-7 col-md-6">
          <input type="text" name="medical_slip_no" id="medical_slip_no" class="form-control"
            placeholder="e.g. 764-783">
        </div>
      </div>
      <div class="form-group medicalSlip">
        <label class="col-lg-3 col-md-4 control-label">Report Date</label>
        <div class="col-lg-7 col-md-6">
          <input type="text" name="medical_slip_date" id="medical_slip_date" value="{{ date('d-m-Y') }}"
            class="form-control dateTimeFormat keypressOff" placeholder="dd-mm-yyyy">
        </div>
      </div>
      <div class="form-group medicalSlip">
        <label class="col-lg-3 col-md-4 control-label">Passport Status</label>
        <div class="col-lg-7 col-md-6">
          <select name="medical_slip_status" id="medical_slip_status" class="form-control">
            <option>--Passport Status---</option>
            <option value="1">Yes</option>
            <option value="2">No</option>
          </select>
        </div>
      </div>
      <!--- Medical Slip End-->

      <!--- MEDICAL CALL START-->
      <div class="form-group medicalCall">
        <label class="col-lg-3 col-md-4 control-label">Date</label>
        <div class="col-lg-7 col-md-6">
          <input type="text" name="medical_call_date" class="form-control dateTimeFormat keypressOff"
            id="medical_call_date" value="{{ date('d-m-Y') }}" placeholder="e.g.dd-mm-yyyy">
        </div>
      </div>
      <div class="form-group medicalCall">
        <label class="col-lg-3 col-md-4 control-label">Remarks</label>
        <div class="col-lg-7 col-md-6">
          <textarea name="medical_call_remarks" id="medical_call_remarks" class="form-control" cols="3"
            rows="5"></textarea>
        </div>
      </div>
      <div class="form-group release_candidate">
        <label class="col-lg-3 col-md-4 control-label">Change Project</label>
        <div class="col-lg-7 col-md-6">
          <select name="change_project_id" id="change_project_id" class="form-control select2">
            <option value="">Select Project</option>
            @foreach ($interview_call_projects as $ic_project)
            {{ $ic_project }}
            <option value="{{ @Helper::projects($ic_project->ew_project_id)->id }}">
              {{ @Helper::projects($ic_project->ew_project_id)->project_name }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <!-- MEDICAL CALL END-->

      <!-- MEDICAL SENT START-->
      <div class="form-group medicalSent">
        <label class="col-lg-3 col-md-4 control-label">Medical Sent Date</label>
        <div class="col-lg-7 col-md-6">
          <div class="input-group">
            <span class="input-group-addon">
              <i class="fa fa-calendar"></i>
            </span>
            <input id="medical_sent_date" name="medical_sent_date" class="form-control dateTimeFormat keypressOff"
              placeholder="dd-mm-yyyy" value="{{ date('d-m-Y') }}">
          </div>
        </div>
      </div>

      <!-- MEDICAL SENT END-->
      <!--PCC SENT START-->
      <div class="form-group pccSent">
        <label class="col-lg-3 col-md-4 control-label">PCC Serial No.</label>
        <div class="col-lg-7 col-md-6">
          <input type="text" name="pcc_serial_number" id="pcc_serial_number" class="form-control"
            placeholder="PCC Serial number e.g. 1463009">
        </div>
      </div>
      <div class="form-group pccSent">
        <label class="col-lg-3 col-md-4 control-label">Date</label>
        <div class="col-lg-7 col-md-6">
          <input type="text" name="pcc_date" class="form-control dateTimeFormat keypressOff" id="pcc_date"
            placeholder="e.g.dd-mm-yyyy" value="{{ date('d-m-Y') }}">
        </div>
      </div>
      <!--PCC SENT END-->

      <!--GTC SENT START-->
      <input type="hidden" id="cur_date" value="{{ date('d-m-Y') }}">
      <input type="checkbox" id="gtc_sent_NA" class="gtcSent"> 
      <span style="font-size:15px;float: right;" id="gtc_sent_na_text" class="gtcSent">N/A</span>
      <div class="form-group gtcSent">
        <label class="col-lg-3 col-md-4 control-label">GTC Serial No.</label>
        <div class="col-lg-7 col-md-6">
          <input type="text" name="gttc_serial_number" id="gttc_serial_number" class="form-control"
            placeholder="GTC Serial number e.g. 1463009">
        </div>
      </div>
      <div class="form-group gtcSent">
        <label class="col-lg-3 col-md-4 control-label">Slip Rcv. Date</label>
        <div class="col-lg-7 col-md-6">
          <input type="text" name="gttc_date" class="form-control dateTimeFormat keypressOff" id="gttc_date"
            placeholder="e.g.dd-mm-yyyy" value="{{ date('d-m-Y') }}">
        </div>
      </div>
      <div class="form-group gtcSent">
        <label class="col-lg-3 col-md-4 control-label">Training Center Name</label>
        <div class="col-lg-7 col-md-6">
          <input type="text" name="training_center_name" id="training_center_name" class="form-control"
            placeholder="Taining Center Name">
        </div>
      </div>
      <div class="form-group gtcSent">
        <label class="col-lg-3 col-md-4 control-label">Training Start Date</label>
        <div class="col-lg-7 col-md-6">
          <input type="text" name="training_start_date" class="form-control dateTimeFormat keypressOff"
            id="training_start_date" placeholder="e.g.dd-mm-yyyy" value="{{ date('d-m-Y') }}">
        </div>
      </div>
      <!--GTC SENT END-->

      <!-- REMEDICAL START-->
      <div class="form-group remedical">
        <label class="col-lg-3 col-md-4 control-label">Medical Name</label>
        <div class="col-lg-7 col-md-6">
          <input type="text" name="remedical_name" class="form-control" id="remedical_name"
            placeholder="e.g. Dhaka Medical">
        </div>
      </div>
      <div class="form-group remedical">
        <label class="col-lg-3 col-md-4 control-label">Remedical Date</label>
        <div class="col-lg-7 col-md-6">
          <input type="text" name="remedical_date" class="form-control dateTimeFormat keypressOff" id="remedical_date"
            placeholder="e.g.dd-mm-yyyy" value="{{ date('d-m-Y') }}">
        </div>
      </div>
      <div class="form-group remedical">
        <label class="col-lg-3 col-md-4 control-label">Remedical Status</label>
        <div class="col-lg-7 col-md-6">
          <select name="remedical_actual_status" id="remedical_actual_status" class="form-control">
            <option>---Select Status---</option>
            <option value="1">Fit</option>
            <option value="2">Unfit</option>
          </select>
        </div>
      </div>
      <div class="form-group remedical">
        <label class="col-lg-3 col-md-4 control-label">Remarks</label>
        <div class="col-lg-7 col-md-6">
          <textarea name="remedical_call_remarks" id="remedical_call_remarks" class="form-control" cols="3"
            rows="5"></textarea>
        </div>
      </div>
      <!-- REMEDICAL END-->

      <!--PTA REQUEST START-->
      <div class="form-group ptaRequest">
        <label class="col-lg-3 col-md-4 control-label">PTA Request</label>
        <div class="col-lg-7 col-md-6">
          <div class="input-group">
            <span class="input-group-addon">
              <i class="fa fa-calendar"></i>
            </span>
            <input id="pta_request_date" name="pta_request_date" class="form-control dateTimeFormat keypressOff"
              placeholder="e.g. dd-mm-yyyy" value="{{ date('d-m-Y') }}">
          </div>
        </div>
      </div>
      <div class="form-group ptaRequest">
        <label class="col-lg-3 col-md-4 control-label">PTA Request Status</label>
        <div class="col-lg-7 col-md-6">
          <select name="pta_request_status" id="pta_request_status" class="form-control">
            <option value="1">Yes</option>
            <option value="2">No</option>
          </select>
        </div>
      </div>
      <div class="form-group ptaRequest">
        <label class="col-lg-3 col-md-4 control-label">Remarks</label>
        <div class="col-lg-7 col-md-6">
          <textarea name="pta_request_remarks" id="pta_request_remarks" class="form-control" cols="3"
            rows="5"></textarea>
        </div>
      </div>
      <!--PTA REQUEST END-->

      <!--PTA RECEIVE START-->
      <div class="form-group ptaReceive">
        <label class="col-lg-3 col-md-4 control-label">Ticket/PTA Date </label>
        <div class="col-lg-7 col-md-6">
          <div class="input-group">
            <span class="input-group-addon">
              <i class="fa fa-calendar"></i>
            </span>
            <input id="pta_receive_date" name="pta_receive_date" class="form-control dateTimeFormat keypressOff"
              placeholder="e.g. dd-mm-yyyy" value="{{ date('m-d-Y') }}">
          </div>
        </div>
      </div>
      <div class="form-group ptaReceive">
        <label class="col-lg-3 col-md-4 control-label">Flight Date</label>
        <div class="col-lg-7 col-md-6">
          <div class="input-group">
            <span class="input-group-addon">
              <i class="fa fa-calendar"></i>
            </span>
            <input id="pta_flight_date" name="pta_flight_date" class="form-control dateTimeFormat keypressOff"
              placeholder="e.g. dd-mm-yyyy" value="{{ date('d-m-Y') }}">
          </div>
        </div>
      </div>
      <div class="form-group ptaReceive">
        <label class="col-lg-3 col-md-4 control-label">Flight No.</label>
        <div class="col-lg-7 col-md-6">
          <input type="text" name="flight_no" id="flight_no" class="form-control" placeholder="e.g. 764-783">
        </div>
      </div>
      <div class="form-group ptaReceive">
        <label class="col-lg-3 col-md-4 control-label">Flight Time.</label>
        <div class="col-lg-7 col-md-6">
          <input type="text" id="time_picker" name="flight_time" id="flight_time" class="form-control time_picker"
            placeholder="e.g. 12:00 AM">
        </div>
      </div>
      <div class="form-group ptaReceive transit_place">
        <label class="col-lg-3 col-md-4 control-label">Transit Place</label>
        <div class="col-lg-7 col-md-6">
          <input type="text" name="transit_place" id="transit_place" class="form-control" placeholder="e.g. Abu Dhabi">
        </div>
      </div>
      <!--PTA RECEIVE END-->

      <!--FLIGHT BRIEFING -->
      <div class="form-group flightNo flight_status">
        <label class="col-lg-3 col-md-4 control-label">Flight Status</label>
        <div class="col-lg-7 col-md-6">
          <select name="flight_status" id="flight_status" class="form-control" placeholder="Select Status">
            <option>--Select Status--</option>
            <option value="1">Yes</option>
            <option value="2">No</option>
          </select>
        </div>
      </div>
      <div class="form-group flightNo flight_briefing_date">
        <label class="col-lg-3 col-md-4 control-label">Briefing Date.</label>
        <div class="col-lg-7 col-md-6">
          <input type="text" name="flight_briefing_date" id="flight_briefing_date" class="form-control dateTimeFormat"
            placeholder="e.g. 21-04-2018">
            <span id="msg" style="color: red"></span></br>
            <span id="f_date_text" style="color: red"></span><span id="f_date" style="color: red"></span>
        </div>
      </div>
      <div class="form-group flightNo flight_remarks">
        <label class="col-lg-3 col-md-4 control-label">Remarks</label>
        <div class="col-lg-7 col-md-6">
          <textarea name="flight_remarks" id="flight_remarks" class="form-control" cols="3" rows="5"></textarea>
        </div>
      </div>

      <!--FLIGHT NO START -->

      <!--VISA ONLINE START-->
      <div class="form-group visaOnlineGroup">
        <label class="col-lg-3 col-md-4 control-label">Visa Online Date</label>
        <div class="col-lg-7 col-md-6">
          <div class="input-group">
            <span class="input-group-addon">
              <i class="fa fa-calendar"></i>
            </span>
            <input type="text" id="visa_online_date" name="visa_online_date"
              class="form-control dateTimeFormat keypressOff" placeholder="e.g. dd-mm-yyyy" value="{{ date('d-m-Y') }}">
          </div>
        </div>
      </div>
      <div class="form-group visaOnlineGroup">
        <label class="col-lg-3 col-md-4 control-label" id="visa_status_code_label">Visa Status Code</label>
        <div class="col-lg-7 col-md-6">
          <input type="text" name="visa_status_code" id="visa_status_code" placeholder="e.g.3112748"
            class="form-control">
        </div>
      </div>
      <div class="form-group visaOnlineGroup visaGroup">
        <label class="col-lg-3 col-md-4 control-label">Visa Issued Date</label>
        <div class="col-lg-7 col-md-6">
          <div class="input-group">
            <span class="input-group-addon">
              <i class="fa fa-calendar"></i>
            </span>
            <input id="visa_issued_date" name="visa_issued_date" class="form-control dateTimeFormat keypressOff"
              placeholder="e.g. dd-mm-yyyy" value="{{ date('d-m-Y') }}">
          </div>
        </div>
      </div>
      <div class="form-group visaOnlineGroup visaGroup visaExpiryDate">
        <label class="col-lg-3 col-md-4 control-label">Visa Expiry Date</label>
        <div class="col-lg-7 col-md-6">
          <div class="input-group">
            <span class="input-group-addon">
              <i class="fa fa-calendar"></i>
            </span>
            <input id="visa_expiry_date" name="visa_expiry_date" class="form-control dateTimeFormat keypressOff"
              placeholder="e.g. dd-mm-yyyy" value="{{ date('d-m-Y') }}">
          </div>
        </div>
      </div>
      <!--VISA ONLINE END-->

      <!--OTHERS FORM START-->
      <input type="checkbox" id="gtc_rec_NA" class="othersGroup"> 
      <span style="font-size:15px;float: right;" id="gtc_rec_na_text" class="othersGroup">N/A</span>
      <div class="form-group othersGroup">
        <label class="col-lg-3 col-md-4 control-label">Date</label> 
        <div class="col-lg-7 col-md-6">
          <input type="text" name="mobilize_date" id="mobilize_date" title="{{$mobilizeId}}" class="form-control dateTimeFormat keypressOff"
            placeholder="e.g. dd-mm-yyyy" value="{{ date('d-m-Y') }}">
        </div>
      </div>
      <div class="form-group  visaPrint">
        <label class="col-lg-3 col-md-4 control-label">Visa No.</label>
        <div class="col-lg-7 col-md-6">
          <input type="text" name="visa_no" id="visa_no" class="form-control" placeholder="e.g. 945849">
        </div>
      </div>
      <!--OTHERS FORM END-->

      <!-- visa stamp expiry date -->
      <div class="form-group visaOnlineGroup visaGroup visa_stamp_expire">
        <label class="col-lg-3 col-md-4 control-label">Visa Expiry Date</label>
        <div class="col-lg-7 col-md-6">
          <div class="input-group">
            <span class="input-group-addon">
              <i class="fa fa-calendar"></i>
            </span>
            <input id="visa_stamp_expiry_date" name="visa_stamp_expiry_date" class="form-control dateTimeFormat keypressOff"
              placeholder="e.g. dd-mm-yyyy" value="{{ date('d-m-Y') }}">
          </div>
        </div>
      </div>
      <!-- end visa stamp expiry date -->

      <!--  visa print expire date -->
      <div class="form-group visaOnlineGroup visaGroup visa_print_expire">
        <label class="col-lg-3 col-md-4 control-label">Visa Expiry Date</label>
        <div class="col-lg-7 col-md-6">
          <div class="input-group">
            <span class="input-group-addon">
              <i class="fa fa-calendar"></i>
            </span>
            <input id="visa_print_expiry_date" name="visa_print_expiry_date" class="form-control dateTimeFormat keypressOff"
              placeholder="e.g. dd-mm-yyyy" value="{{ date('d-m-Y') }}">
          </div>
        </div>
      </div>
      <!-- end visa print expire date -->


    </div>
  </div>
</form>

<script> 
  //gtc na checked
  $('#myModal').on('hidden.bs.modal', function () {
    $('#gtc_rec_NA').prop('checked', false);
    $('#gtc_sent_NA').prop('checked', false);
  });
  $('#gtc_rec_NA').change(function() {
      var date = $('#cur_date').val();
      if(this.checked) {
          $('#mobilize_date').val('N/A');
      }else{
          $('#mobilize_date').val(date);
      }      
    });
    
  $('#gtc_sent_NA').change(function() {
      var date = $('#cur_date').val();
      if(this.checked) {
          $('#gttc_serial_number').val('N/A');
          $('#gttc_date').val('N/A');
          $('#training_center_name').val('N/A');
          $('#training_start_date').val('N/A');
      }else{
          $('#gttc_serial_number').val('');
          $('#gttc_date').val(date);
          $('#training_center_name').val('');
          $('#training_start_date').val(date);
      }      
  });
  // $('.time_picker').datetimepicker({
  //          format: 'LT'
  //      });
  $('#time_picker').datetimepicker({
    // locale: 'it',
    format: "LT",
    widgetParent: 'body' //<-----IMPORTANT
  });

  $('#time_picker').on('dp.show', function () {
    var datepicker = $('body').find('.bootstrap-datetimepicker-widget:last');

    if (datepicker.hasClass('bottom')) {

      var top = $(this).offset().top + $(this).outerHeight();
      var left = $(this).offset().left;
      datepicker.css({
        'top': top + 'px',
        'bottom': 'auto',
        'left': left + 'px'
      });

    }

    if (datepicker.hasClass('top')) {

      var top = $(this).offset().top - datepicker.outerHeight();
      var left = $(this).offset().left;
      datepicker.css({
        'top': top + 'px',
        'bottom': 'auto',
        'left': left + 'px'
      });

    }

  });


  /* Select2 */
  $("select.select2").select2({
    placeholder: "Select"
  });

  $('#medical_actual_status').change(function () {

    if (this.value == 3) {

      $('.othersGroup').show();
      $('#gtc_rec_NA').hide();
      $('#gtc_rec_na_text').hide();

    } else {

      $('.othersGroup').hide();

    }

  }).trigger('change');

  $('#gtc_sent').on('change', function () {
    if (this.checked) {

      $('#gtc_serial_number').show();

    } else {

      $('#gtc_serial_number').hide();

    }
  });

  $('#pcc_sent').on('change', function () {

    if (this.checked) {

      $('#pcc_serial_number').show();

    } else {

      $('#pcc_serial_number').hide();

    }

  });

  /* MEDICAL SENT WITH MEDICAL SLIP */
  $('#medical_slip').on('change', function () {
    console.log(this.checked);

    if (this.checked) {

      $('.medical_slip_show').show();

    } else {

      $('.medical_slip_show').hide();

    }

  });


  $("#flight_briefing_date").on("changeDate", function() {
      var  url = '{{ route('recruit.getFliteBriefDate') }}'; 
      var brief_date = $(this).val();
      var candidateId = $('.candidateId').val();
      var projectId = $('.projectId').val();
      $.ajax({
        url: url,
        type: 'GET',
        data: {
                projectId:projectId,
                candidate_id:candidateId,
                brief_date:brief_date,
                mobilizeId:26,
                _token: "{{ csrf_token() }}"
              },
        success: function (data) {
          if(data.status == 'error')
          {
              $('#msg').text(data.msg);
              $('#f_date_text').text('flight date:');
              $('#f_date').text(data.flight_date);
              $('#mobilizeBtn').addClass('disabled');  
              $('#mobilizeBtn').css('pointer-events','none');
          }
          else
          {
            $('#msg').text('');
            $('#f_date_text').text('');
            $('#f_date').text('');
            $('#mobilizeBtn').removeClass('disabled');  
            $('#mobilizeBtn').css('pointer-events','unset');
          }

        },
    });
  });


  //medical status onchange

  $('#medical_status_date').on('changeDate', function () {
      var m_status_data = $(this).val();
      var from = m_status_data.split("-");
      var m_status_date = new Date(from[2], from[1] - 1, from[0]);
      var n=90; //number of days to add. 
      var requiredDate=new Date(m_status_date.getFullYear(),m_status_date.getMonth(),m_status_date.getDate()+n).toLocaleDateString('en-GB', {
          day : 'numeric',
          month : 'numeric',
          year : 'numeric'
      }).split(' ').join('-');
      requiredDate = requiredDate.replace("/", "-");
      requiredDate = requiredDate.replace("/", "-");
      $('#medical_expire_date').val(requiredDate);
  });

  $('#mobilize_date').on('changeDate', function () {

      var mobilize_id = $(this).attr('title');
      if(mobilize_id == 11){

        var m_status_data = $(this).val();
        var from = m_status_data.split("-");
        var m_status_date = new Date(from[2], from[1] - 1, from[0]);
        var n=90; //number of days to add. 
        var requiredDate=new Date(m_status_date.getFullYear(),m_status_date.getMonth(),m_status_date.getDate()+n).toLocaleDateString('en-GB', {
            day : 'numeric',
            month : 'numeric',
            year : 'numeric'
        }).split(' ').join('-');

        requiredDate = requiredDate.replace("/", "-");
        requiredDate = requiredDate.replace("/", "-");
        $('#visa_stamp_expiry_date').val(requiredDate);

      }

  });

</script>
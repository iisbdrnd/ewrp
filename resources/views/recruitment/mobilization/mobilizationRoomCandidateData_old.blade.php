<style>
  #panelId .panel-heading .panel-controls .panel-refresh {
    display: none;
  }

  div.dataTables_filter label {

    /* float: right !important;
  margin-top: -34px !important; */
  }

  /* DataTable print, excel, csv and pdf button customizing design */
  div.dt-buttons {

    position: absolute !important;
    margin-left: 60% !important;
  }

  label {
    margin-bottom: 9px;
  }

  a.buttons-copy {
    background: #056E11;
    color: #fff;
    border: 1px solid #36A9CA !important;
  }

  a.buttons-excel {
    background: #056E11;
    color: #fff;
    border: 1px solid #1C6C49 !important;
  }

  a.buttons-csv {
    background: #056E11;
    color: #fff;
    border: 1px solid #056E18 !important;
  }

  a.buttons-pdf {
    background: #056E11;
    color: #fff;
    border: 1px solid #056E18 !important;
  }

  a.buttons-print {
    background: #056E11;
    color: #fff;
    border: 1px solid #0F5BA1 !important;
  }

  a.buttons-copy:hover {
    background: #056E11 !important;
    color: #fff !important;
    border: 1px solid !important;
  }

  a.buttons-excel:hover {
    background: #1C6C49 !important;
    color: #fff !important;
    border: 1px solid !important;
  }

  a.buttons-csv:hover {
    background: #056E18 !important;
    color: #fff !important;
    border: 1px solid !important;
  }

  a.buttons-pdf:hover {
    background: #056E18 !important;
    color: #fff !important;
    border: 1px solid !important;
  }

  a.buttons-print:hover {
    background: #056E18 !important;
    color: #fff !important;
    border: 1px solid !important;
  }
</style>
<div class="form-inline">
  <div class="row">
    <div class="col-md-12 col-lg-12">
      <div id="myTabContent2" class="tab-content">
        <div class="tab-pane fade active in" id=home2>
          <div class="form-inline">
            <table id="myTable" cellspacing="0" class="responsive table table-striped table-bordered">
              <thead>
                <tr>
                  <th width="1%">No.</th>
                  <th width="7%">CV.No.</th>
                  <th width="10%">Name</th>
                  <th width="7%">PP. No.</th>
                  <th width="7%">S. Trade</th>
                  <th width="5%">Salary</th>
                  <th width="7%">Ref.</th>
                  <th width="7%">Dealer</th>
                  <th width="5%">Contact No.</th>
                  <th width="5%" style="font-size:10px;">PP. Status</th>
                  <th width="14%" class="mobilize_date_field_name" style="font-size:12px;"></th>
                  <th class="status_field" width="7%">Status</th>
                  <th width="7%" class="action_button">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php $paginate = $candidateDetails; $i = 0; ?>
                @forelse ($candidateDetails as $index => $candidateDetail)

                <?php $i++;?>
                <tr>
                  <td>{{ $i }}</td>
                  <td>{{ $candidateDetail->cv_number }}</td>
                  <td>
                    <a href="" menu-active="mobilization" class="ajax-link">{{ $candidateDetail->full_name }}
                    </a>
                  </td>
                  <td>{{ $candidateDetail->passport_no }}</td>
                  <td>{{ @Helper::singleTrade($candidateDetail->selected_trade)->trade_name }}</td>
                  <td>{{ @Helper::interviewTable($candidateDetail->ew_project_id, $candidateDetail->id)->salary }}</td>
                  <td>{{ @Helper::reference($candidateDetail->reference_id)->reference_name }}</td>
                  <td>

                    @if (!empty(json_decode(@Helper::reference($candidateDetail->reference_id)->dealer, true)) && json_decode(@Helper::reference($candidateDetail->reference_id)->dealer, true) != null)

                    @foreach(json_decode(@Helper::reference($candidateDetail->reference_id)->dealer, true) as $dealerId)
                    {{ @Helper::dealer($dealerId)->name }}
                    @endforeach

                    @endif

                  </td>
                  <td>{{ $candidateDetail->contact_no }}</td>
                  <td>
                    {{ $candidateDetail->passport_status == 1 
                    ? 'In Office'
                    : ($candidateDetail->passport_status == 2 
                    ? 'Yes' 
                    : ($candidateDetail->passport_status == 3 
                    ? 'No' 
                    : '')) }}
                  </td>
                  <td width="14%" class="mobilize_date_field">
                    <p>
                      @if (@$mobilization_date[$index] == "0000-00-00 00:00:00")
                      {{Carbon\Carbon::now()->format('d-m-Y')}}

                      @else
                      {{Carbon\Carbon::parse(@$mobilization_date[$index])->format('d-m-Y')}}</p>

                    @endif
                    <p>
                      <span class="text-info">
                        @if (@$depMobilizeId != false)
                        {{@Helper::single_mobilization(@$depMobilizeId)->name}}:
                        @else

                        @if ($mobilizeId == "candidates" || $mobilizeId == "finalizing")
                        @else

                        Selection Date:
                        @endif

                        @endif

                        @if ($mobilizeId == "candidates" || $mobilizeId == "finalizing")
                        @else
                      </span> ({{Carbon\Carbon::parse(@$prevDate[$index])->format('d-m-Y')}})
                      @endif
                    </p>
                    @if ($mobilizeId == 26)
                    <p class="text-primary"><strong>Flight Date:
                        {{Carbon\Carbon::parse(@$flight_date[$index])->format('d-m-Y')}}</strong></p>
                    @endif

                  </td>
                  <td class="status_field">
                    <?php
                      switch (@$medical_status[$index]) {
                        case 1:
                            echo "Fit";
                            break;
                        case 2:
                            echo "Unfit";
                            break;
                        case 3:
                            echo "Remedical";
                            break;
                        case 4:
                            echo "Fit Self";
                            break;
                        default:

                            if (@$filering == 1 || @$filering == 3) {
                              if ($mobilizeId == 28 && $candidateDetail->approved_status == 1) {
                                 echo "<strong class='text-success'>Approved</strong>";
                              } else {
                                echo "<strong class='text-success'>Completed</strong>";
                              }
                            } else {
                              
                              if ($mobilizeId == 28 && $candidateDetail->approved_status == 1) {
                                echo "<strong class='text-success'>Yes</strong>";
                              } elseif ($mobilizeId == 28 && $candidateDetail->approved_status == 0) {
                                echo "<strong class='text-danger'>Not Approved</strong>";  
                              } else {
                                 echo "<strong class='text-danger'>Incompleted</strong>";
                              }
                            }

                          break;
                      }
                      ?>

                    @if (@Helper::deployed($projectId, $candidateDetail->id) == true)
                    <strong class="text-primary">Deployed</strong>
                    <img src="{{ asset('public/img/flight.png') }}" alt="image">
                    @endif

                    <p><a href="#" id="wip_status{{$candidateDetail->id}}"
                        class="btn btn-xs {{$candidateDetail->wip_status == 1?'btn-danger':'btn-success'}}"
                        onclick="wip_status('{{$candidateDetail->id}}', '{{$candidateDetail->wip_status}}')"><i
                          class="fa fa-pencil"></i> {{$candidateDetail->wip_status == 1?'Declined':'Activated'}} </a>
                    </p>

                  </td>
                  <td class="text-center action_button">
                    <button type="button" {{$candidateDetail->wip_status == 1?'disabled':''}}
                      id="viewBtn{{ $candidateDetail->id }}"
                      onclick="mobilizationModalView('{{ $candidateDetail->id }}')" btnType="mobilize" class="mobilize_button btn btn-sm {{ @Helper::mcStatus($projectId,$candidateDetail->id, $mobilizeId)['count'] != 0 ||  $candidateDetail->approved_status == 1 && $mobilizeId == 28
                    ? 'btn-success'
                    : 'btn-default' }}">

                      <i
                        class="fa fa-{{@Helper::mcStatus($projectId,$candidateDetail->id, $mobilizeId)['count'] != 0? 'pencil':'save'}}"></i>
                      {{@Helper::mcStatus($projectId,$candidateDetail->id, $mobilizeId)['count'] != 0 || $candidateDetail->approved_status == 1 && $mobilizeId == 28? 'Edit':'Save'}}
                      {{ @Helper::single_mobilization($mobilizeId)->name }}
                    </button>

                    @if ($mobilizeId == 'finalizing' )
                    <strong class="text-primary">Deployed
                      <img src="{{ asset('public/img/flight.png') }}" alt="image">
                    </strong>
                    @else

                    <button style="display:none;" class="approved_button btn btn-sm {{ $candidateDetail->approved_status == 0
                    ? 'btn-default'
                    : 'btn-success' }} " {{ $candidateDetail->approved_status == 0
                    ? 'Approve'
                    : 'disabled' }} onclick="approvedStatus('{{ $candidateDetail->id }}')">
                      {{ $candidateDetail->approved_status == 0
                    ? 'Approve'
                    : ($candidateDetail->approved_status == 1
                    ?'Approved':'Mobilize') }}
                    </button>

                    <button style="{{@$filering == 2? 'display:block':'display:none'}}"
                      class="btn btn-sm btn-warning mt10 restore"
                      onclick="restoreCandidate('{{ $candidateDetail->id }}')"><i class="fa fa-undo"></i>
                      Restore</button>
                    @endif
                   
                    
                    @if (@Helper::getMobilizeStage($candidateDetail->ew_project_id, $candidateDetail->id) != 0 && $mobilizeId == "candidates")
                    
                    <button class="btn btn-xs btn-success">
                      <strong> {{@Helper::single_mobilization(@Helper::getMobilizeStage($candidateDetail->ew_project_id, $candidateDetail->id))->name}}</strong>
                    </button>

                    @elseif(@Helper::getMobilizeStage($candidateDetail->ew_project_id, $candidateDetail->id) == 0 && $mobilizeId == "candidates")
                    

                    <button class="btn btn-xs btn-danger"><strong>No Mobilization</strong></button>
                    <button class="btn btn-xs btn-info mt5" onclick="releaseCandidate('{{ $candidateDetail->id }}')">Release Candidate</button>

                    @endif
                    
                  </td>
                </tr>
                @empty
                <tr>
                  <td style="border:0px;"></td>
                  <td style="border:0px;"></td>
                  <td style="border:0px;"></td>
                  <td style="border:0px;"></td>
                  <td style="border:0px;"></td>
                  <td style="border:0px;"></td>
                  <td style="border:0px;"></td>
                  <td style="border:0px;"></td>
                  <td style="border:0px;"></td>
                  <td style="border:0px;"></td>
                  <td class="mobilize_date_field" style="border:0px;"></td>
                  <td class="status_field" style="border:0px;"></td>
                  <td style="border:0px;" class="action_button"></td>
                </tr>
                @endforelse
              </tbody>
            </table>
            <div class="row">
              <div class="col-md-12 col-xs-12">

              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!--Modal button,  click event action on this button and it is not displaying in view.
  Check the data list. When click there then that click trigger on this button
-->
<button style="display:none;" type="button" id="mobilizeModal" class="btn btn-default btn-sm" data-toggle="modal"
  data-target="#myModal">
  modal
</button>

<!-- This is main modal content viewing after click on Modal Button-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
      </div>
      <div class="modal-body">
        @include('recruitment.mobilization.mobilizeModalViewForm')
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <!-- Mobilize activity Button-->
        <button type="button" id="mobilizeBtn" data-dismiss="modal" class="btn btn-success">Save</button>
      </div>
    </div>
  </div>
</div>
<input type="hidden" id="mobilizeCandidateId">
<script type="text/javascript">
  /* DATATABLE HTML5 SUPPOERTED INIT*/
  $(document).ready(function () {

    $('#myTable').DataTable({
      dom: 'Blfrtip',
      buttons: [{
          extend: 'excelHtml5',
          footer: false,
          exportOptions: {
            /* ANY FILE ONLY SHOWING THESE COLUMN */
            columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
          }
        },
        // {
        //   extend: 'csvHtml5',
        //   footer: false,
        //   exportOptions: {
        //     columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10,11,12]
        //   }

        // },
        {
          extend: 'pdfHtml5',
          footer: false,
          orientation: 'landscape',
          pageSize: 'LEGAL',
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
          }
        },
        {
          extend: 'print',
          footer: false,
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
          }
        }
      ],
      //   columnDefs: [
      //   {
      //       targets: -1,
      //       className: 'dt-body-right'
      //   }
      // ]  
    });
  });

  /**
   *Both Id comes from MobilizationController
   *Method: mobilizationRoom
   */
  var projectId = '{{ $projectId }}';
  var mobilizeId = '{{ $mobilizeId }}';


  /** completed, WIP & incompleted filtering option is appending when datatable is loaded. */
  $('label input[type=search]').prop('id', 'search');
  $('#myTable_filter').append(
    '<label><button class="btn btn-md btn-danger mobilizeFiltering  ml5" data="2" onclick="getFilteringButtonText(mobilizeId, 2)"><i class="fa fa-hand-o-up"></i> Incomplete({{@$incompleted}})</button><button class="btn btn-md btn-success mobilizeFiltering completed_btn ml5" data="1" onclick="getFilteringButtonText(mobilizeId, 1)"><i class="fa fa-hand-o-up"></i> Completed({{@$completed}})</button><button class="btn btn-md btn-primary mobilizeFiltering ml5" data="3" onclick="getFilteringButtonText(mobilizeId, 3)"><i class="fa fa-clock-o" aria-hidden="true"></i> WIP({{@$wip}})</button> <button type="button" data-toggle="modal" data-target=".bd-example-modal-lg" class="btn btn-md btn-warning  selectedTrade ml5" data="4" onclick="selectedTrade(projectId, mobilizeId, 4)"><i class="fa fa-clock-o" aria-hidden="true"></i> Trade Summary</button> </label>'
    );

  $("select.select2").select2({
    placeholder: "Select"
  });

  $('.selectedTrade').hide();

  /**
   * After click on selection mobilization the following action is happened.
   * 
   */
  if (mobilizeId == "finalizing" || mobilizeId == "candidates") {

    $('.restore').hide();
    $('.mobilizeFiltering').hide();
    // $('.mobilize_date_field_name').hide();
    // $('.mobilize_date_field').hide();
    $('.status_field').hide();
    $('.selectedTrade').show();

  }

  if (mobilizeId == 'candidates') {
    $('.selectedTrade').show();
  }


  /**
   * mobilization date field in html table td and th element name is apppend
   * where click on mobilization
   */
  let mobilizeName = $('#mobilizeName').val();
  var getNextMobilize = $('.getMobilizeNameById2').text();
  var nextMobilizeId = $('.getMobilizeNameById2').attr('nextMobilizeId');
  var mobilize_date = mobilizeName + " " + "Date";
  var deployed_status = '{{@$deployed_status}}';
  var prevMobilizeId = $('#prevMobilizeId').val();
  console.log(mobilize_date);
  switch (mobilizeId) {
    case "candidates":
    case "finalizing":
      $('.mobilize_date_field_name').text("Selection Date");
      break;
  }
  switch (Number(mobilizeId)) {
    case 1:
      $('.mobilize_date_field_name').text(mobilize_date);
      break;
    case 2:
      $('.mobilize_date_field_name').text(mobilize_date);
      break;
    case 3:
      $('.mobilize_date_field_name').text(mobilize_date);
      break;
    case 4:
      $('.mobilize_date_field_name').text(mobilize_date);
      break;
    case 5:
      $('.mobilize_date_field_name').text(mobilize_date);
      break;
    case 6:
      $('.mobilize_date_field_name').text(mobilize_date);
      break;
    case 7:
      $('.mobilize_date_field_name').text(mobilize_date);
      break;
    case 8:
      $('.mobilize_date_field_name').text(mobilize_date);
      break;
    case 9:
      $('.mobilize_date_field_name').text(mobilize_date);
      break;
    case 10:
      $('.mobilize_date_field_name').text(mobilize_date);
      break;
    case 11:
      $('.mobilize_date_field_name').text(mobilize_date);
      break;
    case 12:
      $('.mobilize_date_field_name').text(mobilize_date);
      break;
    case 13:
      $('.mobilize_date_field_name').text(mobilize_date);
      break;
    case 14:
      $('.mobilize_date_field_name').text(mobilize_date);
      break;
    case 15:
      $('.mobilize_date_field_name').text(mobilize_date);
      break;
    case 16:
      $('.mobilize_date_field_name').text(mobilize_date);
      break;
    case 17:
      $('.mobilize_date_field_name').text("Medical Slip Date");
      break;
    case 18:
      $('.mobilize_date_field_name').text(mobilize_date);
      break;
    case 19:
      $('.mobilize_date_field_name').text(mobilize_date);
      break;
    case 20:
      $('.mobilize_date_field_name').text(mobilize_date);
      break;
    case 21:
      $('.mobilize_date_field_name').text(mobilize_date);
      break;
    case 22:
      $('.mobilize_date_field_name').text(mobilize_date);
      break;
    case 23:
      $('.mobilize_date_field_name').text(mobilize_date);
      break;
    case 24:
      $('.mobilize_date_field_name').text(mobilize_date);
      break;
    case 25:
      $('.mobilize_date_field_name').text(mobilize_date);
      break;
    case 26:
      console.log(mobilize_date);
      $('.mobilize_date_field_name').text(mobilize_date);
      break;
    case 27:
      $('.mobilize_date_field_name').text(mobilize_date);
      break;
    case 28:
      $('.mobilize_date_field_name').text(mobilize_date);
      // $('.mobilize_date_field_name').hide();//text(mobilize_date);
      // $('.mobilize_date_field').hide();
      break;
    case 29:
      $('.mobilize_date_field_name').text(mobilize_date);
      break;
    case 30:
      $('.mobilize_date_field_name').text(mobilize_date);
      break;
  }



  var mobilizeSerialId = $('.mobilizationSingleListClick' + mobilizeId).attr('serialId');
  var projectCountryId = "{{$projectCountryId}}";
  $('.projectCountryId').val(projectCountryId);
  // console.log(projectCountryId);

  /**
   * Approved Status  
   * controller: mobilizationController
   * method: candidateApproveStatus
   * column: approved_status in ew_candidatescv table
   * post data: approved_status = 1
   * route: candidateApproveStatus
   */
  var incrementByOne = 0;
  var decrementByOne = 0;

  function approvedStatus(candidateId) {

    $.confirm({
      title: 'Confirm!',
      content: 'Are you sure you want to confirm this? Yes / No',
      buttons: {
        confirm: function () {
          // $.alert({
          //   title: 'Congratulation!',
          //   content: 'Thank you! Candidate is approved!',
          // });
          $.post('{{ route('recruit.candidateApproveStatus') }}', {
              'projectId': projectId,
              'candidateId': candidateId,
              '_token': $('input[name=_token]').val()
            },
            function (response) {
              incrementByOne++;
              decrementByOne--;
              $.gritter.add({
                title: "Done !!!",
                text: response.messege,
                time: "",
                close_icon: "entypo-icon-cancel s12",
                icon: "icomoon-icon-checkmark-3",
                class_name: "success-notice"
              });
              var total_approved = $('.mobilizationSingleListClick28 span.notification').text();
              $('.mobilizationSingleListClick28 span.notification').text(Number(total_approved) + Number(
                incrementByOne));
              $('.mobilizationSingleListClick28').trigger('click');
              var total_selection = $('#total_candidate span.text-danger').text();
              $('#total_candidate span.text-danger').text(Number(total_selection) + Number(decrementByOne));
            });
        },
        somethingElse: {
          text: 'Cancel!',
          btnClass: 'btn-red',
          keys: ['enter', 'shift'],
          cancel: function () {}
        }
      }
    });
  }

  /**
   * Restore candidate
   * Controller: MobilizationController
   * @method restoreCandidate
   * Data remove from "EwMobilizationMasterTable"
   * Candidate store in selection list
   */
  var incrementSelection = 0;
  var decrementSelection = 0;

  function restoreCandidate(candidateId) {

    $.confirm({
      title: 'Confirm!',
      content: 'Are you sure you want to restore this candidate ?',
      buttons: {
        confirm: function () {
          // $.alert({
          //   title: 'Congratulation!',
          //   content: 'Thank you! Candidate is approved!',
          // });
          $.post('recruitment/restoreCandidate', {
              'projectId': projectId,
              'candidateId': candidateId,
              '_token': $('input[name=_token]').val()
            },
            function (response) {
              incrementByOne++;
              decrementByOne--;
              $.gritter.add({
                title: "Done !!!",
                text: response.messege,
                time: "",
                close_icon: "entypo-icon-cancel s12",
                icon: "icomoon-icon-checkmark-3",
                class_name: "success-notice"
              });

              $('.mobilizationSingleListClick' + mobilizeId + ' span.notification').trigger('click');
              var total_selection = $('#total_candidate span.text-danger').text();
              $('.mobilizationSingleListClick28').trigger('click');
              $('#total_candidate span.text-danger').text(Number(total_selection) + Number(incrementSelection));
            });
        },
        somethingElse: {
          text: 'Cancel!',
          btnClass: 'btn-red',
          keys: ['enter', 'shift'],
          cancel: function () {}
        }
      }
    });
  }

  var incrementCands = 0;
  var decrementCands = 0;
  function releaseCandidate(candidateId) {

    $.confirm({
      title: 'Confirm!',
      content: 'Are you sure, want to release this candidate ?',
      buttons: {
        confirm: function () {
          // $.alert({
          //   title: 'Congratulation!',
          //   content: 'Thank you! Candidate is approved!',
          // });
          $.post('recruitment/releaseCandidate', {
              'projectId': projectId,
              'candidateId': candidateId,
              'projectCountryId': projectCountryId,
              '_token': $('input[name=_token]').val()
            },
            function (response) {
              incrementCands++;
              decrementCands--;
              $.gritter.add({
                title: "Done !!!",
                text: response.messege,
                time: "",
                close_icon: "entypo-icon-cancel s12",
                icon: "icomoon-icon-checkmark-3",
                class_name: "success-notice"
              });

              $('.mobilizationSingleListClick' + mobilizeId + ' span.notification').trigger('click');
              var total_selection = $('#total_candidate span.text-danger').text();
              $('.mobilizationSingleListClick28').trigger('click');
              $('#total_candidate span.text-danger').text(Number(total_selection) + Number(decrementCands));
              $('#total_candidate').trigger('click');
            });
        },
        somethingElse: {
          text: 'Cancel!',
          btnClass: 'btn-red',
          keys: ['enter', 'shift'],
          cancel: function () {}
        }
      }
    });
  }

  /** Declined, Activated status
   * Controller: MobilizationController
   * @method wip_status
   * Data insert into ew_candidatescv table
   * column name: wip_status
   * Declined for 1 and activated for 0
   */
  function wip_status(candidateId, status) {
    $.confirm({
      title: 'Confirm!',
      content: 'Are you sure you want to change status?',
      buttons: {
        confirm: function () {
          // $.alert({
          //   title: 'Congratulation!',
          //   content: 'Thank you! Candidate is approved!',
          // });
          $.ajax({
            url: '{{ route('recruit.wip_status') }}',
            type: 'GET',
            data: {
              projectId: projectId,
              candidateId: candidateId,
              mobilizeId: mobilizeId,
              projectCountryId: projectCountryId,
              status: status
            },
            processData: true,
            contentType: false,
            success: function (response) {
              console.log(response);

              if (response.data == 1) {
                $('#wip_status' + candidateId).removeClass('btn-success');
                $('#wip_status' + candidateId).addClass('btn-danger');
                $('#wip_status' + candidateId).html('<i class="fa fa-pencil"></i> Declined');
                $('#viewBtn' + candidateId).prop('disabled', true);
                $.gritter.add({
                  title: "Done !!!",
                  text: response.message,
                  time: "",
                  close_icon: "entypo-icon-cancel s12",
                  icon: "icomoon-icon-checkmark-3",
                  class_name: "danger-notice"
                });
              }

              if (response.data == 0) {
                $('#wip_status' + candidateId).removeClass('btn-danger');
                $('#wip_status' + candidateId).addClass('btn-success');
                $('#wip_status' + candidateId).html('<i class="fa fa-pencil"></i> Activated');
                $('#viewBtn' + candidateId).prop('disabled', false);
                $.gritter.add({
                  title: "Done !!!",
                  text: response.message,
                  time: "",
                  close_icon: "entypo-icon-cancel s12",
                  icon: "icomoon-icon-checkmark-3",
                  class_name: "success-notice"
                });
              }
            }
          });
        },
        somethingElse: {
          text: 'Cancel!',
          btnClass: 'btn-red',
          keys: ['enter', 'shift'],
          cancel: function () {}
        }
      }
    });
  }

  /**
   *modalInfo() using in 
   *mobilizationModalView()
   *mobilizeActivityModalView()
   */
  function modalInfo(candidateId) {

    /**
     * Those modalbtn, modal and modalLabel are defined for inserting 
     * candidate id into modal button id and modal id
     */
    let modalbtn = $('#mobilizeModal').attr('data-target');
    let modal = $('#myModal').attr('id');
    let modalLabel = $('#myModalLabel').addClass('modalTitle');

    /** 
     * After click on mobilize button from datalist then this mobilizeModal button is 
     * triggered and showing the modal  
     */
    $('#mobilizeModal').trigger('click');

    /* Check mobilizeModalActivityViewFrom.blade.php where are defined those input
     * element and value is assigned from here 
     */

    $('input.projectId').val(projectId);
    $('input.candidateId').val(candidateId);
    $('input.mobilizeId').val(mobilizeId);
    $('#mobilizeCandidateId').val(candidateId);


    $('.modal-title').text('');
    let mobilizeName = $('#mobilizeName').val();
    $('.modal-title').text(mobilizeName);

    if (modalbtn != "" && modal != "") {

      $('#mobilizeModal').attr('data-target', '');
      $('#mobilizeModal').attr('data-target', '#myModal' + candidateId);
      $('.modal').attr('id', '');
      $('.modal').attr('id', 'myModal' + candidateId);
      $('.modalTitle').attr('id', 'modalTitle' + candidateId);

    }
  }

  /**
   * Form group hide/show
   */

  $('.medicalCall').hide();
  $('.medicalSent').hide();
  $('.remedical').hide();
  $('.ptaRequest').hide();
  $('.ptaReceive').hide();
  $('.flightNo').hide();
  $('.visaOnlineGroup').hide();
  $('.visaGroup').hide();
  $('.othersGroup').hide();
  $('.visaPrint').hide();
  $('.medicalSlip').hide();
  $('.pccSent').hide();
  $('.gtcSent').hide();
  $('.medicalStatus').hide();
  $('.release_candidate').hide();

  /** 
   * View modal 
   * Edit save candidate data
   * Blade:  mobilizeModalViewForm.blade.php
   */
  function mobilizationModalView(candidateId) {
    console.log('candidateId ' + candidateId + " mobilizeId " + mobilizeId);
    let modalViewType = $('#viewBtn' + candidateId).attr('btnType');
    modalInfo(candidateId);

    if (mobilizeId == "candidates") {
      $('.release_candidate').show();
      $('.modal-title').text('Release Candidate');
    }

    $.ajax({
      // mimeType: 'text/html; charset=utf-8',
      url: '{{ route('recruit.getMobilizationData') }}',
      type: 'GET',
      data: {
        projectId: projectId,
        candidateId: candidateId,
        mobilizeId: mobilizeId,
        projectCountryId:projectCountryId
      },
      processData: true,
      contentType: false,
      success: function (response) {

        switch (Number(mobilizeId)) {
          case 1:
            $('.medicalStatus').show();
            $('.medical_name').hide();
            $('.medical_gone_date').hide();
            $('.medical_code').hide();

            $('#mobilize_date').val(response.medical_gone_date == "0000-00-00 00:00:00" ? moment().format(
              'DD-MM-YYYY') : moment(response.medical_gone_date).format('DD-MM-YYYY'));

            $('.medicalStatus select').each(function (index, item) {
              var pushVal = $(this).attr('id');
              console.log(pushVal + " " + index);
              console.log(typeof (Number(response.medical_actual_status)));
              if (index == 0) {
                switch (Number(response.medical_actual_status)) {
                  case 1:
                    $(this).find('option:eq(1)').attr('selected', 'selected');
                    break;
                  case 2:
                    $(this).find('option:eq(2)').attr('selected', 'selected');
                    break;
                  case 3:
                    $(this).find('option:eq(3)').attr('selected', 'selected');
                    break;
                  case 4:
                    $(this).find('option:eq(4)').attr('selected', 'selected');
                    break;
                }
              }
            });
            break;
          case 2:
            $('.medicalStatus').show();
            $('.medicalStatus input').each(function (index, item) {
              var pushVal = $(this).attr('id');
              console.log(pushVal + " " + index);
              switch (index) {
                case 0:
                  $(this).val(response.medical_online_name);
                  break;
                case 1:
                  $(this).val(moment(response.medical_online_date).format('DD-MM-YYYY'));
                  break;
                case 2:
                  $(this).val(response.medical_online_code);
                  break;
              }
            });
            $('.medicalStatus select').each(function (index, item) {
              if (index == 0) {
                switch (Number(response.medical_online_status)) {
                  case 1:
                    $(this).find('option:eq(1)').prop('selected', true);
                    break;
                  case 2:
                    $(this).find('option:eq(2)').prop('selected', true);
                    break;
                  case 3:
                    $(this).find('option:eq(3)').prop('selected', true);
                    break;
                }
              }
            });
            // $('.medicalName').text("");
            // $('.medicalDate').text("");
            // $('.medicalCode').text("");
            // $('.medicalStatus').text("");
            $('.medical_name').hide();
            $('.medical_code').hide();
            $('.medical_status').hide();

            // $('.medicalName').text("Medical Online Name ").css({'font-size':'11px'});
            $('.medicalDate').text("Medical Online Date").css({
              'font-size': '11px'
            });
            // $('.medicalCode').text("Medical Online Code").css({'font-size':'11px'});
            // $('.medicalStatus').text("Medical Online Status").css({'font-size':'11px'});
            break;
          case 3:
            $('.medicalStatus').show();
            $('.medicalStatus input').each(function (index, item) {
              var pushVal = $(this).attr('id');
              console.log(pushVal + " " + index);
              switch (index) {
                case 0:
                  $(this).val(response.medical_self_name);
                  break;
                case 1:
                  $(this).val(moment(response.medical_self_date).format('DD-MM-YYYY'));
                  break;
                case 2:
                  $(this).val(response.medical_self_code);
                  break;
              }
            });

            $('.medicalStatus select').each(function (index, item) {
              if (index == 0) {
                switch (Number(response.medical_self_status)) {
                  case 1:
                    $(this).find('option:eq(1)').prop('selected', true);
                    break;
                  case 2:
                    $(this).find('option:eq(2)').prop('selected', true);
                    break;
                  case 3:
                    $(this).find('option:eq(3)').prop('selected', true);
                    break;
                }
              }
            });
            break;
          case 4:
          case 5:
          case 6:
          case 7:
          case 10:
          case 11:
          case 14:
          case 15:
          case 16:
          case 18:
          case 20:
          case 21:
          case 22:
          case 27:
          case 28:
            $('.othersGroup').show();

            $('.othersGroup input').each(function (index, item) {
              var pushVal = $(this).attr('id');
              console.log(pushVal + " " + index);
              if (index == 0) {
                switch (Number(mobilizeId)) {
                  case 4:
                    $(this).val(moment(response.fit_card_received_date).format('DD-MM-YYYY'));
                    break;
                  case 5:
                    $(this).val(moment(response.mofa_date).format('DD-MM-YYYY'));
                    break;
                  case 6:
                    $(this).val(moment(response.visa_document_sent_date).format('DD-MM-YYYY'));
                    break;
                  case 7:
                    $(this).val(moment(response.embassy_submission_date).format('DD-MM-YYYY'));
                    break;
                  case 10:
                    $(this).val(moment(response.visa_print_date).format('DD-MM-YYYY'));
                    $('.visaPrint').show();
                    $('#visa_no').val(response.visa_no);
                    break;
                  case 11:
                    $(this).val(moment(response.visa_attached_date).format('DD-MM-YYYY'));
                    $('.visaPrint').show();
                    $('#visa_no').val(response.visa_attached_no);
                    break;
                  case 14:
                    $(this).val(moment(response.fingerprint_date).format('DD-MM-YYYY'));
                    break;
                  case 15:
                    $(this).val(moment(response.bmet_date).format('DD-MM-YYYY'));
                    break;
                  case 16:
                    $(this).val(moment(response.gamca_gone_date).format('DD-MM-YYYY'));
                    break;
                  case 18:
                    $(this).val(moment(response.document_sent_date).format('DD-MM-YYYY'));
                    break;
                  case 20:
                    $(this).val(moment(response.pcc_received_date).format('DD-MM-YYYY'));
                    break;
                  case 21:
                    $(this).val(moment(response.gttc_received_date).format('DD-MM-YYYY'));
                    break;
                  case 22:
                    $(this).val(moment(response.smartcard_date).format('DD-MM-YYYY'));
                    break;
                  case 27:
                    $(this).val(moment(response.qvc_appointment_date).format('DD-MM-YYYY'));
                    break;
                  case 28:
                    console.log(response.approved_date);
                    $(this).val(moment(response.approved_date).format('DD-MM-YYYY'));
                    break;
                  default:
                    break;
                }
              }

            });
            $('.othersGroup label').text(" ");
            $('.othersGroup label').text(mobilizeName + " Date");
            console.log('mobilize from switch ' + mobilizeId);
            break;
          case 8:
            $('.visaGroup').show();
            break;
          case 9:
            $('.visaOnlineGroup').show();
            $('.visaGroup').hide();
            $('.visaExpiryDate').show();

            $('.visaOnlineGroup input[type=text]').each(function (index, item) {
              var pushVal = $(this).attr('id');
              console.log(pushVal + " " + index);
              switch (index) {
                case 0:
                  console.log('response.visa_online_date ' + response.visa_online_date);
                  $(this).val(moment(response.visa_online_date).format('DD-MM-YYYY'));
                  break;
                case 1:
                  $(this).val(response.visa_online_status_code);
                  break;
              }
              $('#visa_expiry_date').val(moment(response.visa_online_expiry_date).format('DD-MM-YYYY'));
            });
            break;
          case 12:
            $('.pccSent').show();
            $('.pccSent input').each(function (index, item) {
              var pushVal = $(this).attr('id');
              console.log(pushVal + " " + index);
              switch (index) {
                case 0:
                  $(this).val(response.pcc_serial_number);
                  break;
                case 1:
                  $(this).val(moment(response.pcc_date).format('DD-MM-YYYY'));
                  break;
              }
            });
            break;
          case 13:
            $('.gtcSent').show();
            $('.gtcSent input').each(function (index, item) {
              var pushVal = $(this).attr('id');
              console.log(pushVal + " " + index);
              switch (index) {
                case 0:
                  $(this).val(response.gttc_serial_number);
                  break;
                case 1:
                  $(this).val(moment(response.gttc_date).format('DD-MM-YYYY'));
                  break;
                case 2:
                  $(this).val(response.training_center_name);
                  break;
                case 3:
                  $(this).val(moment(response.training_start_date).format('DD-MM-YYYY'));
                  break;
              }
            });
            break;
          case 17:
            $('.medicalSlip').show();
            $('.medicalSlip input').each(function (index, item) {
              var pushVal = $(this).attr('id');
              console.log(pushVal + " " + index);
              switch (index) {
                case 0:
                  $(this).val(response.medical_slip_center);
                  break;
                case 1:
                  $(this).val(response.medical_slip_no);
                  break;
                case 2:
                  $(this).val(moment(response.medical_slip_date).format('DD-MM-YYYY'));
                  break;
              }
            });

            $('.medicalSlip select').each(function (index, item) {
              var pushVal = $(this).attr('id');
              console.log(pushVal + " " + index);
              if (index == 0) {
                switch (Number(response.medical_slip_status)) {
                  case 1:
                    $(this).find('option:eq(1)').prop('selected', true);
                    break;
                  case 2:
                    $(this).find('option:eq(2)').prop('selected', true);
                    break;
                }
              }
            });
            break;
          case 19:
            $('.remedical').show();
            $('.remedical input').each(function (index, item) {
              var pushVal = $(this).attr('id');
              console.log(pushVal + " " + index);
              switch (index) {
                case 0:
                  $(this).val(response.remedical_name);
                  break;
                case 1:
                  $(this).val(moment(response.remedical_date).format('DD-MM-YYYY'));
                  break;
              }
            });

            $('.remedical select').each(function (index, item) {
              if (index == 0) {
                switch (Number(response.remedical_actual_status)) {
                  case 0:
                    $(this).find('option:eq(1)').prop('selected', true);
                    break;
                    $(this).find('option:eq(2)').prop('selected', true);
                  case 1:
                    break;
                }
              }
            });
            break;
          case 23:
            $('.ptaRequest').show();
            $('.ptaRequest input').each(function (index, item) {
              var pushVal = $(this).attr('id');
              console.log(pushVal + " " + index);
              if (index == 0) {
                $(this).val(moment(response.pta_request_date).format('DD-MM-YYYY'));
              }
            });

            $('.ptaRequest select').each(function (index, item) {
              var pushVal = $(this).attr('id');
              console.log(pushVal + " " + index);
              if (index == 0) {
                switch (Number(response.pta_request_status)) {
                  case 1:
                    $(this).find('option:eq(1)').prop('selected', true);
                    break;
                  case 2:
                    $(this).find('option:eq(2)').prop('selected', true);
                    break;
                }
              }

            });
            break;
          case 24:
            $('.ptaReceive').show();
            $('.ptaReceive input').each(function (index, item) {
              var pushVal = $(this).attr('id');
              console.log(pushVal + " " + index);
              switch (index) {
                case 0:
                  $(this).val(moment(response.pta_received_date).format('DD-MM-YYYY'));
                  break;
                case 1:
                  $(this).val(moment(response.flight_date).format('DD-MM-YYYY'));
                  break;
                case 2:
                  $(this).val(response.flight_no);
                  break;
                case 3:
                  $(this).val(response.flight_time);
                  break;
                case 4:
                  $(this).val(response.transit_place);
                  break;
              }
            });
            break;
          case 25:
            $('.flightNo').show();
            $('.flight_status').show();
            $('.flight_briefing_date').hide();
            $('.flight_remarks').hide();
            $('.flightNo select').each(function (index, item) {
              var pushVal = $(this).attr('id');
              console.log(pushVal + " " + index);
              switch (index) {
                case 0:
                  $(this).find('option:eq(1)').prop('selected', true);
                  break;
                case 1:
                  $(this).find('option:eq(2)').prop('selected', true);
                  break;
              }
            });
            break;
          case 26:
            $('.flightNo').show();
            $('.flight_status').hide();
            $('.flightNo input').each(function (index, item) {
              var pushVal = $(this).attr('id');
              console.log(pushVal + " " + index);
              switch (index) {
                case 0:
                  $(this).val(moment(response.flight_briefing_date).format('DD-MM-YYYY'));
                  break;
              }
            });
            break;
          case 28:
            $('.mobilize-button-hide').hide();
            break;
          case 29:
            $('.medicalCall').show();
            $('.medicalCall input').each(function (index, item) {
              var pushVal = $(this).attr('id');
              console.log(pushVal + " " + index);
              switch (index) {
                case 0:
                  $(this).val(moment(response.medical_call_date).format('DD-MM-YYYY'));
                  break;
                case 1:

                  break;
                case 2:
                  $(this).val(response.gttc_serial_number);
                  break;
                case 3:
                  $(this).val(moment(response.gttc_date).format('DD-MM-YYYY'));
                  break;
                case 4:

                  break;
                case 5:
                  $(this).val(response.pcc_serial_number);
                  break;
                case 6:
                  $(this).val(moment(response.pcc_date).format('DD-MM-YYYY'));
                  break;
              }
            });
            break;
          case 30:
            $('.medicalSent').show();
            $('.medicalSent input').each(function (index, item) {
              var pushVal = $(this).attr('id');
              console.log(pushVal + " " + index);
              switch (index) {
                case 0:
                  $(this).val(moment(response.medical_sent_date).format('DD-MM-YYYY'));
                  break;
                case 1:

                  break;
                case 2:
                  $(this).val(response.medical_slip_center);
                  break;
                case 3:
                  $(this).val(response.medical_slip_no);
                  break;
                case 4:
                  $(this).val(moment(response.medical_slip_date).format('DD-MM-YYYY'));
                  break;
              }
            });

            $('.medicalSent select').each(function (index, item) {
              if (index == 0) {
                switch (Number(response.medical_slip_status)) {
                  case 1:
                    $(this).find('option:eq(1)').prop('selected', true);
                    break;
                  case 2:
                    $(this).find('option:eq(2)').prop('selected', true);
                    break;
                }
              }
            });
            break;
        }
      }
    });
  }


  /** 
   * Mobilize data update
   * Controller: MobilizationController
   * Data Table : ew_mobilization_master_table
   */

  $('#mobilizeBtn').on('click', function () {
    var candidateId = $('#mobilizeCandidateId').val();
    var url = "";
    if (
      mobilizeId == "candidates" ||
      mobilizeId == 1 ||
      mobilizeId == 2 ||
      mobilizeId == 3 ||
      mobilizeId == 4 ||
      mobilizeId == 5 ||
      mobilizeId == 6 ||
      mobilizeId == 7 ||
      mobilizeId == 8 ||
      mobilizeId == 9 ||
      mobilizeId == 10 ||
      mobilizeId == 11 ||
      mobilizeId == 12 ||
      mobilizeId == 13 ||
      mobilizeId == 14 ||
      mobilizeId == 15 ||
      mobilizeId == 16 ||
      mobilizeId == 17 ||
      mobilizeId == 18 ||
      mobilizeId == 19 ||
      mobilizeId == 20 ||
      mobilizeId == 21 ||
      mobilizeId == 22 ||
      mobilizeId == 23 ||
      mobilizeId == 24 ||
      mobilizeId == 25 ||
      mobilizeId == 26 ||
      mobilizeId == 27 ||
      mobilizeId == 28 ||
      mobilizeId == 29 ||
      mobilizeId == 30
    ) {

      url = '{{ route('recruit.general-page') }}';

    }

    var formData = new FormData($('#mobilizeFormData')[0]);
    formData.append('allowCompleteness', 1);
    formData.append('total_completed', mobilizeSerialId);
    $.ajax({
      url: url,
      type: 'post',
      data: formData,
      processData: false,
      contentType: false,
      success: function (data) {
        $.gritter.add({
          title: "Done !!!",
          text: data.messege,
          time: "",
          close_icon: "entypo-icon-cancel s12",
          icon: "icomoon-icon-checkmark-3",
          class_name: "success-notice"
        });
        // $('#panelId').load(location.href + ' #panelId');
        $('.mobilizationSingleListClick' + mobilizeId).trigger('click');
      },
      error: function (error) {}
    });
  });

  /**
   * DATE TIME FORMAT AND KEYPRESS OFF  
   */
  // $('.keypressOff').keypress(function(e) {
  //   return false
  // });

  $('.dateTimeFormat').datepicker({
    format: "dd-mm-yyyy"
  });

  /** 
   * This event happend when click on pagination selector 
   */
  $(document).on('click', '.paginate_button', function () {
    if (mobilizeId == 'candidates' || mobilizeId == 'finalizing') {
      $('.mobilize_button').hide();
      $('.selectionListButton').show();
      $('.restore').hide();
      // $('.mobilize_date_field_name').hide();
      // $('.mobilize_date_field').hide();
      $('.status_field').hide();
      $('.selectedTrade').show();
    }


    if (mobilizeId == 28) {
      $('.approved_button').hide();
      $('.restore').hide();
    }

  });

  /* This event is executed ater onchange filtering value */
  $(document).on('change', 'select[name=myTable_length]', function () {
    if (mobilizeId == 'candidates' || mobilizeId == 'finalizing') {
      $('.mobilize_button').hide();
      $('.selectionListButton').show();
      $('.restore').hide();
      // $('.mobilize_date_field_name').hide();
      // $('.mobilize_date_field').hide();
      $('.status_field').hide();
      $('.selectedTrade').show();
    }


    if (mobilizeId == 28) {
      $('.approved_button').hide();
      $('.restore').hide();
      $('.mobilize_button').show();
    }

  });

  /** This event work when searching data*/
  $(document).on('keyup', '#search', function () {
    if (mobilizeId == 'candidates' || mobilizeId == 'finalizing') {
      $('.mobilize_button').hide();
      $('.selectionListButton').show();
      $('.restore').hide();
      // $('.mobilize_date_field_name').hide();
      // $('.mobilize_date_field').hide();
      $('.status_field').hide();
      $('.selectedTrade').show();
    }

    if (mobilizeId == 28) {
      $('.approved_button').hide();
      $('.restore').hide();
      $('.mobilize_button').show();
    }

  });

  if (mobilizeId == 28) {
    $('.approved_button').hide();
    $('.restore').hide();
    $('.mobilize_button').show();
  }

  if (mobilizeId == 'candidates') {
    $('.mobilize_button').hide();
  }

  if (mobilizeId == 'finalizing') {
    $('.mobilize_button').hide();
  }

  /**TRADE INFO
   * selected-trade button was defined in mobilizationRoomData balde
   * code line no. 58 after .form-inline class.
   * Where event click action on Trade Info button then selected-trade modal button is 
   * triggered click and modal is opened.
   * Trade Info Button located above in javascript where you can see other three button 
   * completed, incomplete and WIP
   */
  function selectedTrade(projectId, mobilizeId, i) {
    $('#selected-trade').trigger('click');
    $('.btn-success').hide();

  }
</script>
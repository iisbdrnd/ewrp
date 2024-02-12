<style>
  #panelId .panel-heading .panel-controls .panel-refresh {
    display: none;
  }

  div.dataTables_filter label {

    /* float: right !important;
  margin-top: -34px !important; */
  }

  #myTable_paginate{
    display:none !important;
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
  .search_button
  {
    margin-right: 30px;
    height: 32px;
    margin-top: -2px;
  }
  .dataTables_info
  {
    display: none;
  }
  .pagination
  {
    float: right;
  }
  #paginate {
  list-style-type: none;
  margin: 0;
  padding: 0;
  overflow: hidden;
  float: right;
  }
  #paginate li {
    float: left;
    margin-right: 1%;
    margin-top: 6px;
    height: 28px;
    border: 1px solid;
    border-radius: 6px;
  }
  #paginate li a {
  display: block;
  color: black;
  text-align: center;
  padding: 16px;
  text-decoration: none;
  padding-top: 8px;
  padding-bottom: 9px;
  margin-top: -3px;
  }

  #paginate li a:hover {
    background-color: #4faede;
  }
  .ul_disable li a
  {
    cursor: not-allowed;
    pointer-events: all !important;
  }
  .ul_disable li a:hover
  {
    background: none !important;
  }
  #all_view
  {
    float: right;
  }
</style>
<div class="form-inline">
  <div class="row">
    <div class="col-md-12 col-lg-12">
      <div id="myTabContent2" class="tab-content">
        <div class="tab-pane fade active in" id=home2>
          <div class="form-inline" id="load_paginate_data">
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
                <?php
                  $i = $candidateDetails->perPage() * ($candidateDetails->currentPage() - 1); 
                ?>
              @forelse ($candidateDetails as $index => $candidateDetail)
                <?php 
                    $refereceInfo =  @Helper::getReference($candidateDetail->reference_id);
                    if(isset($refereceInfo->reference_name))
                    {
                      $referenceName = $refereceInfo->reference_name;
                    }else{
                      $referenceName = "";
                    }

                    if(isset($refereceInfo->dealer))
                    {
                      $delearName = $refereceInfo->dealer;

                      //$delearName = array();
                      //if(json_decode($refereceInfo->dealer, true) != null){
                          //$delearName = json_decode($refereceInfo->dealer, true);
                      //}
                    }else{
                      $delearName = "";
                    }
                    $total_completed = @Helper::getMobilizeStage($candidateDetail->ew_project_id, $candidateDetail->id);
                    $mcStatus = @Helper::mcStatus($projectId, $candidateDetail->id, $mobilizeId)['count'];
                    $currentDate = date('d-m-Y');
                    $i++;
                ?>
                <tr>
                  <td>{{ $i }}</td>
                  <td>{{ $candidateDetail->cv_number }}</td>
                  <td>
                    <a href="" menu-active="mobilization" class="ajax-link">{{ $candidateDetail->full_name }}
                    </a>
                  </td>
                  <td>{{ $candidateDetail->passport_no }}
                  @if($mobilizeId == 28)
                    {!! @Helper::passportExpireMobilization($candidateDetail->id) !!}
                  @endif
                  </td>
                  <td>{{ @Helper::getTradeName($candidateDetail->selected_trade)}}</td>
                  <td>{{ @Helper::getInterviewSalary($candidateDetail->ew_project_id, $candidateDetail->id) }}</td>
                  <td>{{$referenceName}}</td>
                  <td>
                  @if(!empty($delearName))
                    {{-- @foreach($delearName as $dealerId)
                        {{ @Helper::getDealerName($dealerId) }}
                    @endforeach --}}
                    {{ @Helper::getDealerName($delearName) }}
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
                    @if (@$mobilization_date[$i-1] == "0000-00-00 00:00:00")
                        {{$currentDate}}
                    @else 
                        {{Helper::dmyDateFormate(@$mobilization_date[$i-1])}}</p>
                    @endif
                    <p>
                      <span class="text-info">
                        @if (@$depMobilizeId != false)
                        {{@Helper::getMobilizationName(@$depMobilizeId)}}:
                        @else

                        @if ($mobilizeId == "candidates" || $mobilizeId == "finalizing")
                        @else
                        Selection Date:
                        @endif
                        @endif

                        @if ($mobilizeId == "candidates" || $mobilizeId == "finalizing")
                        @else
                      </span> ({{Helper::dmyDateFormate(@$prevDate[$i-1])}})
                      @endif
                    </p>
                    @if ($mobilizeId == 26)
                    <p class="text-primary"><strong>Flight Date:.
                        {{Helper::dmyDateFormate(@$flight_date[$i-1])}}</p>
                    @endif
                  </td>
                  <td class="status_field">
                    <?php
                      switch (@$medical_status[$i-1]) {
                        case 1:
                            echo "<strong style='margin:10px;' class='text-center btn-success btn btn-xs btn-default'>Fit</strong>";
                            break;
                        case 2:
                            echo "<strong style='margin:10px;' class='text-center btn-danger btn btn-xs btn-default'>Unfit</strong>";
                            break;
                        case 3:
                            echo "<strong style='margin:10px;' class='text-center btn-warning btn btn-xs btn-default'>Remedical</strong>";
                            break;
                        case 4:
                            echo "<strong style='margin:10px;' class='text-center btn-info btn btn-xs btn-default'>Fit Self</strong>";
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
                    @if($mobilizeId == 1)
                    {!! @Helper::medicalStatusMobilization($candidateDetail->id) !!}
                    @endif
                    @if($mobilizeId == 11)
                    {!! @Helper::visaAttachMobilization($candidateDetail->id) !!}
                    @endif

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
                    <?php 
                    $mobilizeInfo = @Helper::single_mobilization(@Helper::getMobilizeStage($candidateDetail->ew_project_id, $candidateDetail->id)); 
                    ?>
                    <span class="btn btn-xs btn-success">
                      <strong onclick="goMobilizeCandidateList('{{ $mobilizeInfo->id }}'); goGetMobilizeName('{{ $mobilizeInfo->id }}');"> {{ $mobilizeInfo->name }}</strong>
                    </span>

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
                  <td style="border:0px;">No data</td>
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
              <div class="col-md-10 col-xs-10 mt10 pull-right">
              @if($candidateDetails->total() > 20)
                <ul id="paginate" @if($candidateDetails->currentPage() == $candidateDetails->lastpage()) class="fast_forward ul_disable" @endif>
                  <li>
                      <a href="{{ $candidateDetails->url($candidateDetails->lastpage()) }}" aria-label="Last">
                          <span><i class="fa fa-fast-forward"></i></span>
                      </a>
                  </li>
                </ul>
                @endif

                {!! $candidateDetails->render() !!}

                @if($candidateDetails->total() > 20)
                <ul id="paginate" @if($candidateDetails->currentPage() == 1) class="fast_backward ul_disable" @endif>
                  <li>
                      <a href="{{ $candidateDetails->url(1) }}" aria-label="first">
                          <span><i class="fa fa-fast-backward"></i></span>
                      </a>
                  </li>
                </ul>
                @endif
              </div>
              <div class="col-md-2 col-xs-2 mt20">
                <span class="pull-left">Showing {{ $candidateDetails->currentPage() }} to {{ $i }} of  {!! $candidateDetails->total() !!}</span>
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
        <button type="button" title="" id="mobilizeBtn" data-dismiss="modal" class="btn btn-success">Save</button>
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
      searching: true,
      paging: false,
      buttons: [{
          extend: 'excelHtml5',
          footer: false,
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
          }
        },
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
            columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            modifier: {
                  page: 'all',
                  search: 'none'   
                }
          }
        }
      ], 
    });
    
  });


  /**
   *Both Id comes from MobilizationController
   *Method: mobilizationRoom
   */
  var projectId = '{{ $projectId }}';
  var mobilizeId = '{{ $mobilizeId }}';
  var projectCountryId = '{{ $projectCountryId }}';

  /** completed, WIP & incompleted filtering option is appending when datatable is loaded. */
  $('#myTable_filter').append(
    '<label><input id="search_input" placeholder="search"><button name="search" class="search_button btn btn-primary btn-md" type="button" onclick="custom_search({{$projectId}});">Go</button><button title="{{$projectId}}" class="incomplete_btn btn btn-md btn-danger mobilizeFiltering  ml5" data="2" onclick="getFilteringButtonText(mobilizeId, 2);"><i class="fa fa-hand-o-up"></i> Incomplete(<span id="incomplete_btn">{{@$incompleted}}</span>)</button><button title="{{$projectId}}" class="btn btn-md btn-success mobilizeFiltering completed_btn ml5" data="1" onclick="getFilteringButtonText(mobilizeId, 1)"><i class="fa fa-hand-o-up"></i> Completed(<span id="completed_btn">{{@$completed}}</span>)</button><button title="{{$projectId}}" class="wp_btn btn btn-md btn-primary mobilizeFiltering ml5" data="3" onclick="getFilteringButtonText(mobilizeId, 3)"><i class="fa fa-clock-o" aria-hidden="true"></i> WIP({{@$wip}})</button> <button type="button" data-toggle="modal" data-target=".bd-example-modal-lg" class="btn btn-md btn-warning  selectedTrade ml5" data="4" onclick="selectedTrade(projectId, mobilizeId, 4)"><i class="fa fa-clock-o" aria-hidden="true"></i> Trade Summary</button></label><label id="all_view"><button class="btn btn-info btn-md" type="button" onclick="custom_data({{$projectId}});">View All Records</button></label>'
    );

  var filering_status = '{{ $filering }}';
  $("#myTable_filter label:first").hide();

  $('#myTable_filter').on('click', '.incomplete_btn', function(){
    $('#mobilizeTemplate').html("");
    let project_id = $(this).attr('title');
    $('#mobilizeTemplate').append('<center><img style="width:70px; height:70px;" src="{{asset('public/img/loading-data.gif')}}"><center>');
    let filterData = $(this).attr('data');
    let url        = '{{ url('recruitment/mobilization/mobilizationRoomCandidateData') }}/'+project_id+'/{{$projectCountryId}}/'+mobilizeId+'/'+filterData;

      $.ajax({
      mimeType: 'text/html; charset=utf-8', 
      type: 'GET',
      url:url,
      data: {'mobilizeId':mobilizeId},
      processData: false,
      contentType: false,
      success: function(data){
        $('#mobilizeTemplate').load(url);
        $('#panelId').show();
      }
    });
  });
  
  $('#myTable_filter').on('click', '.completed_btn', function(){
    $('#mobilizeTemplate').html("");
    let project_id = $(this).attr('title');
    $('#mobilizeTemplate').append('<center><img style="width:70px; height:70px;" src="{{asset('public/img/loading-data.gif')}}"><center>');
    let filterData = $(this).attr('data');
    let url        = '{{ url('recruitment/mobilization/mobilizationRoomCandidateData') }}/'+project_id+'/{{$projectCountryId}}/'+mobilizeId+'/'+filterData;

      $.ajax({
      mimeType: 'text/html; charset=utf-8', 
      type: 'GET',
      url:url,
      data: {'mobilizeId':mobilizeId},
      processData: false,
      contentType: false,
      success: function(data){
        $('#mobilizeTemplate').load(url);
        $('#panelId').show();
      }
    });
  });

  $('#myTable_filter').on('click', '.wp_btn', function(){
    $('#mobilizeTemplate').html("");
    let project_id = $(this).attr('title');
    $('#mobilizeTemplate').append('<center><img style="width:70px; height:70px;" src="{{asset('public/img/loading-data.gif')}}"><center>');
    let filterData = $(this).attr('data');
    let url        = '{{ url('recruitment/mobilization/mobilizationRoomCandidateData') }}/'+project_id+'/{{$projectCountryId}}/'+mobilizeId+'/'+filterData;
      $.ajax({
      mimeType: 'text/html; charset=utf-8', 
      type: 'GET',
      url:url,
      data: {'mobilizeId':mobilizeId},
      processData: false,
      contentType: false,
      success: function(data){
        $('#mobilizeTemplate').load(url);
        $('#panelId').show();
      }
    });
  }); 

  // custom search function
  function custom_search(p_id)
  {
    var search_value = $('#search_input').val();
    let search_url  = '{{ url('recruitment/mobilization/mobilizationRoomCandidateData') }}/'+p_id+'/{{$projectCountryId}}/'+mobilizeId+'/'+filering_status;
    $.ajax({
        type: 'GET',
        url:search_url,
        data: {'search_data':search_value},
        success: function(data){
          $('#mobilizeTemplate').html(data);
          $('#search_input').val(search_value);
        }
    });
  }

  // custom data function
  function custom_data(p_id)
  {
    $('#mobilizeTemplate').html('');
    $('#mobilizeTemplate').append('<center><img style="width:70px; height:70px;" src="{{asset('public/img/loading-data.gif')}}"><center>');
    var data_value = 'all_data_value';
    let all_data_url  = '{{ url('recruitment/mobilization/mobilizationRoomCandidateData') }}/'+p_id+'/{{$projectCountryId}}/'+mobilizeId+'/'+filering_status;
    $.ajax({
        type: 'GET',
        url:all_data_url,
        data: {'all_data':data_value},
        success: function(data){
          $('#mobilizeTemplate').html(data);
          $('.ul_disable').hide();
        }
    });
  }

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
    $('.status_field').hide();
    $('.selectedTrade').show();

  }

  if (mobilizeId == 'candidates') {
    $('.selectedTrade').show();
  }


  $('ul.pagination a').on('click', function(e){
    e.preventDefault();
    if ($(e.target).is('.pagination a')) {
        // do stuff here
      var url = $(this).attr('href');
      $.get(url, function(data){
          $('#mobilizeTemplate').html(data);
      });  
    }
  });
  
  $('ul#paginate a').on('click', function(e){
    e.preventDefault();
      var url = $(this).attr('href');
      $.get(url, function(data){
          $('#mobilizeTemplate').html(data);
      });  
  });
  


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
  // console.log(mobilize_date);
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
                $('#wip_status'+candidateId).attr('onclick','wip_status('+candidateId+',"1")');
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
                $('#wip_status'+candidateId).attr('onclick','wip_status('+candidateId+',"0")');
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
  $('.visa_print_expire').hide();
  $('.visa_stamp_expire').hide();

  /** 
   * View modal 
   * Edit save candidate data
   * Blade:  mobilizeModalViewForm.blade.php
   */
  function mobilizationModalView(candidateId) {

    $('#msg').text('');
    $('#f_date_text').text('');
    $('#f_date').text('');
    $('#mobilizeBtn').removeClass('disabled');  
    $('#mobilizeBtn').css('pointer-events','unset');

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
            $('#mobilize_date').val(response.medical_gone_date == "0000-00-00 00:00:00" ? moment().format('DD-MM-YYYY') : moment(response.medical_gone_date).format('DD-MM-YYYY'));

            $('.medicalStatus #medical_status_date').val(response.medical_status_date == "0000-00-00 00:00:00" ? moment().format('DD-MM-YYYY') : moment(response.medical_status_date).format('DD-MM-YYYY'));

            $('.medicalStatus #medical_expire_date').val( response.medical_expire_date == "0000-00-00" ? moment(response.medical_status_date).add(90, 'days').format('DD-MM-YYYY') : moment(response.medical_expire_date).format('DD-MM-YYYY')  );


            if(response.medical_expire_date == undefined){
              $('.medicalStatus #medical_expire_date').val( moment().add(90, 'days').format('DD-MM-YYYY')  );
            }


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
                  $(this).val(response.medical_online_date == "0000-00-00 00:00:00" ? moment().format(
              'DD-MM-YYYY') : moment(response.medical_online_date).format('DD-MM-YYYY'));  
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
                  $(this).val(response.medical_self_date == "0000-00-00 00:00:00" ? moment().format(
              'DD-MM-YYYY') : moment(response.medical_self_date).format('DD-MM-YYYY'));  
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
            if(mobilizeId == 10)
            {
                $('.visa_print_expire').show();
                $('#visa_print_expiry_date').val(response.visa_print_expiry_date == "0000-00-00" ? moment().format('DD-MM-YYYY') : moment(response.visa_print_expiry_date).format('DD-MM-YYYY'));
            }
            if(mobilizeId == 11)
            {
                $('.visa_stamp_expire').show();
                $('#visa_stamp_expiry_date').val(response.visa_attach_expiry_date == "0000-00-00" ? moment().format('DD-MM-YYYY') : moment(response.visa_attach_expiry_date).format('DD-MM-YYYY'));
            }
            if(mobilizeId == 21 && response.gttc_received_status == 1)
            { 
              $('#gtc_rec_NA').hide();
              $('#gtc_rec_na_text').hide();
            }
            $('.othersGroup input').each(function (index, item) {
              var pushVal = $(this).attr('id');
              console.log(pushVal + " " + index);
              if (index == 0) {
                switch (Number(mobilizeId)) {
                  case 4:
                    $('#gtc_rec_NA').hide();
                    $('#gtc_rec_na_text').hide();
                    $(this).val(response.fit_card_received_date == "0000-00-00 00:00:00" ? moment().format('DD-MM-YYYY') : moment(response.fit_card_received_date).format('DD-MM-YYYY'));
                    break;
                  case 5:
                    $('#gtc_rec_NA').hide();
                    $('#gtc_rec_na_text').hide();
                    $(this).val(response.mofa_date == "0000-00-00 00:00:00" ? moment().format('DD-MM-YYYY') : moment(response.mofa_date).format('DD-MM-YYYY'));
                    break;
                  case 6:
                    $('#gtc_rec_NA').hide();
                    $('#gtc_rec_na_text').hide();
                    $(this).val(response.visa_document_sent_date == "0000-00-00 00:00:00" ? moment().format('DD-MM-YYYY') : moment(response.visa_document_sent_date).format('DD-MM-YYYY'));
                    break;
                  case 7:
                    $('#gtc_rec_NA').hide();
                    $('#gtc_rec_na_text').hide();
                    $(this).val(response.embassy_submission_date == "0000-00-00 00:00:00" ? moment().format('DD-MM-YYYY') : moment(response.embassy_submission_date).format('DD-MM-YYYY'));
                    break;
                  case 10:
                    $('#gtc_rec_NA').hide();
                    $('#gtc_rec_na_text').hide();
                    $(this).val(response.visa_print_date == "0000-00-00 00:00:00" ? moment().format('DD-MM-YYYY') : moment(response.visa_print_date).format('DD-MM-YYYY'));
                    $('.visaPrint').show();
                    $('#visa_no').val(response.visa_no);
                    break;
                  case 11:
                    $('#gtc_rec_NA').hide();
                    $('#gtc_rec_na_text').hide();
                    $(this).val(response.visa_attached_date == "0000-00-00 00:00:00" ? moment().format('DD-MM-YYYY') : moment(response.visa_attached_date).format('DD-MM-YYYY'));
                    $('.visaPrint').show();
                    $('#visa_no').val(response.visa_attached_no);

                    $('#visa_stamp_expiry_date').val( response.visa_attach_expiry_date == "0000-00-00" ? moment(response.visa_attached_date).add(90, 'days').format('DD-MM-YYYY') : moment(response.visa_attach_expiry_date).format('DD-MM-YYYY')  );

                    if(response.visa_attach_expiry_date == undefined){
                      $('#visa_stamp_expiry_date').val( moment().add(90, 'days').format('DD-MM-YYYY')  );
                    }


                    break;
                  case 14:
                    $('#gtc_rec_NA').hide();
                    $('#gtc_rec_na_text').hide();
                    $(this).val(response.fingerprint_date == "0000-00-00 00:00:00" ? moment().format('DD-MM-YYYY') : moment(response.fingerprint_date).format('DD-MM-YYYY'));
                    break;
                  case 15:
                    $('#gtc_rec_NA').hide();
                    $('#gtc_rec_na_text').hide();
                    $(this).val(response.bmet_date == "0000-00-00 00:00:00" ? moment().format('DD-MM-YYYY') : moment(response.bmet_date).format('DD-MM-YYYY'));
                    break;
                  case 16:
                    $('#gtc_rec_NA').hide();
                    $('#gtc_rec_na_text').hide();
                    $(this).val(response.gamca_gone_date == "0000-00-00 00:00:00" ? moment().format('DD-MM-YYYY') : moment(response.gamca_gone_date).format('DD-MM-YYYY'));
                    break;
                  case 18:
                    $('#gtc_rec_NA').hide();
                    $('#gtc_rec_na_text').hide();
                    $(this).val(response.document_sent_date == "0000-00-00 00:00:00" ? moment().format('DD-MM-YYYY') : moment(response.document_sent_date).format('DD-MM-YYYY'));
                    break;
                  case 20:
                    $('#gtc_rec_NA').hide();
                    $('#gtc_rec_na_text').hide();
                    $(this).val(response.pcc_received_date == "0000-00-00 00:00:00" ? moment().format('DD-MM-YYYY') : moment(response.pcc_received_date).format('DD-MM-YYYY'));
                    break;
                  case 21:
                    $(this).val(response.gttc_received_date == "0000-00-00 00:00:00" ? moment().format('DD-MM-YYYY') : moment(response.gttc_received_date).format('DD-MM-YYYY'));
                    break;
                  case 22:
                    $('#gtc_rec_NA').hide();
                    $('#gtc_rec_na_text').hide();
                    $(this).val(response.smartcard_date == "0000-00-00 00:00:00" ? moment().format('DD-MM-YYYY') : moment(response.smartcard_date).format('DD-MM-YYYY'));
                    break;
                  case 27:
                    $('#gtc_rec_NA').hide();
                    $('#gtc_rec_na_text').hide();
                    $(this).val(response.qvc_appointment_date == "0000-00-00 00:00:00" ? moment().format('DD-MM-YYYY') : moment(response.qvc_appointment_date).format('DD-MM-YYYY'));
                    break;
                  case 28:
                    $('#gtc_rec_NA').hide();
                    $('#gtc_rec_na_text').hide();
                    $(this).val(response.approved_date == "0000-00-00 00:00:00" ? moment().format('DD-MM-YYYY') : moment(response.approved_date).format('DD-MM-YYYY'));
                    break;
                  default:
                    break;
                }
              }

            });
            $('.othersGroup label').text(" ");
            $('.othersGroup label').text(mobilizeName + " Date");
            // console.log('mobilize from switch ' + mobilizeId);
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
                  $(this).val(response.visa_online_date == "0000-00-00 00:00:00" ? moment().format(
              'DD-MM-YYYY') : moment(response.visa_online_date).format('DD-MM-YYYY'));  
                  break;
                case 1:
                  $(this).val(response.visa_online_status_code);
                  break;
              }
              $('#visa_expiry_date').val(response.visa_online_expiry_date == "0000-00-00 00:00:00" ? moment().format(
              'DD-MM-YYYY') : moment(response.visa_online_expiry_date).format('DD-MM-YYYY'));  
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
                  $(this).val(response.pcc_date == "0000-00-00 00:00:00" ? moment().format(
              'DD-MM-YYYY') : moment(response.pcc_date).format('DD-MM-YYYY'));  
                  break;
              }
            });
            break;
          case 13:
            $('.gtcSent').show();
            if(response.gttc_completed == 1)
            { 
              $('#gtc_sent_NA').hide();
              $('#gtc_sent_na_text').hide();
            }else{

            }
            $('.gtcSent input').each(function (index, item) {
              var pushVal = $(this).attr('id');
              // console.log(pushVal + " " + index);
              switch (index) {
                case 0:
                  $(this).val(response.gttc_serial_number);
                  break;
                case 1:
                  $(this).val(response.gttc_date == "0000-00-00 00:00:00" ? moment().format(
              'DD-MM-YYYY') : moment(response.gttc_date).format('DD-MM-YYYY'));  
                  break;
                case 2:
                  $(this).val(response.training_center_name);
                  break;
                case 3:
                  $(this).val(response.training_start_date == "0000-00-00 00:00:00" ? moment().format(
              'DD-MM-YYYY') : moment(response.training_start_date).format('DD-MM-YYYY'));  
                  break;
              }
            });
            break;
          case 17:
            $('.medicalSlip').show();
            $('.medicalSlip input').each(function (index, item) {
              var pushVal = $(this).attr('id');
              // console.log(pushVal + " " + index);
              switch (index) {
                case 0:
                  $(this).val(response.medical_slip_center);
                  break;
                case 1:
                  $(this).val(response.medical_slip_no);
                  break;
                case 2:
                  $(this).val(response.medical_slip_date == "0000-00-00 00:00:00" ? moment().format(
              'DD-MM-YYYY') : moment(response.medical_slip_date).format('DD-MM-YYYY'));  
                  break;
              }
            });

            $('.medicalSlip select').each(function (index, item) {
              var pushVal = $(this).attr('id');
              // console.log(pushVal + " " + index);
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
              // console.log(pushVal + " " + index);
              switch (index) {
                case 0:
                  $(this).val(response.remedical_name);
                  break;
                case 1:
                  $(this).val(response.remedical_date == "0000-00-00 00:00:00" ? moment().format(
              'DD-MM-YYYY') : moment(response.remedical_date).format('DD-MM-YYYY'));  
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
              // console.log(pushVal + " " + index);
              if (index == 0) {
                $(this).val(response.pta_request_date == "0000-00-00 00:00:00" ? moment().format(
              'DD-MM-YYYY') : moment(response.pta_request_date).format('DD-MM-YYYY'));  
              }
            });

            $('.ptaRequest select').each(function (index, item) {
              var pushVal = $(this).attr('id');
              // console.log(pushVal + " " + index);
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
              // console.log(pushVal + " " + index);
              switch (index) {
                case 0:
                    $(this).val( response.pta_request_date == "0000-00-00 00:00:00" ? moment(response.pta_request_date).add(5, 'days').format('DD-MM-YYYY') : moment(response.pta_request_date).add(5, 'days').format('DD-MM-YYYY')  );

                    if(response.pta_request_date == undefined){
                      $(this).val( moment().add(5, 'days').format('DD-MM-YYYY')  );
                    }

                  break;
                case 1:
                  $(this).val(response.flight_date == "0000-00-00 00:00:00" ? moment().format(
              'DD-MM-YYYY') : moment(response.flight_date).format('DD-MM-YYYY'));  
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
              // console.log(pushVal + " " + index);
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
              // console.log(pushVal + " " + index);
              switch (index) {
                case 0:
                  $(this).val(response.flight_briefing_date == "0000-00-00 00:00:00" ? moment().format(
              'DD-MM-YYYY') : moment(response.flight_briefing_date).format('DD-MM-YYYY'));  
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
              // console.log(pushVal + " " + index);
              switch (index) {
                case 0:
                  $(this).val(response.medical_call_date == "0000-00-00 00:00:00" ? moment().format(
              'DD-MM-YYYY') : moment(response.medical_call_date).format('DD-MM-YYYY'));  
                  break;
                case 1:

                  break;
                case 2:
                  $(this).val(response.gttc_serial_number);
                  break;
                case 3:
                  $(this).val(response.gttc_date == "0000-00-00 00:00:00" ? moment().format(
              'DD-MM-YYYY') : moment(response.gttc_date).format('DD-MM-YYYY'));  
                  break;
                case 4:

                  break;
                case 5:
                  $(this).val(response.pcc_serial_number);
                  break;
                case 6:
                  $(this).val(response.pcc_date == "0000-00-00 00:00:00" ? moment().format(
              'DD-MM-YYYY') : moment(response.pcc_date).format('DD-MM-YYYY'));  
                  break;
              }
            });
            break;
          case 30:
            $('.medicalSent').show();
            $('.medicalSent input').each(function (index, item) {
              var pushVal = $(this).attr('id');
              // console.log(pushVal + " " + index);
              switch (index) {
                case 0:
                  $(this).val(response.medical_sent_date == "0000-00-00 00:00:00" ? moment().format(
              'DD-MM-YYYY') : moment(response.medical_sent_date).format('DD-MM-YYYY'));  
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
                  $(this).val(response.medical_slip_date == "0000-00-00 00:00:00" ? moment().format(
              'DD-MM-YYYY') : moment(response.medical_slip_date).format('DD-MM-YYYY'));  
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

        if(data.status == 1){

          $.gritter.add({
            title: "Done !!!",
            text: data.messege,
            time: "",
            close_icon: "entypo-icon-cancel s12",
            icon: "icomoon-icon-checkmark-3",
            class_name: "success-notice"
          });

          if(filering_status == 2) {
            $('#viewBtn'+candidateId).closest("tr").remove();
            var text_value = $('.mobilizeName'+mobilizeId).next().text();
            if (!text_value.length){ 
              $('.mobilizationSingleListClick'+mobilizeId).append('<span class="notification green">1</span>');
            }else{
              var int = parseInt(text_value);
              $('.mobilizeName'+mobilizeId).next().text(int+1);
            }
            var incom_text  = $('#myTable_filter #incomplete_btn').text();
            var incom_int = parseInt(incom_text);
            var com_text = $('#myTable_filter #completed_btn').text();
            var com_int = parseInt(com_text);

            $('#myTable_filter #incomplete_btn').text(incom_int-1);
            $('#myTable_filter #completed_btn').text(com_int+1);
          }
        }else{
          
          $.gritter.add({
            title: "Error !!!",
            text: data.messege,
            time: "",
            close_icon: "entypo-icon-cancel s12",
            icon: "icomoon-icon-checkmark-3",
            class_name: "error-notice"
          });
        
        }
        // $('#panelId').load(location.href + ' #panelId');
        /*$('.mobilizationSingleListClick' + mobilizeId).trigger('click');*/
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
      $('button.btn-success').hide();
  }


</script>
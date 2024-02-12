<style>
  .stats-custom-btn {
    /*border-radius: 2px;
    padding: 5px;
    background: #ffff !important;
    display: inline-block;
    position: center;
    box-shadow: 0px 2px 3px #264d5a !important;*/
  }

  .stats-btn .txt {
    color: #000000;
    font-family: sans-serif;
  }

  .custom-stats-btn {
    height: 63px !important;

    /* background-image:
    linear-gradient(
      to right, 
      #fffdc2,
      #fffdc2 15%,
      #d7f0a2 15%,
      #d7f0a2 85%,
      #fffdc2 85%
    ); */
    /* background-image: linear-gradient(to right top, #051937, #004d7a, #008793, #00bf72, #a8eb12); */
    /* background-image: url('https://enjoycss.com/webshots/hB_1.png'); */
  }

  .custom-circle {
    border-radius: 50%;
    /* background-image: url('https://enjoycss.com/webshots/hB_1.png'); */

  }

  .custom-stats-top {
    right: 21px;
    top: -19px;
  }

  .custom-stats-bottom {
    right: 21px;
    top: 71px;
  }

  .stats-btn .notification {
    padding: 0 6px 0px !important;

  }

  .page-content {
    background: #F1F1F1;
  }
</style>
<div class="form-inline">
<button id="selected-trade" url="getSelectedTrade?projectId={{$projectId}}"  style="display:none;" class="add-btn" view-type="modal" modal-size="medium">Trade Summary</button>
  <div class="row">
    <div class="col-lg-12 col-md-12 sortable-layout ui-sortable">
      <div class="panel panel-default chart">
        <div class="panel-body pt0 pb0">
          <div class="simple-chart">
            <div class="row mt10">
              <div class="col-sm-12">
                <div class="lead-details pb0 mb10">
                  <ul>
                    <li>
                        <a class="ajax-popover ajax-link hand" href="accounts/61"
                        menu-active="accounts" data-title="" load-popover="1" data-original-title="" title="">
                      </a>
                    </li>
                    <li>
                      <strong>Project : </strong>
                      <strong class="text-primary">{{ @Helper::projects($projectId)->project_name }}</strong>
                      |
                      <strong>Co-ordinator: </strong>
                      <strong class="text-primary">
                        {{ @Helper::assignUser(@Helper::projects($projectId)->coordinator) }}
                      </strong>
                      |
                      <strong> Date : </strong>
                      <?php
                         $start_date = @Helper::projects($projectId)->start_date;
                         $create_date = @Helper::projects($projectId)->created_at;
                         if($start_date == "0000-00-00")
                         {
                            $date_value= date('d-m-Y', strtotime($create_date));
                         }
                         else
                         {
                            $date_value= date('d-m-Y', strtotime($start_date));
                         }
                      ?>

                      <span class="opportunity-closed-date">{{ $date_value }}</span>
                      <!-- <span class="opportunity-closed-date">{{ Carbon\Carbon::parse(@Helper::projects($projectId)->created_at)->format('d-m-Y') }}</span> -->
                    </li>
                    <li><strong> Required Qty : </strong> {{ @Helper::projects($projectId)->required_quantity }}</li>
                    <li>
                      <strong style="color:#0080FC;">Accounts Transfer: </strong>
                      <strong class="opportunity-amount">{{ @Helper::accountTransferred(@Helper::projects($projectId)->id, 1) }}
                      </strong>
                    </li>
                    <li>
                      <strong style="color:#D95044;">Total Selected: </strong>
                      <strong>{{ @$total_selected }}</strong>
                    </li>
                    <li class="pull-right"> <a href="{{ url('recruitment#mobilization') }}" class="ajax-link"><i
                        class="fa fa-arrow-left"></i> Back</a></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row ">
    <div class="col-md-1 col-lg-1 col-sm-3 col-xs-12 text-center">
      <a href=# title="" id="total_candidate" onclick="mobilizeCandidateList('candidates')" candidatedata="candidates"
        style="width:100px; height: 100px;" class="stats-btn stats-custom-btn custom-circle">
        <i class="icon icomoon-icon-users" style=""></i>
        <span class="txt">Selection List</span>
        <p class="txt" style="color:#73B800;">
          <strong>
          {{ @$total_selected }} /  
          {{ @$total_candidates }}
          </strong>
      </p>
      </a>
    </div>

    <!-- Mobilization list show -->
    <div class="col-md-10 col-lg-10 col-sm-10 col-xs-10  text-center" style="">
      <?php $i=0;?>

      @foreach ($mobilizations as $mobilization)

      @foreach (json_decode($mobilization->mobilization_id) as $index => $mobilizingId)

      <?php $i++;?>
      <input type="hidden" name="mobilization" event="enter" class="data-search form-control" id="search-input{{ $i }}"
        value="{{$mobilizingId}}">
      <div class="col-lg-1 col-md-1 col-sm-3 col-xs-6 ml5 mt20" style="padding: 0 0 0 0!important;">
        <a href="#" style="{{ @Helper::single_mobilization($mobilizingId)->mobilize_action == 1
          ? 'border:2px solid #FF9800' : 'border:2px solid #9FC569' }}" name="mobilization" serialId='{{ $i }}'
          onclick="mobilizeCandidateList('{{ $mobilizingId }}', '{{$i}}'); getMobilizeName('{{ $mobilizingId }}');"

          event="click"
          valueFrom="#search-input{{ $i }}"
          class="data-search stats-btn stats-custom-btn custom-stats-btn ml5
          mobilizationSingleListClick{{ $mobilizingId }}"
          id="mobilize">

          <span class="txt pt10 getMobilizeNameById{{ $i }} mobilizeName{{ $mobilizingId }}"
            nextMobilizeId="{{ $mobilizingId }}">
            {{ @Helper::single_mobilization($mobilizingId)->name }} 
          </span>
          
          @if(@Helper::mobilizationCompleted($projectId, $projectCountryId, $mobilizingId) != 0)
          <span class="notification green">
            {{ @Helper::mobilizationCompleted($projectId, $projectCountryId, $mobilizingId) }}
          @endif

        </a>
      </div>
      @endforeach
      @endforeach
    </div>
    <div class="col-md-1 col-lg-1 col-sm-3 col-xs-12 text-center" style="left:-24px;">
      <a href="#" onclick="mobilizeCandidateList('finalizing')" candidatedata="finalizing" style="width:100px; height: 100px;"
        class="stats-btn stats-custom-btn custom-circle">
        <i class="icon icomoon-icon-airplane-2" style=""></i>
        <span class="txt">Finalizing</span>
        <p class="txt" style="color:#73B800;"><strong>{{ @$total_selected }} / {{ $finzalizing }}</strong></p>
      </a>
    </div> 
  </div>
  <!--END FOLLOW UP ROW-->
  <script type="text/javascript">
    $("select.select2").select2({
      placeholder: "Select"
    });
/**
* Panel Title append from this function
*/
  function getMobilizeName(mobilizeId){
    
    var name = $('.mobilizeName'+mobilizeId).text();
    $('.panel-title').text(name+" "+"Incomplete List").css({'color':'red'});
    
    var mobilizeName = {
      name:name
    }
    return mobilizeName;
  }

  function goGetMobilizeName(mobilizeId){

    var name = $('.mobilizeName'+mobilizeId).text();
    $('.panel-title').text(name+" "+"Complete List").css({'color':'green'});
    
    var mobilizeName = {
      name:name
    }
    return mobilizeName;
  }

  function getFilteringButtonText(mobilizeId, status){
    var getName = getMobilizeName(mobilizeId);
    // console.log(getName.name);
    if (status == 1) {
      // console.log('completed');
      $('.panel-title').text(getName.name+" "+"Completed List").css({'color':'#479D47'});
    }
    
    if (status == 2) {
      // console.log('incompleted');
      $('.panel-title').text(getName.name+" "+"Incomplete List").css({'color':'red'});

    }

    if (status == 3) {
      // console.log('wip');
      $('.panel-title').text(getName.name+" "+"WIP List").css({'color':'#3374AD'});
    }
  }

// $(document).ready(function(){
// $('#total_candidate').trigger('click');
// });
  </script>
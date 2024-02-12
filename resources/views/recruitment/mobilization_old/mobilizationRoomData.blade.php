<style>
    .stats-custom-btn{       
    /*border-radius: 2px;
    padding: 5px;
    background: #ffff !important;
    display: inline-block;
    position: center;
    box-shadow: 0px 2px 3px #264d5a !important;*/
    }

    .custom-circle{
        border-radius: 50%;
    }

    .custom-stats-top{
       right: 21px;
        top: -19px;
    } 
    .custom-stats-bottom{
        right: 21px;
        top: 71px;
    }
    .stats-btn .notification{
           padding: 0 6px 0px !important;

    }
</style>
<div class="form-inline">
    {{-- <div class="row datatables_header"> --}}
        <div class="row">
            <div class="col-lg-12 col-md-12 sortable-layout ui-sortable">
                <div class="panel panel-default chart">
                    <div class="panel-body pt0 pb0">
                        <div class="simple-chart">
                            <div class="row mt10">
                                <div class="col-sm-12">
                                    <div class="lead-details pb0 mb10">
                                        <ul>
                                            <li><strong><!-- Project Name: --></strong> <a class="ajax-popover ajax-link hand" href="accounts/61" menu-active="accounts" data-title="" load-popover="1" data-original-title="" title=""></a></li>
                                            <li><strong>Date: </strong> <span class="opportunity-closed-date">{{ date('d-m-Y') }}</span></li>
                                            <li><strong>Total Candidates:</strong> <span class="opportunity-amount">{{ $total_candidates }}
                                            </span></li>
                                            <li class="pull-right"> <a href="{{ url('recruitment#mobilization') }}" class="ajax-link"><i class="fa fa-arrow-left"></i> Back</a></li>
                                        </ul>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt10" style="">
            <div class="col-md-12 col-lg-12">
                <div class="col-md-1 col-lg-1 col-sm-1 col-xs-12 text-center">
                 <a href=# title="" id="total_candidate" onclick="mobilizeCandidateList('candidates')" candidatedata="candidates" style="width:123px; height: 123px; border-radius: 50%;"
                    class="stats-btn stats-custom-btn tipB mb20">
                    <i class="icon icomoon-icon-users" style="margin-top:30px;"></i>
                    <span class="txt text-danger">{{ @$total_candidates }}</span>
                    <span class="txt">Candidates</span>
                </a> 
            </div>
            <div class="col-md-8 col-lg-8 col-sm-8 col-xs-12 mt30 text-center ml30" style="">
                <div class="col-md-12">
                    <?php $i=0;?>
                    @foreach($mobilizations as $mobilization)
                        @foreach(json_decode($mobilization->mobilization_id) as $mobilizingId)
                        <?php $i++;?>
                            <input type="hidden" name="mobilization" event="enter" class="data-search form-control" id="search-input{{ $i }}" value="{{$mobilizingId}}">
                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-6 ml5"  style="padding: 0 2px 0 0!important;">
                                <a href="#"name="mobilization" @if(Helper::mobilizationCompleted($projectId, $mobilizingId) != 0 ||  $i == 1) onclick="mobilizeCandidateList('{{ $mobilizingId }}');" @else onclick="hideMobilizeList('{{ $mobilizingId }}');" @endif event="click" valueFrom="#search-input{{ $i }}"  class="data-search stats-btn stats-custom-btn mb20 ml5"> <br> 
                                <span class="txt pt10">{{ Helper::single_mobilization($mobilizingId)->name }}</span>
                                @if(Helper::mobilizationCompleted($projectId, $mobilizingId) != 0)
                                   <span class="notification green">
                                   {{ Helper::mobilizationCompleted($projectId, $mobilizingId) }} 
                                   </span>
                                @endif
                            </a>
                            </div>

                        @endforeach
                    @endforeach
                </div>
                <div class="col-md-12">
                     <?php $i=0;?>
                    @foreach($mobilizations as $mobilization)
                        @foreach(json_decode($mobilization->mobilization_id) as $mobilizingId)
                        <?php $i++;?>
                        <input type="hidden" name="mobilization" event="enter" class="data-search form-control" id="search-input{{ $i }}" value="{{$mobilizingId}}">
                         <div class="col-lg-1 col-md-1 col-sm-1 col-xs-6 ml5 mt20"  style="padding: 0 2px 0 0!important;">
                            <a href="#" name="mobilization" event="click" valueFrom="#search-input{{ $i }}"  class="data-search stats-btn custom-circle mb20 ml5">  <br>
                                <span class="txt pt10">{{ Helper::single_mobilization($mobilizingId)->name }}</span>
                            @if(Helper::currentCandidateQue($projectId, $mobilizingId)['current'] != 0)
                                <span onclick="mobilizeCandidateList('{{ $mobilizingId }}', '{{ $i }}');" id="currentId{{ $i }}" currentAttr="current" class="notification green custom-stats-top">
                                 {{ Helper::currentCandidateQue($projectId, $mobilizingId)['current'] }}
                                </span> 
                            @endif
                                <br>
                            @if(Helper::currentCandidateQue($projectId, $mobilizingId)['late'] != 0)
                                <span onclick="lateCandidateList('{{ $mobilizingId }}', '{{ $i }}');" id="lateId{{ $i }}" lateAttr="late" class="notification custom-stats-bottom">
                                 {{ Helper::currentCandidateQue($projectId, $mobilizingId)['late'] }}
                                </span>
                            @endif
                            </a>
                            </div>
                     @endforeach
                    @endforeach  
                </div>  
            </div>
        <div class="col-md-1 col-lg-1 col-sm-1 col-xs-12 text-center">
            <a href=# title="I`m with gradient" style="width:123px; height: 123px; border-radius: 50%;"
                class="stats-btn stats-custom-btn tipB mb20">
                <i class="icon icomoon-icon-users" style="margin-top:30px;"></i>
                <span class="txt text-danger">5</span>
                <span class="txt">Finalizing</span>
            </a>
        </div> 
        </div> 
    </div>     
</div>
<script type="text/javascript">
    $("select.select2").select2({
            placeholder: "Select"
        });
   
</script>


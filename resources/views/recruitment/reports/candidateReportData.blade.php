<div class="form-inline">
    <div class="row datatables_header">
        <div class="col-md-5 col-xs-12">
            <div class="input-group">
                <input name="search" event="enter" class="data-search form-control" id="search-input" value="{{@$search}}" kl_virtual_keyboard_secure_input="on" placeholder="Search">
                <span class="input-group-btn"><button name="search" event="click" valueFrom="#search-input" class="data-search btn btn-default" type="button">Go</button></span>
            </div>
        </div>
        <div class="col-md-7 col-xs-12">
           {{--  @if($access->create)
            <button class="add-btn btn btn-default pull-right btn-sm" view-type="modal" type="button" style="margin-left: 12px;"><i class="glyphicon glyphicon-plus mr5"></i>Add New</button>
            @endif --}}
            @include("perPageBox")
            <a href="{{ url('recruitment#reports') }}" style="margin-left: 12px;" class="ajax-link btn btn-sm btn-default pull-right mr5"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
</div>
<div id=myTabContent2 class=tab-content>
    <div class="tab-pane fade active in" id=home2>
        <div class="form-inline">
            <table cellspacing="0" class="responsive table table-striped table-bordered">
                <thead>
                    <tr>
                        <th width="1%">No.</th>
                       @foreach($cvLabels as $cvLabel)
                       <th data="1">{{ $cvLabel }}</th>
                       @endforeach
                       <th>Mobilization</th>
                   </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th width="1%">No.</th>
                       @foreach($cvLabels as $cvLabel)
                       <th data="1">{{ $cvLabel }}</th> 
                       @endforeach
                       <th>Mobilization</th>
                   </tr>
                </tfoot>
                <tbody>
                <?php $paginate = $reports; ?>
               <?PHP $i= 1;?>
                @foreach($reports as $key => $report) 
                <tr>
                    <td>{{ $i++ }}</td>
                    @foreach($cvFields as $objectName)
                        <td> 
                            @if($objectName == 'full_name')
                            <a href="" url="reports/view-report/{{ $projectId }}/{{ $reports->candidatesIds[$key] }}" view-type="modal" modal-size="medium" class="add-btn">{{ $report->$objectName }}</a>
                            @elseif($objectName == 'passport_status') 
                            {!!  @Helper::passportStatus($report->$objectName) !!}
                            @elseif($objectName == 'reference_id')
                            {{ @Helper::reference($report->$objectName)->reference_name }}
                            @elseif($objectName == 'country_id')
                            {{ @Helper::country($report->$objectName) }}  
                        @else
                             {{ $report->$objectName }}  
                        @endif
                    </td>

                    @endforeach

                    <td><a href="mobilization/single-candidate/{{ $projectId }}/{{  $reports->candidatesIds[$key] }}" menu-active="mobilization" class="ajax-link btn  btn-sm btn-default"><i class="fa fa-arrow-right"></i> Go To Mobilization</a></td>
         
                </tr>
                @endforeach 
                </tbody>
            </table>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    @include("pagination")
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    function plusMinus(i){
       

    }
</script>

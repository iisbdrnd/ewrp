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
                       <th>{{ $cvLabel }}</th>
                       @endforeach
                       <th>View Report</th>
                </thead>
                <tfoot>
                    <tr>
                        <th>No.</th>
                       @foreach($cvLabels as $cvLabel)
                       <th>{{ $cvLabel }}</th>
                       @endforeach
                        <th>View Report</th>
                    </tr>
                </tfoot>
                <tbody>
                <?php $paginate = $reports; ?>
                @if(count($reports)>0)  
                <?php $i=0;?>
                @foreach($reports as $report)
                <?php $i++;?>
                    <tr>
                        <td>{{$sn++}}</td>
                        <td><a href="#">{{$report->full_name}}</a></td>
                        <td>
                            <a  class="btn btn-xs btn-success" href="{{ url('recruitment#candiate-report/'.$report->ew_project_id.'/'.$report->id) }}">
                                <i class="fa fa-eye"></i> View Report
                            </a>
                        </td>
                    </tr>
                @endforeach
                @else    
                    <tr>
                        <td colspan="5" class="emptyMessage">Empty</td>
                    </tr>
                @endif
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
        console.log(i);
    }
</script>

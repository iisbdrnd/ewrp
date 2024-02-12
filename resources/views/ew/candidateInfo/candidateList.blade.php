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
                <button class="add-btn btn btn-default pull-right btn-sm" type="button" style="margin-left: 12px;"><i class="glyphicon glyphicon-plus mr5"></i>Add New</button>
            @endif --}}
            @include("perPageBox")
        </div>
    </div>
    <table cellspacing="0" class="responsive table table-striped table-bordered">
        <thead>
            <tr>
                <th width="1%">No.</th>
                <th data="30%" data="1">Project Name</th>
                <th data="2">Trades</th>
                <th width="10%" width="" data="3">Candidate</th>
        </thead>
        <tfoot>
            <tr>
                <th>No.</th>
                <th>Project Name</th>
                <th>Trades</th>
                <th>Candidate</th>
            </tr>
        </tfoot>
        <tbody>
        <?php $paginate = $ewProjects; ?>
        @if(count($ewProjects)>0) 
            @foreach($ewProjects as $ewProject)
                <tr>
                    <td>{{$sn++}}</td>
                    <td>{{$ewProject->project_name}}</td>
                    <td>{{ $ewProject->trades }}</td>
                    <td class="tac">
                        <a href="candidate-list/{{$ewProject->id}}" id="mobilizeActivityBlade"
                            menu-active="candidate-info" class="ajax-link hand btn btn-sm btn-default"><i class="fa fa-arrow-right"></i> Candidate</a>
                    </td>
                    <td>
                        
                    </td>
                </tr>
            @endforeach
        @else  
            <tr>
                <td colspan="10" class="emptyMessage">Empty</td>
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
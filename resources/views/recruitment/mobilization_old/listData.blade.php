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
                <th  data="60%">Project Name</th>
                <th width="30%" width="" data="1">Trades</th>
                <th width="9%" width="" data="1">Mobilization</th>
        </thead>
        <tfoot>
            <tr>
                <th>No.</th>
                <th>Project Name</th>
                <th>Trades</th>
                <th>Mobilization</th>
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
                        @if(@Helper::checkAssignuser($ewProject->id) == "notAllowed")
                       <button  
                            menu-active="mobilization" class="btn btn-sm btn-default " disabled> Not Allowed</button>
                        @else
                        <a href="mobilization/mobilization-room/{{$ewProject->id}}" 
                            menu-active="mobilization" class="ajax-link hand btn btn-sm btn-default"><i class="fa fa-arrow-right"></i> Mobilizing Room</a>
                        @endif    
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
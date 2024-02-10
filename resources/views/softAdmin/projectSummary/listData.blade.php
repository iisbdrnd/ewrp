<div class="form-inline">
    <div class="row datatables_header">
        <div class="col-md-5 col-xs-12">
            <div class="input-group">
                <input name="search" event="enter" class="data-search form-control" id="search-input" value="{{@$search}}" kl_virtual_keyboard_secure_input="on" placeholder="Search">
                <span class="input-group-btn"><button name="search" event="click" valueFrom="#search-input" class="data-search btn btn-default" type="button">Go</button></span>
            </div>
        </div>
        <div class="col-md-7 col-xs-12">
           
        </div>
    </div>
    <table cellspacing="0" class="responsive table table-striped table-bordered">
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th width="23%" data="1">Project ID</th>
                <th width="34%" data="2">Name</th>
                <th width="23%" data="3">Last Update</th>
                @if(Helper::adminAccess('projectRegistration.edit') || Helper::adminAccess('projectRegistration.destroy') || Helper::adminAccess('projectAccess', 0))
                <th class="text-center" width="15%">Action</th>
                @endif
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>No.</th>
                <th>Name</th>
                <th>Link</th>
                <th>Last Update</th>
                @if(Helper::adminAccess('projectRegistration.edit') || Helper::adminAccess('projectRegistration.destroy') || Helper::adminAccess('projectAccess', 0))
                <th class="text-center">Action</th>
                @endif
            </tr>
        </tfoot>
        <tbody>
        <?php $paginate = $projects; ?>
        @if(count($projects)>0)
            @foreach($projects as $project)
                <tr>
                    <td>{{$sn++}}</td>
                    <td>{{$project->project_id}}</td>
                    <td>{{$project->name}}</td>
                    <?php
                        $updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $project->updated_at);
                        $updated_at = $updatedAt->format('d/m/Y g:i A');
                    ?>
                    <td>{{$updated_at}}</td>
                    @if(Helper::adminAccess('projectRegistration.edit') || Helper::adminAccess('projectRegistration.destroy') || Helper::adminAccess('projectAccess', 0))
                    <td class="text-center">
                        @if(Helper::adminAccess('projectRegistration.edit'))
                            <i class="fa fa-edit" id="edit" data="{{$project->id}}"></i>
                        @endif 
                        @if(Helper::adminAccess('projectRegistration.destroy'))
                            <i class="fa fa-trash-o" id="delete" data="{{$project->id}}"></i>@endif
                        <br>
                        @if(Helper::adminAccess('projectAccess', 0))
                            <button url="projectAccess" data="{{$project->id}}" callBack="projectAccessView" class="go-btn btn btn-default btn-xs" type="button">Access</button>
                        @endif 
                        <button url="projectRenew" data="{{$project->id}}" class="go-btn btn btn-default btn-xs" type="button">Renew</button>
                        <button url="projectMailConfiguration" data="{{$project->id}}" class="go-btn btn btn-default btn-xs" type="button" style="margin-top:6px;">Mail Config.</button>
                        
                    </td>
                    @endif
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
            <?php $paginate = $projects; ?>
            @include("pagination")
        </div>
    </div>
</div>
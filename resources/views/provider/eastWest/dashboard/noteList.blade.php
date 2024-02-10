<div class="form-inline">
    <div class="row datatables_header">
        <div class="col-md-5 col-xs-12">
            <div class="input-group">
                <input name="search" event="enter" class="data-search form-control" id="search-input" value="{{@$search}}" placeholder="Search">
                <span class="input-group-btn"><button name="search" event="click" valueFrom="#search-input" class="data-search btn btn-default" type="button">Go</button></span>
            </div>
        </div>
        <div class="col-md-7 col-xs-12">
            @include("perPageBox")
        </div>
    </div>
</div>
<div id=myTabContent2 class=tab-content>
    <div class="tab-pane fade active in" id=home2>
        <div class="form-inline">
            <table cellspacing="0" class="responsive table table-striped table-bordered">
                <thead>
                <tr>
                    <th width="5%">No.</th>
                    <th data="1">Subject</th>
                    <th width="20%" data="2">Related To</th>
                    @if($access->edit || $access->destroy)
                        <th width="5%">Action</th>
                    @endif
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>No.</th>
                    <th>Subject</th>
                    <th>Related To</th>
                    @if($access->edit || $access->destroy)
                        <th>Action</th>
                    @endif
                </tr>
                </tfoot>
                <tbody>
                <?php $paginate = $crmActivitiesNote; ?>
                @if(count($crmActivitiesNote)>0)
                    @foreach($crmActivitiesNote as $crmActivitiesNote)
                        <tr>
                            <td>{{$sn++}}</td>
                            <td>@if($access->show)<a href="activities/note/{{$crmActivitiesNote->id}}" menu-active="activities/note" class="ajax-link hand">{{$crmActivitiesNote->subject}}</a>@else{{$crmActivitiesNote->subject}}@endif</td>
                            <td>{{$crmActivitiesNote->related_to_name}}</td>
                            @if($access->edit || $access->destroy)
                                <td class="text-center">@if($user_id==$crmActivitiesNote->assign_to) @if($access->edit)<i class="fa fa-edit" id="edit" data="{{$crmActivitiesNote->id}}"></i>@endif @if($access->destroy)<i class="fa fa-trash-o" id="delete" data="{{$crmActivitiesNote->id}}"></i>@endif @else @if($access->edit)<i class="fa fa-edit icon-disabled"></i>@endif @if($access->destroy)<i class="fa fa-trash-o icon-disabled"></i>@endif @endif</td>
                            @endif
                        </tr>
                    @endforeach
                @else    
                    <tr>
                        <td colspan="4" class="emptyMessage">Empty</td>
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

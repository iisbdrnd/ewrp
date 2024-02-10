<div class="form-inline">
    <div class="row datatables_header">
        <div class="col-md-5 col-xs-12">
            <div class="input-group">
                <input name="search" event="enter" class="data-search form-control" id="search-input" value="{{@$search}}" kl_virtual_keyboard_secure_input="on" placeholder="Search">
                <span class="input-group-btn"><button name="search" event="click" valueFrom="#search-input" class="data-search btn btn-default" type="button">Go</button></span>
            </div>
        </div>
        <div class="col-md-7 col-xs-12">
            @if($access->create)
            <button class="add-btn btn btn-default pull-right btn-sm" type="button" style="margin-left: 12px;"><i class="glyphicon glyphicon-plus mr5"></i>Add New</button>
            @endif
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
                        <th width="25%" data="1">Title</th>
                        <th width="50%">Description</th>
                        <th width="15%">Upload Photo</th>
                        @if($access->edit || $access->destroy)
                        <th width="5%">Action</th>
                        @endif
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No.</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Upload Photo</th>
                        @if($access->edit || $access->destroy)
                        <th>Action</th>
                        @endif
                    </tr>
                </tfoot>
                <tbody>
                <?php $paginate = $companyPolicies; ?>
                @if(count($companyPolicies)>0)  
                @foreach($companyPolicies as $companyPolicy)
                    <tr>
                        <td>{{$sn++}}</td>
                        <td>{{$companyPolicy->title}}</td>
                        <td>{{ Str::words(strip_tags($companyPolicy->description), 50) }}</td>
                        <td>
                            <button url="facilitiesGalleryImage?photo_gallery_id={{$companyPolicy->id}}" main-selector="mainDiv" clone-selector="cloneDiv" data-prefix="cl" panel-title="Invitations of {{$companyPolicy->title}} class" class="go-btn hand btn btn-info btn-xs" type="button" style="margin-bottom:6px;">Upload Photo</button> 
                        </td>
                        @if($access->edit || $access->destroy)
                        <td>@if($access->edit)<i class="fa fa-edit" id="edit" data="{{$companyPolicy->id}}"></i>@endif @if($access->destroy)<i class="fa fa-trash-o" id="delete" data="{{$companyPolicy->id}}"></i>@endif</td>
                        @endif
                    </tr>
                @endforeach
                @else    
                    <tr>
                        <td colspan="7" class="emptyMessage">Empty</td>
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

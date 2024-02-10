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
                        <th width="10%">Banner</th>
                        <th width="20%" data="1">Title</th>
                        <th width="10%">Button</th>
                        <th width="15%">Button Link</th>
                        <th width="35%">Description</th>
                        @if($access->edit || $access->destroy)
                        <th width="5%">Action</th>
                        @endif
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No.</th>
                        <th>Banner</th>
                        <th>Title</th>
                        <th>Button</th>
                        <th>Button Link</th>
                        <th>Description</th>
                        @if($access->edit || $access->destroy)
                        <th>Action</th>
                        @endif
                    </tr>
                </tfoot>
                <tbody>
                <?php $paginate = $banners; ?>
                @if(count($banners)>0)  
                @foreach($banners as $banner)
                    <tr>
                        <td>{{$sn++}}</td>
                        <td><img src="{{asset('public/uploads/banner/'.$banner->banner)}}" width="150" alt=""></td>
                        <td>{{ $banner->title }}</td>
                        <td>{{ $banner->btn_text }}</td>
                        <td>{{ $banner->btn_link }}</td>
                        <td>{!! $banner->description !!}</td>
                        @if($access->edit || $access->destroy)
                        <td>@if($access->edit)<i class="fa fa-edit" id="edit" data="{{$banner->id}}"></i>@endif @if($access->destroy)<i class="fa fa-trash-o" id="delete" data="{{$banner->id}}"></i>@endif</td>
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

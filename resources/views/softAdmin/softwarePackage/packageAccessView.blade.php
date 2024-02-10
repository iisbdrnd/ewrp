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
    <table cellspacing="0" class="responsive table table-striped table-bordered">
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th width="25%" data="1">Menu Name</th>
                <th width="25%" data="2">Link Name</th>
                <th width="25%" data="3">Module Name</th>
                <th width="20%" data="4">Access Date</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>No.</th>
                <th>Menu Name</th>
                <th>Link Name</th>
                <th>Module Name</th>
                <th>Access Date</th>
            </tr>
        </tfoot>
        <tbody>
        <?php $paginate = $packageAccess; ?>
        @foreach($packageAccess as $packageAccess)
            <tr>
                <td>{{$sn++}}</td>
                <td>{{$packageAccess->menu_name}}</td>
                <td>{{$packageAccess->link_name}}</td>
                <td>{{$packageAccess->module_name}}</td>
                <td>{{$packageAccess->updated_at}}</td>
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
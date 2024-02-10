<?php $panelTitle = "Service Category Chain"; $panelBodyClass = "data-list"; $dataPrefix=""; ?>
@include("panelStart")
<div class="form-inline">
    <div class="row datatables_header">
        <div class="col-md-5 col-xs-12">
            <div class="input-group">
                <input name="search" event="enter" class="data-search form-control" id="search-input" value="{{@$search}}" kl_virtual_keyboard_secure_input="on" placeholder="Search">
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
                        <th width="10%">No.</th>
                        <th width="70%" data="1">Category Name</th>
                        <th width="10%">Chain</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No.</th>
                        <th>Category Name</th>
                        <th>Chain</th>
                    </tr>
                </tfoot>
                <tbody>
                <?php $paginate = $chainCategories; ?>
                @if(count($chainCategories)>0)  
                @foreach($chainCategories as $category)
                    <tr>
                        <td>{{$sn++}}</td>
                        <td>{{$category->name}}</td>
                        <td style="text-align:center">
                            <a href="updateChain?cat_id={{$category->id}}" panel-title="Update Chain" class="btn btn-primary pull-right btn-xs ajax-link" style="padding:5px;float: left !important;">Create Your Chain </a>
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
@include("panelEnd")
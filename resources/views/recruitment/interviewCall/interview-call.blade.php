<?php $panelTitle = "Interview Call"; ?>

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
        </div>
    </div>
</div>
<div id=myTabContent2 class=tab-content>
    <div class="tab-pane fade active in" id=home2>
         {{-- <h1>{{ $ew_project_id }}</h1> --}}
        <div class="form-inline">
            <table cellspacing="0" class="responsive table table-striped table-bordered">
                <thead>
                    <tr>
                        <th width="5%">No.</th>
                        <th width="25%" data="1">Project Name</th>
                        <th data="2">Start Date</th>
                        <th width="15%" data="3">End Date</th>
                        <th width="15%" data="3">Time</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No.</th>
                        <th>Project Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Time</th>
                    </tr>
                </tfoot>
                <tbody>
                    <tr>
                       
                        
                    </tr>
                </tbody>
            </table>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                   
                </div>
            </div>
        </div>
    </div>
</div>
@include("panelEnd")
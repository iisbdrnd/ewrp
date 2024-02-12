@include("urlParaMeter")
@include("seqPaginateParaMeter")
<?php $tableTitle = "Interview Project List"; $loadUrl = "interviewListData"; $dataPrefix="ci"; ?>

<div id="mainDiv">
@include("dataListFrame")
</div>

<?php $panelTitle = ""; $panelBodyClass = "data-list"; $dataPrefix=""; ?>
<div id="cloneDiv" style="display:none;">
@include("panelStart")
{{csrf_field()}}
@include("panelEnd")
</div>

<div id="data-list-view"></div>

<div id="data-list-view-clone" style="display: none;">
    <div class=row>
        <div class="col-lg-12">
            <div data-prefix="dataList" id="dataListView" class="panel panel-default panelMove showControls toggle panelClose panelRefresh" seq-back="false">
                <div class="panel-heading">
                    <h4 class="panel-title">List</h4>
                </div>
                {{csrf_field()}}
                <div class="panel-body">
                    <div id="invite_course_details" style="margin-bottom: 14px;"></div>
                    <div class="data-list"></div>
                </div>
            </div>
        </div>
    </div>
</div>

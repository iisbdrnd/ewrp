@include("urlParaMeter")
<?php $dataTableId='adminList'; $tableTitle = "Admin List"; $loadUrl = "adminListData"; $refreshCallBack = 'refreshOthers';  ?>
@include("dataListFrame")

<div id="admin-access-view"></div>

<div id="admin-access-view-clone" style="display: none;">
    <div class=row>
        <div class="col-lg-12">
            <!-- col-lg-12 start here -->
            <div data-prefix="adminAccess" load-url="adminAccessView" id="adminAccessView" class="panel panel-default panelMove showControls toggle panelClose panelRefresh">
                <!-- Start .panel -->
                <div class="panel-heading">
                    <h4 class="panel-title">Admin Access</h4>
                </div>
                {{csrf_field()}}
                <div class="panel-body data-list"></div>
            </div>
            <!-- End .panel -->
        </div>
    </div>
</div>

<script type="text/javascript">
    function refreshOthers() {
        $('#admin-access-view').html('');
    }

    function adminAccessView() {
        var admin_id = $("#admin_id").val();
        $('#admin-access-view').html($('#admin-access-view-clone').html());
        $('#admin-access-view').find('#adminAccessView').attr("load-url", "adminAccessView?admin_id="+admin_id);
        $(".adminAccess-data-input").remove();
        loadDataTable($('#admin-access-view').find('#adminAccessView'), "admin", false);
    }
</script>

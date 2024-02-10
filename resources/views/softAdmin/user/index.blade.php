@include("urlParaMeter")

<?php $dataTableId='userList'; $tableTitle = "User List"; $loadUrl = "userList"; $refreshCallBack = 'refreshOthers';  ?>
@include("dataListFrame")

<div id="menu-view"></div>

<div id="user-access-view"></div>

<div id="user-access-view-clone" style="display: none;">
    <div class=row>
        <div class="col-lg-12">
            <!-- col-lg-12 start here -->
            <div data-prefix="userAccess" load-url="userAccessView" id="userAccessView" class="panel panel-default panelMove showControls toggle panelClose panelRefresh">
                <!-- Start .panel -->
                <div class="panel-heading">
                    <h4 class="panel-title">User Access</h4>
                </div>
                {{csrf_field()}}
                <div class="panel-body data-list"></div>
            </div>
            <!-- End .panel -->
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $("#userList").on('click', '.soft-module', function(){
            if(!($(this).hasClass('stats-btn-selected'))) {
                var module_id = $(this).attr('data');
                var user_id = $("#user_id").val();
                $(this).removeClass('pattern').addClass('stats-btn-selected');
                $('.soft-module').not(this).removeClass('stats-btn-selected').addClass('pattern');

                $.ajax({
                    url: "{{route('softAdmin.userAccessMenuView')}}",
                    data: {module_id: module_id, user_id: user_id},
                    success: function (data) {
                        dataFilter(data);
                        $('#menu-view').html(data);
                    }
                });
                userAccessView(module_id);
            }
        });
    });

    function refreshOthers() {
        $('#menu-view').html('');
        $('#user-access-view').html('');
    }

    function userAccessView(module_id) {
        if(!module_id) { $('#menu-view').html('') }
        var user_id = $("#user_id").val();
        var loadUrl = (module_id) ? "userAccessView?user_id="+user_id+"&module_id="+module_id : "userAccessView?user_id="+user_id;
        $('#user-access-view').html($('#user-access-view-clone').html());
        $('#user-access-view').find('#userAccessView').attr("load-url", loadUrl);
        $(".userAccess-data-input").remove();
        loadDataTable($('#user-access-view').find('#userAccessView'), "user", false);
    }
</script>
<style>
    #topLoader {
        width: 256px;
        height: 256px;
        margin-bottom: 32px;
    }

    #container {
        width: 274px;
        padding: 10px;
        margin-left: auto;
        margin-right: auto;
    }

    #animateButton {
        width: 256px;
    }
</style>

@include("urlParaMeter")

<?php $dataTableId='employeeList'; $tableTitle = "List of Employee"; $loadUrl = "employeeListData"; $refreshCallBack = 'refreshOthers';  ?>
@include("dataListFrame")

<div id="menu-view"></div>

<div id="emp-access-view"></div>

<div id="emp-access-view-clone" style="display: none;">
    <div class=row>
        <div class="col-lg-12">
            <!-- col-lg-12 start here -->
            <div data-prefix="employeeAccess" load-url="employeeAccessView" id="employeeAccessView" class="panel panel-default panelMove showControls toggle panelClose panelRefresh">
                <!-- Start .panel -->
                <div class="panel-heading">
                    <h4 class="panel-title">Employee Access</h4>
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
        $("#employeeList").on('click', '.soft-module', function(){
            if(!($(this).hasClass('stats-btn-selected'))) {
                var module_id = $(this).attr('data');
                var user_id = $("#user_id").val();
                $(this).removeClass('pattern').addClass('stats-btn-selected');
                $('.soft-module').not(this).removeClass('stats-btn-selected').addClass('pattern');

                $.ajax({
                    url: "{{route('provider.admin.employeeAccessMenuView')}}",
                    data: {module_id: module_id, user_id: user_id},
                    success: function (data) {
                        dataFilter(data);
                        $('#menu-view').html(data);
                    }
                });
                employeeAccessView(module_id);
            }
        });
    });

    function refreshOthers() {
        $('#menu-view').html('');
        $('#emp-access-view').html('');
    }

    function employeeAccessView(module_id) {
        if(!module_id) { $('#menu-view').html('') }
        var user_id = $("#user_id").val();
        var loadUrl = (module_id) ? "employeeAccessView?user_id="+user_id+"&module_id="+module_id : "employeeAccessView?user_id="+user_id;
        $('#emp-access-view').html($('#emp-access-view-clone').html());
        $('#emp-access-view').find('#employeeAccessView').attr("load-url", loadUrl);
        $(".employeeAccess-data-input").remove();
        loadDataTable($('#emp-access-view').find('#employeeAccessView'), "admin", false);
    }
</script>


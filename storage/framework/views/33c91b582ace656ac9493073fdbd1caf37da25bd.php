<?php echo $__env->make("urlParaMeter", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $dataTableId='projectList'; $tableTitle = "Project List"; $loadUrl = "projectList"; $refreshCallBack = 'refreshOthers';  ?>
<?php echo $__env->make("dataListFrame", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div id="menu-view"></div>

<div id="project-access-view"></div>

<div id="project-access-view-clone" style="display: none;">
    <div class=row>
        <div class="col-lg-12">
            <!-- col-lg-12 start here -->
            <div load-url="projectAccessView" id="projectAccessView" class="panel panel-default panelMove showControls toggle panelClose panelRefresh">
                <!-- Start .panel -->
                <div class="panel-heading">
                    <h4 class="panel-title">Project Access</h4>
                </div>
                <?php echo e(csrf_field()); ?>

                <div class="panel-body data-list"></div>
            </div>
            <!-- End .panel -->
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $("#projectList").on('click', '.soft-module', function(){
            if(!($(this).hasClass('stats-btn-selected'))) {
                var module_id = $(this).attr('data');
                var project_id = $("#project_id").val();
                $(this).removeClass('pattern').addClass('stats-btn-selected');
                $('.soft-module').not(this).removeClass('stats-btn-selected').addClass('pattern');

                $.ajax({
                    url: "<?php echo e(route('softAdmin.projectAccessMenuView')); ?>",
                    data: {module_id: module_id, project_id: project_id},
                    success: function (data) {
                        dataFilter(data);
                        $('#menu-view').html(data);
                    }
                });
                projectAccessView(module_id);
            }
        });
    });

    function refreshOthers() {
        $('#menu-view').html('');
        $('#project-access-view').html('');
    }

    function projectAccessView(module_id) {
        if(!module_id) { $('#menu-view').html('') }
        var project_id = $("#project_id").val();
        var loadUrl = (module_id) ? "projectAccessView?project_id="+project_id+"&module_id="+module_id : "projectAccessView?project_id="+project_id;
        $('#project-access-view').html($('#project-access-view-clone').html());
        $('#project-access-view').find('#projectAccessView').attr("load-url", loadUrl);
        loadDataTable($('#project-access-view').find('#projectAccessView'), "projectRegistration", false);
    }
</script><?php /**PATH /home/eastymap/public_html/resources/views/softAdmin/project/index.blade.php ENDPATH**/ ?>
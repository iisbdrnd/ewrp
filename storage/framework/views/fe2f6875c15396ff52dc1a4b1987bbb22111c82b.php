<?php echo $__env->make("urlParaMeter", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $tableTitle = "Gallery List"; $loadUrl = "galleryListData";?>
<?php echo $__env->make("dataListFrame", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div id="data-list-view"></div>

<div id="data-list-view-clone" style="display: none;">
    <div class="row" id="galleryDiv">
        <div class="col-lg-12">
            <div data-prefix="dataList" id="dataListView" class="panel panel-default panelMove showControls toggle panelClose panelRefresh" seq-back="false">
                <div class="panel-heading">
                    <h4 class="panel-title">List</h4>
                </div>
                <?php echo e(csrf_field()); ?>

                <div class="panel-body">
                    <div id="invite_course_details" style="margin-bottom: 14px;"></div>
                    <div class="data-list"></div>
                </div>
            </div>
        </div>
    </div>
</div><?php /**PATH /home/eastymap/public_html/resources/views/provider/eastWest/gallery/list.blade.php ENDPATH**/ ?>
<form type="create" id="productsForm" action="<?php echo e(route('provider.eastWest.provider.eastWest.setInterviewAction')); ?>" panelTitle="" class="form-load form-horizontal group-border stripped" callback="sendForTesting">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="job_id" value="<?php echo e($job->id); ?>">
    <div class=form-group>
        <label class="col-lg-3 col-md-3 control-label">Set Interview</label>
        <div class="col-lg-9 col-md-9">
            <select name="interview_status" data-fv-icon="false" class="select2 form-control ml0">
                <option value="">Select</option>
                <option value="1" <?php echo e(@$job->interview_status == 1 ? 'selected' : ''); ?>>Yes</option>
                <option value="0" <?php echo e(@$job->interview_status == 0 ? 'selected' : ''); ?>>No</option>
            </select>
        </div>
    </div>
    <div class=form-group>
        <label class="col-lg-3 col-md-3 control-label">Interview Date</label>
        <div class="col-lg-9 col-md-9">
            <input type="text" name="interview_date" value="<?php echo e(@$job->interview_date); ?>" class="form-control">
        </div>
    </div>
</form>
<script type="text/javascript">
    function sendForTesting() {
        bootbox.hideAll();
    }
    // $(".dtpicker").datepicker({format: 'yyyy-mm-dd'});
</script><?php /**PATH /home/eastymap/public_html/resources/views/provider/eastWest/jobOpening/setInterview.blade.php ENDPATH**/ ?>
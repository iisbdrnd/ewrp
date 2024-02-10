<?php $panelTitle = "Carrier Create"; ?>
<?php echo $__env->make("panelStart", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<form type="create" panelTitle="<?php echo e($panelTitle); ?>" class="form-load form-horizontal group-border stripped">
    <?php echo e(csrf_field()); ?>

    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Company Name</label>
        <div class="col-lg-8 col-md-6">
            <input required name="company_name" placeholder="Company Name" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-2 control-label required">Country</label>
        <div class="col-lg-8 col-md-9">
            <select required name="country_id" class="select2 form-control ml0">
                <option value="">Select</option>
                <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($country->id); ?>"><?php echo e($country->country); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-2 control-label required">Trade Name</label>
        <div class="col-lg-8 col-md-9">
            <select required name="job_category_id" class="select2 form-control ml0">
                <option value="">Select</option>
                <?php $__currentLoopData = $jobCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
    </div>
    
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Quantity</label>
        <div class="col-lg-8 col-md-6">
            <input name="quantity" placeholder="Quantity" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Salary</label>
        <div class="col-lg-8 col-md-6">
            <input name="salary" placeholder="Salary" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-2 control-label required">Food</label>
        <div class="col-lg-8 col-md-9">
            <input name="food_status" placeholder="Food Status" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-2 control-label required">Accommodation</label>
        <div class="col-lg-8 col-md-9">
            <input name="accommodation_status" placeholder="Accommodation Status" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Age</label>
        <div class="col-lg-8 col-md-6">
            <input required name="age" placeholder="Age" class="form-control">
        </div>
    </div>

    <div class="form-group">
        <label class="col-lg-2 col-md-2 control-label required">File Upload Type</label>
        <div class="col-lg-8 col-md-9">
            <select required id="archiveOrNot" class="select2 form-control ml0">
                <option value="1">Archive</option>
                <option value="0">Upload</option>
            </select>
        </div>
    </div>
    <div class="form-group" id="archiveData">
        <label class="col-lg-2 col-md-2 control-label required">Attachment Name</label>
        <div class="col-lg-8 col-md-9">
            <select required name="attachment_id" class="select2 form-control ml0">
                <option value="">Select</option>
                <?php $__currentLoopData = $attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($attachment->id); ?>"><?php echo e($attachment->attachment_real_name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
    </div>

    <div class=form-group id="uploadData" style="display: none;">
        <label class="col-lg-2 col-md-3 control-label">File</label>
        <div class="col-lg-6 col-md-5 row" id="file_attachment">
            <div class="col-lg-10 col-md-9">
                <button id="file_attachment_upload" input-prefix="fau" file-path="public/uploads/job_opening_attachments" _token="<?php echo e(csrf_token()); ?>" auto-remove="true" type="button" data-loading-text="Uploading..." class="btn btn-info"> Attach Files </button>
                <div id="status_file_attachment_upload" style="padding-top: 10px"> </div>
            </div>
            <div class="col-lg-10 col-md-9" style="margin-bottom: -10px;">
                <div id="attachment_area_file_attachment_upload" class="attachment_area"></div>
            </div>
        </div>
    </div>
    
    <div class="form-group">
        <div class="col-lg-offset-2">
            <button type="submit" class="btn btn-default ml15">Create</button>
        </div>
    </div>
</form>

<script>
    multipleFileUpload("file_attachment_upload", "fl");

    $(document).ready(function() { 
        $(".select2").select2({
            placeholder: "Select"
        });   
        $("#description").summernote({
            height: 150
        });
        $("#job_description").summernote({
            height: 100
        });

        $("#archiveOrNot").on('change', function () {
            if ($("#archiveOrNot").val() == 1) {
                $('#archiveData').show();
                $('#uploadData').hide();
            } else {
                $('#archiveData').hide();
                $('#uploadData').show();
            }
        });
    });
</script>

<?php echo $__env->make("panelEnd", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/eastymap/public_html/resources/views/provider/eastWest/jobOpening/create.blade.php ENDPATH**/ ?>
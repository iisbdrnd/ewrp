<?php $panelTitle = "Customer Update"; ?>


<form type="update" id="customersForm" panelTitle="<?php echo e($panelTitle); ?>" class="form-load form-horizontal group-border stripped">
    <?php echo e(csrf_field()); ?>

    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Country</label>
        <div class="col-lg-8 col-md-6">
            <select required name="country_id" class="select2 form-control ml0">
                <option value="">Select</option>
                <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($country->id); ?>" <?php echo e($country->id == $customer->country_id ? 'selected' : ''); ?>><?php echo e($country->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Name</label>
        <div class="col-lg-8 col-md-6">
            <input autofocus required name="name" placeholder="Name" value="<?php echo e($customer->name); ?>" class="form-control">
        </div>
    </div>

    <div class="form-group col-lg-12 col-md-12 col-xs-12">
        <label class="col-lg-2 col-md-3 control-label required">Image</label>
        <div class="col-lg-3 col-md-3">
            <div class="file-upload" input="image" filepath="public/uploads/customers" prefile="<?php echo e($customer->image); ?>" reqwidth="250" reqheight="122" ext="jpg,jpeg,png,gif"></div>
            <div class="pt5 s10">[Size: 250px * 122px]</div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2">
            <button type="submit" class="btn btn-default ml15">Update</button>
        </div>
    </div>
</form>

<script>
    $(".select2").select2({
        placeholder: "Select"
    }); 
</script><?php /**PATH /home/eastymap/public_html/resources/views/provider/eastWest/ourCustomers/update.blade.php ENDPATH**/ ?>
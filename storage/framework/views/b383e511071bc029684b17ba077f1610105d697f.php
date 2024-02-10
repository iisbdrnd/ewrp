
            <?php $__currentLoopData = $customerWiseCountries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li id="<?php echo e($country->id); ?>" >
                    <div class="box nostatus">
                        
                        <img src="<?php echo e(asset('public/uploads/customers/'.$country->image)); ?>" alt="<?php echo e($country->name); ?>">
                        <h4 style="width: 100%">
                            <?php echo e($country->name); ?>

                        </h4>
                    </div>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <?php /**PATH /home/eastymap/public_html/resources/views/provider/eastWest/ourCustomers/countryWiseCompany.blade.php ENDPATH**/ ?>
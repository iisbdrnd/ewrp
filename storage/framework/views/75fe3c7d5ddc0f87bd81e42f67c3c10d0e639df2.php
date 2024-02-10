<?php $__env->startSection('content'); ?>
<style>
    .short-info{
        padding: 15px 0px 18px;
    }

    .acordion-head {
        width: 100%;
        height: auto;
        background-color: #fafafa;
        padding: 10px 10px 10px;
        border: 0px;
    }
    .acordion-head img{
        height: 100%;
        width: 25px;
        margin-right: 5px;
    }
</style>
<!-- Hero Section -->
<div class="page-header" id="home">
    <div class="header-caption">
        <div class="header-caption-contant">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="header-caption-inner">
                            <h2>Our Clients</h2>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Hero Section -->
<!-- Team Section -->
<div class="team-area inner-padding">
    <div class="container">

        <div class="panel-group" id="choose-why">
            <?php if(count($country_ids) > 0): ?>
                <?php $__currentLoopData = $country_ids; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $country_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title acordion-head <?php if($key == 0): ?> active <?php endif; ?>" style="padding-bottom: 0px;">
                                <a data-toggle="collapse" data-parent="#choose-why" href="#choose-why<?php echo e($key); ?>">
                                    
                                    
                                    
                                    
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td width="5%">
                                                    <div class="panel-icon <?php if($key == 0): ?> active <?php endif; ?>">
                                                        <?php if($key == 0): ?>
                                                            <i class="fa fa-minus"></i>
                                                        <?php else: ?>
                                                            <i class="fa fa-plus"></i>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                                <td class="flag-icon-background flag-icon-<?php echo e(Str::lower($country_data->iso)); ?>" style="width: 5%;"></td>
                                                <td><?php echo e($country_data->country_name); ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </a>
                            </h4>
                        </div>
                        <div id="choose-why<?php echo e($key); ?>" class="panel-collapse collapse <?php if($key == 0): ?> in <?php endif; ?>">
                            <div class="panel-body">
                                <div class="row">
                                    <?php if(count($country_data->clients) > 0): ?>
                                        <?php $__currentLoopData = $country_data->clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="col-xs-12 col-sm-6 col-md-3" style="height: 100px; margin-bottom: 20px;">
                                            <div class="team-member" data-sr='enter'>
                                                <div class="team-header">
                                                    <img src="<?php echo e(asset('public/uploads/customers/'.$client->image)); ?>" width="180" alt="responsive img">
                                                </div>
                                                <div class="team-body" style="height: 100%;">
                                                    <div class="short-info">
                                                        <h3 class="member-name"><b><?php echo e($client->name); ?></b></h3>
                                                        <h4 class="designation"><?php echo e($client->address); ?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>        
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
            <?php endif; ?>
            

            

        </div>
        
    </div>
</div>
<!-- End Team Section -->
<?php $__env->stopSection(); ?>
<?php echo $__env->make('web.layouts.subDefault', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/eastymap/public_html/resources/views/web/ourClients.blade.php ENDPATH**/ ?>
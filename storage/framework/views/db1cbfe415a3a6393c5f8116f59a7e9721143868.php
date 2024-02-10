<?php $__env->startPush('css'); ?>

<?php $__env->stopPush(); ?>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<style>
    .google-map, #gmap{
        height: 180px !important;
    }
    .panel-icon .fa{
        margin-top: 5px;
    }
    .contact-details span{
        line-height: 28px;
    }
    .form-control2{
        border: 1px solid #ccc !important;
    }
    label{
        display: block !important;
    }
    .input-group-modify{

    }
    .contact-form li {
        margin-bottom: 9px !important;
    }
    @media(max-width: 667px){
        .google-map iframe{
            width: 240px !important;
        }
        label{
            display: none !important;
        }
    }
</style>
<?php $__env->startSection('content'); ?>
<!-- Hero Section -->
<div class="page-header" id="home">
    <div class="header-caption">
        <div class="header-caption-contant">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="header-caption-inner">
                            <h1 style="text-transform: inherit; font-size: 28px;">Contact Us</h1>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Hero Section -->

<!-- Contact Section -->
<div class="contact-area inner-padding6">
    <!-- Contact Form Section -->
    <div class="container">
        
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="form-area-row" data-sr='enter'>
                    
                    <div class="form-area col-sm-12 col-md-6">
                        <div class="form-area-title">
                            <h2>
                                <strong>Leave a Message </strong> <br>
                                <span style="font-size: 12px;">For inquiries or any other concerns you can send email to one of our staff.</span>
                            </h2>
                        </div>
                        <div class="cf-msg"></div>
                        <form class="form-inline" action="<?php echo e(route('contactUsAction')); ?>" method="post" id="cf">
                            <?php echo csrf_field(); ?>
                            <div class="row">
                                <ul class="contact-form">
                                    <li class="col-xs-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="email" class="col-md-3 control-label" style="text-align: right; margin-top: 7px; color: black;"> <b> Your name : </b></label>
                                            <div class="input-group-modify col-md-9" style="padding: 0px;">
                                                <input required type="text" id="name" name="name" class="form-control2" placeholder="Your name">
                                                <input type="hidden" id="actionUrl" name="actionUrl" value="<?php echo e(route('contactUsAction')); ?>" class="form-control2" placeholder="Your name">
                                            </div>
                                        </div>
                                    </li>
                                    <li class="col-xs-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="email" class="col-md-3 control-label" style="text-align: right; margin-top: 7px; color: black;"> <b> Your Email : </b></label>
                                            <div class="input-group-modify col-md-9" style="padding: 0px;">
                                                <input required type="email" class="form-control2" id="email" name="email" placeholder="Your Email">
                                            </div>
                                        </div>
                                    </li>
                                    <li class="col-xs-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="email" class="col-md-3 control-label" style="text-align: right; margin-top: 7px; color: black;"> <b> Contact No. : </b></label>
                                            <div class="input-group-modify col-md-9" style="padding: 0px;">
                                                <input required type="text" class="form-control2" id="contact" name="contact" placeholder="Contact No.">
                                            </div>
                                        </div>
                                    </li>
                                    <li class="col-xs-12">
                                        <div class="form-group">
                                            <label for="email" class="col-md-3 control-label" style="text-align: right; margin-top: 7px; color: black;"> <b> Subject : </b></label>
                                            <div class="input-group-modify col-md-9" style="padding: 0px;">
                                                <input required type="text" id="subject" class="form-control2" placeholder="Subject" name="subject">
                                            </div>
                                        </div>
                                    </li>
                                    <li class="col-xs-12">
                                        <div class="form-group">
                                            <label for="email" class="col-md-3 control-label" style="text-align: right; margin-top: 7px; color: black;"> <b> Write here : </b></label>
                                            <div class="input-group-modify col-md-9" style="padding: 0px;">
                                                <textarea required rows="3" class="form-control2 form-message" placeholder="Write here" id="message" name="message"></textarea>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="col-xs-9 col-lg-offset-3" style="padding: 7px;">
                                        <div class="form-group">
                                            <div class="g-recaptcha" data-sitekey="6LezWJUbAAAAAFYFpFeDeYSgw0QBdVqJAbczzbWV"></div>
                                            
                                            
                                        </div>
                                    </li>
                                </ul>
                                <div class="col-xs-12 text-center">
                                    <button type="submit" id="submit" name="submit" class="btn btn-default btn-form">SUBMIT</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        
                        <div class="choose-left foo" data-sr='enter'>
                            <div class="form-area-title">
                                <h2><strong>East West Human Resource Center Ltd.</strong> <br> RL-980 </h2>
                            </div>
                            <div class="panel-group" id="choose-why">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title active">
                                        <a data-toggle="collapse" data-parent="#choose-why" href="#main-office"><div class="panel-icon active"><i class="fa fa-minus"></i></div><strong>Head Office</strong></a>
                                    </h4>
                                    </div>
                                    <div id="main-office" class="panel-collapse collapse in">
                                        <div class="panel-body">
                                            <!-- Map area Section -->
                                            <div class="map-area">
                                                <div class="google-map">
                                                    
                                                  
                                                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3650.7056728732473!2d90.40092511498217!3d23.79349308456809!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c70e1d71eedb%3A0x1b3acb696d677c9c!2z4KaH4Ka44KeN4KafIOCmk-Cmr-CmvOCnh-CmuOCnjeCmnyDgprngpr_gpongpq7gp43gpq_gpr7gpqgg4Kaw4Ka_4Ka44KeL4Kaw4KeN4Ka4IOCmuOCnh-CmqOCnjeCmn-CmvuCmsCDgprLgpr_gpoM!5e0!3m2!1sbn!2sbd!4v1626172317129!5m2!1sbn!2sbd" width="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                                                </div>
                                            </div>
                                            <!-- End Map area Section -->
                                            <p class="contact-details">
                                                <span><i class="fa fa-phone" style="padding-right: 10px;"></i><?php echo e($contactUs->head_office_phone); ?></span> <br>
                                                <span><i class="fa fa-envelope" style="padding-right: 10px;"></i><?php echo e($contactUs->head_office_email); ?></span> <br>
                                                <span>
                                                    <i class="fa fa-map-marker" style="font-size: 20px; padding-right: 10px;"></i> <?php echo $contactUs->head_office_address; ?> </p>
                                                </span> 
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#choose-why" href="#dhaka-training-center"><div class="panel-icon"><i class="fa fa-plus"></i></div><strong> Training Center </strong></a>
                                        </h4>
                                    </div>
                                    <div id="dhaka-training-center" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <div class="map-area">
                                                <div class="google-map">
                                                    
                                                    <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d14593.001365440161!2d90.3767819!3d23.88074!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x337867766234e7d5!2z4KaH4Ka34KeN4KafIOCmk-Cnn-Cnh-Cmt-CnjeCmnyDgpp_gp43gprDgp4fgpqjgpr_gpoIg4KaP4Kao4KeN4KahIOCmleCmqOCmn-CnjeCmsOCmvuCmleCmn-CmsCDgppXgp4vgpq7gp43gpqrgpr7gpqjgp4Ag4Kay4Ka_4KaD!5e0!3m2!1sbn!2sbd!4v1626262603999!5m2!1sbn!2sbd" width="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                                                </div>
                                            </div>
                                            <p>
                                                <span><i class="fa fa-phone" style="padding-right: 10px;"></i><?php echo e($contactUs->training_center_phone); ?></span> <br>
                                                <span><i class="fa fa-envelope" style="padding-right: 10px;"></i><?php echo e($contactUs->training_center_email); ?></span> <br>
                                                <span>
                                                    <i class="fa fa-map-marker" style="font-size: 20px; padding-right: 10px;"></i> <?php echo $contactUs->training_center_address; ?> </p>
                                                </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Contact Form Section -->
</div>
<!-- End Contact Section -->

<?php $__env->stopSection(); ?>
<?php $__env->startPush('js'); ?>
<script src="https://maps.googleapis.com/maps/api/js"></script>
<script src="<?php echo e(asset('public/web/js/google-map.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('web.layouts.subDefault', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/eastymap/public_html/resources/views/web/contact.blade.php ENDPATH**/ ?>
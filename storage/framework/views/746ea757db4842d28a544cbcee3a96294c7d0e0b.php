<!DOCTYPE html>
<html lang=en>
    <head>
        <meta charset=utf-8>
        <title>Login | Application Frame</title>
        <!-- Mobile specific metas -->
        <meta name=viewport content="width=device-width,initial-scale=1,maximum-scale=1">
        <!-- Import google fonts - Heading first/ text second -->
        <link href="<?php echo asset('public/css/googleapis_open_sans.css'); ?>" rel=stylesheet type=text/css>
        <link href="<?php echo asset('public/css/googleapis_droid_sans.css'); ?>" rel=stylesheet type=text/css>
        <!-- Css files -->
        <link type="text/css" rel="stylesheet" href="<?php echo asset('public/css/main.min.css'); ?>" />
        <link type="text/css" rel="stylesheet" href="<?php echo asset('public/css/custom.css'); ?>" />
        <link type="text/css" rel="stylesheet" href="<?php echo asset('public/plugins/validation/css/formValidation.min.css'); ?>" />
        <!-- Fav and touch icons -->
        <link rel="icon" href="<?php echo e(url('web/img/favicon.ico')); ?>" type="image/png">
    </head>

    <body class=login-page>
        <div id=header class="animated fadeInDown">
            <div class=row>
                <div class=navbar>
                    <div class="container text-center">
                        <a class=navbar-brand href="#" style="margin-right: 0;"><img src="<?php echo asset('public/img/logo_inno.png'); ?>" style="display: inline; height: 50px;"></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Start login container -->
        <div class="container login-container">
            <div class="login-panel panel panel-default plain animated bounceIn">
                <div class=panel-body>
                    <form class="form-horizontal mt0" action="<?php echo e(route('provider.login')); ?>" method="post" id=login-form role=form>
                    <?php echo csrf_field(); ?>

                        <div class=form-group>
                            <div class=col-md-12>
                                <label for="">Email:</label>
                            </div>
                            <div class=col-lg-12>
                                <div class="input-group input-icon">
                                    <input name="email" id="email" type="email" class="form-control" placeholder="Email" data-fv-trigger="blur" autofocus>
                                    <span class=input-group-addon><i class="icomoon-icon-user s16"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class=form-group>
                            <div class=col-md-12>
                                <label for="">Password:</label>
                            </div>
                            <div class=col-lg-12>
                                <div class="input-group input-icon">
                                    <input type=password name=password id=password class=form-control placeholder="password">
                                    <span class=input-group-addon>
                                        <i class="icomoon-icon-lock s16"></i>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="col-lg-12 text-center text-denger"><?php echo session('error'); ?></div>
                        </div>
                        <div class="form-group mb0">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-8">
                                <div class=checkbox-custom>
                                    <input type=checkbox name=remember id=remember value=option>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mb25">
                                <button class="btn btn-default pull-right" type=submit>Login</button>
                                <!--<button class="btn btn-default pull-right mr15" type=reset>Reset</button>-->
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class=container>
            <div class=footer>
                <p class=text-center>Copyrights &copy; <?php echo e(date('Y')); ?> <a href="http://iisbd.com" class="color-blue strong" target=_blank> INNOVATION</a>. All right reserved !!!</p>
            </div>
        </div>
        <!-- Javascripts -->
        <script src="<?php echo asset('public/js/libs/jquery-2.1.1.min.js'); ?>"></script>
        <script src="<?php echo asset('public/js/libs/jquery-ui-1.10.4.min.js'); ?>"></script>
        <script src="<?php echo asset('public/plugins/validation/js/formValidation.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo asset('public/plugins/validation/js/framework/bootstrap.js'); ?>"></script>

        <script>
        $(document).ready(function() {
            $('#login-form').formValidation({
                fields: {
                    email: {
                        validators: {
                            notEmpty: {
                                message: 'The email is required'
                            }
                        }
                    },
                    password: {
                        validators: {
                            notEmpty: {
                                message: 'The password is required'
                            }
                        }
                    }
                }
            });
        });
        </script>
    </body>
</html><?php /**PATH /home/eastymap/public_html/resources/views/provider/login.blade.php ENDPATH**/ ?>
<!DOCTYPE html>
<!--[if IE 9]>
<html class="ie" lang="en">
<![endif]-->
<html lang="en">

<head>
    <!-- Basic Page Needs  -->
    <meta charset="utf-8">
    <title>East West</title>
    <meta name="description" content="Established in 2006 East west Human Resource Center Ltd, which is based in Dhaka, Bangladesh, provides manpower to clients of EWHRCL. East West recognizes the scarcity of skilled workers and the growing demand by employers for Skilled categories. East west has designed solutions targeting specific client requirements and is taking pro-active steps to recruiting.EWHRCL has well trained Representatives in all 64 Districts and Industrial areas to source experienced candidates.">
    <meta name="author" content="East West Human Resource Center Ltd">
    <meta name="keywords" content="recruiting agency, HumanResource, EastWest ">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name=”robots” content="index, follow">

    <!-- Mobile Specific Mega  -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700|Raleway:400,600,700" rel="stylesheet">
    <!-- CSS -->
    
    <link rel="stylesheet" href="<?php echo e(asset('public/web/css/bootstrap.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('public/web/css/font-awesome.min.css')); ?>">
    <!--Owl Carousel CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('public/web/css/owl.carousel.min.css')); ?>">
    <!-- Animated CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('public/web/css/animate.min.css')); ?>">
    <!-- Prettyphoto Css -->
    <link rel="stylesheet" href="<?php echo e(asset('public/web/css/prettyPhoto.css')); ?>">
    <!-- Theme CSS-->
    <link rel="stylesheet" href="<?php echo e(asset('public/web/css/default.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('public/web/css/typography.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('public/web/css/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('public/web/css/responsive.css')); ?>">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/png" href="<?php echo e(asset('public/web/img/favicon.png')); ?>">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/2.3.1/css/flag-icon.min.css" rel="stylesheet"/>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <?php echo $__env->yieldPushContent('css'); ?>
    <style>
        .navbar-default .navbar-nav > li > a{
            color: #000;
            font-family: arial;
            font-weight: 600;
        }
        .container-big{
            width:95%;
        }

        /* Start */
        .social-kit {
            position: fixed;
            z-index: 9999;
            top: 30%;
            right: 0%;
            /* -webkit-transform: translateY(-50%);
                    transform: translateY(-50%); */
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            /* -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
                -ms-flex-direction: column;
                    flex-direction: column; */
        }

        .social-kit a {
        text-decoration: none;
        height: 170px;
        padding: 5px;
        margin-bottom: 5px;
        font-size: 20px;
        color: #ffffff;
        /* width: 210px; */
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: end;
            -ms-flex-pack: end;
                justify-content: flex-end;
        -webkit-box-align: center;
            -ms-flex-align: center;
                align-items: center;
        -webkit-transform: translateX(50px);
                transform: translateX(50px);
        }

        .social-kit a.notice_board {
            background: #07aca7;
        }

        .social-kit a:hover {
        -webkit-transform: translateX(0);
                transform: translateX(0);
        -webkit-transition: all 0.5s linear;
        transition: all 0.5s linear;
        }

        .social-kit a i {
        padding-left: 20px;
        font-size: 30px;
        animation: letszoom 3s linear alternate-reverse infinite;
        }

        @-webkit-keyframes letszoom {
        from {
            -webkit-transform: scale(0.5);
                    transform: scale(0.5);
        }
        to {
            -webkit-transform: scale(1);
                    transform: scale(1);
        }
        }

        @keyframes  letszoom {
        from {
            -webkit-transform: scale(0.5);
                    transform: scale(0.5);
        }
        to {
            -webkit-transform: scale(1);
                    transform: scale(1);
        }
        }
        
        /* #291d5b */
    </style>
</head>

<body data-spy="scroll" data-target="#scroll-menu" data-offset="65">
    <!-- Preloader -->
    <div class="preloader-wrap">
        <div class="preloader-inside">
            <div class="spinner spinner-1">
                <img src="<?php echo e(asset('public/web/img/logo.png')); ?>" alt="responsive img">
            </div>
        </div>
    </div>
    <!-- End Preloader -->
    <!-- Scroll Top Button -->
    <a href="#home" class="smoothscroll">
        <div class="scroll-top"><i class="fa fa-angle-up"></i></div>
    </a>
    <!-- End Scroll Top Button -->

    
    
    <!-- End Nav Section -->

    <?php echo $__env->make('web.includes.nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php echo $__env->yieldContent('content'); ?>

    <?php echo $__env->make('web.includes.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- Ends Copyright Section -->
    <!-- Scripts -->
    <script src="<?php echo e(asset('public/web/js/jquery-2.1.1.min.js')); ?>"></script>
    <script src="<?php echo e(asset('public/web/js/bootstrap.min.js')); ?>"></script>
    <script src="<?php echo e(asset('public/web/js/scrollreveal.min.js')); ?>"></script>
    <script src="<?php echo e(asset('public/web/js/jquery.waypoints.min.js')); ?>"></script>
    <script src="<?php echo e(asset('public/web/js/jquery.counterup.min.js')); ?>"></script>
    <script src="<?php echo e(asset('public/web/js/owl.carousel.min.js')); ?>"></script>
    <!-- Isotope Js -->
    <script src="<?php echo e(asset('public/web/js/isotope.pkgd.min.js')); ?>"></script>
    <script src="<?php echo e(asset('public/web/js/isotope_custom.js')); ?>"></script>
    <!-- Masonary Js -->
    <script src="<?php echo e(asset('public/web/js/masonry.pkgd.min.js')); ?>"></script>
    <!-- Prettyphoto js -->
    <script src="<?php echo e(asset('public/web/js/jquery.prettyPhoto.js')); ?>"></script>
    <script src="<?php echo e(asset('public/web/js/theme.js')); ?>"></script>

    <?php echo $__env->yieldPushContent('js'); ?>
</body>

</html>
<?php /**PATH /home/eastymap/public_html/resources/views/web/layouts/default.blade.php ENDPATH**/ ?>
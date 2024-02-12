<!DOCTYPE html>
<html lang=en>
    <head>
        <meta charset=utf-8>
        <title>Login | Application Frame</title>
        <!-- Mobile specific metas -->
        <meta name=viewport content="width=device-width,initial-scale=1,maximum-scale=1">
        <!-- Import google fonts - Heading first/ text second -->
        <link href="{!! asset('public/css/googleapis_open_sans.css') !!}" rel=stylesheet type=text/css>
        <link href="{!! asset('public/css/googleapis_droid_sans.css') !!}" rel=stylesheet type=text/css>
        <!-- Css files -->
        <link type="text/css" rel="stylesheet" href="{!! asset('public/css/main.min.css') !!}" />
        <link type="text/css" rel="stylesheet" href="{!! asset('public/css/custom.css') !!}" />
        <link type="text/css" rel="stylesheet" href="{!! asset('public/plugins/validation/css/formValidation.min.css') !!}" />
        <!-- Fav and touch icons -->
        <link rel="icon" href="{{url('public/img/favicon.png')}}" type="image/png">
        <style type="text/css">
        body.login-page > .login-container {
            width: 800px;
        }
        body.login-page .login-panel {
            margin-top: 250px;
        }
        </style>
    </head>

    <body class=login-page>
        <div id=header class="animated fadeInDown">
            <div class=row>
                <div class=navbar>
                    <div class="container text-center">
                        <!--<a class=navbar-brand href="{{url()}}" style="margin-right: 0;"><img src="{!! asset('public/img/logo_inno.png') !!}" style="display: inline;"></a>-->
                        <a class=navbar-brand href="{{url()}}" style="margin-right: 0;"><img src="{!! asset('public/img/logo.png') !!}" style="display: inline; height: 50px;"></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Start login container -->
        <div class="container login-container">
            <div class="login-panel panel panel-default plain animated bounceIn">
                <div class="panel-body" style="text-align: center;">
                    <h1 style="color:#f00">Your subscription time is expired</h1>
                    <h2 style="color:#f00">Please contact with vendor</h2>
                </div>
            </div>
        </div>
        <!-- Javascripts -->
        <script src="{!! asset('public/js/libs/jquery-2.1.1.min.js') !!}"></script>
        <script src="{!! asset('public/js/libs/jquery-ui-1.10.4.min.js') !!}"></script>
        <script src="{!! asset('public/plugins/validation/js/formValidation.min.js') !!}"></script>
        <script type="text/javascript" src="{!! asset('public/plugins/validation/js/framework/bootstrap.js') !!}"></script>

    </body>
</html>

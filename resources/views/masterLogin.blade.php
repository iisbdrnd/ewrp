<!DOCTYPE html>
<html lang=en>
    <head>
        <meta charset=utf-8>
        <title>Login | Application Frame</title>
        <!-- Mobile specific metas -->
        <meta name=viewport content="width=device-width,initial-scale=1,maximum-scale=1">
        <!-- Import google fonts - Heading first/ text second -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,700" rel=stylesheet type=text/css>
        <link href="http://fonts.googleapis.com/css?family=Droid+Sans:400,700" rel=stylesheet type=text/css>
        <!-- Css files -->
        <link type="text/css" rel="stylesheet" href="{!! asset('public/css/main.min.css') !!}" />
        <link type="text/css" rel="stylesheet" href="{!! asset('public/css/custom.css') !!}" />
        <link type="text/css" rel="stylesheet" href="{!! asset('public/plugins/validation/css/formValidation.min.css') !!}" />
        <!-- Fav and touch icons -->
        <link rel="icon" href="{{url('public/img/favicon.png')}}" type="image/png">
    </head>

    <body class=login-page>
        <div id=header class="animated fadeInDown">
            <div class=row>
                <div class=navbar>
                    <div class="container text-center">
                        <a class=navbar-brand href="{{url()}}" style="margin-right: 0;"><img src="{!! asset('public/img/logo_inno.png') !!}" style="display: inline;"></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Start login container -->
        <div class="container login-container">
            <div class="login-panel panel panel-default plain animated bounceIn">
                <div class=panel-body>
                    <form class="form-horizontal mt0" action="{{route('login')}}" method="post" id=login-form role=form>
                    {!! csrf_field() !!}
                        <div class=form-group>
                            <div class=col-md-12>
                                <label for="">Username:</label>
                            </div>
                            <div class=col-lg-12>
                                <div class="input-group input-icon">
                                    <input name=username id=username class=form-control placeholder="username">
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
                            <span class="col-lg-12 help-block text-right">
                                <a href=lost-password.html>Forgot password ?</a>
                            </span>
                            <div class="col-lg-12 text-center text-denger">{!!session('error')!!}</div>
                        </div>
                        <div class="form-group mb0">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-8">
                                <div class=checkbox-custom>
                                    <input type=checkbox name=remember id=remember value=option>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-4 mb25">
                                <button class="btn btn-default pull-right" type=submit>Login</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class=container>
            <div class=footer>
                <p class=text-center>&copy;2015 Copyright INNOVATION Information System. All right reserved !!!</p>
            </div>
        </div>
        <!-- Javascripts -->
        <script src=http://code.jquery.com/jquery-2.1.1.min.js></script>
        <script>window.jQuery || document.write('<script src="{!! asset('public/js/libs/jquery-2.1.1.min.js') !!}">\x3C/script>')</script>
        <script src=http://code.jquery.com/ui/1.10.4/jquery-ui.js></script>
        <script>window.jQuery || document.write('<script src="{!! asset('public/js/libs/jquery-ui-1.10.4.min.js') !!}">\x3C/script>')</script>
        <script src="{!! asset('public/plugins/validation/js/formValidation.min.js') !!}"></script>
        <script type="text/javascript" src="{!! asset('public/plugins/validation/js/framework/bootstrap.js') !!}"></script>

        <script>
        $(document).ready(function() {
            $('#login-form').formValidation({
                fields: {
                    username: {
                        validators: {
                            notEmpty: {
                                message: 'The username is required'
                            },
                            stringLength: {
                                min: 5,
                                max: 30,
                                message: 'The username must be more than 6 and less than 30 characters long'
                            },
                            regexp: {
                                regexp: /^[a-zA-Z0-9_\.]+$/,
                                message: 'The username can only consist of alphabetical, number, dot and underscore'
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
</html>
<!DOCTYPE html>
<html lang=en>
    <head>
        <meta charset=utf-8>
        <title>Participate | Application Frame</title>
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
        <link rel=icon href=img/ico/favicon.ico type=image/png>
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
                    <form class="form-horizontal mt0" action="{{route('login')}}" method="post" id=perticipate-form role=form>
                    {!! csrf_field() !!}
                        <div class=form-group>
                            <div class=col-md-12>
                                <label for="">Name:</label>
                            </div>
                            <div class=col-lg-12>
                                <div class="input-group input-icon">
                                    <input name=name id=name class=form-control placeholder="Name">
                                </div>
                            </div>
                        </div>
                        <div class=form-group>
                            <div class=col-md-12>
                                <label for="">Email:</label>
                            </div>
                            <div class=col-lg-12>
                                <div class="input-group input-icon">
                                    <input name=email id=email class=form-control placeholder="Email">
                                </div>
                            </div>
                        </div>
                        <div class=form-group>
                            <div class=col-md-12>
                                <label for="">Message:</label>
                            </div>
                            <div class=col-lg-12>
                                <div class="input-group input-icon">
                                    <textarea name="career[message]" class="form-control" tabindex="4"placeholder="Write your message"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb0">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-8">
                                <div class=checkbox-custom>
                                    <input type=checkbox name=remember id=remember value=option>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-4 mb25">
                                <button class="btn btn-default pull-right" type=submit>Send</button>
                            </div>
                        </div>
                    </form>
                </div>
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
            $('#perticipate-form').formValidation({
                fields: {
                    name: {
                        validators: {
                            notEmpty: {
                                message: 'The name is required'
                            },
                            stringLength: {
                                min: 5,
                                max: 30,
                                message: 'The username must be more than 6 and less than 30 characters long'
                            },
                            regexp: {
                                regexp: /^[a-zA-Z0-9_\.]+$/,
                                message: 'The name can only consist of alphabetical, number, dot and underscore'
                            }
                        }
                    },
                    email: {
                        validators: {
                            notEmpty: {
                                message: 'The email is required'
                            }
                        }
                    }
                }
            });
        });
        </script>
    </body>
</html>
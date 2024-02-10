<!DOCTYPE html>
<html lang="zxx" class="no-js">
    <!-- Mirrored from preview.colorlib.com/theme/carrental/ by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 21 Mar 2021 10:50:14 GMT -->
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="shortcut icon" href="img/fav.html">
        <meta name="author" content="codepixer">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta charset="UTF-8">
        <title>Car Rentals</title>
        <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,400,300,500,600,700" rel="stylesheet">
        <link rel="stylesheet" href="{{asset('public/web/css/linearicons.css')}}">
        <link rel="stylesheet" href="{{asset('public/web/css/font-awesome.min.css')}}">
        <link rel="stylesheet" href="{{asset('public/web/css/bootstrap.css')}}">
        <link rel="stylesheet" href="{{asset('public/web/css/magnific-popup.css')}}">
        <link rel="stylesheet" href="{{asset('public/web/css/nice-select.css')}}">
        <link rel="stylesheet" href="{{asset('public/web/css/animate.min.css')}}">
        <link rel="stylesheet" href="{{asset('http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css')}}">
        <link rel="stylesheet" href="{{asset('public/web/css/owl.carousel.css')}}">
        <link rel="stylesheet" href="{{asset('public/web/css/main.css')}}">
    </head>
    <body>
        {{-- INCLUDE MENUBER --}}
        @php($banner=\App\Model\TubBanner_web::valid()->first())
        
        <section class="banner-area relative" id="home" style="background: url({{url('public/uploads/banner/'.$banner->banner)}}) center !important; background-size: cover;">
            <div class="overlay overlay-bg"></div>
            <div class="container">
                <div class="row fullscreen d-flex align-items-center justify-content-center">
                    <div class="col-lg-5  col-md-6 header-right">
                        <h4 class="text-white pb-30">Enter your registered mail address</h4>
                        
                        <form id="" class="form" role="form" action="{{route('forgetPasswordAction')}}" method="post" autocomplete="off">
                            @csrf
                            @if (isset($prevUrl))
                            <input type="hidden" value="{{$prevUrl}}" name="prevUrl">
                            @endif
                            <div id="errorMsgDiv">
								<div id="responseMsg"></div>
							</div>
                            <div class="from-group">
                                <input class="form-control txt-field" type="email" name="email" placeholder="Email address">
                            </div>
                            <div id="errorMsg">
                                @if(Session::get('msgType') == 0)
                                <div class="text-danger" style="">{{Session::get('message')}}</div>
                                @else
                                <div class="text-success" style="">{{Session::get('message')}}</div>
                                @endif
                            </div>
                            {{-- <div class=" row">
                                <div class="col-md-6 offset-md-6 text-right">
                                    <a href="{{ route('login') }}">Login</a>
                                </div>
                            </div> --}}
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-default btn-lg btn-block text-center text-uppercase">Login</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        
        <script src="{{asset('public/web/js/vendor/jquery-2.2.4.min.js')}}"></script>
        <script src="{{asset('public/web/js/vendor/bootstrap.min.js')}}"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script src="{{asset('public/web/js/jquery.ajaxchimp.min.js')}}"></script>
        <script src="{{asset('public/web/js/jquery.sticky.js')}}"></script>
        <script src="{{asset('public/web/js/mail-script.js')}}"></script>
        <script src="{{asset('public/web/js/main.js')}}"></script>
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-23581568-13')}}"></script>
        <script src="{!! asset('public/plugins/validation/js/formValidation.min.js') !!}"></script>

        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            
            gtag('config', 'UA-23581568-13');
        

            $(document).ready(function () {
                $('#loginForm').formValidation({
                    fields: {
                        email: {
                            validators: {
                                notEmpty: {
                                    message: 'The email is required'
                                },
                                stringLength: {
                                    min: 5,
                                    max: 30,
                                    message: 'The email must be more than 6 and less than 30 characters long'
                                },
                                regexp: {
                                    regexp: /^[a-zA-Z0-9_\.]+@/,
                                    message: 'The email can only consist of alphabetical, number, dot and underscore'
                                }
                            }
                        }
                    }
                });
            });
            
        </script>
    </body>
    <!-- Mirrored from preview.colorlib.com/theme/carrental/ by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 21 Mar 2021 10:50:27 GMT -->
</html>
{{-- <link type="text/css" rel="stylesheet" href="{!! asset('public/assets/css/styles.css') !!}" />
<script src="{{asset('public/web/{!! asset('public/assets/js/html5.js') !!}"></script> --}}

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
        <title>Tubingen Chemicals(BD) Ltd. </title>
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
        <link rel="icon" type="image/png" href="{{asset('public/web/img/fav.png')}}" sizes="16x16">
        
        <style>
            .footer-area p{
                color: #777 !important;
            }
        </style>

        <script src="{{asset('public/web/js/vendor/jquery-2.2.4.min.js')}}"></script>
    </head>
    <body>
        {{-- INCLUDE MENUBER --}}
        @include('web.includes.nav')

        @yield('content')

        @include('web.includes.footer')
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="{{asset('public/web/js/vendor/bootstrap.min.js')}}"></script>
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBhOdIF3Y9382fqJYt5I_sswSrEw5eihAA"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script src="{{asset('public/web/js/easing.min.js')}}"></script>
        <script src="{{asset('public/web/js/hoverIntent.js')}}"></script>
        <script src="{{asset('public/web/js/superfish.min.js')}}"></script>
        <script src="{{asset('public/web/js/jquery.ajaxchimp.min.js')}}"></script>
        <script src="{{asset('public/web/js/jquery.magnific-popup.min.js')}}"></script>
        <script src="{{asset('public/web/js/owl.carousel.min.js')}}"></script>
        <script src="{{asset('public/web/js/jquery.sticky.js')}}"></script>
        <script src="{{asset('public/web/js/jquery.nice-select.min.js')}}"></script>
        <script src="{{asset('public/web/js/waypoints.min.js')}}"></script>
        <script src="{{asset('public/web/js/jquery.counterup.min.js')}}"></script>
        <script src="{{asset('public/web/js/parallax.min.js')}}"></script>
        <script src="{{asset('public/web/js/mail-script.js')}}"></script>
        <script src="{{asset('public/web/js/main.js')}}"></script>
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-23581568-13')}}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            
            gtag('config', 'UA-23581568-13');
        </script>
        
        @stack('javascript')
    </body>
    <!-- Mirrored from preview.colorlib.com/theme/carrental/ by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 21 Mar 2021 10:50:27 GMT -->
</html>
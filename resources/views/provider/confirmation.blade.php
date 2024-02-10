<!DOCTYPE html>
<html lang=en>
    <head>
        <meta charset=utf-8>
        <title>Congratulations | Application Frame</title>
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

    <body class="error-page">
      <div class="container error-container">
         <div class="error-panel panel panel-default plain animated bounceIn">
            <!-- Start .panel -->
            <div class=panel-body>
               <div class=page-header>
                   <h3 class="text-center mt30 mb10">{{$title}}</h3>
                   <h3 class="text-center mb30">{{$message}}</h3>
               </div>
               <div class="text-center">
                  @if($isLogin==true)
                    <a href="{{route('provider.login')}}" class="btn btn-default">Go To Login</a>
                  @else 
                    <a href="{{route('provider.apps')}}" class="btn btn-default">Go To Apps</a>
                  @endif
                </div>
            </div>
         </div>
      </div>
  </body>
</html>
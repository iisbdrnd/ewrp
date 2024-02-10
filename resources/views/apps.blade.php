<!DOCTYPE html>
<html lang=en>
    <head>
        <meta charset=utf-8>
        <title>Application | Application Frame</title>
        <!-- Mobile specific metas -->
        <meta name=viewport content="width=device-width,initial-scale=1,maximum-scale=1">
        <!-- Import google fonts - Heading first/ text second -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,700" rel=stylesheet type=text/css>
        <link href="http://fonts.googleapis.com/css?family=Droid+Sans:400,700" rel=stylesheet type=text/css>
        <!-- Css files -->
        <link type="text/css" rel="stylesheet" href="{!! asset('public/css/main.min.css') !!}" />
        <link type="text/css" rel="stylesheet" href="{!! asset('public/css/custom.css') !!}" />
        <!-- Fav and touch icons -->
        <link rel="icon" href="{{url('public/img/favicon.png')}}" type="image/png">
    </head>

    <body class=login-page>
        <div id=header class="animated fadeInDown">
            <div class=row>
                <div class=navbar>
                    <?php 
                        $poject_logo = (!empty($projectInfo->logo)) ? $projectInfo->logo : 'default.png';
                    ?>
                    <div class="container text-center">
                        <a class=navbar-brand href="{{url()}}" style="margin-right: 0;"><img height="40" src="{{ asset('public/uploads/logo/'.$poject_logo) }}" style="display: inline;"></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="animated fadeInDown header-fixed ml0 mr0" id="header">
            <div class="navbar">
                <div class="navbar-header">
                    <a class="navbar-brand" href="{{url()}}" style="margin-right: 0;">
                        <img height="40" src="{!! asset('public/uploads/logo/'.$poject_logo) !!}" style="display: inline">
                    </a>
                </div>
                <div id="navbar-no-collapse" class="navbar-no-collapse">
                    <ul class="nav navbar-right usernav">
                        @if($day_left<=15)
                            <li>
                               <a class="expireLimitBox" href="#" aria-expanded="false"><span class="note-current-fontname">Days left to expire </span> <strong class="expireDays">({{$day_left}})</strong></a>
                            </li>
                        @endif
                        <li class="dropdown">
                            <a aria-expanded="false" href="#" class="dropdown-toggle avatar" data-toggle="dropdown">
                                @if(empty($userImage->image))
                                    <img src="{{url('public/img/avatar.jpg')}}" alt="Profile Image" class="image">
                                @else
                                    <img src="{{url('public/uploads/user_profile_images/'.$userImage->image)}}" alt="Profile Image" class="image">
                                @endif
                                <span class="txt">{{Auth::user()->get()->name}}</span>
                            </a>
                        </li>
                        <li><a href={{route('logout')}}><i class="s16 icomoon-icon-exit"></i><span class="txt">Logout</span></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Start login container -->
        @if($module_number==0)
        <div class="container pt20" style="width:584px;">
            <div class="login-panel panel panel-default plain animated bounceIn">
                <div class=panel-body>
                    <div class="row">
                        <div class="col-lg-12 mt10 mb10 text-center">
                            <strong>Sorry, You have no access.</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <?php if($module_number<4) { $width = 'style=width:'.($module_number*292).'px;'; $col = 'col-lg-'.(12/$module_number); } else { $width = ''; $col = 'col-lg-3'; } ?>
        <div class="container pt20" {{$width}}>
            <div class="login-panel panel panel-default plain animated bounceIn">
                <div class=panel-body>
                    @foreach($modules->chunk(4) as $modules)
                    <div class="row">
                        @foreach($modules as $module)
                        <div class="{{$col}} mt10 mb10">
                            <a class="soft-module stats-btn pattern" href="{{url($module->url_prefix)}}"><i class="icon {{$module->module_icon}}"></i> <span class="txt">{{$module->module_name}}</span></a>
                        </div>
                        @endforeach
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <div id=footer class="clearfix sidebar-page right-sidebar-page" style="margin-top: 270px;">
			<!-- Start #footer  -->
			<p class=pull-left>Copyrights &copy; {{date('Y')}} <a href="http://www.iisbd.com/" class="color-blue strong" target=_blank>INNOVATION information system</a>. All rights reserved.</p>
			<p class=pull-right><a href=# class=mr5>Terms of use</a> | <a href=# class="ml5 mr25">Privacy police</a></p>
		</div>
        <!-- End #footer  -->
    </body>
</html>
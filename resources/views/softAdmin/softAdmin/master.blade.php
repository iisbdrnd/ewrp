<!DOCTYPE html>
<html class=no-js>
    <head>
        <meta charset=utf-8>
        <title>{{$title}}</title>
        <!-- Mobile specific metas -->
        <meta name=viewport content="width=device-width,initial-scale=1,maximum-scale=1">
        <!-- Force IE9 to render in normal mode --><!--[if IE]>
        <meta http-equiv="x-ua-compatible" content="IE=9" />
        <![endif]-->
        <!-- Fav and touch icons -->
        <link rel="icon" href="{{url('public/img/favicon.png')}}" type="image/png">
        <!-- Import google fonts - Heading first/ text second -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,700" rel=stylesheet type=text/css>
        <link href="http://fonts.googleapis.com/css?family=Droid+Sans:400,700" rel=stylesheet type=text/css>
        <!-- Css files -->
        <link type="text/css" rel="stylesheet" href="{!! asset('public/plugins/validation/css/formValidation.min.css') !!}" />
        <link type="text/css" rel="stylesheet" href="{!! asset('public/css/bootstrap-select.min.css') !!}" />
        <link type="text/css" rel="stylesheet" href="{!! asset('public/css/main.min.css') !!}" />
        <link type="text/css" rel="stylesheet" href="{!! asset('public/css/custom.css') !!}" />
        <link type="text/css" rel="stylesheet" href="{!! asset('public/plugins/fullcalendar/fullcalendar.css') !!}" />
        <!--Summer Note-->
        <link type="text/css" rel="stylesheet" href="{!! asset('public/plugins/summernote/summernote.css') !!}" />
        <!-- Bootstrap DatePicker -->
        <link type="text/css" rel="stylesheet" href="{!! asset('public/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css') !!}" />        
    </head>
    <body>
        <!--[if lt IE 9]>
        <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]--><!-- .#header -->
        <div id=header>
            <nav class="navbar navbar-default" role=navigation>
                <div class=navbar-header><a class=navbar-brand href="{{route('softAdmin.master')}}" style="margin-right: 0;"><img src="{!! asset('public/img/logo_inno.png') !!}" style="display: inline" width="85"></a></div>
                <div id=navbar-no-collapse class=navbar-no-collapse>
                    <ul class="nav navbar-nav">
                        <li>
                            <!--Sidebar collapse button--><a href=# class="collapseBtn leftbar"><i class="s16 minia-icon-list-3"></i></a>
                        </li>
                        <li><a href=# class="tipB reset-layout" title="Reset panel postions"><i class="s16 icomoon-icon-history"></i></a></li>
                    </ul>
                    <ul class="nav navbar-right usernav">
                        <li class=dropdown>
                            <a href=# class="dropdown-toggle avatar" data-toggle=dropdown><img src="{{url('public/img/avatar.jpg')}}" alt="" class="image"> <span class=txt>{{Auth::admin()->get()->name.' ('.Auth::admin()->get()->username.')'}}</span> <b class=caret></b></a>
                            <ul class="dropdown-menu right">
                                <li class=menu>
                                    <ul>
                                        <li><a href=#><i class="s16 icomoon-icon-user-plus"></i>Edit profile</a></li>
                                        <li><a href=#><i class="s16 icomoon-icon-bubble-2"></i>Comments</a></li>
                                        <li><a href=#><i class="s16 icomoon-icon-plus"></i>Add user</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li><a href={{route('softAdmin.logout')}}><i class="s16 icomoon-icon-exit"></i><span class=txt>Logout</span></a></li>
                    </ul>
                </div>
                <!-- /.nav-collapse -->
            </nav>
            <!-- /navbar -->
        </div>
        <!-- / #header -->
        <div id=wrapper>
            <!-- #wrapper --><!--Sidebar background-->
            <div id=sidebarbg class="hidden-lg hidden-md hidden-sm hidden-xs"></div>
            <!--Sidebar content-->
            <div id=sidebar class="page-sidebar hidden-lg hidden-md hidden-sm hidden-xs">
                <div class=shortcuts>
                    <ul>
                        <li><a href=support.html title="Support section" class=tip><i class="s24 icomoon-icon-support"></i></a></li>
                        <li><a href=# title="Database backup" class=tip><i class="s24 icomoon-icon-database"></i></a></li>
                        <li><a href=charts.html title="Sales statistics" class=tip><i class="s24 icomoon-icon-pie-2"></i></a></li>
                        <li><a href=# title="Write post" class=tip><i class="s24 icomoon-icon-pencil"></i></a></li>
                    </ul>
                </div>
                <!-- End search --><!-- Start .sidebar-inner -->
                <div class=sidebar-inner>
                    <!-- Start .sidebar-scrollarea -->
                    <div class=sidebar-scrollarea>
                        <div class=sidenav>
                            <div class="sidebar-widget mb0">
                                <h6 class="title mb0">Navigation</h6>
                            </div>
                            <!-- End .sidenav-widget -->
                            <div class=mainnav>
                                <ul>
                                    <?php
                                    $parent_ids = $admin_menus->pluck('parent_id');
                                    $menu_list_1 = $parent_ids->filter(function($item) { return $item==0; })->all();
                                    ?>
                                    @foreach($menu_list_1 as $menu_key_1=>$val)
                                        <?php
                                        $menu_1 = $admin_menus[$menu_key_1];
                                        $menu_list_2 = $parent_ids->filter(function($item) use ($menu_1) { return $item==$menu_1->id; })->all();
                                        ?>
                                        <li>
                                            <?php
                                            $route = explode(".", $menu_1->route);
                                            $link = ($route[count($route)-1]=='#')?'#':route($menu_1->route);
                                            if($link!='#') { $link = explode(url($prefix).'/', $link); $link = $link[1]; }
                                            ?>
                                            <a @if(!empty($menu_list_2)) href="#" @else href="{{$link}}" class="ajax-link" @endif>
                                                <i class="s16 {{empty($menu_1->menu_icon) ? 'icomoon-icon-arrow-right-3' : $menu_1->menu_icon}}"></i>
                                                <span class="txt">{{$menu_1->menu_name}}</span>
                                                <span class="indicator"></span>
                                            </a>
                                            @if(!empty($menu_list_2))
                                                <ul class="sub">
                                                    @foreach($menu_list_2 as $menu_key_2=>$val)
                                                        <?php
                                                        $menu_2 = $admin_menus[$menu_key_2];
                                                        $menu_list_3 = $parent_ids->filter(function($item) use ($menu_2) { return $item==$menu_2->id; })->all();
                                                        ?>
                                                        <li>
                                                            <?php
                                                            $route = explode(".", $menu_2->route);
                                                            $link = ($route[count($route)-1]=='#')?'#':route($menu_2->route);
                                                            if($link!='#') { $link = explode(url($prefix).'/', $link); $link = $link[1]; }
                                                            ?>
                                                            <a @if(!empty($menu_list_3)) href="#" @else href="{{$link}}" class="ajax-link" @endif>
                                                                <i class="s16 {{empty($menu_2->menu_icon) ? 'icomoon-icon-arrow-right-3' : $menu_2->menu_icon}}"></i>
                                                                <span class="txt">{{$menu_2->menu_name}}</span>
                                                                <span class="indicator"></span>
                                                            </a>
                                                            @if(!empty($menu_list_3))
                                                                <ul class="sub">
                                                                    @foreach($menu_list_3 as $menu_key_3=>$val)
                                                                        <?php
                                                                        $menu_3 = $admin_menus[$menu_key_3];
                                                                        $menu_list_4 = $parent_ids->filter(function($item) use ($menu_3) { return $item==$menu_3->id; })->all();
                                                                        ?>
                                                                        <li>
                                                                            <?php
                                                                            $route = explode(".", $menu_3->route);
                                                                            $link = ($route[count($route)-1]=='#')?'#':route($menu_3->route);
                                                                            if($link!='#') { $link = explode(url($prefix).'/', $link); $link = $link[1]; }
                                                                            ?>
                                                                            <a @if(!empty($menu_list_4)) href="#" @else href="{{$link}}" class="ajax-link" @endif>
                                                                                <i class="s16 {{empty($menu_3->menu_icon) ? 'icomoon-icon-arrow-right-3' : $menu_3->menu_icon}}"></i>
                                                                                <span class="txt">{{$menu_3->menu_name}}</span>
                                                                                <span class="indicator"></span>
                                                                            </a>
                                                                            @if(!empty($menu_list_4))
                                                                                <ul class="sub">
                                                                                    @foreach($menu_list_4 as $menu_key_4=>$val)
                                                                                        <?php
                                                                                        $menu_4 = $admin_menus[$menu_key_4];
                                                                                        $menu_list_5 = $parent_ids->filter(function($item) use ($menu_4) { return $item==$menu_4->id; })->all();
                                                                                        ?>
                                                                                        <li>
                                                                                            <?php
                                                                                            $route = explode(".", $menu_4->route);
                                                                                            $link = ($route[count($route)-1]=='#')?'#':route($menu_4->route);
                                                                                            if($link!='#') { $link = explode(url($prefix).'/', $link); $link = $link[1]; }
                                                                                            ?>
                                                                                            <a @if(!empty($menu_list_5)) href="#" @else href="{{$link}}" class="ajax-link" @endif>
                                                                                                <i class="s16 {{empty($menu_4->menu_icon) ? 'icomoon-icon-arrow-right-3' : $menu_4->menu_icon}}"></i>
                                                                                                <span class="txt">{{$menu_4->menu_name}}</span>
                                                                                                <span class="indicator"></span>
                                                                                            </a>
                                                                                            @if(!empty($menu_list_5))
                                                                                                <ul class="sub">
                                                                                                    @foreach($menu_list_5 as $menu_key_5=>$val)
                                                                                                        <?php
                                                                                                        $menu_5 = $admin_menus[$menu_key_5];
                                                                                                        ?>
                                                                                                        <li>
                                                                                                            <?php
                                                                                                            $route = explode(".", $menu_5->route);
                                                                                                            $link = ($route[count($route)-1]=='#')?'#':route($menu_5->route);
                                                                                                            if($link!='#') { $link = explode(url($prefix).'/', $link); $link = $link[1]; }
                                                                                                            ?>
                                                                                                            <a href="{{$link}}" class="ajax-link">
                                                                                                                <i class="s16 {{empty($menu_5->menu_icon) ? 'icomoon-icon-arrow-right-3' : $menu_5->menu_icon}}"></i>
                                                                                                                <span class="txt">{{$menu_5->menu_name}}</span>
                                                                                                                <span class="indicator"></span>
                                                                                                            </a>
                                                                                                        </li>
                                                                                                    @endforeach
                                                                                                </ul>
                                                                                            @endif
                                                                                        </li>
                                                                                    @endforeach
                                                                                </ul>
                                                                            @endif
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <!-- End .sidenav-widget -->
                    </div>
                    <!-- End .sidebar-scrollarea -->
                </div>
                <!-- End .sidebar-inner -->
            </div>
            <div id="content" class="page-content clearfix">
                <div class=contentwrapper>
                    <div class="heading">
                        <h3></h3>
                        <ul class="breadcrumb">
                            <li>You are here:</li>
                        </ul>
                    </div>
                    <!-- End  / heading-->
                    <div id="ajax-content">
                        <!-- content will load here -->
                    </div>
                </div>
            </div>
            <!-- End #content -->
            <div id=footer class="clearfix sidebar-page right-sidebar-page">
                <!-- Start #footer  -->
                <p class=pull-left>Copyrights &copy; 2016 <a href="http://iisbd.com/" class="color-blue strong" target=_blank>INNOVATION information system</a>. All rights reserved.</p>
                <p class=pull-right><a href=# class=mr5>Terms of use</a> | <a href=# class="ml5 mr25">Privacy police</a></p>
            </div>
            <!-- End #footer  -->
        </div>
        <!-- / #wrapper --><!-- Back to top -->
        <div id=back-to-top><a href=#>Back to Top</a></div>

        <!-- Javascripts --><!-- Load pace first -->
        <script src="{{route('softAdmin.setUrl')}}"></script>
        <!-- Important javascript libs(put in all pages) -->
        <script src="{!! asset('public/js/libs/jquery-2.1.1.min.js') !!}"></script>
        <script src="{!! asset('public/js/libs/jquery-ui-1.10.4.min.js') !!}"></script>
        <script src="{!! asset('public/js/libs/jquery-migrate-1.2.1.min.js') !!}"></script>
        <!--[if lt IE 9]>
        <script type="text/javascript" src="{!! asset('public/js/libs/excanvas.min.js') !!}"></script>
        <script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <script type="text/javascript" src="{!! asset('public/js/libs/respond.min.js') !!}"></script>
        <![endif]-->
        <!-- Important javascript (App js) -->
        <!-- Ajax Upload -->
        <script type="text/javascript" src="{!! asset('public/js/ajaxupload.3.5.js') !!}"></script>
        <script src="{!! asset('public/plugins/validation/js/formValidation.min.js') !!}"></script>
        <script type="text/javascript" src="{!! asset('public/plugins/validation/js/framework/bootstrap.js') !!}"></script>
        <script src="{!! asset('public/js/bootstrap/bootstrap-select.min.js') !!}"></script>
        <script src="{!! asset('public/js/main_2.5.js') !!}"></script>
        <script src="{!! asset('public/js/frame_2.4.js') !!}"></script>
        <script src="{!! asset('public/js/custom_2.5.js') !!}"></script>
        <!--Summer Note-->
        <script src="{!! asset('public/plugins/summernote/summernote.min.js') !!}"></script>
        <script src="{!! asset('public/js/hideShowPassword.min.js') !!}"></script>
        <!-- Bootstrap DatePicker -->
        <script type="text/javascript" src="{!! asset('public/plugins/bootstrap-datetimepicker/moment-with-locales.js') !!}"></script>
        <script type="text/javascript" src="{!! asset('public/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') !!}"></script>
    </body>
</html>
            


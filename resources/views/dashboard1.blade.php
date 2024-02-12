<div class=heading>
    <!--  .heading-->
    <h3>Dashboard</h3>
    <ul class=breadcrumb>
        <li>You are here:</li>
        <li>
            <a href=# class=tip title="back to dashboard">
                <i class="s16 icomoon-icon-screen-2"></i>
            </a> <span class=divider><i class="s16 icomoon-icon-arrow-right-3"></i></span>
        </li>
        <li class=active>Blank Page</li>
    </ul>
</div>
<!-- End  / heading-->
<div class=row>
    <!-- .row -->
    <div class=col-md-8>
        <!-- col-md-8 start here -->
        <div class=row>
            <!-- .row start -->
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                <!-- .col-md-3 --><a href=# title="I`m with gradient" class="stats-btn tipB mb20"><i class="icon icomoon-icon-users"></i> <span class=txt>Users</span> <span class=notification>5</span></a>
            </div>
            <!-- / .col-md-3 -->
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                <!-- .col-md-3 --><a href=# class="stats-btn mb20"><i class="icon icomoon-icon-support"></i> <span class=txt>Support tickets</span> <span class="notification blue">12</span></a>
            </div>
            <!-- / .col-md-3 -->
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                <!-- .col-md-3 --><a href=# title="I`m with pattern" class="stats-btn pattern tipB mb20"><i class="icon icomoon-icon-bubbles-2"></i> <span class=txt>New Comments</span> <span class="notification green">23</span></a>
            </div>
            <!-- / .col-md-3 -->
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                <!-- .col-md-3 --><a href=# class="stats-btn mb20"><i class="icon icomoon-icon-basket"></i> <span class=txt>Orders</span> <span class=notification>+5</span></a>
            </div>
            <!-- / .col-md-3 -->
        </div>
        <!-- / .row -->
    </div>
    <!-- col-md-8 end here -->
    <div class=col-md-4>
        <!-- col-md-4 start here -->
        <div class=text-center>
            <div dir=ltr class="circle-stats mb10">
                <div class="circular-item tipB" title="Site overload"><i class="icon icomoon-icon-fire"></i> <input value=62 class="redCircle"></div>
                <div class="circular-item tipB" title="Site average load time"><i class="icon icomoon-icon-busy"></i> <input value=12 class="blueCircle"></div>
                <div class="circular-item tipB" title="Target complete"><i class="icon icomoon-icon-target-2"></i> <input value=94 class="greenCircle"></div>
            </div>
        </div>
    </div>
    <!-- col-md-4 end here -->
</div>
<!-- / .row -->
<div class=row>
    <!-- .row start -->
    <div class=col-md-8>
        <!-- col-md-8 start here -->
        <div class="panel panel-default chart">
            <div class=panel-heading>
                <h4 class=panel-title><i class="s16 icomoon-icon-bars"></i> <span>Visitors chart</span></h4>
            </div>
            <div class=panel-body>
                <div class="visitors-chart mt15 mb15" style="height: 230px; width:100%"></div>
                <div class=row>
                    <!-- .row start -->
                    <div class="col-md-3 col-md-3 col-sm-3 col-xs-6">
                        <!-- col-md-3 start here --><a href=# class=chartShortcuts><span class=head>Total Visits</span> <span class=number>509</span></a>
                    </div>
                    <!-- col-md-3 end here -->
                    <div class="col-md-3 col-md-3 col-sm-3 col-xs-6">
                        <!-- col-md-3 start here --><a href=# class=chartShortcuts><span class=head>Uniqiue Visits</span> <span class=number>309</span></a>
                    </div>
                    <!-- col-md-3 end here -->
                    <div class="col-md-3 col-md-3 col-sm-3 col-xs-6">
                        <!-- col-md-3 start here --><a href=# class=chartShortcuts><span class=head>External Visits</span> <span class=number>109</span></a>
                    </div>
                    <!-- col-md-3 end here -->
                    <div class="col-md-3 col-md-3 col-sm-3 col-xs-6">
                        <!-- col-md-3 start here --><a href=# class=chartShortcuts><span class=head>Impressions</span> <span class=number>325</span></a>
                    </div>
                    <!-- col-md-3 end here -->
                </div>
                <!-- / .row -->
            </div>
        </div>
        <!-- End .panel -->
    </div>
    <!-- col-md-8 end here -->
    <div class=col-md-4>
        <!-- col-md-4 start here -->
        <div class="sparkStats mb25">
            <!-- .sparkStats -->
            <h4>389 people visited this site <a href=# class="icon tip pull-right mr15" title=Configure><i class="s16 icomoon-icon-cog-2 mr0"></i></a></h4>
            <ul class=list-unstyled>
                <li><span class=sparkLine1></span> Visits: <span class=number>509</span></li>
                <li><span class=sparkLine2></span> Unique Visitors: <span class=number>389</span></li>
                <li><span class=sparkLine3></span> Pageviews: <span class=number>731</span></li>
                <li><span class=sparkLine4></span> Pages / Visit: <span class=number>1.44</span></li>
                <li><span class=sparkLine5></span> Avg. Visit Duration: <span class=number>00:01:21</span></li>
                <li><span class=sparkLine6></span> Bounce Rate: <span class=number>68.37%</span></li>
                <li><span class=sparkLine7></span> % New Visits: <span class=number>76.23%</span></li>
            </ul>
            <div class=pt15><a href=charts.html class="btn btn-info">View full statistic <i class="s16 icomoon-icon-arrow-right-3 color-white"></i></a></div>
        </div>
        <!-- End .sparkStats -->
    </div>
    <!-- col-md-4 end here -->
</div>
<!-- / .row -->
<script src="{!! asset('public/js/pages/dashboard.js') !!}"></script>
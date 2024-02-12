@if($module==0)
<style type="text/css">
    .card-box {background-color: #fff; border-radius: 2px; border: 1px solid #ccc; box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.2);margin-bottom: 20px;padding:20px;}
    .dash-widget-icon {background-color: #eee;border-radius: 100%;color: #777;display: inline-block;float: left;font-size: 30px;height: 60px;line-height: 60px;margin-right: 10px;text-align: center;width: 60px;}
    .dash-widget-info {text-align: right;}
    .dash-widget-info > h3 {font-size: 30px;font-weight: 600;margin-top: 0px;}
    .dash-widget-info > span {font-size: 16px;}
    .panel {
        box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.2);
        margin-bottom: 20px;
    }

    .panel-table .panel-heading {border-bottom: 1px solid #ddd;padding: 15px;}
    .panel-table .panel-body {padding: 0;}
    .panel-table .panel-footer {background-color: #fff;text-align: center;}
    .panel-title > a.btn {color:#fff;}
    table.table td h2 {
        display: inline-block;
        font-size: inherit;
        font-weight: 600;
        margin: 0;
        padding: 0;
        vertical-align: middle;
        color: #666;
    }
    table.table td h2 a {
        color: #757575;
    }
    .progress-xs {
        height: 5px;
    }
    .progress {
        height: 20px;
        margin-bottom: 20px;
        overflow: hidden;
        background-color: #f5f5f5;
        border-radius: 4px;
        -webkit-box-shadow: inset 0 1px 2px rgba(0,0,0,.1);
        box-shadow: inset 0 1px 2px rgba(0,0,0,.1);
    }
    .text-ellipsis {
        display: block;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .block {
        display: block !important;
    }
    .bg-success, .label-success {background-color:#55ce63;}
</style>

<div class=row>
    <!-- .row -->
    <div class=col-md-12>
        <!-- col-md-10 start here -->
        <div class=row>
            <!-- .row start -->
            <a class="ajax-link" href="on-going-projects">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                    <div class="dash-widget clearfix card-box">
                        <span class="dash-widget-icon"><i class="fa fa-cubes" aria-hidden="true"></i></span>
                        <div class="dash-widget-info">
                            <h3>{{$projects}}</h3>
                            <span>Projects</span>
                        </div>
                    </div>
                </div>
            </a>
            <!-- / .col-md-3 -->
            <a class="ajax-link" href="total-candidates">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                    <div class="dash-widget clearfix card-box">
                        <span class="dash-widget-icon"><i class="fa fa-users"></i></span>
                        <div class="dash-widget-info">
                            <h3>{{$candidates}}</h3>
                            <span>Candidates</span>
                        </div>
                    </div>
                </div>
            </a>
            <!-- / .col-md-3 -->
            <a class="ajax-link" href="total-references">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                    <div class="dash-widget clearfix card-box">
                        <span class="dash-widget-icon"><i class="fa fa-user" aria-hidden="true"></i></span>
                        <div class="dash-widget-info">
                            <h3>{{$references}}</h3>
                            <span>References</span>
                        </div>
                    </div>
                </div>
            </a>
            <!-- / .col-md-3 -->
            <a class="ajax-link" href="total-trades">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                    <div class="dash-widget clearfix card-box">
                        <span class="dash-widget-icon"><i class="fa fa-briefcase" aria-hidden="true"></i></span>
                        <div class="dash-widget-info">
                            <h3>{{$trades}}</h3>
                            <span>Trades</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <!-- / .row -->
    </div>
</div>
<div class=row>
    <!-- .row -->
    <div class=col-md-9>
        <!-- col-md-9 start here -->
        <div class=row>
            <div class="col-lg-12 col-md-12 sortable-layout">
                <div class="panel panel-default chart toggle panelClose panelRefresh panelMove" refresh-url='dashboard?module=2'>
                    <div class=panel-heading>
                        <h4 class=panel-title><i class="s16 icomoon-icon-bars"></i> <span>Incomes & Expenses (Current Month)</span></h4>
                    </div>
                    <div class=panel-body>
@endif
@if($module==0 || $module==2)
                        <div id="income-expense" style="width:100%; height: 250px"></div>
@endif
@if($module==0)
                    </div>
                </div>
                <!-- End .panel -->
                <div class="panel panel-default chart toggle panelClose panelRefresh panelMove" refresh-url='dashboard?module=4'>
                    <div class=panel-heading>
                        <h4 class=panel-title><i class="s16 icomoon-icon-bars"></i> <span>Flights(Current Month)</span></h4>
                    </div>
                    <div class=panel-body>
@endif
@if($module==0 || $module==4)
                        <div class="flights" style="height: 250px; width:100%"></div>
@endif
@if($module==0)
                    </div>
                </div>
                <!-- End .panel -->
            </div>
            <!-- col-md-12 end here -->

            <div class="col-lg-6 col-md-12 sortable-layout">
                <div class="panel panel-default chart toggle panelClose panelRefresh panelMove" refresh-url='dashboard?module=1'>
                    <div class=panel-heading>
                        <h4 class=panel-title><i class="s16 icomoon-icon-bars"></i> <span>Todays Voucher</span></h4>
                    </div>
                    <div class=panel-body>
@endif
@if($module==0 || $module==1)
                        <table class="table table-responsive table-striped">
                            <thead>
                                <tr>
                                    <td width="40%"><strong>Voucher</strong></td>
                                    <td width="30%" class="text-right"><strong>Debit Amount</strong></td>
                                    <td width="30%" class="text-right"><strong>Credit Amount</strong></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $total_debit = 0;
                                    $total_credit = 0;
                                ?>
                                @foreach($voucher_names as $key => $voucher)
                                <?php
                                    $key+=1;
                                    $key_exists = (array_key_exists($key, $todays_voucher));
                                    $debit_amount = ($key_exists) ? $todays_voucher[$key]->debit_amount : 0;
                                    $credit_amount = ($key_exists) ? $todays_voucher[$key]->credit_amount : 0;

                                    $total_debit += $debit_amount;
                                    $total_credit += $credit_amount;
                                ?>
                                <tr>
                                    <td>{{$voucher}}</td>
                                    <td class="text-right">&#2547;{{number_format($debit_amount, 2)}}</td>
                                    <td class="text-right">&#2547;{{number_format($credit_amount, 2)}}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td class="text-right">Total:</td>
                                    <td class="text-right">&#2547;{{number_format($total_debit, 2)}}</td>
                                    <td class="text-right">&#2547;{{number_format($total_credit, 2)}}</td>
                                </tr>
                            </tbody>
                        </table>
@endif
@if($module==0)
                    </div>
                </div>
                <!-- col-md-12 start here -->
            </div>
            <div class="col-lg-6 col-md-12 sortable-layout">
                
                <div class="panel panel-table toggle panelClose panelRefresh panelMove" refresh-url='dashboard?module=3'>
                    <div class="panel-heading" style="height: 40px; background: #f7f7f7;">
                        <h3 class="panel-title"><i class="s16 icomoon-icon-bars"></i>Ongoing Projects</h3>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive" style="height: 225px;">
@endif
@if($module==0 || $module==3)
                            <table class="table table-striped custom-table m-b-0">
                                <thead>
                                    <tr>
                                        <th class="col-md-3">Project Name </th>
                                        <th class="col-md-3">Progress</th>
                                    </tr>
                                </thead>
                                <tbody style="min-height: 50px;">
                                    @foreach($ongoingProject as $ongoingProject)
                                    <?php $percentage=round(($ongoingProject->flightQty/$ongoingProject->candidateQty)*100); ?>
                                    <tr>
                                        <td>
                                            <h2>{{$ongoingProject->project_name}}</h2>
                                            <small class="block text-ellipsis">
                                                <span class="text-xs">{{$ongoingProject->candidateQty-$ongoingProject->flightQty}}</span> <span class="text-muted">Candidate Processing, </span>
                                                <span class="text-xs">{{$ongoingProject->flightQty}}</span> <span class="text-muted">Flight Completed</span>
                                            </small>
                                        </td>
                                        <td>
                                            <div class="progress progress-xs progress-striped">
                                                <div class="progress-bar bg-success" role="progressbar" data-toggle="tooltip" title="" style="width: {{$percentage.'%'}}" data-original-title="{{$percentage.'%'}}"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
@endif
@if($module==0)
                        </div>
                    </div>
                    <div class="panel-footer">
                        <a href="project-registration" class="text-primary ajax-link">View all projects</a>
                    </div>
                </div>
                <!-- End .panel -->
                
                <!-- <div class="panel panel-table toggle panelClose panelRefresh panelMove">
                    <div class="panel-heading">
                        <h3 class="panel-title">Completed Projects</h3>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped custom-table m-b-0">
                                <thead>
                                    <tr>
                                        <th class="col-md-3">Project Name </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <h2><a href="project-view.html">Food and Drinks</a></h2>
                                            <small class="block text-ellipsis">
                                                <span class="text-xs">34</span> <span class="text-muted">Candidates, </span>
                                                <span class="text-xs">9</span> <span class="text-muted">References</span>
                                            </small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h2><a href="project-view.html">School Guru</a></h2>
                                            <small class="block text-ellipsis">
                                                <span class="text-xs">34</span> <span class="text-muted">Candidates, </span>
                                                <span class="text-xs">9</span> <span class="text-muted">References</span>
                                            </small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h2><a href="project-view.html">Penabook</a></h2>
                                            <small class="block text-ellipsis">
                                                <span class="text-xs">34</span> <span class="text-muted">Candidates, </span>
                                                <span class="text-xs">9</span> <span class="text-muted">References</span>
                                            </small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h2><a href="project-view.html">Harvey Clinic</a></h2>
                                            <small class="block text-ellipsis">
                                                <span class="text-xs">34</span> <span class="text-muted">Candidates, </span>
                                                <span class="text-xs">9</span> <span class="text-muted">References</span>
                                            </small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h2><a href="project-view.html">The Gigs</a></h2>
                                            <small class="block text-ellipsis">
                                                <span class="text-xs">34</span> <span class="text-muted">Candidates, </span>
                                                <span class="text-xs">9</span> <span class="text-muted">References</span>
                                            </small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <a href="projects.html" class="text-primary">View all projects</a>
                    </div>
                </div> -->
            </div>
            <!-- col-lg-6 end here -->
         </div>
        <!-- / .row -->
    </div>
    <div class=col-md-3> <!-- col-md-3 start here -->
        <div class="dash-widget clearfix card-box">
            <span class="dash-widget-icon"><i class="fa fa-money" aria-hidden="true"></i></span>
            <div class="dash-widget-info">
                <h4>&#2547;{{number_format($receivable_amount,2)}}</h4>
                <span>Total Receivable</span>
            </div>
        </div>
       <!-- End .panel -->
    </div> <!-- col-md-3 end here -->
    <div class=col-md-3> <!-- col-md-3 start here -->
        <div class="dash-widget clearfix card-box">
            <span class="dash-widget-icon"><i class="fa fa-money" aria-hidden="true"></i></span>
            <div class="dash-widget-info">
                <h4>&#2547;{{number_format($payable_amount,2)}}</h4>
                <span>Total Payable</span>
            </div>
        </div>
        <!-- End .panel -->
        <!-- <div class="panel panel-default chart toggle panelClose panelRefresh panelMove">
            <div class=panel-heading>
                <h4 class=panel-title><i class="s16 icomoon-icon-bars"></i> <span>Project Overview</span></h4>
            </div>
            <div class=panel-body>
                <div class='project-overview' style="height: 230px;width:100%"></div>
            </div>
        </div> -->
        <!-- End .panel -->
</div>
<!-- Javascripts -->
<script src="{!! asset('public/js/libs/excanvas.min.js') !!}"></script>
<script src="{!! asset('public/js/libs/respond.min.js') !!}"></script>
<script src="{!! asset('public/js/pages/loader.js') !!}"></script>
@endif
@if($module==0 || $module==2)
<script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Date', 'Incomes', 'Expenses'],
            <?php
                $day = 1;
                $date = $firstDay;
                $lastDay = intval(date('d',strtotime("last day of this month")));

                for($i = 1; $i<=$lastDay; $i++){
                    echo '["'.$day.'",'.((array_key_exists($date, $incomes) ? $incomes[$date]->incBalance: 0)).','.((array_key_exists($date, $expenses) ? $expenses[$date]->expBalance: 0)).'],';

                    $date = new DateTime($date);
                    $date->add(new DateInterval('P1D'));
                    $day = intval($date->format('d'));
                    $date = $date->format('Y-m-d');
                }
            ?>
        ]);
       
        var options = {
          title: 'Company Performance',
          curveType: 'function',
          legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('income-expense'));

        chart.draw(data, options);
      }
</script>
@endif
@if($module==0 || $module==4)
<script type="text/javascript">
    $(document).ready( function() {
        var a = ["#88bbc8", "#ed7a53", "#9FC569", "#bbdce3", "#9a3b1b", "#5a8022", "#2c7282"];

        $(".flights").length && $(function() {
            var b = [
                <?php
                    $day = 1;
                    $date = $firstDay;
                    $lastDay = intval(date('d',strtotime("last day of this month")));

                    for($i = 1; $i<=$lastDay; $i++){
                        echo '['.$day.','.((array_key_exists($date, $flights) ? $flights[$date]->flight_qty: 0)).'],';

                        $date = new DateTime($date);
                        $date->add(new DateInterval('P1D'));
                        $day = intval($date->format('d'));
                        $date = $date->format('Y-m-d');
                    }
                ?>
                ],
                d = $(".flights"),
                e = {
                    grid: {
                        show: !0,
                        aboveData: !0,
                        color: "#3f3f3f",
                        labelMargin: 5,
                        axisMargin: 0,
                        borderWidth: 0,
                        borderColor: null,
                        minBorderMargin: 5,
                        clickable: !0,
                        hoverable: !0,
                        autoHighlight: !0,
                        mouseActiveRadius: 20
                    },
                    series: {
                        grow: {
                            active: !1
                        },
                        lines: {
                            show: !0,
                            fill: !0,
                            lineWidth: 2,
                            steps: !1
                        },
                        points: {
                            show: !1
                        }
                    },
                    legend: {
                        position: "se"
                    },
                    yaxis: {
                        min: 0
                    },
                    xaxis: {
                        ticks: 11,
                        tickDecimals: 0
                    },
                    colors: a,
                    shadowSize: 1,
                    tooltip: !0,
                    tooltipOpts: {
                        content: "%s : %y.0",
                        shifts: {
                            x: -30,
                            y: -50
                        }
                    }
                };
            $.plot(d, [{
                label: "Flight",
                data: b,
                lines: {
                    fillColor: "#f2f7f9"
                },
                points: {
                    fillColor: "#88bbc8"
                }
            },], e)
        })
    });
    </script>
@endif
@if($module==0)
<script type="text/javascript">
    $(document).ready( function() {
        var a = ["#88bbc8", "#ed7a53", "#9FC569", "#bbdce3", "#9a3b1b", "#5a8022", "#2c7282"];
        $(".project-overview").length && $(function() {
            var a = [{
                label: "Ongoing",
                data: 65,
                color: "#88bbc8"
            }, {
                label: "Completed",
                data: 35,
                color: "#9FC569"
            }];
            $.plot($(".project-overview"), a, {
                series: {
                    pie: {
                        show: !0,
                        highlight: {
                            opacity: .1
                        },
                        radius: 1,
                        stroke: {
                            color: "#fff",
                            width: 2
                        },
                        startAngle: 2,
                        combine: {
                            color: "#353535",
                            threshold: .05
                        },
                        label: {
                            show: !0,
                            radius: 1,
                            formatter: function(a, b) {
                                return '<div class="pie-chart-label">' + a + "&nbsp;" + Math.round(b.percent) + "</div>"
                            }
                        }
                    },
                    grow: {
                        active: !1
                    }
                },
                legend: {
                    show: !1
                },
                grid: {
                    hoverable: !0,
                    clickable: !0
                },
                tooltip: !0,
                tooltipOpts: {
                    content: "%s : %y.1%",
                    shifts: {
                        x: -30,
                        y: -50
                    }
                }
            })
        })
    });
    
</script>
@endif

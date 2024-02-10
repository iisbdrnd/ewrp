<span class="fc-header-title">
	<h5>{{$chartTitle}}</h5>
</span>
<div id=opportunitiesSourcePieChart style="height: 221px; width:100%"></div>

<script type="text/javascript">
    $(document).ready(function(){
        $(function() {
            var a = <?php echo $chartData; ?>;
            $.plot($("#opportunitiesSourcePieChart"), a, {
                series: {
                    pie: {
                        show: !0,
                        highlight: {
                            opacity: .1
                        },
                        stroke: {
                            color: "#fff",
                            width: 3
                        },
                        startAngle: 2,
                        label: {
                            radius: 1
                        }
                    },
                    grow: {
                        active: !1
                    }
                },
                legend: {
                    position: "ne",
                    labelBoxBorderColor: null
                },
                grid: {
                    hoverable: !0,
                    clickable: !0
                },
                tooltip: !0,
                tooltipOpts: {
                    content: "%s : %y.1",
                    shifts: {
                        x: -30,
                        y: -50
                    }
                }
            })
        });
    });
</script>
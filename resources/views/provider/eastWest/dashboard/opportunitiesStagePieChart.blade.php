<span class="fc-header-title">
	<h5>{{$chartTitle}}</h5>
	<?php 
		$initial_opportunities = ($total_opportunity==0) ? 0 : round((($initial_opportunities/$total_opportunity)*100), 2);
		$analysis_opportunities = ($total_opportunity==0) ? 0 : round((($analysis_opportunities/$total_opportunity)*100), 2);
		$presentation_opportunities = ($total_opportunity==0) ? 0 : round((($presentation_opportunities/$total_opportunity)*100), 2);
		$proposal_opportunities = ($total_opportunity==0) ? 0 : round((($proposal_opportunities/$total_opportunity)*100), 2);
		$negotiate_opportunities = ($total_opportunity==0) ? 0 : round((($negotiate_opportunities/$total_opportunity)*100), 2);
		$won_opportunities = ($total_opportunity==0) ? 0 : round((($won_opportunities/$total_opportunity)*100), 2);
		$lost_opportunities = ($total_opportunity==0) ? 0 : round((($lost_opportunities/$total_opportunity)*100), 2);
	?>
</span>
<div id=opportunitiesStagePieChart style="height: 221px; width:100%"></div>

<script type="text/javascript">
    $(document).ready(function(){
        $(function() {
            var a = [{
                label: "{{$initial_opportunities}}% Initial",
                data: {{$initial_opportunities}},
                color: "#88bbc8"
            }, {
                label: "{{$analysis_opportunities}}% Analysis",
                data: {{$analysis_opportunities}},
                color: "#ed7a53"
            }, {
                label: "{{$presentation_opportunities}}% Presentation",
                data: {{$presentation_opportunities}},
                color: "#FDE758"
            }, {
                label: "{{$proposal_opportunities}}% Proposal",
                data: {{$proposal_opportunities}},
                color: "#9a3b1b"
            }, {
                label: "{{$negotiate_opportunities}}% Negotiate",
                data: {{$negotiate_opportunities}},
                color: "#1568A6"
            }, {
                label: "{{$won_opportunities}}% Closed Won",
                data: {{$won_opportunities}},
                color: "#9FC569"
            }, {
                label: "{{$lost_opportunities}}% Closed Lost",
                data: {{$lost_opportunities}},
                color: "#FF324D"
            }];
            $.plot($("#opportunitiesStagePieChart"), a, {
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
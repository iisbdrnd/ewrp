<span class="fc-header-title">
	<h5>{{$chartTitle}}</h5>
	<?php 
		$unqualified_leads = ($total_leads==0) ? 0 : round((($unqualified_leads/$total_leads)*100), 2);
		$primary_leads = ($total_leads==0) ? 0 : round((($primary_leads/$total_leads)*100), 2);
		$maturity_leads = ($total_leads==0) ? 0 : round((($maturity_leads/$total_leads)*100), 2);
		$findings_leads = ($total_leads==0) ? 0 : round((($findings_leads/$total_leads)*100), 2);
		$qualified_leads = ($total_leads==0) ? 0 : round((($qualified_leads/$total_leads)*100), 2);
	?>
</span>
<div id=leadStatusPieChart style="height: 221px; width:100%"></div>

<script type="text/javascript">
    $(document).ready(function(){
        $(function() {
            var a = [{
                label: "{{$unqualified_leads}}% Unqualified",
				data: {{$unqualified_leads}},
                color: "#88BBC8"
            }, {
                label: "{{$primary_leads}}% Primary",
                data: {{$primary_leads}},
                color: "#ed7a53"
            }, {
                label: "{{$maturity_leads}}% Maturity",
                data: {{$maturity_leads}},
                color: "#9a3b1b"
            }, {
                label: "{{$findings_leads}}% Findings",
                data: {{$findings_leads}},
                color: "#1568A6"
            }, {
                label: "{{$qualified_leads}}% Qualified",
                data: {{$qualified_leads}},
                color: "#9FC569"
            }];
            $.plot($("#leadStatusPieChart"), a, {
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
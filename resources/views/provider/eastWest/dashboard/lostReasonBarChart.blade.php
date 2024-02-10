<div style="width:100%;">
    <canvas id="lostReasonBarChart"></canvas>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        var horizontalBarChartData = {
            labels: [<?php echo implode(',', $label); ?>],
            datasets: [{
                label: 'Total',
                backgroundColor: "rgba(220,220,220,0.5)",
                data: [{{implode(',', $ttlLost)}}]
            }, {
                label: 'Lost',
                backgroundColor: "rgba(151,187,205,0.5)",
                data: [{{implode(',', $lostAmount)}}]
            }]

        };

        var ctx = document.getElementById("lostReasonBarChart").getContext("2d");
        window.myHorizontalBar = new Chart(ctx, {
            type: 'horizontalBar',
            data: horizontalBarChartData,
            options: {
                // Elements options apply to all of the options unless overridden in a dataset
                // In this case, we are setting the border of each horizontal bar to be 2px wide and green
                elements: {
                    rectangle: {
                        borderWidth: 2,
                        borderColor: 'rgb(0, 255, 0)',
                        borderSkipped: 'left'
                    }
                },
                responsive: true,
                legend: {
                    position: 'bottom',
                },
                title: {
                    display: true,
                    text: '{{$chartTitle}}'
                }
            }
        });
    });
</script>
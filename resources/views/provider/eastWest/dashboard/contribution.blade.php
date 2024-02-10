<div style="width:100%;">
    <canvas id="contribution"></canvas>
</div>
<div class="row mt15">
    <div class="col-sm-3 col-xs-3">
        <a href=# class=chartShortcuts><span class=head>Target</span> <span class=number>{{number_format(array_sum($targetAmount), 2, '.', ',')}}</span></a>
    </div>
    <div class="col-sm-3 col-xs-3">
        <a href=# class=chartShortcuts><span class=head>Ongoing</span> <span class=number>{{number_format($ongoingAmount[count($ongoingAmount)-1]+0, 2, '.', ',')}}</span></a>
    </div>
    <div class="col-sm-3 col-xs-3">
        <a href=# class=chartShortcuts><span class=head>Achieved</span> <span class=number>{{number_format(array_sum($achievedAmount), 2, '.', ',')}}</span></a>
    </div>
    <div class="col-sm-3 col-xs-3">
        <a href=# class=chartShortcuts><span class=head>Lost</span> <span class=number>{{number_format(array_sum($lostAmount), 2, '.', ',')}}</span></a>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        var config = {
            type: 'line',
            data: {
                labels: [@foreach($month as $month)<?php echo "'".$month."', "; ?>@endforeach],
                datasets: [{
                    label: "Target",
                    data: [@foreach($targetAmount as $targetAmount)<?php echo "'".$targetAmount."', "; ?>@endforeach],
                    fill: false,
                    borderDash: [5, 5],
                    fillColor: "#1568A6"
                }, {
                    label: "Ongoing",
                    data: [@foreach($ongoingAmount as $ongoingAmount)<?php echo "'".$ongoingAmount."', "; ?>@endforeach],
                    fill: false,
                    fillColor: "#88bbc8"
                }, {
                    label: "Achieved",
                    data: [@foreach($achievedAmount as $achievedAmount)<?php echo "'".$achievedAmount."', "; ?>@endforeach],
                    //lineTension: 0,
                    fill: false,
                    fillColor: "#9FC569"
                }, {
                    label: "Lost",
                    data: [@foreach($lostAmount as $lostAmount)<?php echo "'".$lostAmount."', "; ?>@endforeach],
                    //lineTension: 0,
                    fill: false,
                    fillColor: "#ed7a53"
                }]
            },
            options: {
                responsive: true,
                legend: {
                    position: 'bottom',
                },
                hover: {
                    mode: 'label'
                },
                scales: {
                    xAxes: [{
                        display: true
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Opportunities Amount'
                        }
                    }]
                },
                title: {
                    display: true,
                    text: '{{$chartTitle}}'
                }
            }
        };

        $.each(config.data.datasets, function(i, dataset) {
            dataset.borderColor = dataset.fillColor;
            dataset.backgroundColor = dataset.fillColor;
            dataset.pointBorderColor = dataset.fillColor;
            dataset.pointBackgroundColor = dataset.fillColor;
            dataset.pointBorderWidth = 1;
        });

        var ctx = document.getElementById("contribution").getContext("2d");
        new Chart(ctx, config);
    });
</script>
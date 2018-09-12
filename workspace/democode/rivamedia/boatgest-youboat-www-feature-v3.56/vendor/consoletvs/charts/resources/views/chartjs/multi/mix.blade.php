<?php
    $labels = '';
    foreach($model->labels as $label) {
        $labels .= '"' . $label . '",';
    }

    $datasets = '';
    for ($i = 0; $i < count($model->datasets); $i++) {
        $fill = array_key_exists('fill', $model->datasets[$i]['options']) ? $model->datasets[$i]['options']['fill'] : 'false';
        $label = array_key_exists('label', $model->datasets[$i]) ? $model->datasets[$i]['label'] : '/';
        $type = array_key_exists('type', $model->datasets[$i]['options']) ? $model->datasets[$i]['options']['type'] : 'line';
        $colors = '';
        if($model->colors and count($model->colors) > $i) {
            $colors .= 'borderColor: "' . $model->colors[$i] . '",';
            $colors .= 'backgroundColor: "' . $model->colors[$i] . '",';
        } else {
            $c = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
            $colors .= 'borderColor: "' . $c . '",';
            $colors .= 'backgroundColor: "' . $c . '",';
        }
        $datas = '';
        $values = $model->datasets[$i]['values'];
        foreach($values as $data) {
            $datas .= $data . ',';
        }
        $datasets .= '{';
        $datasets .= '  fill: '. $fill . ',';
        $datasets .= '  label: "'.  $label . '",';
        $datasets .= '  type: "'.  $type . '",';
        $datasets .= '  lineTension: 0.3,';
        $datasets .= '  '.  $colors . '';
        $datasets .= '  data: [' . $datas .'],';
        $datasets .= '},';
    }
    $responsive = ($model->responsive || !$model->width) ? 'true' : 'false';
    $title = isset($model->title) ? $model->title : '/';
?>
@if(!$model->customId)
    @include('charts::_partials.container.canvas2')
@endif

<script type="text/javascript">
    var ctx = document.getElementById("{{ $model->id }}")
    var myMixChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                {!! $labels !!}
            ],
            datasets: [
                {!! $datasets !!}
            ]
        },
        options: {
            responsive: {!! $responsive !!},
            maintainAspectRatio: false,
            @if($model->title)
            title: {
                display: true,
                text: "{!! $title !!}",
                fontSize: 20,
            },
            @endif
            scales: {
                yAxes: [{
                    display: true,
                    ticks: {
                        beginAtZero: true,
                    }
                }]
            }
        }
    });
</script>

<?php
header('Content-Type: text/javascript');
$slides = '';
$img = ['dir' => 'assets/vendor/youboat/landing/img/boats/', 'count'=>5];
for ($i=0; $i<$img['count']; $i++) {
    $slides .= '{ src: "' . $img['dir'] . $i . '-min.jpg" },';
}
$js_code = <<<EOF
    $(document).ready(function() {
        $("body").vegas({
            delay: 6000,
            timer: false,
            transitionDuration: 2000,
            slides: [
                $slides
            ],
            transition: 'swirlRight',
            animation: 'kenburns'
        });
    });
EOF;
echo preg_replace('/^\s+|\n|\r|\s+$/m', '', $js_code);
//echo preg_replace('/\v(?:[\v\h]+)/', '', $js_code);
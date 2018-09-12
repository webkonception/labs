<?php
    $metas = "";
    $pageTitle = "";
    if (isset($metas_title) && !empty($metas_title)) {
        $pageTitle = $metas_title;
    } else {
        $pageTitle = $website_name . ' | ' . config('youboat.' . $country_code . '.default_metas_title') ;
    }
    View::share('pageTitle',$pageTitle);
    $metas .= "\n" .'<title>' . $metas_title . '</title>';

    if (isset($metas_description) && !empty($metas_description)) {
        $metas .= "\n" .'<meta name="description" content="' . $metas_description . '">';
    } else {
        $metas .= "\n" .'<meta name="description" content="' . config('youboat.' . $country_code . '.default_metas_description') . '">';
    }
    /*if (isset($metas_keywords) && !empty($metas_keywords)) {
        $metas .= "\n" .'<meta name="keywords" content="' . $metas_keywords . '">';
    } else {
        $metas .= "\n" .'<meta name="keywords" content="' . config('youboat.' . $country_code . '.default_metas_keywords') . '">';
    }*/

    if (isset($metas_og) && !empty($metas_og)) {
        foreach($metas_og as $k => $v) {
            $metas .= '<meta id="' . $k . '" property="' . $v['property'] . '" content="' . $v['content'] . '">';
        }
    }

    echo $metas;
?>
<?php
    $metas_default = "";
    //$metas_default .= "\n" .'<title>' . config('youboat.' . $country_code . '.default_metas_title') . '</title>';
    $metas_default .= "\n" .'<title>' . trans('metas.title', ['website_name' => $website_name]) . '</title>';
    //$metas_default .= "\n" .'<meta name="description" content="' . config('youboat.' . $country_code . '.default_metas_description') . '">';
    $metas_default .= "\n" .'<meta name="description" content="' . trans('metas.description') . '">';
    //$metas_default .= "\n" .'<meta name="keywords" content="' . config('youboat.' . $country_code . '.default_metas_keywords') . '">';
?>
@yield('metas', $metas_default)
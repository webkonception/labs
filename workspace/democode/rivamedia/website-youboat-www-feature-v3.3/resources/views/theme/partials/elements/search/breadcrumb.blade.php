<?php
    $breadcrumbLinks = [];
    $breadcrumb = '';
    $url = '';
    $manufacturer_url = '';

    $navigation = 'for_sale';
    $breadcrumbLinks[] = ['navigation' => $navigation];

    // adstypes urls
    if (isset($adstype['rewrite_url'])) {
        $breadcrumbLinks[] = ['adstypes' => $adstype['rewrite_url']];
    }
    // categories urls
    if (isset($category['rewrite_url'])) {
        $breadcrumbLinks[] = ['categories' => $category['rewrite_url']];
    }
    // subcategories urls
    if (isset($subcategory['rewrite_url'])) {
        $breadcrumbLinks[] = ['subcategories' => $subcategory['rewrite_url']];
    }

    if (isset($manufacturer['rewrite_url'])) {
        $subnavigation = isset($manufacturer['rewrite_url']) ? 'manufacturers' : '';
    } else if (isset($manufacturerengine['rewrite_url'])) {
        $subnavigation = isset($manufacturerengine['rewrite_url']) ? 'manufacturers_engines' : '';
    }

    // manufacturers urls
    if (isset($manufacturer['rewrite_url'])) {
        $breadcrumbLinks[] = ['manufacturer' => $manufacturer['rewrite_url']];
    }

    // models urls
    if (isset($model['rewrite_url'])) {
        $breadcrumbLinks[] = ['model' => $model['rewrite_url']];
    }

    // manufacturersengines urls
    if (isset($manufacturerengine['rewrite_url'])) {
        $breadcrumbLinks[] = ['manufacturerengine' => $manufacturerengine['rewrite_url']];
    }
    // modelsengines urls
    if (isset($modelengine['rewrite_url'])) {
        $breadcrumbLinks[] = ['modelengine' => $modelengine['rewrite_url']];
    }

    $i = 1;
    foreach ($breadcrumbLinks as $key => $val) {
        foreach ($val as $key_nav => $val_nav) {
            $url .= '/' . trans('routes.' . str_replace('-', '_', $val_nav));
            if (preg_match("/manufacturer/i", $key_nav)) {
                $manufacturer_url = '/' . trans('routes.' . str_replace('-', '_', $navigation));
                $manufacturer_url .= '/' . trans('routes.' . str_replace('-', '_', $subnavigation));
                $manufacturer_url .= '/' . $val_nav;

                if (count($breadcrumbLinks) == $i) {
                    $breadcrumb .= '<li class="active">' . ucwords(str_slug($val_nav, ' ')) . '</li>';
                    //$breadcrumb .= '<li class="active"><a href="' . url($manufacturer_url) . '" title="' . ucwords(str_slug($val_nav, ' ')) . '">' . ucwords(str_slug($val_nav, ' ')) . '</a></li>';
                } else {
                    $breadcrumb .= '<li><a href="' . url($manufacturer_url) . '" title="' . ucwords(str_slug($val_nav, ' ')) . '">' . ucwords(str_slug($val_nav, ' ')) . '</a></li>';
                }
            } else if (preg_match("/model/i", $key_nav)) {
                if (count($breadcrumbLinks) == $i) {
                    $breadcrumb .= '<li class="active">' . ucwords(str_slug($val_nav, ' ')) . '</li>';
                    //$breadcrumb .= '<li class="active"><a href="' . url($manufacturer_url . '/' . $val_nav) . '" title="' . ucwords(str_slug($val_nav, ' ')) . '">' . ucwords(str_slug($val_nav, ' ')) . '</a></li>';
                } else {
                    $breadcrumb .= '<li><a href="' . url($manufacturer_url . '/' . $val_nav) . '" title="' . ucwords(str_slug($val_nav, ' ')) . '">' . ucwords(str_slug($val_nav, ' ')) . '</a></li>';
                }
            } else {
                if (count($breadcrumbLinks) == $i) {
                    $breadcrumb .= '<li class="active">' . trans($key_nav . '.' . $val_nav) . '</li>';
                } else {
                    $breadcrumb .= '<li>' . link_trans_url($url, $key_nav . '.' . $val_nav) . '</li>';
                }
            }
            $i++;
        }
    }
    if (!empty($search_query)) {
        $breadcrumb .= '<li class="accent-color-danger">' . ucwords($search_query) . '</li>';
    }
?>
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url('/') }}" title="{!! trans('navigation.home') !!}">{!! trans('navigation.home') !!}</a></li>
        @if ($breadcrumb)
            {!! $breadcrumb !!}
        @endif
    </ol>
@endsection
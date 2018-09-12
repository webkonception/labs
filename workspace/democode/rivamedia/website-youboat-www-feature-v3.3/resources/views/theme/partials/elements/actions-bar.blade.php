<?php
    $ListingSortBtnLabel = '';
    /*if (!empty($datasRequest['sort_by']) && $datasRequest['sort_by'] == 'ad_price-asc') {
        $ListingSortBtnLabel = trans('filters.price') . ' (' . trans('filters.low_to_high') .')';
    } else if (!empty($datasRequest['sort_by']) && $datasRequest['sort_by'] == 'ad_price-desc') {
        $ListingSortBtnLabel = trans('filters.price') . ' (' . trans('filters.high_to_low') .')';
    } else if (!empty($datasRequest['sort_by']) && $datasRequest['sort_by'] == 'model-asc') {
        $ListingSortBtnLabel = trans('filters.model') . ' (' . trans('filters.low_to_high') .')';
    } else if (!empty($datasRequest['sort_by']) && $datasRequest['sort_by'] == 'model-desc') {
        $ListingSortBtnLabel = trans('filters.model') . ' (' . trans('filters.high_to_low') .')';
    } else if (!empty($datasRequest['sort_by']) && $datasRequest['sort_by'] == 'year_built-asc') {
        $ListingSortBtnLabel = trans('filters.year_built') . ' (' . trans('filters.low_to_high') .')';
    } else if (!empty($datasRequest['sort_by']) && $datasRequest['sort_by'] == 'year_built-desc') {
        $ListingSortBtnLabel = trans('filters.year_built') . ' (' . trans('filters.high_to_low') .')';
    } else if (!empty($datasRequest['sort_by']) && $datasRequest['sort_by'] == 'title-asc') {
        $ListingSortBtnLabel = trans('filters.title') . ' (' . trans('filters.low_to_high') .')';
    } else if (!empty($datasRequest['sort_by']) && $datasRequest['sort_by'] == 'title-desc') {
        $ListingSortBtnLabel = trans('filters.title') . ' (' . trans('filters.high_to_low') .')';
    }*/
    if (!empty($datasRequest['sort_by'])) {
        $by = '';
        $filterTitle = str_replace(['ad_', '-asc', '-desc', 'updated_at'], ['', '', '', ''], $datasRequest['sort_by']);
        if(preg_match('/-asc/', $datasRequest['sort_by'])) {
            $by = ' (' . trans('filters.low_to_high') .')';
        } elseif(preg_match('/-desc/', $datasRequest['sort_by'])) {
            $by = ' (' . trans('filters.high_to_low') .')';
        }
        $ListingSortBtnLabel = !empty($filterTitle) ? trans('filters.' . $filterTitle) . $by : '';
    }
?>
<div class="actions-bar">
    <div class="container">
        <div class="row">
            {{--<div class="col-md-3 col-sm-3 search-actions">
                @include('theme.partials.elements.utility-icons')
            </div>
            <div class="col-md-9 col-sm-9">--}}
            <div class="col-md-12 col-sm-12">

                <div class="btn-group pull-right results-sorter">
                    <button type="button" class="btn {!! !empty($ListingSortBtnLabel) ? 'btn-success' : 'btn-default' !!} listing-sort-btn" data-toggle="dropdown" aria-expanded="false">Sort by{!! !empty($ListingSortBtnLabel) ? ' <span>' . $ListingSortBtnLabel . '</span>' : '' !!}</button>
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu">
                        <li class="clearfix text-right">
                            {!! trans('filters.price') !!}
                            <a href="#" data-sort="ad_price-asc" title="{!! trans('filters.price') !!} ({!! trans('filters.low_to_high') !!})" class="">
                                <span class="btn btn-xs{!! !empty($datasRequest['sort_by']) && $datasRequest['sort_by'] == 'ad_price-asc' ? ' btn-success' : '' !!}">(<i class="fa fa-sort-amount-asc" aria-hidden="true"></i>)</span>
                            </a>
                            <a href="#" data-sort="ad_price-desc" title="{!! trans('filters.price') !!} ({!! trans('filters.high_to_low') !!})" class="">
                                <span class="btn btn-xs{!! !empty($datasRequest['sort_by']) && $datasRequest['sort_by'] == 'ad_price-desc' ? ' btn-success' : '' !!}">(<i class="fa fa-sort-amount-desc" aria-hidden="true"></i>)</span>
                            </a>
                        </li>
                        <li class="clearfix text-right">
                            {!! trans('filters.model') !!}
                            <a href="#" data-sort="model-asc" title="{!! trans('filters.model') !!} ({!! trans('filters.low_to_high') !!})">
                                <span class="btn btn-xs{!! !empty($datasRequest['sort_by']) && $datasRequest['sort_by'] == 'model-asc' ? ' btn-success' : '' !!}">(<i class="fa fa-sort-alpha-asc" aria-hidden="true"></i>)</span>
                            </a>
                            <a href="#" data-sort="model-desc" title="{!! trans('filters.model') !!} ({!! trans('filters.high_to_low') !!})">
                                <span class="btn btn-xs{!! !empty($datasRequest['sort_by']) && $datasRequest['sort_by'] == 'model-desc' ? ' btn-success' : '' !!}">(<i class="fa fa-sort-alpha-desc" aria-hidden="true"></i>)</span>
                            </a>
                        </li>
                        <li class="clearfix text-right">
                            {!! trans('filters.year_built') !!}
                            <a href="#" data-sort="year_built-asc" title="{!! trans('filters.year_built') !!} ({!! trans('filters.low_to_high') !!})">
                                <span class="btn btn-xs{!! !empty($datasRequest['sort_by']) && $datasRequest['sort_by'] == 'year_built-asc' ? ' btn-success' : '' !!}">(<i class="fa fa-sort-amount-asc" aria-hidden="true"></i>)</span>
                            </a>
                            <a href="#" data-sort="year_built-desc" title="{!! trans('filters.year_built') !!} ({!! trans('filters.high_to_low') !!})">
                                <span class="btn btn-xs{!! !empty($datasRequest['sort_by']) && $datasRequest['sort_by'] == 'year_built-desc' ? ' btn-success' : '' !!}">(<i class="fa fa-sort-amount-desc" aria-hidden="true"></i>)</span>
                            </a>
                        </li>
                        <li class="clearfix text-right">
                            {!! trans('filters.title') !!}
                            <a href="#" data-sort="title-asc" title="{!! trans('filters.title') !!} ({!! trans('filters.low_to_high') !!})">
                                <span class="btn btn-xs{!! !empty($datasRequest['sort_by']) && $datasRequest['sort_by'] == 'title-asc' ? ' btn-success' : '' !!}">(<i class="fa fa-sort-alpha-asc" aria-hidden="true"></i>)</span>
                            </a>
                            <a href="#" data-sort="title-desc" title="{!! trans('filters.title') !!} ({!! trans('filters.high_to_low') !!})">
                                <span class="btn btn-xs{!! !empty($datasRequest['sort_by']) && $datasRequest['sort_by'] == 'title-desc' ? ' btn-success' : '' !!}">(<i class="fa fa-sort-alpha-desc" aria-hidden="true"></i>)</span>
                            </a>
                        </li>
                        {{--<li class="{!! !empty($datasRequest['sort_by']) && $datasRequest['sort_by'] == 'updated_at-asc' ? 'active' : '' !!}"><a href="#" data-sort="updated_at-asc">{!! trans('filters.recent') !!} ({!! trans('filters.low_to_high') !!})</a></li>--}}
                        {{--<li class="{!! !empty($datasRequest['sort_by']) && $datasRequest['sort_by'] == 'updated_at-desc' ? 'active' : empty($datasRequest['sort_by']) ? 'active' : '' !!}"><a href="#" data-sort="updated_at-desc">{!! trans('filters.recent') !!} ({!! trans('filters.high_to_low') !!})</a></li>--}}
                    </ul>
                </div>

                <div class="toggle-view view-count-choice pull-right">
                    <label>Show</label>
                    <div class="btn-group">
                        <a href="#" class="btn btn-default {!! !empty($datasRequest['max']) && $datasRequest['max'] == 10 ? 'active' : '' !!}">10</a>
                        <a href="#" class="btn btn-default {!! !empty($datasRequest['max']) && $datasRequest['max'] == 20 ? 'active' : empty($datasRequest['max']) ? 'active' : '' !!}">20</a>
                        <a href="#" class="btn btn-default {!! !empty($datasRequest['max']) && $datasRequest['max'] == 50 ? 'active' : '' !!}">50</a>
                    </div>
                </div>

                <div class="hidden-xs toggle-view view-format-choice pull-right">
                    <label>View</label>
                    <div class="btn-group">
                        <a href="#" class="btn btn-default {!! !empty($datasRequest['results_view']) && $datasRequest['results_view'] == 'list' ? 'active' : ''  !!}" data-sort="list" id="results-list-view"><i class="fa fa-th-list"></i></a>
                        <a href="#" class="btn btn-default {!! !empty($datasRequest['results_view']) && $datasRequest['results_view'] == 'grid' ? 'active' : empty($datasRequest['results_view'])  ? 'active' : ''  !!}" data-sort="grid" id="results-grid-view"><i class="fa fa-th"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-xs-10 col-xs-offset-1 visible-xs hidden-sm">
                <button class="btn btn-primary btn-block" id="Show-Filters">{!! trans('filters.advanced_search_filters') !!}<span class="fa fa-filter fa-fw"></span></button>
            </div>
        </div>
    </div>
</div>

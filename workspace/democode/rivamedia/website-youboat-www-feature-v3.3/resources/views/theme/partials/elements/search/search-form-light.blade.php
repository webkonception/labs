        {!! Form::open(array('url'=>trans_route($currentLocale, 'routes.for_sale'), 'id'=>'search_form_for_sale', 'autocomplete'=>'off', 'method'=>'GET')) !!}
        {!! Form::hidden('country_code', $country_code) !!}
        @if (!empty($max)){!! Form::hidden('max', $max, ['id'=>'_max_']) !!}@endif
        @if (!empty($page)){!! Form::hidden('page', $page, ['id'=>'_page_']) !!}@endif
        @if (!empty($sort_by)){!! Form::hidden('sort_by', $sort_by, ['id'=>'_sort_by_']) !!}@endif
        @if (!empty($results_view)){!! Form::hidden('results_view', $results_view, ['id'=>'_results_view_']) !!}@endif

        @if (!empty($datasRequest['adstypes_id'])){!! Form::hidden('adstypes_id', $datasRequest['adstypes_id'], ['id'=>'_adstypes_id_']) !!}@endif
        @if (!empty($datasRequest['categories_ids'])){!! Form::hidden('categories_ids', $datasRequest['categories_ids'], ['id'=>'_categories_ids_']) !!}@endif
        @if (!empty($datasRequest['subcategories_ids'])){!! Form::hidden('subcategories_ids', $datasRequest['subcategories_ids'], ['id'=>'_subcategories_ids_']) !!}@endif
        @if (!empty($datasRequest['manufacturers_id'])){!! Form::hidden('manufacturers_id', $datasRequest['manufacturers_id'], ['id'=>'_manufacturers_id_']) !!}@endif
        @if (!empty($datasRequest['min_year_built'])){!! Form::hidden('min_year_built', $datasRequest['min_year_built'], ['id'=>'_min_year_built_']) !!}@endif
        @if (!empty($datasRequest['max_year_built'])){!! Form::hidden('max_year_built', $datasRequest['max_year_built'], ['id'=>'_max_year_built_']) !!}@endif
        @if (!empty($datasRequest['min_length'])){!! Form::hidden('min_length', $datasRequest['min_length'], ['id'=>'_min_length_']) !!}@endif
        @if (!empty($datasRequest['max_length'])){!! Form::hidden('max_length', $datasRequest['max_length'], ['id'=>'_max_length_']) !!}@endif
        @if (!empty($datasRequest['min_width'])){!! Form::hidden('min_width', $datasRequest['min_width'], ['id'=>'_min_width_']) !!}@endif
        @if (!empty($datasRequest['max_width'])){!! Form::hidden('max_width', $datasRequest['max_width'], ['id'=>'_max_width_']) !!}@endif
        @if (!empty($datasRequest['sell_type'])){!! Form::hidden('sell_type', $datasRequest['sell_type'], ['id'=>'_sell_type_']) !!}@endif
        @if (!empty($datasRequest['page'])){!! Form::hidden('page', $datasRequest['page'], ['id'=>'_page_']) !!}@endif

        <div class="form-group">
            <div class="input-group input-group-lg">
                {!! Form::text('query', !empty($search_query) ? $search_query : old('query'), ['id'=>'query', 'class'=>'form-control', 'placeholder'=>trans('navigation.form_search_placeholder')]) !!}
                {{--{!! Form::text('query', old('query'), ['class'=>'form-control', 'placeholder'=>trans('navigation.form_search_placeholder')]) !!}--}}
                <span class="input-group-btn">
                    {!! Form::button('<span class="hidden-xs">' . trans('navigation.search') . '</span><i class="visible-xs fa fa-search"></i>', ['type' => 'submit', 'data-ga'=>$view_name . '~' . trans('navigation.search'), 'class' => 'btn btn-primary']) !!}
                </span>
            </div>
        </div>
        {!! Form::close() !!}

@extends('layouts.' . (preg_match('/landing/', $currentRoute) ? 'landing' : 'theme'))
<?php
    $metas_title = trans('metas.news_create_title');
    $metas_description = trans('metas.news_create_desc');
    $metas = [
            'metas_title' => $metas_title . ' - ' . $website_name,
            'metas_description' => $metas_description
        //,'metas_keywords' => $metas_keywords
    ];
?>

@section('metas')
    @include('theme.partials.elements.block.metas-block', $metas)
@endsection

@section('content')
    <div class="row">

        <div class="col-sm-12">
            @if(Session::has('errors'))
                <?php
                $message_modal = '<ul>';
                $message_modal .= implode('', $errors->all('<li>:message</li>'));
                $message_modal .= '</ul>';
                ?>
                @include('theme.partials.modals.msg-modal', ['form_referrer'=>'form_news_create', 'title_modal'=>trans('navigation.news'),'message_modal'=>$message_modal, 'message_type'=>'error'])
            @endif

            <div class="well row">
                <div class="well well-white clearfix">
                    <div class="col-xs-12 col-sm-9">
                        <h2>{!! trans('news.title_page') !!}</h2>
                        <p>{!! trans('news.desc', ['website_name' => $website_name]) !!}</p>
                    </div>
                    <div class="hidden-xs col-sm-3">
                        {!! image(thumbnail(asset('/assets/img/news.png'), 150, 150, false, false, true, 100), trans('news.news'), ['class'=>'img-thumbnail img-illus pull-right']) !!}
                    </div>
                </div>

                {!! Form::open(array('url'=>trans_route($currentLocale, 'routes.news_create'), 'class'=>'form', 'id'=>'form_news_create', 'role'=>'form', 'files'=>true)) !!}
                    {!! csrf_field() !!}
                    {!! Form::hidden('country_code', $country_code) !!}

                    <section class="row">
                        <div class="col-sm-6">
                            <?php
                            $label_txt = ucfirst(trans('validation.attributes.name')) . ' (' . trans('news.author') . ')';
                            $placeholder = trans('navigation.form_enter_placeholder');
                            $input_name = 'author_name';
                            $attributes = [
                                    'required' => 'required',
                                    'data-placeholder' => $placeholder,
                                    'placeholder' => $placeholder,
                                    'class' => 'form-control',
                                    'id' => $input_name
                            ];
                            $css_state = '';
                            $help_block = '';
                            if (!empty($$input_name)) {
                                $css_state = 'has-success';
                            }
                            if ($errors->has($input_name)) {
                                $css_state = 'has-error';
                                $help_block = '<span class="help-block bg-danger"><strong class="text-danger">' . $errors->first($input_name) . '</strong></span>';
                            }
                            $input_value = !empty($$input_name) ? $$input_name : old($input_name);
                            ?>
                            <div class="form-group {!! $css_state !!}">
                                {!! Form::label($input_name, $label_txt, ['class'=>'control-label']) !!}
                                {!! Form::text($input_name, $input_value, $attributes) !!}
                                {!! $help_block !!}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <?php
                            $label_txt = ucfirst(trans('validation.attributes.email')) . ' (' . trans('news.author') . ')';
                            $placeholder = trans('navigation.form_enter_placeholder');
                            $input_name = 'author_email';
                            $attributes = [
                                    'required' => 'required',
                                    'data-placeholder' => $placeholder,
                                    'placeholder' => $placeholder,
                                    'class' => 'form-control',
                                    'id' => $input_name
                            ];
                            $css_state = '';
                            $help_block = '';
                            if (!empty($author_email)) {
                                $css_state = 'has-success';
                            }
                            if ($errors->has($input_name)) {
                                $css_state = 'has-error';
                                $help_block = '<span class="help-block bg-danger"><strong class="text-danger">' . $errors->first($input_name) . '</strong></span>';
                            }
                            $input_value = !empty($author_email) ? $author_email : old($input_name);
                            ?>
                            <div class="form-group {!! $css_state !!}">
                                {!! Form::label($input_name, $label_txt, ['class'=>'control-label']) !!}
                                {!! Form::email($input_name, $input_value, $attributes) !!}
                                {!! $help_block !!}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <?php
                            $label_txt = ucfirst(trans('validation.attributes.phone')) . ' (' . trans('news.author') . ')';
                            $placeholder = trans('navigation.form_enter_placeholder');
                            $input_name = 'author_phone';
                            $attributes = [
                                    'data-placeholder' => $placeholder,
                                    'placeholder' => $placeholder,
                                    'class' => 'form-control',
                                    'id' => $input_name
                            ];
                            $css_state = '';
                            $help_block = '';
                            if (!empty($$input_name)) {
                                $css_state = 'has-success';
                            }
                            if ($errors->has($input_name)) {
                                $css_state = 'has-error';
                                $help_block = '<span class="help-block bg-danger"><strong class="text-danger">' . $errors->first($input_name) . '</strong></span>';
                            }
                            $input_value = !empty($$input_name) ? $$input_name : old($input_name);
                            ?>
                            <div class="form-group {!! $css_state !!}">
                                {!! Form::label($input_name, $label_txt, ['class'=>'control-label']) !!}
                                {!! Form::tel($input_name, $input_value, $attributes) !!}
                                {!! $help_block !!}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <?php
                            $label_txt = trans('news.author_website_url') . ' (' . trans('news.author') . ')';
                            $placeholder = trans('navigation.form_enter_placeholder');
                            $input_name = 'author_url';
                            $attributes = [
                                    'data-placeholder' => $placeholder,
                                    'placeholder' => $placeholder,
                                    'class' => 'form-control',
                                    'id' => $input_name
                            ];
                            $css_state = '';
                            if (!empty($$input_name)) {
                                $css_state = 'has-success';
                            }
                            if ($errors->has($input_name)) {
                                $css_state = 'has-error';
                                $help_block = '<span class="help-block bg-danger"><strong class="text-danger">' . $errors->first($input_name) . '</strong></span>';
                            }
                            $input_value = !empty($$input_name) ? $$input_name : old($input_name);
                            ?>
                            <div class="form-group {!! $css_state !!}">
                                {!! Form::label($input_name, $label_txt, ['class'=>'control-label']) !!}
                                {!! Form::tel($input_name, $input_value, $attributes) !!}
                                {!! $help_block !!}
                            </div>
                        </div>
                    </section>

                    <hr>

                    <section class="row well well-white">
                        <div class="col-sm-6">
                            <?php
                            $label_txt = ucfirst(trans('validation.attributes.photo'));
                            $placeholder = trans('navigation.form_enter_placeholder');
                            $input_name = 'photo';
                            $attributes = [
                                    'required' => 'required',
                                    'class' => 'form-control',
                                    'id' => $input_name
                            ];
                            $css_state = '';
                            $help_block = '';
                            if (!empty($$input_name)) {
                                $css_state = 'has-success';
                            }
                            if ($errors->has($input_name)) {
                                $css_state = 'has-error';
                                $help_block = '<span class="help-block bg-danger pull-right"><strong class="text-danger">' . $errors->first($input_name) . '</strong></span>';
                            }
                            $input_value = !empty($$input_name) ? $$input_name : old($input_name);
                            ?>
                            <div class="form-group clearfix">
                                {!! Form::label($input_name, $label_txt, ['class'=>'pull-left control-label']) !!}
                                <div class="pull-right">
                                    {!! Form::file($input_name, $attributes) !!}
                                    {!! Form::hidden('photo_w', 1024) !!}
                                    {!! Form::hidden('photo_h', 768) !!}
                                </div>
                                {!! $help_block !!}
                            </div>
                            <?php
                            $label_txt = ucfirst(trans('validation.attributes.date')) . ' ' . trans('news.date_format_text');
                            $placeholder = trans('navigation.form_enter_placeholder');
                            $input_name = 'date';
                            $attributes = [
                                    'required' => 'required',
                                    'data-placeholder' => $placeholder,
                                    'placeholder' => $placeholder,
                                    'class' => 'form-control',
                                    'id' => $input_name
                            ];
                            $css_state = '';
                            $help_block = '';
                            if (!empty($$input_name)) {
                                $css_state = 'has-success';
                            }
                            if ($errors->has($input_name)) {
                                $css_state = 'has-error';
                                $help_block = '<span class="help-block bg-danger"><strong class="text-danger">' . $errors->first($input_name) . '</strong></span>';
                            }
                            $input_value = !empty($$input_name) ? $$input_name : old($input_name);
                            ?>
                            <div class="form-group {!! $css_state !!}">
                                {!! Form::label($input_name, $label_txt, ['class'=>'control-label']) !!}
                                {!! Form::text($input_name, $input_value, $attributes) !!}
                                {!! $help_block !!}
                            </div>
                            <?php
                            $label_txt = trans('news.title');
                            $placeholder = trans('navigation.form_enter_placeholder');
                            $input_name = 'title';
                            $attributes = [
                                    'required' => 'required',
                                    'data-placeholder' => $placeholder,
                                    'placeholder' => $placeholder,
                                    'class' => 'form-control',
                                    'id' => $input_name
                            ];
                            $css_state = '';
                            $help_block = '';
                            if (!empty($$input_name)) {
                                $css_state = 'has-success';
                            }
                            if ($errors->has($input_name)) {
                                $css_state = 'has-error';
                                $help_block = '<span class="help-block bg-danger"><strong class="text-danger">' . $errors->first($input_name) . '</strong></span>';
                            }
                            $input_value = !empty($$input_name) ? $$input_name : old($input_name);
                            ?>
                            <div class="form-group {!! $css_state !!}">
                                {!! Form::label($input_name, $label_txt, ['class'=>'control-label']) !!}
                                {!! Form::text($input_name, $input_value, $attributes) !!}
                                {!! $help_block !!}
                            </div>
                            <?php
                            $label_txt = trans('news.intro');
                            $placeholder = trans('navigation.form_enter_placeholder');
                            $input_name = 'intro';
                            $attributes = [
                                    'required' => 'required',
                                    'data-placeholder' => $placeholder,
                                    'placeholder' => $placeholder,
                                    'class' => 'form-control',
                                    'id' => $input_name,
                                    'rows' => 6
                            ];
                            $css_state = '';
                            $help_block = '';
                            if (!empty($$input_name)) {
                                $css_state = 'has-success';
                            }
                            if ($errors->has($input_name)) {
                                $css_state = 'has-error';
                                $help_block = '<span class="help-block bg-danger"><strong class="text-danger">' . $errors->first($input_name) . '</strong></span>';
                            }
                            $input_value = !empty($$input_name) ? $$input_name : old($input_name);
                            ?>
                            <div class="form-group {!! $css_state !!}">
                                {!! Form::label($input_name, $label_txt, ['class'=>'control-label']) !!}
                                {!! Form::textarea($input_name, $input_value, $attributes) !!}
                                {!! $help_block !!}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <?php
                            $label_txt = ucfirst(trans('validation.attributes.description'));
                            $placeholder = trans('navigation.form_enter_placeholder');
                            $input_name = 'description';
                            $attributes = [
                                    'required' => 'required',
                                    'data-placeholder' => $placeholder,
                                    'placeholder' => $placeholder,
                                    'class' => 'form-control',
                                    'id' => $input_name,
                                    'rows' => 12
                            ];
                            $css_state = '';
                            $help_block = '';
                            if (!empty($$input_name)) {
                                $css_state = 'has-success';
                            }
                            if ($errors->has($input_name)) {
                                $css_state = 'has-error';
                                $help_block = '<span class="help-block bg-danger"><strong class="text-danger">' . $errors->first($input_name) . '</strong></span>';
                            }
                            $input_value = !empty($$input_name) ? $$input_name : old($input_name);
                            ?>
                            <div class="form-group {!! $css_state !!}">
                                {!! Form::label($input_name, $label_txt, ['class'=>'control-label']) !!}
                                {!! Form::textarea($input_name, $input_value, $attributes) !!}
                                {!! $help_block !!}
                            </div>
                            <?php
                            $label_txt = trans('news.url');
                            $placeholder = trans('navigation.form_enter_placeholder');
                            $input_name = 'url';
                            $attributes = [
                                    //'required' => 'required',
                                    'data-placeholder' => $placeholder,
                                    'placeholder' => $placeholder,
                                    'class' => 'form-control',
                                    'id' => $input_name
                            ];
                            $css_state = '';
                            $help_block = '';
                            if (!empty($$input_name)) {
                                $css_state = 'has-success';
                            }
                            if ($errors->has($input_name)) {
                                $css_state = 'has-error';
                                $help_block = '<span class="help-block bg-danger"><strong class="text-danger">' . $errors->first($input_name) . '</strong></span>';
                            }
                            $input_value = !empty($$input_name) ? $$input_name : old($input_name);
                            ?>
                            <div class="form-group {!! $css_state !!}">
                                {!! Form::label($input_name, $label_txt, ['class'=>'control-label']) !!}
                                {!! Form::text($input_name, $input_value, $attributes) !!}
                                {!! $help_block !!}
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-sm-6">
                            <?php
                            $label_txt = trans('news.start_date');
                            $placeholder = trans('navigation.form_enter_placeholder');
                            $input_name = 'start_date';
                            $attributes = [
                                    'required' => 'required',
                                    'data-placeholder' => $placeholder,
                                    'placeholder' => $placeholder,
                                    'class' => 'form-control datepicker',
                                    'id' => $input_name
                            ];
                            $css_state = '';
                            $help_block = '';
                            if (!empty($$input_name)) {
                                $css_state = 'has-success';
                            }
                            if ($errors->has($input_name)) {
                                $css_state = 'has-error';
                                $help_block = '<span class="help-block bg-danger"><strong class="text-danger">' . $errors->first($input_name) . '</strong></span>';
                            }
                            $input_value = !empty($$input_name) ? $$input_name : old($input_name);
                            ?>
                            <div class="form-group {!! $css_state !!}">
                                {!! Form::label($input_name, $label_txt, ['class'=>'control-label']) !!}
                                <div class="input-group date">
                                    {!! Form::text($input_name, $input_value, $attributes) !!}
                                    <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                </div>
                                {!! $help_block !!}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <?php
                            $label_txt = trans('news.end_date');
                            $placeholder = trans('navigation.form_enter_placeholder');
                            $input_name = 'end_date';
                            $attributes = [
                                    'required' => 'required',
                                    'data-placeholder' => $placeholder,
                                    'placeholder' => $placeholder,
                                    'class' => 'form-control datepicker',
                                    'id' => $input_name
                            ];
                            $css_state = '';
                            $help_block = '';
                            if (!empty($$input_name)) {
                                $css_state = 'has-success';
                            }
                            if ($errors->has($input_name)) {
                                $css_state = 'has-error';
                                $help_block = '<span class="help-block bg-danger"><strong class="text-danger">' . $errors->first($input_name) . '</strong></span>';
                            }
                            $input_value = !empty($$input_name) ? $$input_name : old($input_name);
                            ?>
                            <div class="form-group {!! $css_state !!}">
                                {!! Form::label($input_name, $label_txt, ['class'=>'control-label']) !!}
                                <div class="input-group date">
                                    {!! Form::text($input_name, $input_value, $attributes) !!}
                                    <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                </div>
                                {!! $help_block !!}
                            </div>
                        </div>
                        <div class="spacer-10"></div>
                    </section>

                    <div class="form-group col-xs-12 col-sm-12 col-md-6 pull-right">
                        {!! Form::button(trans('navigation.submit'), ['type'=>'submit', 'class'=>'btn btn-lg btn-primary btn-block btn-exception']) !!}
                    </div>
                {!! Form::close() !!}
            </div>

        </div>
        <div class="col-sm-12">
            <a href="{{ url(trans_route($currentLocale, '/')) }}" title="{!! trans('navigation.back_to_home') !!}" class="btn btn-default pull-right">
                <i class="fa fa-mail-reply fa-fw"></i>
                {!! trans('navigation.back') !!}
            </a>
        </div>
    </div>
@endsection

@section('javascript')
    <script type="text/javascript">
            $(document).ready(function(){
                var $StartDate = $("#start_date");
                var $EndDate = $("#end_date");

                $StartDate.datepicker({
                    format: '{!! trans('news.date_format') !!}',
                    autoclose: true,
                    startDate: '0'
                }).on('changeDate', function (selected) {
                    var minDate = new Date(selected.date.valueOf());
                    // add a day
                    //minDate.setDate(minDate.getDate() + 1);
                    minDate.setDate(minDate.getDate());
                    $EndDate.datepicker('setStartDate', minDate);
                });
                $EndDate.datepicker({
                    format: '{!! trans('news.date_format') !!}',
                    autoclose: true,
                    startDate: '0'
                }).on('changeDate', function (selected) {
                    var maxDate = new Date(selected.date.valueOf());
                    // sub a day
                    //maxDate.setDate(maxDate.getDate() - 1);
                    maxDate.setDate(maxDate.getDate());
                    $StartDate.datepicker('setEndDate', maxDate);
                });
            });
    </script>
@endsection

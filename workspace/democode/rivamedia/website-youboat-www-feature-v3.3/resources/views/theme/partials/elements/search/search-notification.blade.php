@if(isset($datasRequest) && !empty($datasRequest['manufacturers_id']))
<?php
    $manufacturers_id = $datasRequest['manufacturers_id'];
    $manufacturer_name = Search::getManufacturerById($manufacturers_id)['name'];
    $models_id = $model_name = '';
    if(isset($datasRequest) && !empty($datasRequest['models_id'])) {
        $models_id = $datasRequest['models_id'];
        $model_name = !empty($models_id) ? Search::getModelById($models_id)['name'] : '';
    }
?>
@if(Session::has('search_notification_message'))
    @include('theme.partials.modals.msg-modal', ['form_referrer'=>'search_form_notification', 'title_modal'=>trans('search.save_your_research'),'message_modal'=>Session::get('search_notification_message.text'), 'message_type'=>Session::get('search_notification_message.type')])
    {{--{!! Session::forget('search_notification_message') !!}--}}
@elseif(Session::has('errors'))
    <?php
    $message_modal = '<ul>';
    $message_modal .= implode('', $errors->all('<li>:message</li>'));
    $message_modal .= '</ul>';
    ?>
    @include('theme.partials.modals.msg-modal', ['form_referrer'=>'search_form_notification', 'title_modal'=>trans('search.save_your_research'),'message_modal'=>$message_modal, 'message_type'=>'error'])
@endif

{!! Form::open(array('url'=>trans_route($currentLocale, 'routes.search_notification'), 'class'=>'', 'id'=>'search_form_notification', 'autocomplete'=>'off', 'method'=>'POST')) !!}
    {!! csrf_field() !!}
    {!! Form::hidden('country_code', $country_code) !!}
    {!! Form::hidden('manufacturers_id', $manufacturers_id) !!}
    {!! Form::hidden('models_id', $models_id) !!}

    <h4 class="inline">{!! trans('search.save_your_research') !!}</h4>
    <p>
        {!! trans('search.enter_your_email_to_be_notified') !!} {!! trans('navigation.for') !!}
        <br>
        <strong class="accent-color-danger">{!! $manufacturer_name !!} {!! $model_name !!}</strong>
    </p>
    <div class="form-group {{ $errors->has('ci_email') ? 'has-error' : '' }}">
        {!! Form::email('ci_email', old('ci_email'), ['class'=>'form-control', 'placeholder'=> trans('validation.attributes.email') . ' *', 'required'=>'required', 'autocomplete'=>'off']) !!}
        @if ($errors->has('ci_email'))
        <span class="help-block"><strong>{{ $errors->first('ci_email') }}</strong></span>
        @endif
    </div>
    {!! Form::button(trans('navigation.subscribe'), ['type' => 'submit', 'class' => 'btn btn-block btn-danger']) !!}
{!! Form::close() !!}
@endif
{{--
@if (Session::has('search_notification_message'))
    {!! Session::forget('search_notification_message') !!}
@endif--}}

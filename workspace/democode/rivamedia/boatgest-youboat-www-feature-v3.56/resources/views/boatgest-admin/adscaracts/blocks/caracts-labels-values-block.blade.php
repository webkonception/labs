<div class="caracts_labels_values">
    <div class="col-sm-5">{!! Form::label('', $label_caracts_labels, ['class'=>'control-label strong text-primary']) !!}</div>
    <div class="col-sm-2">
        {!! link_to('#' . $caracts_type, '<i class="fa fa-plus fa-fw"></i>' . trans('navigation.add'), ['title' => trans('navigation.add'), 'class' => 'btn btn-add btn-block btn-sm btn-success btn-exception']) !!}
    </div>
    <div class="col-sm-5">{!! Form::label('', $label_caracts_values, ['class'=>'control-label strong text-primary']) !!}</div>
    <div class="col-sm-12 labels_values">
    <?php
        $i = 0;
    ?>
        @foreach($inputsCaracts as $k => $v)
            <?php
                $i ++;
                $fieldset_state = '';

                $input_name_label = $caracts_type . '_labels';
                $label_value = isset($v["label"]) ? $v["label"] : '';
                $css_state_label = '';
                if (!empty($label_value)) {
                    $css_state_label = 'has-success';
                } else {
                    $css_state_label = 'has-error';
                }

                $label_attributes = [
                        'data-placeholder' => $placeholder,
                        'placeholder' => $placeholder,
                        'class' => 'form-control caracts_label',
                        'id' => $input_name_label . '_' . $k,
                        'data-relationship' => $caracts_type . '_value_' . $k
                ];
                if(empty($label_value) && !empty($value_value)) {
                    $label_attributes['required'] = 'required';
                    $fieldset_state = 'well-danger';
                }

                $input_name_value = $caracts_type . '_values';
                $value_value = isset($v["value"]) ? $v["value"] : '';
                $css_state_value = '';
                if (!empty($value_value)) {
                    $css_state_value = 'has-success';
                } else {
                    $css_state_value = 'has-error';
                }

                $value_attributes = [
                        'data-placeholder' => $placeholder,
                        'placeholder' => $placeholder,
                        'class' => 'form-control caracts_value',
                        'id' => $input_name_value . '_' . $k,
                        'data-relationship' => $caracts_type . '_label_' . $k
                ];
                if(empty($value_value) && !empty($label_value)) {
                    $value_attributes['required'] = 'required';
                    $fieldset_state = 'well-danger';
                }

                if(!empty($value_value) && !empty($label_value)) {
                    $fieldset_state = 'well-success';
                }
            ?>
            <fieldset id="{!! $caracts_type !!}_{!! $k !!}" class="well well-white {!! $fieldset_state !!}">
                <div class="col-sm-4">
                    <div class="form-group {!! $css_state_label !!}">
                        <input data-placeholder="{!! $placeholder !!}" placeholder="{!! $placeholder !!}" class="form-control caracts_label"
                               id="{!! $input_name_label . '_' . $k !!}"
                               data-relationship="{!! $caracts_type . '_value_' . $k !!}"
                               name="{!! $input_name_label . '[]' !!}" type="text"
                               value="{!! $label_value !!}">
                    </div>
                </div>
                <div class="col-sm-2 text-center"><i class="fa fa-arrows-h" aria-hidden="true"></i></div>
                <div class="col-sm-4">
                    <div class="form-group {!! $css_state_value !!}">
                        <input data-placeholder="{!! $placeholder !!}" placeholder="{!! $placeholder !!}" class="form-control caracts_value"
                               id="{!! $input_name_value . '_' . $k !!}"
                               data-relationship="{!! $caracts_type . '_label_' . $k !!}"
                               name="{!! $input_name_value . '[]' !!}" type="text"
                               value="{!! $value_value !!}">
                    </div>
                </div>
                <div class="col-sm-2">
                    {!! link_to('#' . $caracts_type . '_' . $k, '<i class="fa fa-trash-o fa-fw"></i>' . trans('navigation.delete'), ['title' => trans('navigation.delete'), 'class' => 'btn-delete btn btn-block btn-sm btn-danger btn-exception']) !!}
                    {!! link_to('#' . $caracts_type . '_' . $k, '<i class="fa fa-refresh fa-fw"></i>' . trans('navigation.reload'), ['title' => trans('navigation.reload'), 'class' => 'btn-reload btn btn-block btn-sm btn-primary btn-exception hidden']) !!}
                </div>
            </fieldset>
        @endforeach
    </div>
</div>
<?php
    unset($caracts_type, $fieldset_state, $css_state_label, $input_name_label, $label_value, $label_attributes, $css_state_value, $input_name_value, $value_value, $value_attributes);
?>

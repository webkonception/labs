<?php
    $country_code = isset($bodcaracts->country_code) ? $bodcaracts->country_code : '';
    $website_name = !empty($website_name) ? $website_name : config('youboat.' . $country_code . '.website_name');
?>
@extends(config('quickadmin.route') . '.layouts.master')

@section('content')

    @if ($errors->any())
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-danger">
                <ul>
                    {!! implode('', $errors->all('<li class="error">:message</li>')) !!}
                </ul>
            </div>
        </div>
    </div>
    <hr>
    @endif

    <div class="show">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs nav-justified" role="tablist">
            <li role="presentation" class="active"><a href="#contact_informations" aria-controls="contact_informations" role="tab" data-toggle="tab">{!! trans('boat_on_demand.customer_details') !!}</a></li>
            <li role="presentation"><a href="#ad_description" aria-controls="ad_description" role="tab" data-toggle="tab">{!! trans('boat_on_demand.title_whished_boat_description') !!}</a></li>
            @if($isAdmin || 'commercial' == Auth::user()->type)
                <li role="presentation" class="text-center">
                @if($isAdmin)
                    {!! Form::open(array('class' => '', 'method' => 'DELETE', 'onsubmit' => 'return confirm(\'Confirm deletion\');',  'route' => array(config('quickadmin.route') . '.bodcaracts.destroy', $bodcaracts->id))) !!}
                        {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.bodcaracts.edit', '<i class="fa fa-pencil fa-fw"></i>Edit', [$bodcaracts->id], ['class' => 'btn btn-primary'])) !!}
                        {!! Form::button('<i class="fa fa-trash-o fa-fw"></i>Delete', array('type' => 'submit', 'class' => 'pull-right btn btn-danger btn-exception')) !!}
                    {!! Form::close() !!}
                 @else
                     {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.bodcaracts.edit', '<i class="fa fa-pencil fa-fw"></i>Edit', [$bodcaracts->id], ['class' => 'btn text-primary'])) !!}
                @endif
                </li>
            @endif
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="contact_informations">
                @include('boatgest-admin.bodcaracts.elements.show.contact-informations-form', ['countries'=>$countries, 'website_name'=>$website_name])
            </div>
            <div role="tabpanel" class="tab-pane" id="ad_description">
                @include('boatgest-admin.bodcaracts.elements.show.filters-form-bod', [])

                @include('boatgest-admin.bodcaracts.elements.show.recovery-form', [])
            </div>
        </div>
    </div>

    @if($isAdmin || 'commercial' == Auth::user()->type)
        <hr>
        <section class="well well-white">
            <div class="row">
                @if($isAdmin)
                    <div class="col-sm-12">
                        <div class="row lead strong">
                            <?php
                            $reference = isset($bodcaracts->reference) ? $bodcaracts->reference : '';
                            $label_txt = 'Ref.';
                            ?>
                            <div class="text-primary col-xs-12 col-sm-4">{!! $label_txt !!}&nbsp;:&nbsp;</div>
                            <div class="col-xs-12 col-sm-8">{!! $reference !!}</div>
                        </div>
                    </div>
                @endif
                <div class="col-sm-12">
                    <div class="row lead strong">
                        <?php
                        $deposit_date = isset($bodcaracts->updated_at) ? $bodcaracts->updated_at : '';
                        $label_txt = ucfirst(trans('boat_on_demand.deposit_date'));
                        ?>
                        <div class="text-primary col-xs-12 col-sm-4">{!! $label_txt !!}&nbsp;:&nbsp;</div>
                        <div class="col-xs-12 col-sm-8">{!! $deposit_date !!}</div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <hr>
@endsection

@section('javascript')
@endsection
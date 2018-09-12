<div class="row welcome">
    <div class="col-md-6">
        <h1 class="uppercase strong accent-color">{!! trans('elements.welcome.title', ['website_name'=>$website_name]) !!}</h1>
        <p class="lead">{!! trans('elements.welcome.lead', ['website_name'=>$website_name]) !!}</p>
    </div>
    <div class="col-md-6">
        <blockquote>
            <p>{!! trans('landing.landing_text_01') !!}</p>
            <p>{!! trans('landing.landing_text_02') !!}</p>
            <p class="text-right">
                {!! link_trans_route('about', 'navigation.about_us', ['class'=>'btn btn-info btn-lg']) !!}
            </p>
        </blockquote>
    </div>
</div>

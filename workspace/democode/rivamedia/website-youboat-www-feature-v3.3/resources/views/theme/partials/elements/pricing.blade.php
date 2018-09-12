<?php
    $pricing_currency = isset(config('youboat.' . $country_code . '.currency')) ? config('youboat.' . $country_code . '.currency') : trans('pricing.currency');
    $starter_price = 9;
    $pro_price = 19;
    $premium_price = 29;
?>
<div class="lgray-bg padding-tb45">
    <div class="container">
        <div class="text-align-center">
            <h2>{!! trans('pricing.starter') !!}</h2>
        </div>
        <div class="spacer-10"></div>
        <div class="pricing-table three-cols margin-0">
            <div class="pricing-column">
                <h3>{!! trans('pricing.basic') !!}</h3>
                <div class="pricing-column-content">
                    <h4> <span class="dollar-sign">{!! $pricing_currency !!}</span> {{ $starter_price }} </h4>
                    <span class="interval">{!! trans('pricing.until_sold') !!}</span>
                    <ul class="features">
                        <li>...</li>
                        <li>... <a href="#" data-toggle="popover" data-trigger="focus" data-placement="right" data-content="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla convallis egestas rhoncus."><i class="fa fa-info-circle"></i></a></li>
                        <li>... <a href="#" data-toggle="popover" data-trigger="focus" data-placement="right" data-content="<i class='fa fa-binoculars fa-3x text-warning'></i><br>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla convallis egestas rhoncus."><i class="fa fa-info-circle"></i></a></li>
                    </ul>
                    <a class="btn btn-primary" href="add-listing-form.html">{!! trans('pricing.create_ad_now') !!}</a>
                </div>
            </div>
            <div class="pricing-column highlight accent-color">
                <h3>{!! trans('pricing.pro') !!}<span class="highlight-reason">{!! trans('pricing.most_popular) !!}</span></h3>
                <div class="pricing-column-content">
                    <h4> <span class="dollar-sign">{!! $pricing_currency !!}</span> {{ $pro_price }} </h4>
                    <span class="interval">{!! trans('pricing.until_sold') !!}</span>
                    <ul class="features">
                        <li>... <a href="#" data-toggle="popover" data-trigger="focus" data-placement="right" data-content="<i class='fa fa-cc-visa fa-3x text-info'></i><br>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla convallis egestas rhoncus."><i class="fa fa-info-circle"></i></a></li>
                        <li>...</li>
                        <li>...</li>
                        <li>...</li>
                    </ul>
                    <a class="btn btn-info" href="add-listing-form.html">{!! trans('pricing.create_ad_now') !!}</a>
                </div>
            </div>
            <div class="pricing-column">
                <h3>{!! trans('pricing.premium') !!}</h3>
                <div class="pricing-column-content">
                    <h4> <span class="dollar-sign">{!! $pricing_currency !!}</span> {{ $premium_price }} </h4>
                    <span class="interval">{!! trans('pricing.until_sold') !!}</span>
                    <ul class="features">
                        <li>...</li>
                        <li>...</li>
                        <li>...</li>
                        <li>...</li>
                    </ul>
                    <a class="btn btn-primary" href="add-listing-form.html">{!! trans('pricing.create_ad_now') !!}</a>
                </div>
            </div>
        </div>
        <div class="spacer-30"></div>
    </div>
</div>
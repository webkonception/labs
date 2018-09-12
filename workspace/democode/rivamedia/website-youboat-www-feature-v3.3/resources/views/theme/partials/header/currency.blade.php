<?php
    $currentLocale = isset($currentLocale) ? $currentLocale : LaravelLocalization::getCurrentLocale();
    $getSupportedCurrencies = [
        'de'          => ['currencies' => ['euro', 'dollar']],
        'en'          => ['currencies' => ['euro', 'dollar', 'gbp']],
        'es'          => ['currencies' => ['euro', 'dollar']],
        'fr'          => ['currencies' => ['euro', 'dollar']],
        'it'          => ['currencies' => ['euro', 'dollar']],
        'pt'          => ['currencies' => ['euro', 'dollar']],
        'us'          => ['currencies' => ['euro', 'dollar']],
    ];
?>
<li>
    <a href="#" data-toggle="dropdown">Currency <span class="caret"></span></a>
    <ul class="dropdown-menu" role="menu" aria-labelledby="currencydropdown">
    @foreach ($getSupportedCurrencies[$currentLocale] as $currencies)
        @foreach ($currencies as $key => $currency)
        <li><a href="#" title="{{ $currency }}"><i class="fa fa-{{ $currency }}"></i></a></li>
        @endforeach
    @endforeach
    </ul>
</li>
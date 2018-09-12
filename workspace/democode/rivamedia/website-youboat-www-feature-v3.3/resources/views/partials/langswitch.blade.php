<?php
    debug('>> langswitch <<');
    $currentLocale = isset($currentLocale) ? $currentLocale : LaravelLocalization::getCurrentLocale();
    $getSupportedLocales = LaravelLocalization::getSupportedLocales();
    $configAppLanguages = config('app.languages');

    foreach($configAppLanguages as $key => $localeCode) {
        if (!array_key_exists($localeCode, $getSupportedLocales)) {
            unset($configAppLanguages[array_search($localeCode, $configAppLanguages)]);
            config(['app.languages' => $configAppLanguages]);
        }
    }
?>
@if (count($configAppLanguages) > 0)
<ul class="nav navbar-nav navbar-right">
    <li class="{{ $currentLocale }} dropdown">
        <a href="#" hreflang="{{ $currentLocale }}" class="dropdown-toggle" data-toggle="dropdown">
            {!! ucfirst($getSupportedLocales[$currentLocale]['native']) !!}
        </a>

        <ul id="language_chooser" class="dropdown-menu">
            @foreach ($getSupportedLocales as $localeCode => $properties)
                @if ($localeCode != $currentLocale && in_array($localeCode, $configAppLanguages))
                    <li class="{{ $localeCode }}" role="presentation">
                        <a rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode) }}" role="menuitem" title="{{ ucfirst($localeCode) }}">
                            <span class="flag-icon flag-icon-{{ $localeCode }}"></span>&nbsp;{{ ucfirst($properties['native']) }}
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </li>
</ul>
@endif
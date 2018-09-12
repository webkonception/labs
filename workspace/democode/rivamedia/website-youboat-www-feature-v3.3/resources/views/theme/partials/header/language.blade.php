<?php
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
<li class="{!! $currentLocale !!}">
	<a href="#" hreflang="{!! $currentLocale !!}" data-toggle="dropdown"><i class="fa fa-2x fa-globe fa-fw" title="{!! ucfirst(trans('language')) !!}"></i><span class="caret"></span></a>
	<ul class="dropdown-menu" role="menu" aria-labelledby="languagedropdown" id="languagedropdown">@foreach ($getSupportedLocales as $localeCode => $properties)
		@if ($localeCode != $currentLocale && in_array($localeCode, $configAppLanguages))<li class="{!! $localeCode !!}" role="presentation">
		<?php
		$index = (config('app.fallback_locale') != $currentLocale) ? 2 : 1;
		$addurl = '';

		if (preg_match("/\/{/i", Route::getCurrentRoute()->getPath())) {
			list($url) = explode("/{", Route::getCurrentRoute()->getPath());

			$langFiles      = array_slice(explode("/", str_replace(['{','}'], ['',''], Route::getCurrentRoute()->getPath())),$index);
			$routesKeys     = array_slice(explode("/", Request::path()),$index);
			$array          = Lang::get('routes');

			foreach($routesKeys as $key => $val) {
			$routeKey   = array_search($val, $array);
			$addurl     .= '/' . Lang::get('routes.' . $routeKey,[],$localeCode);
		}
		?>
		<a rel="alternate" hreflang="{!! $localeCode !!}" href="{!! LaravelLocalization::getLocalizedURL($localeCode, $url) . $addurl !!}" role="menuitem" title="{!! ucfirst($localeCode) !!}">{!! ucfirst($localeCode) !!}</a>
		<?php } else {?>
		<a rel="alternate" hreflang="{!! $localeCode !!}" href="{!! LaravelLocalization::getLocalizedURL($localeCode) !!}" role="menuitem" title="{!! ucfirst($localeCode) !!}"><img class="img-flag" src="{!! asset('assets/img/flags/' . strtoupper(str_replace('en','gb', $localeCode)) . '.png') !!}" alt="{!! ucfirst($properties['native']) !!}"></a>
		<?php
		}
		?>
		</li>@endif
	@endforeach</ul>
</li>
@endif
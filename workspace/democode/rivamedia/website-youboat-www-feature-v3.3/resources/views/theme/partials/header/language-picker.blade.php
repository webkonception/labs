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

$language_list = '';
if (count($configAppLanguages) > 1) {
	$language_list = '<ul class="dropdown-menu" role="menu" aria-labelledby="languagedropdown" id="languagedropdown">';

	foreach ($getSupportedLocales as $localeCode => $properties) {
		if ($localeCode != $currentLocale && in_array($localeCode, $configAppLanguages)) {
			$language_list .= '<li class="'. $localeCode .'" role="presentation">';

			//$index = (config('app.fallback_locale') != $currentLocale) ? 2 : 1;

			$addurl = '';
			$path = Route::getCurrentRoute()->getPath();
			//var_dump($path);
			//var_dump(preg_match("/\/{/i", $path));
			if (preg_match("/\/{/i", $path)) {
				list($url) = explode("/{", $path);
				//$langFiles = array_slice(explode("/", str_replace(['{', '}'], ['', ''], $path)));
				//$routesKeys = array_slice(explode("/", Request::path()), $index);
				$routesKeys = array_slice(explode("/", Request::path()), 2);
				$array = Lang::get('routes');

				foreach ($routesKeys as $key => $val) {
					$routeKey = array_search($val, $array);
					if(Lang::has('routes.' . $routeKey, [], $localeCode)) {
						$addurl .= '/' . Lang::get('routes.' . $routeKey, [], $localeCode);
					} else {
						$addurl .= '/' . $val;
					}
				}
				$url = LaravelLocalization::getLocalizedURL($localeCode, $url) . $addurl;
			} else {
				$url = LaravelLocalization::getLocalizedURL($localeCode);
			}
			$language_list .= '<a rel="alternate" hreflang="' . $localeCode . '" href="' . $url . '" role="menuitem" title="' . ucfirst($localeCode) . '"><img class="img-flag" src="' . asset('assets/img/flags/' . strtoupper(str_replace('en','gb', $localeCode)) . '.png') . '" alt="' . ucfirst($properties['native']) . '"></a>';
			$language_list .= '</li>';
		}
	}
	$language_list .= '</ul>';
}
?>
@if (count($configAppLanguages) > 1)
	<li class="{{ $currentLocale }}">
		<a href="#" hreflang="{{ $currentLocale }}" data-toggle="dropdown"><i class="fa fa-2x fa-globe fa-fw" title="{!! ucfirst(trans('language')) !!}"></i><span class="caret"></span></a>{!! $language_list !!}</li>
@endif
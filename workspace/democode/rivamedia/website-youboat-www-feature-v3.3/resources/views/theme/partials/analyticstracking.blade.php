<?php
    $title_page = !empty($title_page) ? $title_page : trans('navigation.' . $view_name);
?>
{!! "<script>
		var CustomDimensions = '" . $custom_dimensions . "';

		var PageviewName = '" . $view_name . "',
		GA_Prefix_TagName 	= '',
		trackPageviewName 	= GA_Prefix_TagName + PageviewName,
		titlePage = '" . addslashes($title_page) . "',
		UA_GA = '" . $ua . "',
		cookieName_GA = '" . mb_strtoupper(str_slug($website_name, '_')) . "',
		cookieDomain_GA = '" .Request::server('HTTP_HOST') . "',
		GATracker = '" . mb_strtoupper(str_slug($website_name, '_')) . "_Tracker';

		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

		ga('create', UA_GA, 'auto', {
			'name': '" . mb_strtoupper(str_slug($website_name, '_')) . "_Tracker',
			'cookieName': cookieName_GA,
			'cookieDomain': cookieDomain_GA,
			'cookieExpires': 60 * 60 * 24 * 28
		});

        if(trackPageviewName !='' && trackPageviewName != undefined) {
            if(typeof CustomDimensions === 'object') {
                ga('" . mb_strtoupper(str_slug($website_name, '_')) . "_Tracker.send', 'pageview', CustomDimensions);
            } else {
                ga('" . mb_strtoupper(str_slug($website_name, '_')) . "_Tracker.send', {
                    'hitType': 'pageview',
                    'page': window.location.pathname + window.location.search + window.location.hash,
                    'title': titlePage
                });
            }
        } else {
            if(typeof CustomDimensions === 'object'){
                ga('" . mb_strtoupper(str_slug($website_name, '_')) . "_Tracker.send', 'pageview', CustomDimensions);
            } else {
                ga('" . mb_strtoupper(str_slug($website_name, '_')) . "_Tracker.send', 'pageview');
            }
        }
  </script>" !!}

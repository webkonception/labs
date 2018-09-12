<?php
	define('REGLEMENT_TITLE', 'Pay Offers');
	define('REGLEMENT_CONFIRM_TITLE', 'Payment confirmation');
	define('REGLEMENT_CONFIRM_TEXT', '<strong class="lead big">Thank you for registering.</strong> <br>You will recieve a confirmation email shortly on your <em class="strong">%s</em> mailbox.<br>For more information please <a href="/contact-us" title="Contact us" target="_blank">contact</a> the administration team.');

	$plan = isset($_GET['plan']) && !empty($_GET['plan']) ? $_GET['plan'] : '';

	$PageViewName = 'pay';
	$tagPage = 'window.location.pathname + window.location.search + window.location.hash';
	$titlePage = 'Youboat UK | ';
	$plan_description = '';

	$pay_currency = 'gbp';
	$pay_locale = 'en';
	//require dirname(__FILE__). '/actions/pay.php';
	require $_SERVER['DOCUMENT_ROOT'] . '/pay/actions/pay.php';

	if(!isset($customer_email)) {
		$customer_email = '';
	}

	if(isset($StripePlan) && !empty($StripePlan)) {
		$result = json_decode(json_encode($StripePlan), true);
		$PageViewName .= '_' . $result["plan"]["id"];
		$CustomDimensions = "{'subscription_id': '" . $result["id"] . "', 'plan': '" . $result["plan"]["name"] . "', 'price': '" . ($result["plan"]["amount"]/100) . " " . $result["plan"]["currency"] . "'}";
		$titlePage .= 'Subscription to ' . $result["plan"]["name"];
	} else if(isset($plan) && !empty($plan)) {
		if(isset($plan_id) && !empty($plan_id)) {
				$PageViewName .= '_' . $plan_id;
		} else {
				$PageViewName .= '_plan-' . $plan;
		}
		$titlePage .= $plan_description;
	} else {
		$PageViewName .= '_offers';
		$titlePage .= 'Pay Offers';
	}
	$tagPage = "'$PageViewName'";
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
		<meta http-equiv="Content-type" content="text/html; charset=utf-8">
		<title><?php echo isset($titlePage) && !empty($titlePage) ? $titlePage : 'Motorboats, Sailing Boats and Yachts for sale - Pay Offers - Youboat UK'; ?></title>
		<meta name="description" content="Youboat is the largest website for power boats, sailing boats, rigid or semi-rigid hulled inflatables. All the adverts are placed in numerous categories such as passenger tour boats, fishing boats, day cruiser, open flybridge, keelboat sailing boats, dinghies.">
		<meta name="author" content="Youboat UK">
		<meta name="google-site-verification" content="YPlZP3O5iMDMaDsUjf3i5k2ZTwVC0oTQ0OWkJo25tkE">
		<link rel="apple-touch-icon" sizes="57x57" href="/assets/img/favicons/apple-touch-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="/assets/img/favicons/apple-touch-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="/assets/img/favicons/apple-touch-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="/assets/img/favicons/apple-touch-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="/assets/img/favicons/apple-touch-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="/assets/img/favicons/apple-touch-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="/assets/img/favicons/apple-touch-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="/assets/img/favicons/apple-touch-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="/assets/img/favicons/apple-touch-icon-180x180.png">
		<link rel="icon" type="image/png" href="/assets/img/favicons/favicon-32x32.png" sizes="32x32">
		<link rel="icon" type="image/png" href="/assets/img/favicons/favicon-194x194.png" sizes="194x194">
		<link rel="icon" type="image/png" href="/assets/img/favicons/favicon-96x96.png" sizes="96x96">
		<link rel="icon" type="image/png" href="/assets/img/favicons/android-chrome-192x192.png" sizes="192x192">
		<link rel="icon" type="image/png" href="/assets/img/favicons/favicon-16x16.png" sizes="16x16">
		<link rel="manifest" href="/assets/img/favicons/manifest.json">
		<link rel="mask-icon" href="/assets/img/favicons/safari-pinned-tab.svg" color="#2b5797">
		<link rel="shortcut icon" href="/assets/img/favicons/favicon.ico">
		<meta name="apple-mobile-web-app-title" content="YouBoat.com">
		<meta name="application-name" content="YouBoat.com">
		<meta name="msapplication-TileColor" content="#2b5797">
		<meta name="msapplication-TileImage" content="/assets/img/favicons/mstile-144x144.png">
		<meta name="msapplication-config" content="/assets/img/favicons/browserconfig.xml">
		<meta name="theme-color" content="#ffffff">
		<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" media="screen">
		<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" media="screen">
		<link rel="stylesheet" type="text/css" href="/assets/assets.php?type=bootstrap_theme-css&urls=assets/theme/css/bootstrap-theme.css" media="screen">
		<link rel="stylesheet" type="text/css" href="/assets/assets.php?type=common_01-css&urls=assets/theme/css/theme.css" media="screen">
		<!--[if lte IE 9]>
		<link rel="stylesheet" type="text/css" href="/assets/vendor/youboat/css/ie.css" media="screen" />
		<![endif]-->
		<link rel="stylesheet" type="text/css" href="/assets/assets.php?type=common_02-css&urls=assets/vendor/youboat/css/youboat.css|assets/theme/vendor/outlined-iconset/css/outlined-iconset.css|assets/theme/css/styles.css|assets/theme/css/colors/color6.css|assets/theme/uk/css/override.css" media="screen">
		<link rel="stylesheet" type="text/css" href="/pay/css/pay.css" media="screen">
		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body class="pay-plans header-v1">
		<script>
				var CustomDimensions = '';

				var PageviewName = '<?php echo $PageViewName; ?>',
				GA_Prefix_TagName 	= '',
				trackPageviewName 	= GA_Prefix_TagName + PageviewName,
				titlePage = '<?php echo $titlePage; ?>',
				tagPage = <?php echo $tagPage; ?>,
				UA_GA = 'UA-22390730-34',
				cookieName_GA = 'YOUBOAT_UK',
				cookieDomain_GA = 'uk.youboat.com',
				GATracker = 'YOUBOAT_UK_Tracker';
				<?php
						if(isset($CustomDimensions) && !empty($CustomDimensions)) {
								echo "CustomDimensions = $CustomDimensions;";
						}
				?>

				(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
					(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
					m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
				})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

				ga('create', UA_GA, 'auto', {
					'name': 'YOUBOAT_UK_Tracker',
					'cookieName': cookieName_GA,
					'cookieDomain': cookieDomain_GA,
					'cookieExpires': 60 * 60 * 24 * 28
				});
			   if(trackPageviewName !='' && trackPageviewName != undefined) {
			       if(typeof CustomDimensions === 'object') {
			           ga('YOUBOAT_UK_Tracker.send', 'pageview', CustomDimensions);
			       } else {
						 		ga('YOUBOAT_UK_Tracker.send', {
			               'hitType': 'pageview',
			               'page': tagPage,
			               'title': titlePage
		           });
			       }
			   } else {
			       if(typeof CustomDimensions === 'object'){
								ga('YOUBOAT_UK_Tracker.send', 'pageview', CustomDimensions);
			       } else {
			           ga('YOUBOAT_UK_Tracker.send', 'pageview');
			       }
			   }
		</script>
		<div class="body">
			<div class="site-header-wrapper">
				<header class="site-header">
					<div class="container sp-cont">
						<div class="col-xs-4 col-sm-3 logo">
							<a href=""><img src="/assets/theme/uk/img/logo.png" alt="Youboat UK / New and Used boats for sale"></a>
						</div>
						<div class="col-xs-8 col-sm-9 header-right"></div>
					</div>
				</header>
			</div>
			<div class="page-header">
				<div class="container">
					<h1 class="page-title"><?php echo REGLEMENT_TITLE; ?></h1>
				</div>
			</div>
			<div class="main" role="main">
				<div id="content" class="content full">

					<?php
							if(isset($pay_error) && !empty($pay_error)) {
					?>
					<div class="container">
							<div class="alert alert-danger" role="alert">
									<blockquote><?php echo $pay_error; ?></blockquote>
							</div>
					</div>
					<?php
							}
							if($success){
					?>
					<div class="container">
							<h2 class="uppercase strong text-success text-center inbox-title"><?php echo REGLEMENT_CONFIRM_TITLE; ?></h2>
							<div class="alert alert-success" role="alert">
									<div class="row">
											<blockquote class="col-sm-8 pay-success-msg pay-success-msg-infos">
												<?php echo sprintf(REGLEMENT_CONFIRM_TEXT, $customer_email); ?>
											</blockquote>
											<?php echo $success_msg; ?>
									</div>
							</div>
							<a href="/pay/" title="Back to Offers" class="btn btn-default big pull-right">
								<i class="fa fa-mail-reply fa-fw"></i>
								Back to Offers
						  </a>
					</div>
					<?php
							} else {
					?>
					<div class="container">
						<article id="pay_block">
							<section id="yearly" class="row">
								<div id="yearly_uk" class="plan col-sm-6 col-sm-offset-0 col-md-5 col-md-offset-1">
									<div class="plan-item panel panel-primary text-center">
										<div class="panel-heading">
											<i class="fa fa-3x fa-bookmark fa-fw pull-right" aria-hidden="true"></i>
											<h2>Yearly UK Offer</h2>
										</div>
										<div class="panel-body">
											<div class="btn btn-md <?php if($plan==1){ echo 'btn-primary'; } else { echo 'btn-default'; } ?>">
												<label for="yearlyukoffer">450 £</label>
												<input id="yearlyukoffer" class="payradio" type="radio" name="plan" value="1"<?php if($plan==1){ echo ' checked="checked"'; } ?>>
											</div>
										</div>
									</div>
								</div>
								<div id="yearly_uk_fr" class="plan col-sm-6 col-md-5">
									<div class="plan-item panel panel-success text-center">
										<div class="panel-heading">
											<i class="fa fa-3x fa-bookmark-o fa-fw pull-right" aria-hidden="true"></i>
											<h2>Yearly UK Offer <br>+ FR Offer</h2>
										</div>
										<div class="panel-body">
											<div class="btn btn-md <?php if($plan==2){ echo 'btn-success'; } else { echo 'btn-default'; } ?>">
												<label for="yearlyukfroffer">1 490 £</label>
												<input id="yearlyukfroffer" class="payradio" type="radio" name="plan" value="2"<?php if($plan==2){ echo ' checked="checked"'; } ?>>
											</div>
										</div>
									</div>
								</div>
							</section>
							<hr>
							<section id="monthly" class="row">
								<div id="monthly_uk" class="plan col-sm-6 col-md-4">
									<div class="plan-item panel panel-info text-center">
										<div class="panel-heading">
											<i class="fa fa-tags fa-2x fa-fw pull-right" aria-hidden="true"></i>
											<h2>Monthly UK Offer</h2>
										</div>
										<div class="panel-body">
											<div class="btn btn-md <?php if($plan==3){ echo 'btn-info'; } else { echo 'btn-default'; } ?>">
												<label for="monthlyukoffer">50 £</label>
												<input id="monthlyukoffer" class="payradio" type="radio" name="plan" value="3"<?php if($plan==3){ echo ' checked="checked"'; } ?>>
											</div>
										</div>
									</div>
								</div>
								<div id="monthly_fr" class="plan col-sm-6 col-md-4">
									<div class="plan-item panel panel-warning text-center">
										<div class="panel-heading">
											<i class="fa fa-tags fa-2x fa-fw pull-right" aria-hidden="true"></i>
											<h2>Monthly FR Offer</h2>
											<p>
												(3 months UK for free<i class="fa fa-gift fa-fw" aria-hidden="true"></i>)
											</p>
										</div>
										<div class="panel-body">
											<div class="btn btn-md <?php if($plan==4){ echo 'btn-warning'; } else { echo 'btn-default'; } ?>">
												<label for="monthlyfroffer">100 £</label>
												<input id="monthlyfroffer" class="payradio" type="radio" name="plan" value="4"<?php if($plan==4){ echo ' checked="checked"'; } ?>>
											</div>
										</div>
									</div>
								</div>
								<div id="monthly_uk_fr" class="plan col-sm-6 col-md-4">
									<div class="plan-item panel panel-danger text-center">
										<div class="panel-heading">
											<i class="fa fa-tags fa-2x fa-fw pull-right" aria-hidden="true"></i>
											<h2>Monthly UK <br>+ FR Offer</h2>
										</div>
										<div class="panel-body">
											<div class="btn btn-md <?php if($plan==5){ echo 'btn-danger'; } else { echo 'btn-default'; } ?>">
												<label for="monthlyukfroffer">145 £</label>
												<input id="monthlyukfroffer" class="payradio" type="radio" name="plan" value="5"<?php if($plan==5){ echo ' checked="checked"'; } ?>>
											</div>
										</div>
									</div>
								</div>
							</section>
							<hr>
							<section id="stripe_form" class="row <?php if(!isset($plan) || empty($plan)) { echo 'hidden'; } ?>">
								<div class="col-sm-4 col-sm-offset-4 text-center">
									<form id="payform" action="/pay/<?php echo '?plan=' . $plan; ?>" method="POST">
										<script
											src="https://checkout.stripe.com/checkout.js" class="stripe-button"
											data-key="<?php echo $stripe['publishable_key'];?>"
											data-amount="<?php echo $amount; ?>"
											data-name="YOUBOAT.COM"
											data-description="YOUBOAT.COM 2017"
											data-image="https://uk.youboat.com/assets/img/favicons/favicon-194x194.png"
											data-locale="en"
											data-zip-code="true"
											data-currency="<?php echo $pay_currency; ?>"></script>
									</form>
								</div>
							</section>
						</article>
					</div>
					<?php
						}
						?>
				</div>
				<footer class="site-footer">
					<div class="site-footer-bottom">
						<div class="container">
							<div class="row">
								<div class="col-md-4 col-sm-6 copyrights-left">
									<p>© 2016 Youboat UK All Rights Reserved</p>
								</div>
								<div class="col-md-8 col-sm-6 copyrights-right">
									<ul class="social-icons social-icons-colored pull-right">
										<li class="facebook"><a href="https://www.facebook.com/YouboatUK" class="blank"><i class="fa fa-facebook"></i></a></li>
										<li class="twitter"><a href="https://twitter.com/youboat_com" class="blank"><i class="fa fa-twitter"></i></a></li>
									</ul>
									<div class="footer_widget widget widget_custom_menu widget_links">
										<ul>
											<li class="pull-left"><a href="/terms-of-sale-and-use" title="Terms of sale and use"><i class="fa fa-angle-right"></i> Terms of sale and use</a>&nbsp;&nbsp;</li>
											<li class="pull-left"><a href="/about" title="About us"><i class="fa fa-angle-right"></i> About us</a>&nbsp;&nbsp;</li>
											<li class="pull-left"><a href="/contact-us" title="Contact us"><i class="fa fa-angle-right"></i> Contact us</a>&nbsp;&nbsp;</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				</footer>
			</div>
		</div>
		<script src="//code.jquery.com/jquery-2.2.1.min.js"></script>
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
		<script src="/pay/js/pay.php"></script>
		<?php
			if(isset($plan) && !empty($plan)) {
					echo '<script>$(function(){$(\'#payform button[type="submit"]\').trigger(\'click\');});</script>';
			}
		?>

		<!--Start of Tawk.to Script-->
		<script type="text/javascript">
			var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
			(function(){
					var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
					s1.async=true;
					s1.src='https://embed.tawk.to/588b8288c9a1bb25a1fff0df/default';
					s1.charset='UTF-8';
					s1.setAttribute('crossorigin','*');
					s0.parentNode.insertBefore(s1,s0);
			})();
		</script>
		<!--End of Tawk.to Script-->
	</body>
</html>

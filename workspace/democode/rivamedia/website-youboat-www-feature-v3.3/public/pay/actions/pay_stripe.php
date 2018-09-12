<?php
	require $_SERVER['DOCUMENT_ROOT'] . '/pay/script/stripe-php-3.23.0/init.php';
	if(!defined('RUN_TYPE')) {
		define('RUN_TYPE', 'debug');
	}

	//$plan = 1;
	//$amount = 450;
	$pay_error = '';
	$customer_email = '';

	$email = isset($_GET['email']) && !empty($_GET['email']) ? $_GET['email'] :
	(
		isset($_POST['email']) && !empty($_POST['email']) ? $_POST['email'] :
		(
			isset($datasRequest) && !empty($datasRequest['email']) ? $datasRequest['email'] : ''
		)
	);

	if(!isset($amount)) {

		$amount = isset($_GET['amount']) && !empty($_GET['amount']) ? $_GET['amount'] :
		(
			isset($_POST['amount']) && !empty($_POST['amount']) ? $_POST['amount'] :
			(
				isset($datasRequest) && !empty($datasRequest['amount']) ? $datasRequest['amount'] : 0
			)
		);

	}

	$charge_description = isset($_GET['charge_description']) && !empty($_GET['charge_description']) ? $_GET['charge_description'] :
	(
		isset($_POST['charge_description']) && !empty($_POST['charge_description']) ? $_POST['charge_description'] :
		(
			isset($datasRequest) && !empty($datasRequest['charge_description']) ? $datasRequest['charge_description'] : 'Uk offer'
		)
	);

	$pay_currency = isset($_GET['pay_currency']) && !empty($_GET['pay_currency']) ? $_GET['pay_currency'] :
	(
		isset($_POST['pay_currency']) && !empty($_POST['pay_currency']) ? $_POST['pay_currency'] :
		(
			isset($datasRequest) && !empty($datasRequest['pay_currency']) ? $datasRequest['pay_currency'] : 'gbp'
		)
	);

	$pay_locale = isset($_GET['pay_locale']) && !empty($_GET['pay_locale']) ? $_GET['pay_locale'] :
	(
		isset($_POST['pay_locale']) && !empty($_POST['pay_locale']) ? $_POST['pay_locale'] :
		(
			isset($datasRequest) && !empty($datasRequest['pay_locale']) ? $datasRequest['pay_locale'] : 'en'
		)
	);

	$stripeToken = isset($_GET['stripeToken']) && !empty($_GET['stripeToken']) ? $_GET['stripeToken'] :
	(
		isset($_POST['stripeToken']) && !empty($_POST['stripeToken']) ? $_POST['stripeToken'] :
		(
			isset($datasRequest) && !empty($datasRequest['stripeToken']) ? $datasRequest['stripeToken'] : null
		)
	);

	$stripeTokenType = isset($_GET['stripeTokenType']) && !empty($_GET['stripeTokenType']) ? $_GET['stripeTokenType'] :
	(
		isset($_POST['stripeTokenType']) && !empty($_POST['stripeTokenType']) ? $_POST['stripeTokenType'] :
		(
			isset($datasRequest) && !empty($datasRequest['stripeTokenType']) ? $datasRequest['stripeTokenType'] : null
		)
	);

	$stripeEmail = isset($_GET['stripeEmail']) && !empty($_GET['stripeEmail']) ? $_GET['stripeEmail'] :
	(
		isset($_POST['stripeEmail']) && !empty($_POST['stripeEmail']) ? $_POST['stripeEmail'] :
		(
			isset($datasRequest) && !empty($datasRequest['stripeEmail']) ? $datasRequest['stripeEmail'] : null
		)
	);

	$plan = isset($_GET['plan']) && !empty($_GET['plan']) ? $_GET['plan'] :
	(
		isset($_POST['plan']) && !empty($_POST['plan']) ? $_POST['plan'] :
		(
			isset($datasRequest) && !empty($datasRequest['plan']) ? $datasRequest['plan'] : null
		)
	);

	if(isset($plan) && !empty($plan)) {
		//$plan = $_GET['plan'];
		switch($plan) {
			default:
			case 1 :
				$plan_description = "Youboat.com Yearly UK Offer";
				$plan_id = 'yearlyukoffer';
				$amount = 450;
				break;

			case 2 :
				$plan_description = "Youboat.com Yearly UK + FR Offer";
				$plan_id = 'yearlyukfroffer';
				$amount = 1490;
				break;

			case 3 :
				$plan_description = 'Monthly UK Offer';
				$plan_id = 'monthlyukoffer';
				$amount = 50;
				break;

			case 4 :
				$plan_description = 'Monthly FR Offer (3 month UK for free)';
				$plan_id = 'monthlyfroffer';
				$amount = 100;
				break;

			case 5 :
				$plan_description = 'Monthly UK + FR Offer';
				$plan_id = 'monthlyukfroffer';
				$amount = 145;
				break;
		}
	} else if(isset($stripeToken) && !empty($stripeToken) &&
		isset($stripeTokenType) &&  $stripeTokenType == "card" &&
		isset($stripeEmail) && !empty($stripeEmail)) {
		$charge = "1";
	}

	if(empty($stripeToken)) {
		$amount *= 100; // because amount in cents
	}

	if(RUN_TYPE == 'debug')
	{
		// EN TEST
		$stripe = array(
		 "secret_key"      => "sk_test_iwKAQPLUfyBzaDRbX5xF4rgF",
		 "publishable_key" => "pk_test_oD75XtYPm9zg6J7ZQJZZbXbR"
		);
	} else {
		// EN  PROD
		$stripe = array(
		 "secret_key"      => "sk_live_mNsCCJ9lcDNtngd37NIeFZKG",
		 "publishable_key" => "pk_live_G0Xc4Ugkd05xiyhI1dWPVnc2"
		);
	}

	\Stripe\Stripe::setApiKey($stripe['secret_key']);

	$success = false;
	$transaction = [];

	if(isset($stripeToken)) {
		// Get the credit card details submitted by the form
		try {
			// plan
			//if($plan==1 || $plan==2 || $plan==3 || $plan==4 || $plan==5) {
			if(isset($plan) && !empty($plan)) {
				$stripeinfo = \Stripe\Token::retrieve($token);
				$email = $stripeinfo->name;
				$tokenid = $stripeinfo->id;

				$customer = \Stripe\Customer::create(array(
				  "email" => $email,
				  "source" => $tokenid
				));

				$StripePlan = \Stripe\Subscription::create(array(
					"customer" => $customer->id,
				  	"plan" => $plan_id
				));
				$StripeResult = json_decode(json_encode($StripePlan), true);
				//var_dump($StripeResult);
				$plan_description = $StripeResult['plan']['name'];
				$created = $StripeResult['created'];
				$current_period_start = $StripeResult["current_period_start"];
				$current_period_end = $StripeResult['current_period_end'];
				$amount = $StripeResult['plan']['amount'];
				$currency = $StripeResult['plan']['currency'];
				$plan_id = $StripeResult['plan']['id'];
				$plan_interval = $StripeResult['plan']['interval'];
				$plan_interval_count = $StripeResult['plan']['interval_count'];

				$date = new DateTime();
				$date->setTimestamp($created);
				$created = $date->format('Y-m-d H:i:s') . "\n";

				$date->setTimestamp($current_period_start);
				$current_period_start = $date->format('Y-m-d') . "\n";

				$date->setTimestamp($current_period_end);
				$current_period_end = $date->format('Y-m-d') . "\n";

				$success_msg = '<blockquote id="' . $StripeResult['id'] . '" class="col-sm-4 pay-success-msg pay-success-msg-datas pull-right">';
				$success_msg .= '<em>[' . $created . ']</em>';
				$success_msg .= '<br>';
				$success_msg .= '<strong id="' . $plan_id . '">Plan:</strong> ' . $plan_description;
				$success_msg .= ' (' . $plan_interval_count . ' ' . $plan_interval . ' )';
				$success_msg .= '<br>';
				$success_msg .= '<strong>Amount:</strong> ' . $amount/100 . ' ' . ucfirst($currency);
				$success_msg .= '<br>';
				$success_msg .= '<strong>From:</strong> ' . $current_period_start . ' <strong>To:</strong> ' . $current_period_end;
				$success_msg .= '</blockquote>';

				$customer = \Stripe\Customer::retrieve($StripeResult['customer']);
				$StripeResult = json_decode(json_encode($customer), true);
				$customer_email = $StripeResult["sources"]["data"][0]["name"];

				$success = true;

				$transaction = ['id'=>$StripeResult['id'], 'description'=>$plan_description, 'amount'=>$amount/100 . ucfirst($currency)];

			} else if(isset($charge) && !empty($charge)) {
					// Create a Customer:
					$customer = \Stripe\Customer::create(array(
						"email" => $email,
						"source" => $stripeToken,
					));

				$StripeCharge = \Stripe\Charge::create(array(
						"amount" => $amount, // Amount in cents
						"currency" => $pay_currency,
						"customer" => $customer->id,
						//"source" => $stripeToken,
						"description" => $charge_description
					));

					$StripeResult = json_decode(json_encode($StripeCharge), true);
					$charge_description = $StripeResult['description'];
					$created = $StripeResult['created'];
					$date = new DateTime();
					$date->setTimestamp($created);
					$created = $date->format('Y-m-d') . "\n";
					$amount = $StripeResult['amount'];
					$currency = $StripeResult['currency'];
					$charge_id = $StripeResult['id'];
					$customer = $StripeResult['customer'];
					$receipt_email = $StripeResult['receipt_email'];

					$success_msg = '<blockquote id="' . $charge_id . '" class="col-sm-12 pay-success-msg pay-success-msg-datas">';
					$success_msg .= '<strong>' . $charge_description . '</strong>';
					$success_msg .= ' <em>[' . $created . ']</em>';
					$success_msg .= '<br>';
					//$success_msg .= '<strong>Customer:</strong> ' . $customer. ' (' . $receipt_email . ')';
					$success_msg .= '<strong>Customer:</strong> ' . $receipt_email;
					$success_msg .= '<br>';
					$success_msg .= '<strong>Amount:</strong> ' . $amount/100 . ' ' . ucfirst($currency);
					$success_msg .= ', ' . $StripeResult["outcome"]["seller_message"];
					$success_msg .= '</blockquote>';

					$success = true;

					$transaction = ['id'=>$charge_id, 'description'=>$charge_description, 'amount'=>$amount/100 . ucfirst($currency)];
			}
		}
		catch(\Stripe\Error\Card $e) {
			// Since it's a decline, \Stripe\Error\Card will be caught
			$body = $e->getJsonBody();
			$err  = $body['error'];

			//print('Status is:' . $e->getHttpStatus() . "\n");
			//print('Type is:' . $err['type'] . "\n");
			//print('Code is:' . $err['code'] . "\n");
			// param is '' in this case
			//print('Param is:' . $err['param'] . "\n");
			//print('Message is:' . $err['message'] . "\n");
			$success = false;
			$pay_error = $err['message'];
		} catch (\Stripe\Error\RateLimit $e) {
			// Too many requests made to the API too quickly
			$success = false;
			$pay_error = 'Too many requests made to the API too quickly';
		} catch (\Stripe\Error\InvalidRequest $e) {
			// Invalid parameters were supplied to Stripe's API
			$success = false;
			$pay_error = 'Invalid parameters were supplied to Stripe\'s API'. ' ' . $e->getMessage();
			//header("Location: /pay/");
		} catch (\Stripe\Error\Authentication $e) {
			// Authentication with Stripe's API failed
			// (maybe you changed API keys recently)
			$success = false;
			$pay_error = 'Authentication with Stripe\'s API failed';
		} catch (\Stripe\Error\ApiConnection $e) {
			// Network communication with Stripe failed
			$success = false;
			$pay_error = 'Network communication with Stripe failed';
		} catch (\Stripe\Error\Base $e) {
			// Display a very generic error to the user, and maybe send
			// yourself an email
			$success = false;
			$pay_error = $e->getMessage();
		} catch (Exception $e) {
			// Something else happened, completely unrelated to Stripe
			$success = false;
			$pay_error = $e->getMessage();
		}

	}
?>

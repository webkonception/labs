<?php
	require $_SERVER['DOCUMENT_ROOT'] . '/pay/script/stripe-php-3.23.0/init.php';
	//define('RUN_TYPE', 'debug');
	//$plan = 1;
	//$amount = 450;
	$amount = 0;
	$pay_error = '';
	$customer_email = '';
	if(!isset($pay_currency)) {
			$pay_currency = 'gbp';
	}
	if(!isset($pay_locale)) {
			$pay_locale = 'en';
	}
	if(isset($_GET['charge']) && !empty($_GET['charge'])) {
		$charge = $_GET['charge'];
		$amount = 0;
	} else if(isset($_GET['plan']) && !empty($_GET['plan'])) {
		$plan = $_GET['plan'];
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
	}

	$amount *= 100; // because amount in cents

	if(RUN_TYPE=='debug')
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

	if(isset($_POST['stripeToken']) && !empty($_POST['stripeToken'])) {
		// Get the credit card details submitted by the form
		$token = $_POST['stripeToken'];

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
				$result = json_decode(json_encode($StripePlan), true);
				//var_dump($result);
				$plan_description = $result['plan']['name'];
				$created = $result['created'];
				$current_period_start = $result["current_period_start"];
				$current_period_end = $result['current_period_end'];
				$amount = $result['plan']['amount'];
				$currency = $result['plan']['currency'];
				$plan_id = $result['plan']['id'];
				$plan_interval = $result['plan']['interval'];
				$plan_interval_count = $result['plan']['interval_count'];

				$date = new DateTime();
				$date->setTimestamp($created);
				$created = $date->format('Y-m-d H:i:s') . "\n";

				$date->setTimestamp($current_period_start);
				$current_period_start = $date->format('Y-m-d') . "\n";

				$date->setTimestamp($current_period_end);
				$current_period_end = $date->format('Y-m-d') . "\n";

				$success_msg = '<blockquote id="' . $result['id'] . '" class="col-sm-4 pay-success-msg pay-success-msg-datas pull-right">';
				$success_msg .= '<em>[' . $created . ']</em>';
				$success_msg .= '<br>';
				$success_msg .= '<strong id="' . $plan_id . '">Plan:</strong> ' . $plan_description;
				$success_msg .= ' (' . $plan_interval_count . ' ' . $plan_interval . ' )';
				$success_msg .= '<br>';
				$success_msg .= '<strong>Amount:</strong> ' . $amount/100 . ' ' . ucfirst($currency);
				$success_msg .= '<br>';
				$success_msg .= '<strong>From:</strong> ' . $current_period_start . ' <strong>To:</strong> ' . $current_period_end;
				$success_msg .= '</blockquote>';

				$customer = \Stripe\Customer::retrieve($result['customer']);
				$result = json_decode(json_encode($customer), true);
				$customer_email = $result["sources"]["data"][0]["name"];

				$success = true;
			} else if(isset($charge) && !empty($charge)) {
					// plan Yearly
					switch($charge) {
						default :
								$charge_description = "Youboat.com Charge";
								$charge_id = 'charge_id';
								break;
					}

					$StripeCharge = \Stripe\Charge::create(array(
						"amount" => $amount, // Amount in cents
						"currency" => $pay_currency,
						"source" => $token,
						"description" => $charge_description
					));

					$result = json_decode(json_encode($StripeCharge), true);
					//var_dump($result);
					$charge_description = $result['description'];
					$created = $result['created'];
					$date = new DateTime();
					$date->setTimestamp($created);
					$created = $date->format('Y-m-d') . "\n";
					$amount = $result['amount'];
					$currency = $result['currency'];
					$charge_id = $result['plan']['id'];

					$success_msg = '<blockquote id="' . $result['id'] . '" class="col-sm-4 pay-success-msg pay-success-msg-datas pull-right">';
					$success_msg .= '<em>[' . $created . ']</em>';
					$success_msg .= '<br>';
					$success_msg .= '<strong>Plan:</strong> ' . $charge_description;
					$success_msg .= '<br>';
					$success_msg .= '<strong>Amount:</strong> ' . $amount/100 . ' ' . ucfirst($currency);
					$success_msg .= '</blockquote>';
					$success = true;
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
			$pay_error = 'Invalid parameters were supplied to Stripe\'s API';
			header("Location: /pay/");
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

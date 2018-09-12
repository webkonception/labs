<?php
	require PATH_SITE.'script/stripe-php-3.23.0/init.php';
	
	$amount = 18000;
	$pack = 1;
	if(isset($_GET['pack']) && !empty($_GET['pack']))
	{
		switch($_GET['pack'])
		{
			case '1':
				$pack = 1;
				$amount = 18000;
			break;
			
			case '2':
				$pack = 2;
				$amount = 21000;
			break;
			
			case '3':
				$pack = 3;
				$amount = 36000;
			break;
			
			case '4':
				$pack = 4;
				$amount = 3000;
			break;
		}
	}
	
	if(RUN_TYPE=='debug')
	{
		// EN TEST
		$stripe = array(
		 "secret_key"      => "sk_test_NSAMx5zAaZmyZ44StTr0VUq0",
		 "publishable_key" => "pk_test_fd10sdC5U31kpJwxNf9uAYYm"
		);
	}
	else
	{
		// EN  PROD
		$stripe = array(
		 "secret_key"      => "sk_live_274VHy0iInhDdmNWNzTnQLrO",
		 "publishable_key" => "pk_live_xOIu1r4Lx4HOWczZFMES1qbn"
		);
	}
	
	\Stripe\Stripe::setApiKey($stripe['secret_key']);
	
	$success = false;
	
	if(isset($_POST['stripeToken']) && !empty($_POST['stripeToken']))
	{
		// Get the credit card details submitted by the form
		$token = $_POST['stripeToken'];

		// Create a charge: this will charge the user's card
		try
		{
			if($pack==4)
			{
				$stripeinfo = \Stripe\Token::retrieve($token);
				$email = $stripeinfo->name;
				$tokenid = $stripeinfo->id;
				
				$customer = \Stripe\Customer::create(array(
				  "email" => $email,
				  "source" => $tokenid
				));
			
				$plan = \Stripe\Subscription::create(array(
				  "customer" => $customer->id,
				  "plan" => "dugunmugunplan"
				));
				$success = true;
			}
			else
			{
				$charge = \Stripe\Charge::create(array(
					"amount" => $amount, // Amount in cents
					"currency" => "eur",
					"source" => $token,
					"description" => "Dugun Mugun Abonnement 1 an"
				));
				$success = true;
			}
		}
		catch(\Stripe\Error\Card $e)
		{
			echo  $e->getMessage();
		}
	}
?>
<?php
	if($success)
	{
	?>
		<h1><?php echo REGLEMENT_CONFIRM_TITRE; ?></h1>
		<p><?php echo REGLEMENT_CONFIRM_TEXTE; ?></p>
	<?php
	}
	else
	{
	?>
		<h1><?php echo REGLEMENT_TITRE; ?></h1>
		<div id="pack1" class="pack">
			<h2>Pack 1</h2>
			<p>180 &euro;</p>
			<input class="payradio" type="radio" name="pack" value="1"<?php if($pack==1){ echo ' checked="checked"'; } ?> />
		</div>
		<div id="pack2" class="pack">
			<h2>Pack 2</h2>
			<p>210 &euro;</p>
			<input class="payradio" type="radio" name="pack" value="2"<?php if($pack==2){ echo ' checked="checked"'; } ?> />
		</div>
		<div id="pack2" class="pack">
			<h2>Pack 3</h2>
			<p>360 &euro;</p>
			<input class="payradio" type="radio" name="pack" value="3"<?php if($pack==3){ echo ' checked="checked"'; } ?> />
		</div>
		<div id="pack4" class="pack">
			<h2>Pack 4</h2>
			<p>30 &euro; / mois</p>
			<input class="payradio" type="radio" name="pack" value="4"<?php if($pack==4){ echo ' checked="checked"'; } ?> />
		</div>
		<div class="clear"></div>
		<form id="payform" action="<?php echo URL_SITE.'pay-'.$pack; ?>" method="POST">
		 <script
		   src="https://checkout.stripe.com/checkout.js" class="stripe-button"
		   data-key="<?php echo $stripe['publishable_key'];?>"
		   data-amount="<?php echo $amount; ?>"
		   data-name="DUGUN MUGUN"
		   data-description="Dugun Mugun 2017"
		   data-image="https://www.dugun-mugun.com/images/graphic/fav/android-chrome-192x192.png"
		   data-locale="auto"
		   data-zip-code="true"
		   data-currency="eur">
		 </script>
		</form>
	<?php
	}
?>
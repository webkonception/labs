$( function() {
		$('.payradio').change(function(){
				var location = '/';
				var plan_value = $(this).val();
				if(plan_value) {
				location = '/pay/?plan=' + plan_value;
				} else {
					location = '/pay/';
				}
				document.location = location;
		});
});

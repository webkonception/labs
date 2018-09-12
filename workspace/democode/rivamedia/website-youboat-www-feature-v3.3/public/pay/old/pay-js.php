<script language="javascript" type="text/javascript">
	$('.payradio').change(function(){
		if($(this).val()=='3')
		{
			document.location = '<?php echo URL_SITE; ?>pay-3';
		}
		else
		{
			if($(this).val()=='2')
			{
				document.location = '<?php echo URL_SITE; ?>pay-2';
			}
			else
			{
				if($(this).val()=='4')
				{
					document.location = '<?php echo URL_SITE; ?>pay-4';
				}
				else
				{
					document.location = '<?php echo URL_SITE; ?>pay';
				}
			}
		}
	});
</script>

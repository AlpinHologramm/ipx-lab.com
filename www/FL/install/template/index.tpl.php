<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo substr($tpl->getInstall('lang_current_code'), 0, 2); ?>" lang="<?php echo substr($tpl->getInstall('lang_current_code'), 0, 2); ?>" dir="ltr">


<head>

<title><?php echo mb_strtolower(__('Installation')); ?> - iGalerie</title>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $tpl->getInstall('charset'); ?>" />

<link rel="stylesheet" type="text/css" media="screen" title="iGalerie" href="template/style/style.css" />

<script type="text/javascript" src="../js/jquery/jquery.js"></script>

<script type="text/javascript">
jQuery(function($)
{
	$('form .text:first').focus();
	$('.javascript_test_hide').hide();
	$('.javascript_test_show').show();
	$('#langs').change(function()
	{
		$(this).parents('form').submit();
	});
	if (typeof field_error != 'undefined')
	{
		$(field_error).each(function(i, val)
		{
			if (i == 0)
			{
				$('#' + val).focus();
			}
			$('#' + val).parents('p').addClass('field_error');
		});
	}
});
</script>

</head>


<body>

<div id="global">

	<div id="install">
		<h1><?php echo __('Installation'); ?></h1>
<?php $tpl->includePage(); ?>

		<p id="footer"><a href="http://www.igalerie.org/">www.igalerie.org</a></p>
	</div>

</div>

</body>


</html>

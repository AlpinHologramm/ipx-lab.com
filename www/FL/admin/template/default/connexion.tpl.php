<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo substr($tpl->getConnexion('lang_current'), 0, 2); ?>" lang="<?php echo substr($tpl->getConnexion('lang_current'), 0, 2); ?>" dir="ltr">

<head>

<title>iGalerie</title>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $tpl->getConnexion('charset'); ?>" />

<link rel="stylesheet" type="text/css" media="screen" title="style" href="<?php echo $tpl->getConnexion('style_path'); ?>/connexion.css" />

<script type="text/javascript" src="<?php echo $tpl->getConnexion('gallery_path'); ?>/js/jquery/jquery.js"></script>
<script type="text/javascript">
jQuery(function($)
{
	$('#login').focus();
	$('#options_link a').click(function()
	{
		if ($('#options').is(':hidden'))
		{
			$('#options').slideDown('fast');
		}
		else
		{
			$('#options').slideUp('fast');
		}
	});
});
</script>

</head>


<body>

<div id="global">

	<div id="connexion">
		<h1>iGalerie</h1>
		<form action="" method="post">
			<div>
<?php if ($tpl->disReport('success')) : ?>
				<p class="report_msg report_success"><?php echo $tpl->getReport('success'); ?></p>
<?php endif; ?>
<?php if ($tpl->disReport('error')) : ?>
				<p class="report_msg report_error"><?php echo $tpl->getReport('error'); ?></p>
<?php endif; ?>
<?php if ($tpl->disReport('warning')) : ?>
				<p class="report_msg report_warning"><?php echo $tpl->getReport('warning'); ?></p>
<?php endif; ?>
<?php $tpl->includePage(); ?>

			</div>
		</form>
	</div>

	<div id="debug">
		<?php $tpl->displayErrors(); ?>

	</div>

</div>

</body>


</html>

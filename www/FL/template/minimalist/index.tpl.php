<?php if (!CONF_INTEGRATED) : ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo substr($tpl->getGallery('lang_current_code'), 0, 2); ?>" lang="<?php echo substr($tpl->getGallery('lang_current_code'), 0, 2); ?>" dir="ltr">


<head>

<title><?php if ($tpl->disGallery('page_title')) : ?><?php echo $tpl->getGallery('page_title'); ?> - <?php endif; ?><?php echo $tpl->getGallery('gallery_title'); ?></title>

<?php include_once(dirname(__FILE__) . '/head.tpl.php'); ?>

</head>


<body id="section_<?php echo str_replace('-', '_', $_GET['section']); ?>">
<?php endif; ?>

<div id="igalerie">
<div id="igalerie_gallery">

	<div id="ig2_header">
		<h1><a accesskey="1" href="<?php echo $tpl->getGallery('gallery_path'); ?>/"><?php echo $tpl->getGallery('gallery_title_banner'); ?></a></h1>
	</div>

	<div id="ig2_content">
<?php $tpl->inc('page'); ?>

	</div>

	<div id="ig2_footer">
		<p>
			<?php echo $tpl->getGallery('powered_by'); ?>
<?php if ($tpl->disGallery('exec_time')) : ?>
			-
			<?php echo $tpl->getGallery('exec_time'); ?>
<?php endif; ?>

		</p>
<?php if ($tpl->disGallery('footer_message')) : ?>
		<p><?php echo $tpl->getGallery('footer_message'); ?></p>
<?php endif; ?>
	</div>
<?php $tpl->displayErrors(); ?>

</div>
</div>

<?php echo $tpl->getDebugSQL(); ?>

<?php if (!CONF_INTEGRATED) : ?>
</body>


</html>
<?php endif; ?>
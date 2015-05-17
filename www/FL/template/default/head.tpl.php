<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $tpl->getGallery('charset'); ?>" />
<?php if ($tpl->disGallery('meta_description')) : ?>
<meta name="description" content="<?php echo $tpl->getGallery('meta_description'); ?>" />
<?php endif; ?>

<script type="text/javascript" src="<?php echo $tpl->getGallery('gallery_path'); ?>/js/jquery/jquery.js"></script>
<script type="text/javascript" src="<?php echo $tpl->getGallery('gallery_path'); ?>/js/jquery/ui/core.js"></script>
<?php if ($_GET['section'] == 'cameras') : ?>
<script type="text/javascript" src="<?php echo $tpl->getGallery('gallery_path'); ?>/js/jquery/tablesorter.js"></script>
<?php endif; ?>
<?php if (strstr($_GET['section'], 'watermark')) : ?>
<script type="text/javascript" src="<?php echo $tpl->getGallery('gallery_path'); ?>/js/jquery/farbtastic/farbtastic.js"></script>
<?php endif; ?>
<?php if ($tpl->disGallery('geoloc') && (($_GET['section'] == 'image' && $tpl->disWidgets('geoloc')) || $_GET['section'] == 'worldmap')) : ?>
<script type="text/javascript" src="<?php echo GALLERY_HTTPS ? 'https://maps-api-ssl' : 'http://maps'; ?>.google.com/maps/api/js?sensor=false"></script>
<?php endif; ?>
<script type="text/javascript" src="<?php echo $tpl->getGallery('gallery_path'); ?>/js/common.js"></script>
<script type="text/javascript" src="<?php echo $tpl->getGallery('template_path'); ?>/js/gallery.js"></script>
<?php if ($tpl->disGallery('diaporama')) : ?>
<script type="text/javascript" src="<?php echo $tpl->getGallery('gallery_path'); ?>/js/jquery/ui/effects.js"></script>
<script type="text/javascript" src="<?php echo $tpl->getGallery('gallery_path'); ?>/js/diaporama.js"></script>
<?php endif; ?>
<script type="text/javascript">
//<![CDATA[
var gallery_path = "<?php echo $tpl->getGallery('gallery_path'); ?>";
var style_path = "<?php echo $tpl->getGallery('style_path'); ?>";
//]]>
</script>

<?php if ($tpl->disGallery('canonical_image')) : ?>
<link rel="canonical" href="<?php echo $tpl->getImage('canonical'); ?>" />
<?php endif; ?>
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $tpl->getGallery('style_file'); ?>" />

<style type="text/css">
<?php while ($tpl->nextLang()) : ?>
.icon_<?php echo $tpl->getLang('code'); ?> {
	background-image: url(<?php echo $tpl->getGallery('gallery_path'); ?>/images/flags/<?php echo $tpl->getLang('code'); ?>.png);
}
<?php endwhile; ?>
<?php echo $tpl->getGallery('style_additional'); ?>

</style>

<?php $tpl->inc('style_header'); ?>


<?php if ($tpl->disGallery('rss')) : ?>
<link rel="alternate" type="application/rss+xml" title="<?php echo $tpl->getRSS('images_desc_head'); ?>" href="<?php echo $tpl->getRSS('images_url_head'); ?>" />
<?php if ($tpl->disGallery('comments')) : ?>
<link rel="alternate" type="application/rss+xml" title="<?php echo $tpl->getRSS('comments_desc_head'); ?>" href="<?php echo $tpl->getRSS('comments_url_head'); ?>" />
<?php endif; ?>
<?php endif; ?>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $tpl->getGallery('charset'); ?>" />
<?php if ($tpl->disGallery('meta_description')) : ?>
<meta name="description" content="<?php echo $tpl->getGallery('meta_description'); ?>" />
<?php endif; ?>

<?php if ($tpl->disGallery('canonical_image')) : ?>
<link rel="canonical" href="<?php echo $tpl->getImage('canonical'); ?>" />
<?php endif; ?>
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $tpl->getGallery('style_file'); ?>" />

<style type="text/css">
<?php echo $tpl->getGallery('style_additional'); ?>

<?php if ($tpl->disGallery('debug_sql')) : ?>#debug_sql{font-family:verdana,arial,helvetica,sans-serif;font-size:.8em;border-collapse:collapse;border-spacing:0;margin:15px auto;background:white;text-align:left;}#debug_sql td,#debug_sql th{border:1px solid silver;padding:10px;vertical-align:top;max-width:400px;}#debug_sql th{text-align:center;}#debug_sql td.sql{font-family:"Courier New",sans-serif;width:350px;}#debug_sql td.nb_result,#debug_sql th.time{width:80px;}#debug_sql .q{font-family:Verdana,Arial,Helvetica,sans-serif;color:#275F8F;font-weight:bold;margin-right:10px;}#debug_sql .params{display:block;margin-top:15px;color:#275F8F;font-weight:bold;font-family:Verdana,Arial,Helvetica,sans-serif;}#debug_sql .success{color:#648F27;font-weight:bold;text-transform:uppercase;}#debug_sql .failure{color:#D60E0E;font-weight:bold;text-transform:uppercase;}#debug_sql hr{border:0;border-bottom:1px solid silver;}#debug_sql td.num,#debug_sql td.line,#debug_sql td.nb_result,#debug_sql td.time{text-align:right;}<?php endif; ?>

</style>

<?php $tpl->inc('style_header'); ?>


<?php if ($tpl->disGallery('rss')) : ?>
<link rel="alternate" type="application/rss+xml" title="<?php echo $tpl->getRSS('images_desc_head'); ?>" href="<?php echo $tpl->getRSS('images_url_head'); ?>" />
<?php endif; ?>

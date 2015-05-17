
<?php include_once(dirname(__FILE__) . '/pages_submenu.tpl.php'); ?>

		<span id="back"><a href="<?php echo $tpl->getLink('pages'); ?>"><?php echo __('retour'); ?></a></span>
		<p id="position"><span class="current"><?php echo __('Nouvelle page'); ?></span></p>

<?php include_once(dirname(__FILE__) . '/page_text.tpl.php'); ?>

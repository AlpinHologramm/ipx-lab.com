
<?php include_once(dirname(__FILE__) . '/pages_submenu.tpl.php'); ?>

		<p id="position"><a href="<?php echo $tpl->getLink('pages'); ?>"><?php echo __('Pages'); ?></a> / <span class="current"><a href="<?php echo $tpl->getLink('page/perso/' . $tpl->getPagePerso('id')); ?>"><?php echo $tpl->getPagePerso('title'); ?></a></span></p>

<?php include_once(dirname(__FILE__) . '/page_text.tpl.php'); ?>

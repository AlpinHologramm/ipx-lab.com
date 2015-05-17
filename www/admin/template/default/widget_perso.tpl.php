
<?php include_once(dirname(__FILE__) . '/widgets_submenu.tpl.php'); ?>

		<p id="position"><a href="<?php echo $tpl->getLink('widgets'); ?>"><?php echo __('Widgets'); ?></a> / <span class="current"><a href="<?php echo $tpl->getLink('widget/perso/' . $tpl->getWidgetPerso('id')); ?>"><?php echo $tpl->getWidgetPerso('title'); ?></a></span></p>

<?php include_once(dirname(__FILE__) . '/widget_text.tpl.php'); ?>

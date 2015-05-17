
<?php include(dirname(__FILE__) . '/deconnect.tpl.php'); ?>

		<div id="obj_tools">
<?php if ($tpl->disAdminLink() || $tpl->disEdit() || $tpl->disDeconnect()) : ?>
			<p class="obj_tool_menu_icon" id="obj_tools_link"><a href="javascript:;"><?php echo __('Outils'); ?></a></p>
			<div class="obj_tool_box" id="obj_tool_menu">
				<p class="obj_tool_title"><span><?php echo __('Outils'); ?></span></p>
				<ul class="obj_tool_body">
<?php if ($tpl->disAdminLink()) : ?>
					<li id="tool_admin"><span class="icon icon_admin"><a class="normal_link" href="<?php echo $tpl->getAdminLink(); ?>"><?php echo __('Administrer'); ?></a></span></li>
<?php endif; ?>
<?php if ($tpl->disEdit()) : ?>
					<li class="obj_tool_box_link" id="tool_edit"><span class="icon icon_edit"><a class="js_link" href="javascript:;"><?php echo __('Éditer'); ?></a></span></li>
<?php endif; ?>
<?php if ($tpl->disDeconnect()) : ?>
					<li id="deconnect_object_link"><span class="icon icon_deconnect"><a class="js_link" href="javascript:;"><?php echo __('Déconnexion'); ?></a></span></li>
<?php endif; ?>
				</ul>
			</div>
<?php if ($tpl->disEdit()) : ?>
<?php include(dirname(__FILE__) . '/edit.tpl.php'); ?>

<?php endif; ?>

<?php endif; ?>
		</div>

		<p id="position"><?php echo $tpl->getPosition(); ?></p>

<?php if ($tpl->disCategory('desc')) : ?>
		<div id="cat_description"><p><?php echo $tpl->getCategory('desc'); ?></p></div>
<?php endif; ?>

<?php if ($tpl->disNavigation('top')) : ?>
		<div class="nav" id="nav_top">
			<div class="nav_left"></div>
			<div class="nav_right"><?php printf(__('page %s|%s'), $_GET['page'], $tpl->getCategory('nb_pages')); ?></div>

<?php include(dirname(__FILE__) . '/nav.tpl.php'); ?>

		</div>
<?php endif; ?>

<?php if ($tpl->disGallery('thumbs_cat_extended')) : ?>
<?php include(dirname(__FILE__) . '/category_thumbs_extended.tpl.php'); ?>
<?php else : ?>
<?php include(dirname(__FILE__) . '/category_thumbs_compact.tpl.php'); ?>
<?php endif; ?>

<?php if ($tpl->disNavigation('bottom')) : ?>
		<div class="nav" id="nav_bottom">
			<div class="nav_left"></div>
			<div class="nav_right"><?php printf(__('page %s|%s'), $_GET['page'], $tpl->getCategory('nb_pages')); ?></div>

<?php include(dirname(__FILE__) . '/nav.tpl.php'); ?>

		</div>
<?php endif; ?>

		<script type="text/javascript">
		//<![CDATA[
		var cat_id = <?php echo $_GET['object_id']; ?>;
		var page = <?php echo $_GET['page']; ?>;
		var anticsrf = "<?php echo $tpl->getGallery('anticsrf'); ?>";
		var q = "<?php echo $tpl->getGallery('q'); ?>";
		var q_md5 = "<?php echo $tpl->getGallery('q_md5'); ?>";
		var user_lang = "<?php echo $tpl->getGallery('lang_current_code'); ?>";
<?php if ($tpl->disEdit()) : ?>
		var thumbs_cat_extended = <?php echo (int) $tpl->disGallery('thumbs_cat_extended'); ?>;
<?php endif; ?>
		//]]>
		</script>

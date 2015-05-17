		<h2><a href="<?php echo $tpl->getLink('category/1'); ?>"><?php echo __('Gestion des albums'); ?></a></h2>

		<ul id="sub_menu">
<?php if ($tpl->disPerm('albums_edit') || $tpl->disPerm('albums_modif')) : ?>
			<li<?php if ($_GET['section'] != 'images-pending') : ?> class="current"<?php endif; ?>><a href="<?php echo $tpl->getLink('category/1'); ?>"><?php echo __('Galerie'); ?></a></li>
<?php endif; ?>
<?php if ($tpl->disPerm('albums_pending')) : ?>
			<li<?php if ($_GET['section'] == 'images-pending') : ?> class="current"<?php endif; ?>><a href="<?php echo $tpl->getLink('images-pending'); ?>"><?php echo __('Images en attente'); ?></a></li>
<?php endif; ?>
		</ul><div id="sub_menu_line"></div><div id="sub_menu_bg"></div>

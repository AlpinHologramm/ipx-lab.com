<?php if ($tpl->disUserTools()) : ?>
			<ul id="user_menu">
				<li<?php if ($_GET['section'] == 'profile') : ?> class="current"<?php endif; ?>><span class="icon icon_edit"><a href="<?php echo $tpl->getLink('profile'); ?>"><?php echo __('Modifier votre profil'); ?></a></span></li>
<?php if ($tpl->disGallery('avatars')) : ?>
				<li<?php if ($_GET['section'] == 'avatar') : ?> class="current"<?php endif; ?>><span class="icon icon_avatar"><a href="<?php echo $tpl->getLink('avatar'); ?>"><?php echo __('Changer d\'avatar'); ?></a></span></li>
<?php endif; ?>
<?php if ($tpl->disPerm('upload')) : ?>
				<li<?php if ($_GET['section'] == 'upload') : ?> class="current"<?php endif; ?>><span class="icon icon_add_images"><a href="<?php echo $tpl->getLink('upload'); ?>"><?php echo __('Ajouter des images'); ?></a></span></li>
<?php endif; ?>
<?php if ($tpl->disPerm('create_albums')) : ?>
				<li<?php if ($_GET['section'] == 'new-category') : ?> class="current"<?php endif; ?>><span class="icon icon_add_category"><a href="<?php echo $tpl->getLink('new-category'); ?>"><?php echo __('Créer une catégorie'); ?></a></span></li>
<?php endif; ?>
<?php if ($tpl->disGallery('watermark_users')) : ?>
				<li<?php if ($_GET['section'] == 'watermark') : ?> class="current"<?php endif; ?>><span class="icon icon_watermark"><a href="<?php echo $tpl->getLink('watermark'); ?>"><?php echo __('Modifier votre filigrane'); ?></a></span></li>
<?php endif; ?>
<?php if ($tpl->disAuthUser('admin')) : ?>
				<li id="user_menu_admin"><span class="icon icon_admin"><a href="<?php echo $tpl->getAuthUser('admin_link'); ?>"><?php echo __('Administration'); ?></a></span></li>
<?php endif; ?>
			</ul>
<?php endif; ?>

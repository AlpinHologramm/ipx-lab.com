<?php if ($tpl->disPerm('users')) : ?>
		<h2><a href="<?php echo $tpl->getLink('users'); ?>"><?php echo __('Gestion des utilisateurs'); ?></a></h2>

		<ul id="sub_menu">
<?php if ($tpl->disPerm('users_members')) : ?>
			<li<?php if (in_array($_GET['section'], array('new-user', 'user', 'user-avatar', 'user-sendmail', 'user-watermark', 'users'))) : ?> class="current"<?php endif; ?>><a href="<?php echo $tpl->getLink('users'); ?>"><?php echo __('Utilisateurs'); ?></a></li>
<?php endif; ?>
<?php if ($tpl->disPerm('users_groups') || $tpl->disPerm('users_members')) : ?>
			<li<?php if ($_GET['section'] == 'groups' || $_GET['section'] == 'group' || $_GET['section'] == 'new-group') : ?> class="current"<?php endif; ?>><a href="<?php echo $tpl->getLink('groups'); ?>"><?php echo __('Groupes'); ?></a></li>
<?php endif; ?>
<?php if ($tpl->disPerm('users_options')) : ?>
			<li<?php if ($_GET['section'] == 'users-options') : ?> class="current"<?php endif; ?>><a href="<?php echo $tpl->getLink('users-options'); ?>"><?php echo __('Options'); ?></a></li>
<?php endif; ?>
<?php if ($tpl->disPerm('users_members')) : ?>
			<li<?php if ($_GET['section'] == 'users-sendmail') : ?> class="current"<?php endif; ?>><a href="<?php echo $tpl->getLink('users-sendmail'); ?>"><?php echo __('Envoyer un courriel'); ?></a></li>
<?php endif; ?>
		</ul><div id="sub_menu_line"></div><div id="sub_menu_bg"></div>
<?php else : ?>
		<br /><br />
<?php endif; ?>

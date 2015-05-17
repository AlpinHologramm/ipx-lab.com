<?php if ($tpl->disPerm('comments')) : ?>
		<h2><a href="<?php echo $tpl->getLink('comments-images'); ?>"><?php echo __('Gestion des commentaires'); ?></a></h2>

		<ul id="sub_menu">
<?php if ($tpl->disPerm('comments_edit')) : ?>
			<li<?php if ($_GET['section'] == 'comments-images') : ?> class="current"<?php endif; ?>><a href="<?php echo $tpl->getLink('comments-images'); ?>"><?php echo __('Images'); ?></a></li>
			<li<?php if ($_GET['section'] == 'comments-guestbook') : ?> class="current"<?php endif; ?>><a href="<?php echo $tpl->getLink('comments-guestbook'); ?>"><?php echo __('Livre d\'or'); ?></a></li>
<?php endif; ?>
<?php if ($tpl->disPerm('comments_options')) : ?>
			<li<?php if ($_GET['section'] == 'comments-options') : ?> class="current"<?php endif; ?>><a href="<?php echo $tpl->getLink('comments-options'); ?>"><?php echo __('Options'); ?></a></li>
<?php endif; ?>
		</ul><div id="sub_menu_line"></div><div id="sub_menu_bg"></div>
<?php else : ?>
		<br /><br />
<?php endif; ?>

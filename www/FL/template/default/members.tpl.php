
		<p id="position"><?php echo $tpl->getPosition(); ?></p>

<?php if ($tpl->disNavigation('top')) : ?>
		<div class="nav" id="nav_top">
			<div class="nav_left"></div>
			<div class="nav_right"><?php printf(__('page %s|%s'), $_GET['page'], $tpl->getMembersProperty('nbPages')); ?></div>

<?php include(dirname(__FILE__) . '/nav.tpl.php'); ?>

		</div>
<?php endif; ?>

		<div id="members_list">
			<table summary="<?php echo __('Liste des membres'); ?>" class="default">
				<thead>
					<tr>
<?php if ($tpl->disGallery('avatars')) : ?>
						<th class="avatar"><?php echo __('Avatar'); ?></th>
<?php endif; ?>
						<th class="name"><?php echo __('Nom d\'utilisateur'); ?></th>
<?php if ($tpl->disMember('title')) : ?>
						<th class="title"><?php echo __('Titre'); ?></th>
<?php endif; ?>
<?php if ($tpl->disMember('lastvstdt')) : ?>
						<th class="lastvstdt"><?php echo __('Date de dernière visite'); ?></th>
<?php endif; ?>
<?php if ($tpl->disMember('crtdt')) : ?>
						<th class="crtdt"><?php echo __('Date d\'inscription'); ?></th>
<?php endif; ?>
					</tr>
				</thead>
				<tbody>
<?php $n = 1; while ($tpl->nextMember()) : ?>
				<tr<?php if (is_integer($n++ / 2)) : ?> class="even"<?php endif; ?>>
<?php if ($tpl->disGallery('avatars')) : ?>
					<td class="avatar">
						<a href="<?php echo $tpl->getMember('link'); ?>">
							<img src="<?php echo $tpl->getMember('avatar_src'); ?>"
								width="50" height="50"
								alt="<?php printf(__('Avatar de %s'), $tpl->getMember('login')); ?>" />
						</a>
					</td>
<?php endif; ?>
					<td class="name"><a href="<?php echo $tpl->getMember('link'); ?>"><?php echo $tpl->getMember('login'); ?></a></td>
<?php if ($tpl->disMember('title')) : ?>
					<td class="title"><a href="<?php echo $tpl->getMember('group_link'); ?>"><?php echo $tpl->getMember('group_title'); ?></a></td>
<?php endif; ?>
<?php if ($tpl->disMember('lastvstdt')) : ?>
					<td class="lastvstdt"><?php echo $tpl->getMember('lastvstdt'); ?></td>
<?php endif; ?>
<?php if ($tpl->disMember('crtdt')) : ?>
					<td class="crtdt"><?php echo $tpl->getMember('crtdt'); ?></td>
<?php endif; ?>
				</tr>
<?php endwhile; ?>
				</tbody>
			</table>
		</div>

<?php if ($tpl->disNavigation('bottom')) : ?>
		<div class="nav" id="nav_bottom">
			<div class="nav_left"></div>
			<div class="nav_right"><?php printf(__('page %s|%s'), $_GET['page'], $tpl->getMembersProperty('nbPages')); ?></div>

<?php include(dirname(__FILE__) . '/nav.tpl.php'); ?>

		</div>
<?php endif; ?>

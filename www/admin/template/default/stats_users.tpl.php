<?php include_once(dirname(__FILE__) . '/stats_submenu.tpl.php'); ?>

		<table id="users_stats" summary="" class="default sorter">
			<thead>
				<tr>
					<th><?php echo __('Utilisateur'); ?></th>
					<th><?php echo __('Statut'); ?></th>
					<th><?php echo __('Images'); ?></th>
					<th><?php echo __('Commentaires'); ?></th>
					<th><?php echo __('Votes'); ?></th>
					<th><?php echo __('Favoris'); ?></th>
				</tr>
			</thead>
			<tbody>
<?php while ($tpl->nextUser()) : ?>
				<tr>
					<td>
						<a href="<?php echo $tpl->getUser('link'); ?>"><?php echo $tpl->getUser('login'); ?></a>
<?php if ($tpl->disUser('superadmin')) : ?>
						<img src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/crown.png" width="16" height="16"
							alt="<?php echo __('Super-administrateur'); ?>" title="<?php echo __('Super-administrateur'); ?>" />
<?php elseif ($tpl->disUser('admin')) : ?>
						<img src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/medal.png" width="16" height="16"
							alt="<?php echo __('Administrateur'); ?>" title="<?php echo __('Administrateur'); ?>" />
<?php endif; ?>
					</td>
					<td><?php echo $tpl->getUser('status'); ?></td>
					<td><?php echo $tpl->getUser('nb_images'); ?></td>
					<td><?php echo $tpl->getUser('nb_comments'); ?></td>
					<td><?php echo $tpl->getUser('nb_votes'); ?></td>
					<td><?php echo $tpl->getUser('nb_favorites'); ?></td>
				</tr>
<?php endwhile; ?>
			</tbody>
		</table>

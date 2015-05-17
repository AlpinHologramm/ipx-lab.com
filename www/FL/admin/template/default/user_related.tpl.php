		<div id="obj_stats" class="obj_banner_box user_banner_box">
			<p class="obj_banner_box_link">
				<a title="<?php echo __('Statistiques'); ?>" href="javascript:;">
					<img width="20" height="20" alt="<?php echo __('Statistiques'); ?>" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/20x20/chart.png" />
				</a>
			</p>
			<div class="obj_banner_box_inner">
				<h3 class="obj_banner_box_title"><span><?php echo __('Statistiques'); ?></span></h3>
				<div>
					<table class="light">
						<tr>
							<td><?php echo __('Date d\'inscription'); ?></td>
							<td><?php echo $tpl->getUser('crtdt'); ?></td>
						</tr>
						<tr>
							<td><?php echo __('IP d\'inscription'); ?></td>
							<td><?php echo $tpl->getUser('crtip'); ?></td>
						</tr>
						<tr>
							<td><?php echo __('Date de dernière visite'); ?></td>
							<td><?php echo $tpl->getUser('lastvstdt'); ?></td>
						</tr>
						<tr>
							<td><?php echo __('IP de dernière visite'); ?></td>
							<td><?php echo $tpl->getUser('lastvstip'); ?></td>
						</tr>
<?php if ($tpl->disAdmin('superadmin')) : ?>
						<tr>
							<td><?php echo __('Activité'); ?></td>
							<td><?php echo $tpl->getUser('nb_logs'); ?></td>
						</tr>
<?php endif; ?>
						<tr>
							<td><?php echo __('Nombre d\'images'); ?></td>
							<td><?php echo $tpl->getUser('nb_images'); ?></td>
						</tr>
						<tr>
							<td><?php echo __('Nombre d\'images en attente'); ?></td>
							<td><?php echo $tpl->getUser('nb_images_pending'); ?></td>
						</tr>
						<tr>
							<td><?php echo __('Nombre de commentaires'); ?></td>
							<td><?php echo $tpl->getUser('nb_comments'); ?></td>
						</tr>
						<tr>
							<td><?php echo __('Nombre de votes'); ?></td>
							<td><?php echo $tpl->getUser('nb_votes'); ?></td>
						</tr>
						<tr>
							<td><?php echo __('Nombre de favoris'); ?></td>
							<td><?php echo $tpl->getUser('nb_favorites'); ?></td>
						</tr>
						<tr>
							<td><?php echo __('Nombre d\'images dans le panier'); ?></td>
							<td><?php echo $tpl->getUser('nb_basket'); ?></td>
						</tr>
					</table>
				</div>
			</div>
		</div>

		<div id="obj_banner" class="<?php if ($tpl->disUser('activate')) : ?>activate<?php endif; ?><?php if ($tpl->disUser('deactivate')) : ?>deactivate<?php endif; ?><?php if ($tpl->disUser('pending')) : ?>pending<?php endif; ?>">
			<div id="obj_banner_thumb">
				<img width="50" height="50"
					alt="<?php printf(__('Avatar de %s'), $tpl->getUser('login')); ?>"
					src="<?php echo $tpl->getUser('avatar_thumb_src'); ?>" />
			</div>
			<div id="obj_banner_title">
				<div id="obj_banner_name">
					<span><?php echo $tpl->getStrLimit($tpl->getUser('login'), 50); ?></span>
<?php if ($tpl->disUser('activate')) : ?>
					<a title="<?php echo __('Voir dans la galerie'); ?>" class="obj_gallery_link" href="<?php echo $tpl->getUser('gallery_link'); ?>">&nbsp;</a>
<?php endif; ?>
				</div>
				<span id="obj_banner_type">
					<span>
<?php if ($tpl->disPerm('users_groups')) : ?>
						<a title="<?php echo sprintf(__('%s fait partie du groupe \'%s\''), $tpl->getUser('login'), $tpl->getUser('group_name')); ?>" href="<?php echo $tpl->getLink('group/' . $tpl->getUser('group_id')); ?>">
<?php endif; ?>
							<?php echo $tpl->getUser('group_title'); ?>

<?php if ($tpl->disPerm('users_groups')) : ?>
						</a>
<?php endif; ?>
					</span>
<?php if ($tpl->disUser('superadmin')) : ?>
					<img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/crown.png" alt="<?php echo __('Super-administrateur'); ?>" title="<?php echo __('Super-administrateur'); ?>" />
<?php elseif ($tpl->disUser('admin')) : ?>
					<img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/medal.png" alt="<?php echo __('Administrateur'); ?>" title="<?php echo __('Administrateur'); ?>" />
<?php endif; ?>
				</span>
			</div>
		</div>

		<div class="related">
			<label><?php echo __('Section :'); ?></label>
			<select onchange="window.location.href='<?php echo $tpl->getAdmin('admin_base_url'); ?>'+this.options[this.selectedIndex].value">
				<option value="user/<?php echo $tpl->getUser('id'); ?>"<?php if ($_GET['section'] == 'user') : ?> class="selected" selected="selected"<?php endif; ?>><?php echo __('éditer le profil'); ?></option>
<?php if ($tpl->disUser('edit')) : ?>
				<option value="user-avatar/<?php echo $tpl->getUser('id'); ?>"<?php if ($_GET['section'] == 'user-avatar') : ?> class="selected" selected="selected"<?php endif; ?>><?php echo __('modifier l\'avatar'); ?></option>
				<option value="user-watermark/<?php echo $tpl->getUser('id'); ?>"<?php if ($_GET['section'] == 'user-watermark') : ?> class="selected" selected="selected"<?php endif; ?>><?php echo __('modifier le filigrane'); ?></option>
<?php endif; ?>
				<option value="user-sendmail/<?php echo $tpl->getUser('id'); ?>"<?php if ($_GET['section'] == 'user-sendmail') : ?> class="selected" selected="selected"<?php endif; ?>><?php echo __('envoyer un courriel'); ?></option>
			</select>
		</div>

		<h3><?php
		switch ($_GET['section'])
		{
			case 'user' :
				echo __('Édition du profil');
				break;

			case 'user-avatar' :
				echo __('Modification de l\'avatar');
				break;

			case 'user-sendmail' :
				echo __('Envoyer un courriel');
				break;

			case 'user-watermark' :
				echo __('Modification du filigrane');
				break;
		}
		?></h3>

		<div id="map_browse" class="browse browse_wlimit">
			<label><?php echo __('Parcourir :'); ?></label>
			<select onchange="window.location.href='<?php echo $tpl->getAdmin('admin_base_url'); ?>'+this.options[this.selectedIndex].value">
<?php if ($tpl->disPerm('users_members')) : ?>
				<option value="<?php echo $tpl->getUser('back'); ?>"><?php echo __('gestion des utilisateurs'); ?></option>
<?php endif; ?>
				<optgroup label="<?php echo __('Utilisateurs'); ?>">
					<?php echo $tpl->getUsersList(); ?>

				</optgroup>
			</select>
		</div>
		<br />

<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

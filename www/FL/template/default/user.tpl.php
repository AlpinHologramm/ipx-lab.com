<?php if ($tpl->disGallery('members_list')) : ?>
		<div id="user_profile" class="box<?php if (!$tpl->disUserTools()) : ?> box_notools<?php endif; ?>">
			<table>
				<tr>
					<td class="box_title" colspan="<?php if ($tpl->disGallery('avatars')) : ?>2<?php else : ?>1<?php endif; ?>">
						<div>
							<h2><?php echo $tpl->getUser('login'); ?></h2>
<?php if ($tpl->disPerm('members_list')) : ?>
							<span>(<a href="<?php echo $tpl->getUser('group_link'); ?>"><?php echo $tpl->getUser('group_title'); ?></a>)</span>
<?php endif; ?>
						</div>
					</td>
				</tr>
				<tr>
<?php if ($tpl->disGallery('avatars')) : ?>
					<td class="box_avatar">
						<img <?php echo $tpl->getUser('avatar_size'); ?> alt="<?php printf(__('Avatar de %s'), $tpl->getUser('login')); ?>" src="<?php echo $tpl->getUser('avatar_src'); ?>" />
					</td>
<?php endif; ?>
					<td class="box_user">
<?php if ($tpl->disUser('infos')) : ?>
						<div class="box_infos">
							<h3><?php echo __('Informations'); ?></h3>
<?php if ($tpl->disUser('name')) : ?>
							<p>
								<span><?php echo __('Nom :'); ?></span>
								<?php echo $tpl->getUser('name'); ?>

							</p>
<?php endif; ?>
<?php if ($tpl->disUser('firstname')) : ?>
							<p>
								<span><?php echo __('Prénom :'); ?></span>
								<?php echo $tpl->getUser('firstname'); ?>

							</p>
<?php endif; ?>
<?php if ($tpl->disUser('sex')) : ?>
							<p>
								<span><?php echo __('Sexe :'); ?></span>
								<?php echo $tpl->getUser('sex'); ?>

							</p>
<?php endif; ?>
<?php if ($tpl->disUser('birthdate')) : ?>
							<p>
								<span><?php echo __('Date de naissance :'); ?></span>
								<?php echo $tpl->getUser('birthdate'); ?>

							</p>
<?php endif; ?>
<?php if ($tpl->disUser('website')) : ?>
							<p>
								<span><?php echo __('Site Web :'); ?></span>
								<?php echo $tpl->getUser('website'); ?>

							</p>
<?php endif; ?>
<?php if ($tpl->disUser('loc')) : ?>
							<p>
								<span><?php echo __('Localisation :'); ?></span>
								<?php echo $tpl->getUser('loc'); ?>

							</p>
<?php endif; ?>
<?php if ($tpl->disUser('desc')) : ?>
							<p class="box_desc">
								<span><?php echo __('Description :'); ?></span>
								<?php echo $tpl->getUser('desc'); ?>

							</p>
<?php endif; ?>
<?php if ($tpl->disUserPerso()) : ?>
<?php while ($tpl->nextUserPerso()) : ?>
							<p>
								<span><?php echo $tpl->getUserPerso('name'); ?></span>
								<?php echo $tpl->getUserPerso('value'); ?>

							</p>
<?php endwhile; ?>
<?php endif; ?>
						</div>
<?php endif; ?>
						<div class="box_stats<?php if (!$tpl->disUser('infos')) : ?> box_noinfos<?php endif; ?>">
							<h3><?php echo __('Statistiques'); ?></h3>
							<p>
								<span><?php echo __('Date d\'inscription :'); ?></span>
								<?php echo $tpl->getUser('crtdt'); ?>

							</p>
							<p>
								<span><?php echo __('Dernière visite :'); ?></span>
								<?php echo $tpl->getUser('lastvstdt'); ?>

							</p>
							<p>
								<span><?php echo __('Nombre d\'images :'); ?></span>
								<?php echo $tpl->getUser('nb_images'); ?>

							</p>
<?php if ($tpl->disGallery('comments_page')) : ?>
							<p>
								<span><?php echo __('Nombre de commentaires :'); ?></span>
								<?php echo $tpl->getUser('nb_comments'); ?>

							</p>
<?php endif; ?>
<?php if ($tpl->disUser('nb_favorites')) : ?>
							<p>
								<span><?php echo __('Nombre de favoris :'); ?></span>
								<?php echo $tpl->getUser('nb_favorites'); ?>

							</p>
<?php endif; ?>
						</div>
					</td>
				</tr>
			</table>
		</div>
<?php else : ?>
		<p id="not_allowed"><span class="message message_info"><?php echo __('Vous n\'êtes pas autorisé à consulter le profil des membres.'); ?></span></p>
<?php endif; ?>
<?php include(dirname(__FILE__) . '/user_menu.tpl.php'); ?>

		<h2><a href="<?php echo $tpl->getLink('dashboard'); ?>"><?php echo __('Tableau de bord'); ?></a></h2>

		<div id="sub_menu_line"></div>

		<div id="dashboard">
			<div id="dashboard_left">

<?php if ($tpl->disDashboard('start_message')) : ?>
				<div class="dashboard_bloc" id="dashboard_start">
					<h3><?php echo __('Comment utiliser iGalerie ?'); ?></h3>
					<div class="inner">
<?php include_once(GALLERY_ROOT . '/locale/' . $tpl->getAdmin('lang_current') . '/help/start.html'); ?>

						<div id="dashboard_start_hide">
							<a id="dashboard_start_hide_link" style="display:none;" href="javascript:;"><?php echo __('Ne plus afficher ce message'); ?></a>
							<form id="dashboard_start_hide_form" action="" method="post">
								<div>
									<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
									<input name="start_message_hide" type="submit" value="<?php echo __('Ne plus afficher ce message'); ?>" />
								</div>
							</form>
							<script type="text/javascript">
							document.getElementById('dashboard_start_hide_form').style.display = 'none';
							document.getElementById('dashboard_start_hide_link').style.display = 'inline';
							</script>
						</div>
					</div>
					<div style="clear:both"></div>
				</div>
<?php endif; ?>
<?php if ($tpl->disDashboard('lastusers')) : ?>
				<div class="dashboard_bloc" id="dashboard_lastusers">
<?php if ($tpl->getLastUsers('nb_items') > 1) : ?>
					<div class="dashboard_nav">
						<a rel="user" class="dashboard_prev" href="javascript:;"></a>
						<a rel="user" class="dashboard_next" href="javascript:;"></a>
						<span>1/<?php echo $tpl->getLastUsers('nb_items'); ?></span>
					</div>
<?php endif; ?>
					<h3><?php echo __('Derniers utilisateurs'); ?></h3>
					<div class="inner">
<?php $n = 0; $size = 50; while ($tpl->nextLastUsers($size)) : ?>
						<div class="user" id="user_<?php echo ++$n; ?>">
<?php if ($tpl->disLastUsers('avatar')) : ?>
							<a href="<?php echo $tpl->getLastUsers('user_link'); ?>">
								<img width="<?php echo $size; ?>" height="<?php echo $size; ?>"
									alt="<?php echo sprintf(__('Avatar de %s'), $tpl->getLastUsers('user_login')); ?>"
									src="<?php echo $tpl->getLastUsers('avatar_src'); ?>" />
							</a>
<?php endif; ?>
							<p><?php echo $tpl->getLastUsers('infos'); ?></p>
						</div>
<?php endwhile; ?>
<?php if (!$n) : ?>
						<p class="empty"><?php echo __('Aucun utilisateur enregistré.'); ?></p>
<?php endif; ?>
					</div>
					<div style="clear:both"></div>
				</div>
<?php endif; ?>
<?php if (!$tpl->disDashboard('start_message')) : ?>
				<div class="dashboard_bloc" id="dashboard_lastimages">
<?php if ($tpl->getLastImages('nb_items') > 1) : ?>
					<div class="dashboard_nav">
						<a rel="image" class="dashboard_prev" href="javascript:;"></a>
						<a rel="image" class="dashboard_next" href="javascript:;"></a>
						<span>1/<?php echo $tpl->getLastImages('nb_items'); ?></span>
					</div>
<?php endif; ?>
					<h3><?php echo __('Dernières images'); ?></h3>
					<div class="inner" id="dashboard_lastimages_thumbs">
<?php $n = 0; $size = 100; while ($tpl->nextLastImages($size)) : ?>
						<div class="image" id="image_<?php echo ++$n; ?>">
							<dl>
								<dt style="width:<?php echo $size; ?>px">
									<span style="width:<?php echo $size; ?>px;height:<?php echo $size; ?>px;">
										<a href="<?php echo $tpl->getLastImages('image_link'); ?>" title="<?php echo $tpl->getLastImages('title'); ?>">
											<img <?php echo $tpl->getLastImages('thumb_size'); ?>

												style="padding:<?php echo $tpl->getLastImages('center'); ?>;"
												alt="<?php echo $tpl->getLastImages('title'); ?>"
												src="<?php echo $tpl->getLastImages('src'); ?>" />
										</a>
									</span>
								</dt>
							</dl>
							<p><?php echo $tpl->getLastImages('infos'); ?></p>
						</div>
<?php endwhile; ?>
<?php if (!$n) : ?>
						<p class="empty"><?php printf(__('Aucune image dans %s.'), __('la galerie')); ?></p>
<?php endif; ?>
					</div>
					<div style="clear:both"></div>
				</div>
<?php endif; ?>
<?php if ($tpl->disDashboard('lastcomments')) : ?>
				<div class="dashboard_bloc" id="dashboard_lastcomments">
<?php if ($tpl->getLastComments('nb_items') > 1) : ?>
					<div class="dashboard_nav">
						<a rel="comment" class="dashboard_prev" href="javascript:;"></a>
						<a rel="comment" class="dashboard_next" href="javascript:;"></a>
						<span>1/<?php echo $tpl->getLastComments('nb_items'); ?></span>
					</div>
<?php endif; ?>
					<h3><?php echo __('Derniers commentaires'); ?></h3>
					<div class="inner">
<?php $n = 0; while ($tpl->nextLastComments()) : ?>
						<div class="comment" id="comment_<?php echo ++$n; ?>">
							<p class="last_comment_message"><?php echo $tpl->getLastComments('message'); ?></p>
							<p class="last_comment_infos"><?php echo $tpl->getLastComments('infos'); ?></p>
						</div>
<?php endwhile; ?>
<?php if (!$n) : ?>
						<p class="empty"><?php echo __('Aucun commentaire.'); ?></p>
<?php endif; ?>
					</div>
				</div>
<?php endif; ?>

			</div>

			<div id="dashboard_right">

<?php if ($tpl->disDashboard('incidents')) : ?>
				<div class="dashboard_bloc" id="dashboard_errors">
					<h3><?php echo __('Incidents'); ?></h3>
					<div class="inner">
						<p><a href="<?php echo $tpl->getLink('incidents'); ?>"><?php echo $tpl->getIncidents(); ?></a></p>
					</div>
				</div>
<?php endif; ?>

<?php if ($tpl->disDashboard('pending')) : ?>
				<div class="dashboard_bloc" id="dashboard_pending">
					<h3><?php echo __('En attente de validation'); ?></h3>
					<div class="inner">
						<ul>
<?php if ($tpl->disDashboard('images_pending')) : ?>
							<li><?php echo $tpl->getPending('images_pending'); ?></li>
<?php endif; ?>
<?php if ($tpl->disDashboard('comments_pending')) : ?>
							<li><?php echo $tpl->getPending('comments_pending'); ?></li>
<?php endif; ?>
<?php if ($tpl->disDashboard('guestbook_pending')) : ?>
							<li><?php echo $tpl->getPending('guestbook_pending'); ?></li>
<?php endif; ?>
<?php if ($tpl->disDashboard('users_pending')) : ?>
							<li><?php echo $tpl->getPending('users_pending'); ?></li>
<?php endif; ?>
						</ul>
					</div>
				</div>
<?php endif; ?>

				<div class="dashboard_bloc" id="dashboard_stats">
					<h3><?php echo __('Statistiques de la galerie'); ?></h3>
					<div class="inner">
						<ul>
							<li><?php echo $tpl->getStats('nb_images'); ?></li>
							<li><?php echo $tpl->getStats('nb_albums'); ?></li>
<?php if ($tpl->disStats('nb_comments')) : ?>
							<li><?php echo $tpl->getStats('nb_comments'); ?></li>
<?php endif; ?>
<?php if ($tpl->disStats('nb_votes')) : ?>
							<li><?php echo $tpl->getStats('nb_votes'); ?></li>
<?php endif; ?>
<?php if ($tpl->disStats('nb_tags')) : ?>
							<li><?php echo $tpl->getStats('nb_tags'); ?></li>
<?php endif; ?>
						</ul>
						<ul>
							<li><?php echo $tpl->getStats('nb_hits'); ?></li>
							<li><?php echo $tpl->getStats('nb_admins'); ?></li>
<?php if ($tpl->disStats('nb_members')) : ?>
							<li><?php echo $tpl->getStats('nb_members'); ?></li>
<?php endif; ?>
							<li><?php echo $tpl->getStats('nb_groups'); ?></li>
<?php if ($tpl->disStats('nb_favorites')) : ?>
							<li><?php echo $tpl->getStats('nb_favorites'); ?></li>
<?php endif; ?>
						</ul>
						<div style="clear:both"></div>
					</div>
				</div>

<?php if ($tpl->disDashboard('sysinfos')) : ?>
				<div class="dashboard_bloc" id="dashboard_sysinfos">
					<h3><?php echo __('Informations système'); ?></h3>
					<div class="inner">
						<ul>
							<li><?php echo $tpl->getSysInfos('gallery_version'); ?></li>
							<li><?php echo $tpl->getSysInfos('php_version'); ?></li>
							<li><?php echo $tpl->getSysInfos('mysql_version'); ?></li>
							<li><?php echo $tpl->getSysInfos('gd_version'); ?></li>
							<li><?php echo $tpl->getSysInfos('os'); ?></li>
						</ul>
					</div>
				</div>
<?php endif; ?>

			</div>
		</div>

		<div style="clear:both"></div>
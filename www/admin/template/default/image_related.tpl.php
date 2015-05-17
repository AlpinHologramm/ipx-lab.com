<?php if ($tpl->disPerm('access_all') && !$tpl->disImageInfo('empty')) : ?>
		<div id="obj_stats" class="obj_banner_box">
			<p class="obj_banner_box_link">
				<a title="<?php echo __('Statistiques'); ?>" href="javascript:;">
					<img width="20" height="20" alt="<?php echo __('Statistiques'); ?>" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/20x20/chart.png" />
				</a>
			</p>
			<div class="obj_banner_box_inner">
				<h3 class="obj_banner_box_title"><span><?php echo __('Statistiques'); ?></span></h3>
				<div>
					<table class="light">
						<tr><td><?php echo __('Poids'); ?></td><td class="number"><?php echo $tpl->getImageInfo('filesize'); ?></td></tr>
						<tr><td><?php echo __('Dimensions'); ?></td><td class="number"><?php echo $tpl->getImageInfo('size'); ?></td></tr>
						<tr><td><?php echo __('Nombre de visites'); ?></td><td class="number"><?php echo $tpl->getImageInfo('hits'); ?></td></tr>
						<tr><td><?php echo __('Nombre de commentaires'); ?></td><td class="number"><?php echo $tpl->getImageInfo('comments'); ?></td></tr>
						<tr><td><?php echo __('Nombre de votes'); ?></td><td class="number"><?php echo $tpl->getImageInfo('votes'); ?></td></tr>
						<tr><td><?php echo __('Note moyenne'); ?></td><td class="number"><?php echo $tpl->getImageInfo('rate'); ?></td></tr>
					</table>
					<p><?php printf(__('Ajoutée le %s par %s'), $tpl->getImageInfo('adddt'), $tpl->getImageInfo('owner_link')); ?></p>
				</div>
			</div>
		</div>
<?php endif; ?>

<?php if ($tpl->disImageInfo('password')) : ?>
		<div id="obj_key" class="obj_banner_box">
			<p class="obj_banner_box_link">
				<a title="<?php echo __('Accès protégé'); ?>" href="javascript:;">
					<img width="24" height="24" alt="<?php echo __('Accès protégé'); ?>" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/24x24/key.png" />
				</a>
			</p>
			<div class="obj_banner_box_inner">
				<h3 class="obj_banner_box_title"><span><?php echo __('Accès protégé'); ?></span></h3>
				<div>
					<p><?php echo $tpl->getImageInfo('password_infos'); ?></p>
<?php if ($tpl->disPerm('users_groups') && $tpl->disImageInfo('password_groups')) : ?>
					<p><?php echo __('Groupes ayant accès à cette image :'); ?></p>
					<ul>
						<?php echo $tpl->getImageInfo('password_groups'); ?>

					</ul>
<?php endif; ?>
				</div>
			</div>
		</div>
<?php endif; ?>

		<div id="obj_banner" class="<?php if (!$tpl->disImageInfo('publish')) : ?>de<?php endif; ?>activate">
			<div id="obj_banner_thumb">
				<img style="padding:<?php echo $tpl->getImageInfo('thumb_center'); ?>"
					alt="<?php echo $tpl->getImageInfo('title'); ?>"
					src="<?php echo $tpl->getImageInfo('thumb_src'); ?>"
					<?php echo $tpl->getImageInfo('thumb_size'); ?> />
			</div>
			<div id="obj_banner_title">
				<div id="obj_banner_name">
					<span><?php echo $tpl->getStrLimit($tpl->getImageInfo('title'), 50); ?></span>
<?php if ($tpl->disImageInfo('publish')) : ?>
					<a title="<?php echo __('Voir dans la galerie'); ?>" class="obj_gallery_link" href="<?php echo $tpl->getImageInfo('gallery_link'); ?>">&nbsp;</a>
<?php endif; ?>
				</div>
				<span id="obj_banner_type"><?php echo $tpl->getImageInfo('type'); ?></span>
			</div>
		</div>

		<div class="related">
			<label><?php echo __('Section :'); ?></label>
			<select onchange="window.location.href='<?php echo $tpl->getAdmin('admin_base_url'); ?>'+this.options[this.selectedIndex].value">
				<option value="image/<?php echo $_GET['object_id']; ?>"<?php if ($_GET['section'] == 'image') : ?> class="selected" selected="selected"<?php endif; ?>><?php echo __('modifier l\'image'); ?></option>
				<option value="thumb-image/<?php echo $_GET['object_id']; ?>"<?php if ($_GET['section'] == 'thumb-image') : ?> class="selected" selected="selected"<?php endif; ?>><?php echo __('modifier la vignette'); ?></option>
				<option value="edit-image/<?php echo $_GET['object_id']; ?>"<?php if ($_GET['section'] == 'edit-image') : ?> class="selected" selected="selected"<?php endif; ?>><?php echo __('éditer'); ?></option>
				<option value="geoloc-image/<?php echo $_GET['object_id']; ?>"<?php if ($_GET['section'] == 'geoloc-image') : ?> class="selected" selected="selected"<?php endif; ?>><?php echo __('géolocaliser'); ?></option>
			</select>
		</div>
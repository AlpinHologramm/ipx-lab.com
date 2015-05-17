<?php if ($_GET['object_id'] > 1 && $tpl->disPerm('access_all')) : ?>
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
						<tr class="th"><th></th><th class="title"><?php echo __('publié'); ?></th><th class="title"><?php echo __('hors ligne'); ?></th><th class="title"><?php echo __('total'); ?></th></tr>
						<tr><td><?php echo __('Poids'); ?></td><td class="number"><?php echo $tpl->getCategoryInfo('a_size'); ?></td><td class="number"><?php echo $tpl->getCategoryInfo('d_size'); ?></td><td class="number"><?php echo $tpl->getCategoryInfo('size'); ?></td></tr>
						<tr><td><?php echo __('Nombre d\'albums'); ?></td><td class="number"><?php echo $tpl->getCategoryInfo('a_albums'); ?></td><td class="number"><?php echo $tpl->getCategoryInfo('d_albums'); ?></td><td class="number"><?php echo $tpl->getCategoryInfo('albums'); ?></td></tr>
						<tr><td><?php echo __('Nombre d\'images'); ?></td><td class="number"><?php echo $tpl->getCategoryInfo('a_images'); ?></td><td class="number"><?php echo $tpl->getCategoryInfo('d_images'); ?></td><td class="number"><?php echo $tpl->getCategoryInfo('images'); ?></td></tr>
						<tr><td><?php echo __('Nombre de visites'); ?></td><td class="number"><?php echo $tpl->getCategoryInfo('a_hits'); ?></td><td class="number"><?php echo $tpl->getCategoryInfo('d_hits'); ?></td><td class="number"><?php echo $tpl->getCategoryInfo('hits'); ?></td></tr>
						<tr><td><?php echo __('Nombre de commentaires'); ?></td><td class="number"><?php echo $tpl->getCategoryInfo('a_comments'); ?></td><td class="number"><?php echo $tpl->getCategoryInfo('d_comments'); ?></td><td class="number"><?php echo $tpl->getCategoryInfo('comments'); ?></td></tr>
						<tr><td><?php echo __('Nombre de votes'); ?></td><td class="number"><?php echo $tpl->getCategoryInfo('a_votes'); ?></td><td class="number"><?php echo $tpl->getCategoryInfo('d_votes'); ?></td><td class="number"><?php echo $tpl->getCategoryInfo('votes'); ?></td></tr>
						<tr><td><?php echo __('Note moyenne'); ?></td><td class="number"><?php echo $tpl->getCategoryInfo('a_rate'); ?></td><td class="number"><?php echo $tpl->getCategoryInfo('d_rate'); ?></td><td class="number"><?php echo $tpl->getCategoryInfo('rate'); ?></td></tr>
					</table>
					<p><?php printf(__('Créé le %s'), $tpl->getCategoryInfo('crtdt')); ?></p>
					<p><?php printf(__('Propriétaire : %s'), $tpl->getCategoryInfo('owner_link')); ?></p>
				</div>
			</div>
		</div>
<?php endif; ?>

<?php if ($tpl->disCategoryInfo('protected')) : ?>
		<div id="obj_key" class="obj_banner_box">
			<p class="obj_banner_box_link">
				<a title="<?php echo __('Accès protégé'); ?>" href="javascript:;">
					<img width="24" height="24" alt="<?php echo __('Accès protégé'); ?>" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/24x24/key.png" />
				</a>
			</p>
			<div class="obj_banner_box_inner">
				<h3 class="obj_banner_box_title"><span><?php echo __('Accès protégé'); ?></span></h3>
				<div>
					<p><?php echo $tpl->getCategoryInfo('password_infos'); ?></p>
<?php if ($tpl->disPerm('users_groups') && $tpl->disCategoryInfo('password_groups')) : ?>
					<p><?php echo __('Groupes ayant accès à cette catégorie :'); ?></p>
					<ul>
						<?php echo $tpl->getCategoryInfo('password_groups'); ?>

					</ul>
<?php endif; ?>
				</div>
			</div>
		</div>
<?php endif; ?>

<?php if ($_GET['object_id'] > 1) : ?>
		<div id="obj_banner" class="<?php if ($tpl->disCategoryInfo('empty')) : ?>empty <?php endif; ?><?php if (!$tpl->disCategoryInfo('publish')) : ?>de<?php endif; ?>activate">
			<div id="obj_banner_thumb">
<?php if (!$tpl->disCategoryInfo('empty')) : ?>
				<img style="padding:<?php echo $tpl->getCategoryInfo('thumb_center'); ?>"
					alt="<?php echo $tpl->getCategoryInfo('title'); ?>"
					src="<?php echo $tpl->getCategoryInfo('thumb_src'); ?>"
					<?php echo $tpl->getCategoryInfo('thumb_size'); ?> />
<?php endif; ?>
			</div>
			<div id="obj_banner_title">
				<div id="obj_banner_name">
					<span><?php echo $tpl->getStrLimit($tpl->getCategoryInfo('title'), 50); ?></span>
<?php if ($tpl->disCategoryInfo('publish')) : ?>
					<a title="<?php echo __('Voir dans la galerie'); ?>" class="obj_gallery_link" href="<?php echo $tpl->getCategoryInfo('gallery_link'); ?>">&nbsp;</a>
<?php endif; ?>
				</div>
				<span id="obj_banner_type"><?php echo $tpl->getCategoryInfo('type'); ?></span>
			</div>
		</div>
<?php endif; ?>

		<div class="related">
			<label><?php echo __('Section :'); ?></label>
			<select onchange="window.location.href='<?php echo $tpl->getAdmin('admin_base_url'); ?>'+this.options[this.selectedIndex].value">
<?php if ($_GET['object_id'] > 1) : ?>
				<optgroup label="<?php echo __('Catégorie'); ?>">
<?php if ($tpl->disPerm('albums_modif') && !$tpl->disCategoryInfo('empty')) : ?>
					<option value="thumb-category/<?php echo $_GET['object_id']; ?>"<?php if ($_GET['section'] == 'thumb-category') : ?> class="selected" selected="selected"<?php endif; ?>><?php echo __('modifier la vignette'); ?></option>
					<option value="new-thumb/<?php echo $_GET['object_id']; ?>"<?php if ($_GET['section'] == 'new-thumb') : ?> class="selected" selected="selected"<?php endif; ?>><?php echo __('nouvelle vignette'); ?></option>
<?php endif; ?>
					<option value="edit-category/<?php echo $_GET['object_id']; ?>"<?php if ($_GET['section'] == 'edit-category') : ?> class="selected" selected="selected"<?php endif; ?>><?php echo __('éditer'); ?></option>
<?php if ($tpl->disPerm('albums_edit')) : ?>
					<option value="geoloc-category/<?php echo $_GET['object_id']; ?>"<?php if ($_GET['section'] == 'geoloc-category') : ?> class="selected" selected="selected"<?php endif; ?>><?php echo __('géolocaliser'); ?></option>
<?php endif; ?>
<?php if ($tpl->disPerm('albums_modif')) : ?>
					<option value="watermark-category/<?php echo $_GET['object_id']; ?>"<?php if ($_GET['section'] == 'watermark-category') : ?> class="selected" selected="selected"<?php endif; ?>><?php echo __('modifier le filigrane'); ?></option>
<?php endif; ?>
				</optgroup>
<?php endif; ?>
				<optgroup label="<?php echo $tpl->getSectionsList('objects_text'); ?>">
					<option value="category/<?php echo $tpl->getSectionsList('filter_url'); ?>"<?php if ($_GET['section'] == 'category') : ?> class="selected" selected="selected"<?php endif; ?>><?php echo ($tpl->disPosition('normal') || ((isset($_GET['search_type']) && $_GET['search_type'] == 'category'))) ? __('gérer les catégories') : __('gérer les images'); ?></option>
<?php if ($tpl->disPerm('albums_edit') && !$tpl->disCategoryInfo('empty') && (!isset($_GET['search_type']) || $_GET['search_type'] == 'album')) : ?>
					<option value="mass-edit-category/<?php echo $tpl->getSectionsList('filter_url'); ?>"<?php if ($_GET['section'] == 'mass-edit-category') : ?> class="selected" selected="selected"<?php endif; ?>><?php echo __('édition en masse'); ?></option>
<?php endif; ?>
<?php if ($tpl->disItems() && $tpl->disPerm('albums_modif') && $tpl->disPosition('normal')) : ?>
					<option value="sort-category/<?php echo $_GET['object_id']; ?>"<?php if ($_GET['section'] == 'sort-category') : ?> class="selected" selected="selected"<?php endif; ?>><?php echo __('trier les catégories'); ?></option>
<?php endif; ?>
				</optgroup>
			</select>
		</div>
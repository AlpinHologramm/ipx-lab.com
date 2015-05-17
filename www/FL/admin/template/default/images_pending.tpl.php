
<?php include_once(dirname(__FILE__) . '/albums_submenu.tpl.php'); ?>

		<script type="text/javascript">
		//<![CDATA[
		var msg_destination_cat = "<?php echo $tpl->getL10nJS(__('La destination doit être un album.')); ?>";
		var confirm_delete = "<?php echo $tpl->getL10nJS(__('Voulez-vous vraiment supprimer les images sélectionnées ?')); ?>";
		//]]>
		</script>

		<div id="tools_browse">
			<div id="links_tools">
				<span class="icon icon_options show_tool"><a rel="options" class="js" href="javascript:;"><?php echo mb_strtolower(__('Options d\'affichage')); ?></a></span>
			</div>
		</div>

		<form action="" method="post" style="display:none" class="tool" id="options">
			<fieldset>
				<legend><?php echo __('Options d\'affichage'); ?></legend>
				<p class="field">
					<label for="nb_per_page"><?php echo __('Nombre d\'images par page :'); ?></label>
					<input maxlength="3" size="3" value="<?php echo $tpl->getOptions('nb_per_page'); ?>" name="nb_per_page" id="nb_per_page" type="text" class="text focus" />
				</p>
				<p class="field">
					<label for="sortby"><?php echo __('Trier par :'); ?></label>
					<select name="sortby" id="sortby">
						<?php echo $tpl->getOptions('sortby'); ?>

					</select>
					<select name="orderby">
						<?php echo $tpl->getOptions('orderby'); ?>

					</select>
				</p>
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
				<input name="options" class="submit" type="submit" value="<?php echo __('Valider'); ?>" />
			</fieldset>
		</form>

<?php if ($tpl->disItems()) : ?>
		<p id="position" class="current"><?php echo $tpl->getPosition(); ?></p>

<?php if ($tpl->disNavigation()) : ?>
		<div class="nav" id="nav_top">
			<div class="nav_left"></div>
			<div class="nav_right"><?php printf(__('page %s|%s'), $_GET['page'], $tpl->getInfo('nbPages')); ?></div>

<?php include(dirname(__FILE__) . '/nav.tpl.php'); ?>

		</div>
<?php endif; ?>

		<div id="links_js">
			<div id="links_js_show">
				<a class="js" href="javascript:show_all();"><?php echo __('tout montrer'); ?></a>
				-
				<a class="js" href="javascript:hide_all();"><?php echo __('tout cacher'); ?></a>
				&nbsp;<?php echo __('pour'); ?>&nbsp;
				<select id="show_mode">
					<option value=".obj_fold"><?php echo __('tout'); ?></option>
					<option value=".obj_infos"><?php echo __('statistiques'); ?></option>
					<option value=".obj_edition"><?php echo __('édition'); ?></option>
				</select>
			</div>
			<div id="links_js_select">
				<a class="js" href="javascript:select_all();"><?php echo __('tout sélectionner'); ?></a>
				-
				<a class="js" href="javascript:select_invert();"><?php echo __('inverser la sélection'); ?></a>
			</div>
		</div>
<?php endif; ?>

<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

<?php if ($tpl->disItems()) : ?>
		<form id="form_edit" action="" method="post">

<?php while ($tpl->nextImage(50)) : ?>
			<div id="obj_<?php echo $tpl->getImage('id'); ?>" class="selectable_class obj obj_pending obj_invisible">
				<div class="obj_image">
					<a>
						<img style="padding:<?php echo $tpl->getImage('thumb_center'); ?>"
							<?php echo $tpl->getImage('thumb_size'); ?>
							alt="<?php echo $tpl->getImage('title'); ?>"
							src="<?php echo $tpl->getImage('thumb_src'); ?>" />
					</a>
				</div>
				<div class="obj_top">
					<span class="obj_checkbox selectable_zone">
						<input id="obj_<?php echo $tpl->getImage('id'); ?>" name="select[<?php echo $tpl->getImage('id'); ?>]" class="selectable" type="checkbox" />
					</span>
					<div class="obj_right">
						<span class="obj_status"><?php echo $tpl->getImage('status_msg'); ?></span>
						<span class="obj_group"><?php echo $tpl->getImage('object_type'); ?></span>
					</div>
					<div class="obj_left">
						<p class="obj_basics">
							<a class="obj_title obj_title_link" href="<?php echo $tpl->getImage('src_image'); ?>">
								<?php echo utils::strLimit($tpl->getImage('title'), 50); ?>

							</a>
						</p>
						<p class="obj_links">
							<span class=" icon icon_stats show_parts"><a rel="obj_infos_<?php echo $tpl->getImage('id'); ?>" class="js" href="javascript:;"><?php echo __('statistiques'); ?></a></span>
							-
							<span class=" icon icon_edit show_parts"><a rel="obj_edition_<?php echo $tpl->getImage('id'); ?>" class="js" href="javascript:;"><?php echo __('édition'); ?></a></span>
						</p>
					</div>
				</div>
				<div style="display:none" class="obj_infos obj_fold" id="obj_infos_<?php echo $tpl->getImage('id'); ?>">
					<div class="obj_fold_inner">
						<table class="light">
							<tr><td><?php echo __('Poids'); ?></td><td><?php echo $tpl->getImage('filesize'); ?></td></tr>
							<tr><td><?php echo __('Dimensions'); ?></td><td><?php echo $tpl->getImage('size'); ?></td></tr>
							<tr><td><?php echo __('Type'); ?></td><td><?php echo $tpl->getImage('type'); ?></td></tr>
							<tr><td><?php echo __('Date d\'ajout'); ?></td><td><?php echo $tpl->getImage('date'); ?></td></tr>
							<tr><td><?php echo __('Utilisateur'); ?></td><td><?php echo $tpl->getImage('user_link'); ?></td></tr>
							<tr><td><?php echo __('IP'); ?></td><td><?php echo $tpl->getImage('ip'); ?></td></tr>
							<tr><td><?php echo ucfirst(__('album')); ?></td><td><?php echo $tpl->getImage('album_link'); ?></td></tr>
						</table>
					</div>
				</div>
				<div style="display:none" class="obj_edition obj_fold" id="obj_edition_<?php echo $tpl->getImage('id'); ?>">
					<div class="obj_fold_inner">
<?php while ($tpl->nextLang()) : ?>
						<p<?php if (!$tpl->disLang('selected')) : ?> style="display:none"<?php endif; ?> class="field_ftw">
							<label class="icon_lang icon_<?php echo $tpl->getLang('code'); ?>" for="title_<?php echo $tpl->getImage('id'); ?>_<?php echo $tpl->getLang('code'); ?>"><?php echo __('Titre :'); ?></label>
							<input name="<?php echo $tpl->getImage('id'); ?>[title][<?php echo $tpl->getLang('code'); ?>]" size="40" maxlength="255" id="title_<?php echo $tpl->getImage('id'); ?>_<?php echo $tpl->getLang('code'); ?>" type="text" class="text" value="<?php echo $tpl->getImage('title_lang'); ?>" />
						</p>
<?php endwhile; ?>
<?php while ($tpl->nextLang()) : ?>
						<p<?php if (!$tpl->disLang('selected')) : ?> style="display:none"<?php endif; ?> class="field_ftw field_html">
							<label class="icon_lang icon_<?php echo $tpl->getLang('code'); ?>" for="description_<?php echo $tpl->getImage('id'); ?>_<?php echo $tpl->getLang('code'); ?>"><?php echo __('Description :'); ?></label>
							<span class="field_html_tag">
								<a title="<?php echo __('Cliquez pour obtenir la liste des balises autorisées'); ?>" href="javascript:;">HTML</a>
							</span>
							<span class="field_html_textarea">
								<textarea class="resizable" name="<?php echo $tpl->getImage('id'); ?>[description][<?php echo $tpl->getLang('code'); ?>]" rows="6" cols="50" id="description_<?php echo $tpl->getImage('id'); ?>_<?php echo $tpl->getLang('code'); ?>"><?php echo $tpl->getImage('description_lang'); ?></textarea>
							</span>
						</p>
<?php endwhile; ?>
					</div>
				</div>
			</div>
<?php endwhile; ?>

			<div id="actions">
				<p>
					<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
					<input class="submit" name="save" type="submit" value="<?php echo __('Enregistrer les modifications'); ?>" />
				</p>
				<p>
					<label for="selection_action"><?php echo __('Pour la sélection :'); ?></label>
					<select id="selection_action" name="action">
						<option value="publish"><?php echo __('publier'); ?></option>
						<option value="move"><?php echo __('publier et déplacer vers'); ?></option>
						<option value="delete"><?php echo __('supprimer'); ?></option>
					</select>
					<select class="list_action" id="categories" name="destination_cat">
						<?php echo $tpl->getCategoriesList(); ?>

					</select>
					<input disabled="disabled" class="submit js_required" id="action_submit" name="selection" type="submit" value="<?php echo __('Valider'); ?>" />
				</p>
			</div>

		</form>

<?php else : ?>
		<p class="report_zero_item report_msg report_info"><?php echo __('Aucune image en attente.'); ?></p>
<?php endif; ?>

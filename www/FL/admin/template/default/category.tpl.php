
<?php include_once(dirname(__FILE__) . '/albums_submenu.tpl.php'); ?>

<?php if ($_GET['section'] == 'category') : ?>
<?php include_once(dirname(__FILE__) . '/category_related.tpl.php'); ?>
<?php else : ?>
<?php include_once(dirname(__FILE__) . '/album_related.tpl.php'); ?>
<?php endif; ?>

		<div id="links_tools">
			<span class="icon icon_options show_tool"><a rel="options" class="js" href="javascript:;"><?php echo mb_strtolower(__('Options d\'affichage')); ?></a></span>
<?php if ($tpl->disPerm('albums_add') && !$tpl->disSearch()) : ?>
			-
			<span class="icon icon_add_category show_tool"><a rel="new_cat" class="js" href="javascript:;"><?php echo mb_strtolower(__('Nouvelle catégorie')); ?></a></span>
<?php endif; ?>
			-
			<span class="icon icon_search show_tool"><a rel="search" class="js" href="javascript:;"><?php echo __('recherche'); ?></a></span>
		</div>

		<form action="" method="post" style="display:none" class="tool" id="options">
			<fieldset>
				<legend><?php echo __('Options d\'affichage'); ?></legend>
				<p class="field">
					<label for="nb_per_page"><?php echo __('Nombre de catégories par page :'); ?></label>
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

<?php if (!$tpl->disSearch() && $tpl->disPerm('albums_add')) : ?>
		<form action="" method="post" style="display:none" class="tool" id="new_cat">
			<fieldset>
				<legend><?php echo __('Nouvelle catégorie'); ?></legend>
				<p class="field">
					<?php echo __('Type :'); ?>
					<input id="new_cat_cat" value="cat" name="type" type="radio" />
					<label for="new_cat_cat"><?php echo __('catégorie'); ?></label>
					&nbsp;
					<input checked="checked" id="new_cat_alb" value="alb" name="type" type="radio" />
					<label for="new_cat_alb"><?php echo __('album'); ?></label>
<?php if ($tpl->disHelp()) : ?>
					&nbsp;
					<a rel="h_new_cat" title="<?php echo __('Obtenir de l\'aide sur cette fonction'); ?>" class="help_link help_link_context" href="javascript:;"><img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/help-link.png" alt="<?php echo __('Aide'); ?>" /></a>
<?php endif; ?>
				</p>
				<p class="field">
					<label for="new_cat_name"><?php echo __('Titre :'); ?></label>
					<input size="40" maxlength="128" id="new_cat_name" type="text" class="focus text" name="name" />
				</p>
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
				<input name="new_cat" class="submit" type="submit" value="<?php echo __('Créer'); ?>" />
			</fieldset>
		</form>
<?php endif; ?>

<?php include_once(dirname(__FILE__) . '/albums_search.tpl.php'); ?>

		<div id="map_browse" class="browse browse_wlimit">
			<label><?php echo __('Parcourir :'); ?></label>
			<select onchange="window.location.href='<?php echo $tpl->getAdmin('admin_base_url'); ?>'+this.options[this.selectedIndex].value">
				<?php echo $tpl->getMap(); ?>

			</select>
		</div>

		<p id="position"><?php echo $tpl->getPosition(); ?><?php if (!$tpl->disSearch()) : ?> [<?php echo $tpl->getInfo('nbItems'); ?>]<?php endif; ?></p>

<?php if ($tpl->disSearch()) : ?>
		<p id="position_special_exit"><a href="<?php echo $tpl->getSearch('section_link'); ?>"><?php echo __('sortir de la recherche'); ?></a></p>
		<p id="position_special"><?php printf(__('Résultat de votre recherche %s'), '<span id="position_query">' . $tpl->getSearch('query') . '</span>'); ?> <span>[<?php echo $tpl->getInfo('nbItems'); ?>]</span></p>
<?php endif; ?>

<?php if ($tpl->disItems()) : ?>

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
<?php if ($tpl->disPerm('access_all')) : ?>
					<option value=".obj_infos"><?php echo __('statistiques'); ?></option>
<?php endif; ?>
					<option value=".obj_edition"><?php echo __('informations'); ?></option>
					<option value=".obj_geoloc"><?php echo __('géolocalisation'); ?></option>
<?php if ($tpl->disPerm('albums_modif')) : ?>
					<option value=".obj_settings"><?php echo mb_strtolower(__('Réglages')); ?></option>
<?php endif; ?>
				</select>
			</div>
<?php if ($tpl->disPerm('albums_modif')) : ?>
			<div id="links_js_select">
				<a class="js" href="javascript:select_all();"><?php echo __('tout sélectionner'); ?></a>
				-
				<a class="js" href="javascript:select_invert();"><?php echo __('inverser la sélection'); ?></a>
			</div>
<?php else : ?>
			<br />
<?php endif; ?>
		</div>

<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

		<form id="form_edit" action="" method="post">

<?php while ($tpl->nextCategory(50)) : ?>
			<div id="obj_<?php echo $tpl->getCategory('id'); ?>" class="selectable_class obj<?php if (!$tpl->disCategory('publish')) : ?> obj_desactived obj_invisible<?php endif; ?><?php if ($tpl->disCategory('empty')) : ?> obj_empty<?php endif; ?>">
				<div class="obj_image">
<?php if (!$tpl->disCategory('empty')) : ?>
					<a<?php if ($tpl->disPerm('albums_modif')) : ?> title="<?php echo __('Modifier la vignette'); ?>" href="<?php echo $tpl->getCategory('thumb_link'); ?>"<?php endif; ?>>
						<img style="padding:<?php echo $tpl->getCategory('thumb_center'); ?>"
							<?php echo $tpl->getCategory('thumb_size'); ?>
							alt="<?php echo $tpl->getCategory('title'); ?>"
							src="<?php echo $tpl->getCategory('thumb_src'); ?>" />
					</a>
<?php endif; ?>
				</div>
				<div class="obj_top">
					<span class="obj_checkbox selectable_zone">
						<input<?php if (!$tpl->disPerm('albums_modif')) : ?> disabled="disabled"<?php endif; ?> id="obj_check_<?php echo $tpl->getCategory('id'); ?>" name="select[<?php echo $tpl->getCategory('id'); ?>]" class="selectable" type="checkbox" />
					</span>
					<div class="obj_right">
						<span class="obj_status">
							<span><?php echo $tpl->getCategory('status_msg'); ?></span>
<?php if ($tpl->disCategory('protected')) : ?>
							<img class="obj_protected" width="24" height="24" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/24x24/key.png" alt="<?php echo __('Accès protégé'); ?>" title="<?php echo __('Accès protégé par un mot de passe'); ?>" />
<?php endif; ?>
						</span>
						<span class="obj_group"><?php echo $tpl->getCategory('object_type'); ?></span>
					</div>
					<div class="obj_left">
						<p class="obj_basics">
							<a class="obj_title obj_title_link" href="<?php echo $tpl->getCategory('object_link'); ?>"><?php echo $tpl->getStrLimit($tpl->getCategory('title'), 50); ?></a>
<?php if ($tpl->disCategory('publish')) : ?>
							<a title="<?php echo __('Voir dans la galerie'); ?>" class="obj_gallery_link" href="<?php echo $tpl->getCategory('gallery_link'); ?>">&nbsp;</a>
<?php endif; ?>
						</p>
						<p class="obj_links">
<?php if ($tpl->disPerm('access_all')) : ?>
							<span class="icon icon_stats show_parts"><a rel="obj_infos_<?php echo $tpl->getCategory('id'); ?>" class="js" href="javascript:;"><?php echo __('statistiques'); ?></a></span>
							-
<?php endif; ?>
							<span class="icon icon_edit show_parts"><a rel="obj_edition_<?php echo $tpl->getCategory('id'); ?>" class="js" href="javascript:;"><?php echo __('informations'); ?></a></span>
							-
							<span class="icon icon_geomap show_parts"><a rel="obj_geoloc_<?php echo $tpl->getCategory('id'); ?>" class="js" href="javascript:;"><?php echo __('géolocalisation'); ?></a></span>
<?php if ($tpl->disPerm('albums_modif')) : ?>
							-
							<span class="icon icon_settings show_parts"><a rel="obj_settings_<?php echo $tpl->getCategory('id'); ?>" class="js" href="javascript:;"><?php echo mb_strtolower(__('Réglages')); ?></a></span>
<?php endif; ?>
						</p>
					</div>
				</div>
<?php if ($tpl->disPerm('access_all')) : ?>
				<div style="display:none" class="obj_infos obj_fold" id="obj_infos_<?php echo $tpl->getCategory('id'); ?>">
					<div class="obj_fold_inner">
						<table class="light">
							<tr class="th"><th></th><th class="title"><?php echo __('publié'); ?></th><th class="title"><?php echo __('hors ligne'); ?></th><th class="title"><?php echo __('total'); ?></th></tr>
							<tr><td><?php echo __('Poids'); ?></td><td class="number"><?php echo $tpl->getCategory('a_size'); ?></td><td class="number"><?php echo $tpl->getCategory('d_size'); ?></td><td class="number"><?php echo $tpl->getCategory('size'); ?></td></tr>
							<tr><td><?php echo __('Nombre d\'albums'); ?></td><td class="number"><?php echo $tpl->getCategory('a_albums'); ?></td><td class="number"><?php echo $tpl->getCategory('d_albums'); ?></td><td class="number"><?php echo $tpl->getCategory('albums'); ?></td></tr>
							<tr><td><?php echo __('Nombre d\'images'); ?></td><td class="number"><?php echo $tpl->getCategory('a_images'); ?></td><td class="number"><?php echo $tpl->getCategory('d_images'); ?></td><td class="number"><?php echo $tpl->getCategory('images'); ?></td></tr>
							<tr><td><?php echo __('Nombre de visites'); ?></td><td class="number"><?php echo $tpl->getCategory('a_hits'); ?></td><td class="number"><?php echo $tpl->getCategory('d_hits'); ?></td><td class="number"><?php echo $tpl->getCategory('hits'); ?></td></tr>
							<tr><td><?php echo __('Nombre de commentaires'); ?></td><td class="number"><?php echo $tpl->getCategory('a_comments'); ?></td><td class="number"><?php echo $tpl->getCategory('d_comments'); ?></td><td class="number"><?php echo $tpl->getCategory('comments'); ?></td></tr>
							<tr><td><?php echo __('Nombre de votes'); ?></td><td class="number"><?php echo $tpl->getCategory('a_votes'); ?></td><td class="number"><?php echo $tpl->getCategory('d_votes'); ?></td><td class="number"><?php echo $tpl->getCategory('votes'); ?></td></tr>
							<tr><td><?php echo __('Note moyenne'); ?></td><td class="number"><?php echo $tpl->getCategory('a_rate'); ?></td><td class="number"><?php echo $tpl->getCategory('d_rate'); ?></td><td class="number"><?php echo $tpl->getCategory('rate'); ?></td></tr>
						</table>
						<p><?php printf(__('Créé le %s'), $tpl->getCategory('crtdt')); ?></p>
						<p><?php printf(__('Propriétaire : %s'), $tpl->getCategory('owner_link')); ?></p>
					</div>
				</div>
<?php endif; ?>
				<div style="display:none" class="obj_edition obj_fold" id="obj_edition_<?php echo $tpl->getCategory('id'); ?>">
					<div class="obj_fold_inner">
<?php while ($tpl->nextLang()) : ?>
						<p<?php if (!$tpl->disLang('selected')) : ?> style="display:none"<?php endif; ?> class="field_ftw">
							<label class="icon_lang icon_<?php echo $tpl->getLang('code'); ?>" for="title_<?php echo $tpl->getCategory('id'); ?>_<?php echo $tpl->getLang('code'); ?>"><?php echo __('Titre :'); ?></label>
							<input name="<?php echo $tpl->getCategory('id'); ?>[title][<?php echo $tpl->getLang('code'); ?>]" size="40" maxlength="255" id="title_<?php echo $tpl->getCategory('id'); ?>_<?php echo $tpl->getLang('code'); ?>" type="text" class="text" value="<?php echo $tpl->getCategory('title_lang'); ?>" />
						</p>
<?php endwhile; ?>
						<p class="field_ftw">
							<label for="urlname_<?php echo $tpl->getCategory('id'); ?>"><?php echo __('Nom d\'URL :'); ?></label>
							<input name="<?php echo $tpl->getCategory('id'); ?>[urlname]" size="40" maxlength="64" id="urlname_<?php echo $tpl->getCategory('id'); ?>" type="text" class="text" value="<?php echo $tpl->getCategory('urlname'); ?>" />
						</p>
						<p class="field_ftw">
							<label for="dirname_<?php echo $tpl->getCategory('id'); ?>"><?php echo __('Nom de répertoire :'); ?></label>
							<input name="<?php echo $tpl->getCategory('id'); ?>[dirname]" size="40" maxlength="64" id="dirname_<?php echo $tpl->getCategory('id'); ?>" type="text" class="text" value="<?php echo $tpl->getCategory('dirname'); ?>" />
						</p>
<?php while ($tpl->nextLang()) : ?>
						<p<?php if (!$tpl->disLang('selected')) : ?> style="display:none"<?php endif; ?> class="field_ftw field_html">
							<label class="icon_lang icon_<?php echo $tpl->getLang('code'); ?>" for="description_<?php echo $tpl->getCategory('id'); ?>_<?php echo $tpl->getLang('code'); ?>"><?php echo __('Description :'); ?></label>
							<span class="field_html_tag">
								<a title="<?php echo __('Cliquez pour obtenir la liste des balises autorisées'); ?>" href="javascript:;">HTML</a>
							</span>
							<span class="field_html_textarea">
								<textarea class="resizable" name="<?php echo $tpl->getCategory('id'); ?>[description][<?php echo $tpl->getLang('code'); ?>]" rows="6" cols="50" id="description_<?php echo $tpl->getCategory('id'); ?>_<?php echo $tpl->getLang('code'); ?>"><?php echo $tpl->getCategory('description_lang'); ?></textarea>
							</span>
						</p>
<?php endwhile; ?>
					</div>
				</div>
				<div style="display:none" class="obj_geoloc obj_fold" id="obj_geoloc_<?php echo $tpl->getCategory('id'); ?>">
					<div class="obj_fold_inner">
						<p class="field_ftw">
							<label for="latitude_<?php echo $tpl->getCategory('id'); ?>"><?php echo __('Latitude :'); ?></label>
							<input name="<?php echo $tpl->getCategory('id'); ?>[latitude]" size="40" maxlength="20" id="latitude_<?php echo $tpl->getCategory('id'); ?>" type="text" class="text" value="<?php echo $tpl->getCategory('latitude'); ?>" />
						</p>
						<p class="field_ftw">
							<label for="longitude_<?php echo $tpl->getCategory('id'); ?>"><?php echo __('Longitude :'); ?></label>
							<input name="<?php echo $tpl->getCategory('id'); ?>[longitude]" size="40" maxlength="20" id="longitude_<?php echo $tpl->getCategory('id'); ?>" type="text" class="text" value="<?php echo $tpl->getCategory('longitude'); ?>" />
						</p>
						<p class="field_ftw">
							<label for="place_<?php echo $tpl->getCategory('id'); ?>"><?php echo __('Lieu :'); ?></label>
							<input name="<?php echo $tpl->getCategory('id'); ?>[place]" size="40" maxlength="100" id="place_<?php echo $tpl->getCategory('id'); ?>" type="text" class="text" value="<?php echo $tpl->getCategory('place'); ?>" />
						</p>
						<p class="field_ftw">
							<a href="?q=geoloc-<?php echo ($tpl->disCategory('type_album')) ? 'album' : 'category'; ?>/<?php echo $tpl->getCategory('id'); ?>"><?php echo __('carte'); ?></a>
						</p>
					</div>
				</div>
<?php if ($tpl->disPerm('albums_modif')) : ?>
				<div style="display:none" class="obj_settings obj_fold" id="obj_settings_<?php echo $tpl->getCategory('id'); ?>">
					<div class="obj_fold_inner">
<?php if ($tpl->disPerm('access_all')) : ?>
						<p class="field_ftw">
							<label for="password_<?php echo $tpl->getCategory('id'); ?>"><?php echo __('Mot de passe :'); ?></label>
							<input name="<?php echo $tpl->getCategory('id'); ?>[password]" size="40" maxlength="1024" id="password_<?php echo $tpl->getCategory('id'); ?>" type="password" class="text" value="<?php echo $tpl->getCategory('password'); ?>" />
						</p>
<?php endif; ?>
						<p class="field checkbox">
							<input<?php if (!$tpl->disCategory('commentable_parent')) : ?> disabled="disabled"<?php endif; ?><?php if ($tpl->disCategory('commentable')) : ?> checked="checked"<?php endif; ?> id="allow_comments_<?php echo $tpl->getCategory('id'); ?>" name="<?php echo $tpl->getCategory('id'); ?>[commentable]" type="checkbox" />
							<span><label for="allow_comments_<?php echo $tpl->getCategory('id'); ?>"><?php echo __('Autoriser les commentaires'); ?></label></span>
						</p>
						<p class="field checkbox">
							<input<?php if (!$tpl->disCategory('votable_parent')) : ?> disabled="disabled"<?php endif; ?><?php if ($tpl->disCategory('votable')) : ?> checked="checked"<?php endif; ?> id="allow_votes_<?php echo $tpl->getCategory('id'); ?>" name="<?php echo $tpl->getCategory('id'); ?>[votable]" type="checkbox" />
							<span><label for="allow_votes_<?php echo $tpl->getCategory('id'); ?>"><?php echo __('Autoriser les votes'); ?></label></span>
						</p>
						<p class="field checkbox">
							<input<?php if (!$tpl->disCategory('uploadable_parent')) : ?> disabled="disabled"<?php endif; ?><?php if ($tpl->disCategory('uploadable')) : ?> checked="checked"<?php endif; ?> id="allow_upload_<?php echo $tpl->getCategory('id'); ?>" name="<?php echo $tpl->getCategory('id'); ?>[uploadable]" type="checkbox" />
							<span><label for="allow_upload_<?php echo $tpl->getCategory('id'); ?>"><?php echo __('Autoriser l\'ajout d\'images'); ?></label></span>
						</p>
<?php if ($tpl->disCategory('creatable_field')) : ?>
						<p class="field checkbox">
							<input<?php if (!$tpl->disCategory('creatable_parent')) : ?> disabled="disabled"<?php endif; ?><?php if ($tpl->disCategory('creatable')) : ?> checked="checked"<?php endif; ?> id="allow_create_<?php echo $tpl->getCategory('id'); ?>" name="<?php echo $tpl->getCategory('id'); ?>[creatable]" type="checkbox" />
							<span><label for="allow_create_<?php echo $tpl->getCategory('id'); ?>"><?php echo __('Autoriser la création de catégories'); ?></label></span>
						</p>
<?php endif; ?>
						<p class="field">
							<label for="style_<?php echo $tpl->getCategory('id'); ?>"><?php echo __('Style :'); ?></label>
							<select id="style_<?php echo $tpl->getCategory('id'); ?>" name="<?php echo $tpl->getCategory('id'); ?>[style]">
								<?php echo $tpl->getCategory('styles'); ?>

							</select>
						</p>
						<p class="field">
							<label><?php echo ($tpl->disCategory('type_album')) ? __('Trier les images par :') : __('Trier les catégories par :'); ?></label>
						</p>
						<div class="field_second">
							<p class="field">
								<?php printf(__('Critère n°%s :'), 1); ?>
								<select name="<?php echo $tpl->getCategory('id'); ?>[orderby_1]">
									<?php echo $tpl->getCategory('orderby_1'); ?>

								</select>
								<select name="<?php echo $tpl->getCategory('id'); ?>[ascdesc_1]">
									<?php echo $tpl->getCategory('ascdesc_1'); ?>

								</select>
							</p>
							<p class="field">
								<?php printf(__('Critère n°%s :'), 2); ?>
								<select name="<?php echo $tpl->getCategory('id'); ?>[orderby_2]">
									<?php echo $tpl->getCategory('orderby_2'); ?>

								</select>
								<select name="<?php echo $tpl->getCategory('id'); ?>[ascdesc_2]">
									<?php echo $tpl->getCategory('ascdesc_2'); ?>

								</select>
							</p>
							<p class="field">
								<?php printf(__('Critère n°%s :'), 3); ?>
								<select name="<?php echo $tpl->getCategory('id'); ?>[orderby_3]">
									<?php echo $tpl->getCategory('orderby_3'); ?>

								</select>
								<select name="<?php echo $tpl->getCategory('id'); ?>[ascdesc_3]">
									<?php echo $tpl->getCategory('ascdesc_3'); ?>

								</select>
							</p>
						</div>
						<p class="field">
							<a href="<?php echo $tpl->getCategory('watermark_link'); ?>"><?php echo __('modifier le filigrane'); ?></a>
						</p>
					</div>
				</div>
<?php endif; ?>
			</div>
<?php endwhile; ?>

			<div id="actions">
				<p>
					<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
					<input class="submit" name="save" type="submit" value="<?php echo __('Enregistrer les modifications'); ?>" />
				</p>
<?php if ($tpl->disPerm('albums_modif')) : ?>
				<script type="text/javascript">
				//<![CDATA[
				var msg_destination_cat = "<?php echo $tpl->getL10nJS(__('Impossible de déplacer la sélection vers la catégorie choisie.')); ?>";
				var confirm_delete = "<?php echo $tpl->getL10nJS(__('Êtes-vous sûr de vouloir supprimer les catégories sélectionnées, ainsi que toutes les images, commentaires, votes et tags qui s\'y trouvent ?')); ?>";
				//]]>
				</script>
				<p>
					<label for="selection_action"><?php echo __('Pour la sélection :'); ?></label>
					<select id="selection_action" name="action">
						<option value="publish"><?php echo __('publier'); ?></option>
						<option value="unpublish"><?php echo __('hors ligne'); ?></option>
						<option value="delete"><?php echo __('supprimer'); ?></option>
						<option value="move"><?php echo __('déplacer vers'); ?></option>
						<option value="owner"><?php echo __('nouveau propriétaire'); ?></option>
						<option value="reset_hits"><?php echo __('nombre de visites à zéro'); ?></option>
					</select>
					<select class="list_action" id="categories" name="destination_cat">
						<?php echo $tpl->getCategoriesList(); ?>

					</select>
					<select class="list_action" id="users" name="owner">
						<?php echo $tpl->getUsersList(); ?>

					</select>
					<input disabled="disabled" class="submit js_required" id="action_submit" name="selection" type="submit" value="<?php echo __('Valider'); ?>" />
				</p>
<?php endif; ?>
			</div>

		</form>

<?php else : ?>
		<p class="report_zero_item report_msg report_info"><?php echo (isset($_GET['search'])) ? __('Aucune catégorie.') : __('La catégorie ne contient aucun élément.'); ?></p>
<?php endif; ?>

<?php if ($tpl->disNavigation()) : ?>
		<div class="nav" id="nav_bottom">
			<div class="nav_left"></div>
			<div class="nav_right"><?php printf(__('page %s|%s'), $_GET['page'], $tpl->getInfo('nbPages')); ?></div>

<?php include(dirname(__FILE__) . '/nav.tpl.php'); ?>

		</div>
<?php endif; ?>

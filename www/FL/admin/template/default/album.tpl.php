
<?php include_once(dirname(__FILE__) . '/albums_submenu.tpl.php'); ?>

<?php if ($_GET['section'] == 'album') : ?>
<?php include_once(dirname(__FILE__) . '/album_related.tpl.php'); ?>
<?php else : ?>
<?php include_once(dirname(__FILE__) . '/category_related.tpl.php'); ?>
<?php endif; ?>

		<div id="links_tools">
			<span class="icon icon_options show_tool"><a rel="options" class="js" href="javascript:;"><?php echo mb_strtolower(__('Options d\'affichage')); ?></a></span>
<?php if ($tpl->disPosition('normal') && $tpl->disPerm('albums_add')) : ?>
			-
			<span class="icon icon_add_images show_tool"><a rel="add_images" class="js" href="javascript:;"><?php echo mb_strtolower(__('Ajouter des images')); ?></a></span>
<?php endif; ?>
			-
			<span class="icon icon_search show_tool"><a rel="search" class="js" href="javascript:;"><?php echo __('recherche'); ?></a></span>
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

<?php if (!$tpl->disSearch() && $tpl->disPerm('albums_add')) : ?>
		<form enctype="multipart/form-data" method="post" action="" style="display:none" class="tool" id="add_images">
			<fieldset>
				<legend><?php echo __('Ajouter des images'); ?></legend>
				<p id="upload_switch">
					<span class="icon icon_add_image"><a class="js" id="upload_classic" href="javascript:;"><?php echo mb_strtolower(__('Envoi unitaire')); ?></a></span>
					-
					<span class="icon icon_add_images selected"><a class="js" id="upload_multiple" href="javascript:;"><?php echo mb_strtolower(__('Envoi multiple')); ?></a></span>
				</p>
				<div id="classic_upload" style="display:none">
					<p class="field files">
						<span class="icon icon_add"><a id="add_file" class="js" href="javascript:;"><?php echo __('plus de fichiers'); ?></a></span>
					</p>
					<p class="field files">
						<input type="file" name="files[]" size="40" maxlength="2048" />
						<input type="file" name="files[]" size="40" maxlength="2048" />
						<input type="file" name="files[]" size="40" maxlength="2048" />
						<input type="file" name="files[]" size="40" maxlength="2048" />
						<input name="MAX_FILE_SIZE" value="<?php echo $tpl->getMaxFileSize(); ?>" type="hidden" />
					</p>
					<p class="field checkbox">
						<input checked="checked" id="classic_publish_images" name="classic_publish_images" type="checkbox" />
						<span><label for="classic_publish_images"><?php echo __('Publier les images'); ?></label></span>
					</p>
					<p class="field limits"><?php printf(__('Vos images doivent être au format JPEG, GIF ou PNG uniquement et faire %s Ko et %s pixels maximum par fichier (ces valeurs peuvent être changées dans la section "Utilisateurs / Options").'), $tpl->getLimits('maxfilesize'), $tpl->getLimits('maxsize')); ?></p>
					<input name="tempdir" type="hidden" value="<?php echo $tpl->getTempDir(); ?>" />
					<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
					<input name="upload" class="submit" type="submit" value="<?php echo __('Envoyer'); ?>" />
				</div>
				<div id="multiple_upload">
					<div id="fileUploadQueue"></div>
					<p class="field checkbox">
						<input checked="checked" id="multiple_publish_images" name="multiple_publish_images" type="checkbox" />
						<span><label for="multiple_publish_images"><?php echo __('Publier les images'); ?></label></span>
					</p>
					<p class="field limits"><?php printf(__('Vos images doivent être au format JPEG, GIF ou PNG uniquement et faire %s Ko et %s pixels maximum par fichier (ces valeurs peuvent être changées dans la section "Utilisateurs / Options").'), $tpl->getLimits('maxfilesize'), $tpl->getLimits('maxsize')); ?></p>
					<div id="uploadify"></div>
					<div id="fileUploadButtons">
						<input id="fileUploadAdd" class="submit" type="button" value="<?php echo __('Ajouter des fichiers'); ?>" />
						<input id="fileUploadClear" class="submit" type="button" value="<?php echo __('Vider la liste'); ?>" />
						<input id="fileUploadStart" class="submit" type="button" value="<?php echo __('Envoyer'); ?>" />
					</div>
					<script type="text/javascript">
					var max_file_size = <?php echo $tpl->getMaxFileSize(); ?>;
					var upload_args = {
						'from': 'admin',
						'id': '<?php echo (int) $_GET['object_id']; ?>',
						'session_token': '<?php echo $tpl->getAdmin('session_token'); ?>',
						'tempdir': '<?php echo $tpl->getTempDir(); ?>'
					};
					</script>
				</div>
			</fieldset>
		</form>
<?php endif; ?>

<?php include_once(dirname(__FILE__) . '/albums_search.tpl.php'); ?>

		<div class="browse browse_wlimit">
			<label><?php echo __('Parcourir :'); ?></label>
			<select name="browse" onchange="window.location.href='<?php echo $tpl->getAdmin('admin_base_url'); ?>'+this.options[this.selectedIndex].value">
				<?php echo $tpl->getMap(); ?>

			</select>
		</div>

		<p id="position"><?php echo $tpl->getPosition(); ?><?php if ($tpl->disPosition('normal')) : ?> [<?php echo $tpl->getInfo('nbItems'); ?>]<?php endif; ?></p>

<?php if ($tpl->disPosition('search')) : ?>
		<p id="position_special_exit"><a href="<?php echo $tpl->getSearch('section_link'); ?>"><?php echo __('sortir de la recherche'); ?></a></p>
		<p id="position_special"><?php printf(__('Résultat de votre recherche %s'), '<span id="position_query">' . $tpl->getSearch('query') . '</span>'); ?> <span>[<?php echo $tpl->getInfo('nbItems'); ?>]</span></p>
<?php endif; ?>
<?php if ($tpl->disPosition('filter')) : ?>
		<p id="position_special_exit"><a href="<?php echo $tpl->getFilter('section_link'); ?>"><?php echo __('afficher toutes les images'); ?></a></p>
		<p id="position_special"><?php printf($tpl->getFilter('text'), '<span id="position_filter">' . $tpl->getFilter('value') . '</span>'); ?> <span>[<?php echo $tpl->getInfo('nbItems'); ?>]</span></p>
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
<?php if ($tpl->disImage('stats')) : ?>
					<option value=".obj_infos"><?php echo __('statistiques'); ?></option>
<?php endif; ?>
					<option value=".obj_edition"><?php echo __('informations'); ?></option>
					<option value=".obj_geoloc"><?php echo __('géolocalisation'); ?></option>
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
<?php endif; ?>

<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

<?php if ($tpl->disItems()) : ?>
		<form id="form_edit" action="" method="post">
<?php while ($tpl->nextImage(50)) : ?>
			<div id="obj_<?php echo $tpl->getImage('id'); ?>" class="selectable_class obj<?php if (!$tpl->disImage('publish')) : ?> obj_desactived obj_invisible<?php endif; ?>">
				<div class="obj_image">
					<a<?php if ($tpl->disPerm('albums_modif')) : ?> title="<?php echo __('Modifier la vignette'); ?>" href="<?php echo $tpl->getLink('thumb-image/' . $tpl->getImage('id')); ?>"<?php endif; ?>>
						<img style="padding:<?php echo $tpl->getImage('thumb_center'); ?>"
							<?php echo $tpl->getImage('thumb_size'); ?>

							alt="<?php echo $tpl->getImage('title'); ?>"
							src="<?php echo $tpl->getImage('thumb_src'); ?>" />
					</a>
				</div>
				<div class="obj_top">
					<span class="obj_checkbox selectable_zone">
						<input<?php if (!$tpl->disPerm('albums_modif')) : ?> disabled="disabled"<?php endif; ?> name="select[<?php echo $tpl->getImage('id'); ?>]" class="selectable" type="checkbox" />
					</span>
					<div class="obj_right">
						<span class="obj_status">
							<span><?php echo $tpl->getImage('status_msg'); ?></span>
<?php if ($tpl->disImage('protected')) : ?>
							<img class="obj_protected" width="24" height="24" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/24x24/key.png" alt="<?php echo __('Accès protégé'); ?>" title="<?php echo __('Accès protégé par un mot de passe'); ?>" />
<?php endif; ?>
						</span>
						<span class="obj_group"><?php echo $tpl->getImage('object_type'); ?></span>
					</div>
					<div class="obj_left">
						<p class="obj_basics">
							<a class="obj_title obj_title_link" 
<?php if ($tpl->disPerm('albums_modif')) : ?>
								title="<?php echo __('Modifier l\'image'); ?>" href="<?php echo $tpl->getLink('image/' . $tpl->getImage('id')); ?>">
<?php else : ?>
								href="<?php echo $tpl->getImage('src'); ?>">
<?php endif; ?>
								<?php echo $tpl->getStrLimit($tpl->getImage('title'), 50); ?>

							</a>
<?php if ($tpl->disImage('publish')) : ?>
							<a title="<?php echo __('Voir dans la galerie'); ?>" class="obj_gallery_link" href="<?php echo $tpl->getImage('gallery_link'); ?>">&nbsp;</a>
<?php endif; ?>
						</p>
						<p class="obj_links">
<?php if ($tpl->disImage('stats')) : ?>
							<span class="icon icon_stats show_parts"><a rel="obj_infos_<?php echo $tpl->getImage('id'); ?>" class="js" href="javascript:;"><?php echo __('statistiques'); ?></a></span>
							-
<?php endif; ?>
							<span class="icon icon_edit show_parts"><a rel="obj_edition_<?php echo $tpl->getImage('id'); ?>" class="js" href="javascript:;"><?php echo __('informations'); ?></a></span>
							-
							<span class="icon icon_geomap show_parts"><a rel="obj_geoloc_<?php echo $tpl->getImage('id'); ?>" class="js" href="javascript:;"><?php echo __('géolocalisation'); ?></a></span>
						</p>
					</div>
				</div>
<?php if ($tpl->disImage('stats')) : ?>
				<div style="display:none" class="obj_infos obj_fold" id="obj_infos_<?php echo $tpl->getImage('id'); ?>">
					<div class="obj_fold_inner">
						<table class="light">
							<tr><td><?php echo __('Poids'); ?></td><td class="number"><?php echo $tpl->getImage('filesize'); ?></td></tr>
							<tr><td><?php echo __('Dimensions'); ?></td><td class="number"><?php echo $tpl->getImage('size'); ?></td></tr>
							<tr><td><?php echo __('Nombre de visites'); ?></td><td class="number"><?php echo $tpl->getImage('hits'); ?></td></tr>
							<tr><td><?php echo __('Nombre de commentaires'); ?></td><td class="number"><?php echo $tpl->getImage('comments'); ?></td></tr>
							<tr><td><?php echo __('Nombre de votes'); ?></td><td class="number"><?php echo $tpl->getImage('votes'); ?></td></tr>
							<tr><td><?php echo __('Note moyenne'); ?></td><td class="number"><?php echo $tpl->getImage('rate'); ?></td></tr>
						</table>
						<p><?php printf(__('Ajoutée le %s par %s'), $tpl->getImage('adddt'), $tpl->getImage('owner_link')); ?></p>
					</div>
				</div>
<?php endif; ?>
				<div style="display:none" class="obj_edition obj_fold" id="obj_edition_<?php echo $tpl->getImage('id'); ?>">
					<div class="obj_fold_inner">
<?php while ($tpl->nextLang()) : ?>
						<p<?php if (!$tpl->disLang('selected')) : ?> style="display:none"<?php endif; ?> class="field_ftw">
							<label class="icon_lang icon_<?php echo $tpl->getLang('code'); ?>" for="title_<?php echo $tpl->getImage('id'); ?>_<?php echo $tpl->getLang('code'); ?>"><?php echo __('Titre :'); ?></label>
							<input name="<?php echo $tpl->getImage('id'); ?>[title][<?php echo $tpl->getLang('code'); ?>]" size="40" maxlength="255" id="title_<?php echo $tpl->getImage('id'); ?>_<?php echo $tpl->getLang('code'); ?>" type="text" class="text" value="<?php echo $tpl->getImage('title_lang'); ?>" />
						</p>
<?php endwhile; ?>
						<p class="field_ftw">
							<label for="urlname_<?php echo $tpl->getImage('id'); ?>"><?php echo __('Nom d\'URL :'); ?></label>
							<input name="<?php echo $tpl->getImage('id'); ?>[urlname]" size="40" maxlength="255" id="urlname_<?php echo $tpl->getImage('id'); ?>" type="text" class="text" value="<?php echo $tpl->getImage('urlname'); ?>" />
						</p>
						<p class="field_ftw">
							<label for="filename_<?php echo $tpl->getImage('id'); ?>"><?php echo __('Nom de fichier :'); ?></label>
							<input name="<?php echo $tpl->getImage('id'); ?>[filename]" size="40" maxlength="255" id="filename_<?php echo $tpl->getImage('id'); ?>" type="text" class="text" value="<?php echo $tpl->getImage('filename'); ?>" />
						</p>
						<p class="field_ftw">
							<label><?php echo __('Date de création :'); ?></label>
							<br />
							<?php echo $tpl->getImage('crtdt'); ?>

							<a class="js date_reset" href="javascript:;"><?php echo __('effacer'); ?></a>
						</p>
						<p class="field_ftw tags_suggest">
							<label for="tags_<?php echo $tpl->getImage('id'); ?>"><?php echo __('Tags (séparés par une virgule) :'); ?></label>
							<textarea class="textarea_tags" id="tags_<?php echo $tpl->getImage('id'); ?>" name="<?php echo $tpl->getImage('id'); ?>[tags]" rows="2" cols="50"><?php echo $tpl->getImage('tags'); ?></textarea>
						</p>
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
				<div style="display:none" class="obj_geoloc obj_fold" id="obj_geoloc_<?php echo $tpl->getImage('id'); ?>">
					<div class="obj_fold_inner">
						<p class="field_ftw">
							<label for="latitude_<?php echo $tpl->getImage('id'); ?>"><?php echo __('Latitude :'); ?></label>
							<input name="<?php echo $tpl->getImage('id'); ?>[latitude]" size="40" maxlength="20" id="latitude_<?php echo $tpl->getImage('id'); ?>" type="text" class="text" value="<?php echo $tpl->getImage('latitude'); ?>" />
						</p>
						<p class="field_ftw">
							<label for="longitude_<?php echo $tpl->getImage('id'); ?>"><?php echo __('Longitude :'); ?></label>
							<input name="<?php echo $tpl->getImage('id'); ?>[longitude]" size="40" maxlength="20" id="longitude_<?php echo $tpl->getImage('id'); ?>" type="text" class="text" value="<?php echo $tpl->getImage('longitude'); ?>" />
						</p>
						<p class="field_ftw">
							<label for="place_<?php echo $tpl->getImage('id'); ?>"><?php echo __('Lieu :'); ?></label>
							<input name="<?php echo $tpl->getImage('id'); ?>[place]" size="40" maxlength="100" id="place_<?php echo $tpl->getImage('id'); ?>" type="text" class="text" value="<?php echo $tpl->getImage('place'); ?>" />
						</p>
						<p class="field_ftw">
							<a href="?q=geoloc-image/<?php echo $tpl->getImage('id'); ?>"><?php echo __('carte'); ?></a>
						</p>
					</div>
				</div>
			</div>
<?php endwhile; ?>
<?php if ($tpl->disTagsList()) : ?>
			<script type="text/javascript">
			//<![CDATA[
			var tags_suggest = [<?php echo $tpl->getTagsList(); ?>];
			//]]>
			</script>
<?php endif; ?>

			<div id="actions">
				<p>
					<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
					<input class="submit" name="save" type="submit" value="<?php echo __('Enregistrer les modifications'); ?>" />
				</p>
<?php if ($tpl->disPerm('albums_modif')) : ?>
				<script type="text/javascript">
				//<![CDATA[
				var msg_destination_cat = "<?php echo $tpl->getL10nJS(__('La destination doit être un album.')); ?>";
				var confirm_delete = "<?php echo $tpl->getL10nJS(__('Voulez-vous vraiment supprimer les images sélectionnées, ainsi que tous les commentaires, votes et tags associés ?')); ?>";
				//]]>
				</script>
				<p>
					<label for="selection_action"><?php echo __('Pour la sélection :'); ?></label>
					<select id="selection_action" name="action">
						<option value="publish"><?php echo __('publier'); ?></option>
						<option value="unpublish"><?php echo __('hors ligne'); ?></option>
						<option value="delete"><?php echo __('supprimer'); ?></option>
						<option value="update"><?php echo __('mettre à jour'); ?></option>
						<option value="move"><?php echo __('déplacer vers'); ?></option>
						<option value="hits"><?php echo __('nombre de visites à'); ?></option>
					</select>
					<select class="list_action" id="categories" name="destination_cat">
						<?php echo $tpl->getCategoriesList(); ?>

					</select>
					<input type="text" class="list_action text" name="add_tags" id="add_tags" maxlength="1024" size="60" />
					<input type="text" class="list_action text" name="remove_tags" id="remove_tags" maxlength="1024" size="60" />
					<input type="text" class="list_action text" name="hits" id="hits" maxlength="11" size="12" value="0" />
					<input disabled="disabled" class="submit js_required" id="action_submit" name="selection" type="submit" value="<?php echo __('Valider'); ?>" />
				</p>
<?php endif; ?>
			</div>
		</form>

<?php else : ?>
		<p class="report_zero_item report_msg report_info"><?php echo ($tpl->disPosition('normal')) ? __('L\'album ne contient aucune image.') : __('Aucune image.'); ?></p>
<?php endif; ?>

<?php if ($tpl->disNavigation()) : ?>
		<div class="nav" id="nav_bottom">
			<div class="nav_left"></div>
			<div class="nav_right"><?php printf(__('page %s|%s'), $_GET['page'], $tpl->getInfo('nbPages')); ?></div>

<?php include(dirname(__FILE__) . '/nav.tpl.php'); ?>

		</div>
<?php endif; ?>

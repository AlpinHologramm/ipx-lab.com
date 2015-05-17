
<?php include_once(dirname(__FILE__) . '/albums_submenu.tpl.php'); ?>

<?php include_once(dirname(__FILE__) . '/' . ($tpl->disCategoryInfo('type_album') ? 'album' : 'category') . '_related.tpl.php'); ?>

		<h3 class="h3_help_link">
			<span><?php echo __('Édition en masse'); ?></span>
<?php if ($tpl->disHelp()) : ?>
			<a rel="h_mass_edit" title="<?php echo __('Obtenir de l\'aide sur cette fonction'); ?>" class="help_link help_link_context" href="javascript:;"><img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/help-link.png" alt="<?php echo __('Aide'); ?>" /></a>
<?php endif; ?>
		</h3>

		<div class="browse browse_wlimit">
			<label><?php echo __('Parcourir :'); ?></label>
			<select name="browse" onchange="window.location.href='<?php echo $tpl->getAdmin('admin_base_url'); ?>'+this.options[this.selectedIndex].value">
				<?php echo $tpl->getMap(); ?>

			</select>
		</div>

		<p id="position"><?php echo $tpl->getPosition(); ?><?php if ($tpl->disPosition('normal')) : ?> [<?php echo $tpl->getCategoryInfo('images'); ?>]<?php endif; ?></p>

<?php if ($tpl->disPosition('search')) : ?>
		<p id="position_special_exit"><a href="<?php echo $tpl->getSearch('section_link'); ?>"><?php echo __('sortir de la recherche'); ?></a></p>
		<p id="position_special"><?php printf(__('Résultat de votre recherche %s'), '<span id="position_query">' . $tpl->getSearch('query') . '</span>'); ?> <span id="position_nb_images"></span></p>
<?php endif; ?>
<?php if ($tpl->disPosition('filter')) : ?>
		<p id="position_special_exit"><a href="<?php echo $tpl->getFilter('section_link'); ?>"><?php echo __('afficher toutes les images'); ?></a></p>
		<p id="position_special"><?php printf($tpl->getFilter('text'), '<span id="position_filter">' . $tpl->getFilter('value') . '</span>'); ?> <span id="position_nb_images"></span></p>
<?php endif; ?>

<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

		<div id="mass_edit">
			<div id="mass_edit_thumbs" class="thumbs">
				<div id="mass_edit_thumbs_top">
					<span class="icon icon_expand" id="mass_edit_thumbs_expand"><a class="js" href="javascript:;"><?php echo __('élargir'); ?></a></span>
					<span class="icon icon_options show_tool"><a href="javascript:;" class="js" rel="options"><?php echo __('options d\'affichage'); ?></a></span>
					-
					<span class="icon icon_select show_tool"><a href="javascript:;" class="js" rel="select"><?php echo __('sélection'); ?></a></span>
					<form action="" method="post" style="display:none" class="tool" id="options">
						<fieldset>
							<legend><?php echo __('Options d\'affichage'); ?></legend>
							<p class="field">
								<label for="nb_per_page"><?php echo __('Nombre d\'images par page :'); ?></label>
								<select name="nb_per_page" id="nb_per_page">
<?php for ($n = 1; $n <= 20; $n++) : ?>
									<option value="<?php echo $n; ?>"><?php echo $n; ?></option>
<?php endfor; ?>
								</select>
							</p>
							<p class="field">
								<label for="sortby"><?php echo __('Trier par :'); ?></label>
								<select id="sortby">
									<?php echo $tpl->getOptions('sortby'); ?>

								</select>
								<select id="orderby">
									<?php echo $tpl->getOptions('orderby'); ?>

								</select>
							</p>
						</fieldset>
					</form>
					<form action="" method="post" style="display:none" class="tool" id="select">
						<fieldset>
							<legend><?php echo __('Sélection'); ?></legend>
							<p class="field">
								<?php echo $tpl->disCategoryInfo('type_album') ? __('Pour l\'album :') : __('Pour la catégorie :'); ?>
								<a id="cat_select_all" href="javascript:;" class="js"><?php echo __('tout sélectionner'); ?></a>
								-
								<a id="cat_unselect_all" href="javascript:;" class="js"><?php echo __('tout désélectionner'); ?></a>
							</p>
							<p class="field">
								<?php echo __('Sur cette page :'); ?>
								<a id="page_select_all" href="javascript:;" class="js"><?php echo __('tout sélectionner'); ?></a>
								-
								<a id="page_unselect_all" href="javascript:;" class="js"><?php echo __('tout désélectionner'); ?></a>
							</p>
						</fieldset>
					</form>

					<div id="nav_top" class="nav">
						<div class="nav_left"></div>
						<div class="nav_right"></div>
						<span class="page first inactive">&lt;&lt;</span>
						<span class="page prev inactive">&lt;</span>
						<form method="get" action="">
							<div>
								<select>
									<option value="1">1</option>
								</select>
							</div> 
						</form>
						<span class="page next inactive">&gt;</span>
						<span class="page last inactive">&gt;&gt;</span>
					</div>
				</div>

				<div id="mass_edit_thumbs_inner" class="loading">
					<div class="clear"></div>
				</div>
			</div>

			<div id="mass_edit_actions">
				<form action="" method="post">
					<div>
						<p class="field report_msg report_info"><?php printf(__('%s image sélectionnée'), 0); ?></p>
						<br />
						<div class="fieldset">
							<span class="legend"><span><?php echo __('Informations'); ?></span></span>
<?php while ($tpl->nextLang()) : ?>
							<p<?php if (!$tpl->disLang('selected')) : ?> style="display:none"<?php endif; ?> class="field_ftw">
								<label class="icon_lang icon_<?php echo $tpl->getLang('code'); ?>" for="title_<?php echo $tpl->getLang('code'); ?>"><?php echo __('Titre :'); ?></label>
								<input name="title[<?php echo $tpl->getLang('code'); ?>]" size="40" maxlength="255" id="title_<?php echo $tpl->getLang('code'); ?>" type="text" class="text" value="{IMAGE_TITLE}" />
							</p>
<?php endwhile; ?>
							<p class="field_ftw">
								<label for="urlname"><?php echo __('Nom d\'URL :'); ?></label>
								<input name="urlname" size="40" maxlength="255" id="urlname" type="text" class="text" value="{IMAGE_URLNAME}" />
							</p>
							<p class="field_ftw">
								<label><?php echo __('Date de création :'); ?></label>
								<br />
								<?php echo $tpl->getMassEdit('crtdt'); ?>

								<a class="js date_reset" href="javascript:;"><?php echo __('effacer'); ?></a>
							</p>
<?php while ($tpl->nextLang()) : ?>
							<p<?php if (!$tpl->disLang('selected')) : ?> style="display:none"<?php endif; ?> class="field_ftw field_html">
								<label class="icon_lang icon_<?php echo $tpl->getLang('code'); ?>" for="description_<?php echo $tpl->getLang('code'); ?>"><?php echo __('Description :'); ?></label>
								<span class="field_html_tag">
									<a title="<?php echo __('Cliquez pour obtenir la liste des balises autorisées'); ?>" href="javascript:;">HTML</a>
								</span>
								<span class="field_html_textarea">
									<textarea class="resizable" name="description[<?php echo $tpl->getLang('code'); ?>]" rows="8" cols="50" id="description_<?php echo $tpl->getLang('code'); ?>">{IMAGE_DESCRIPTION}</textarea>
								</span>
							</p>
<?php endwhile; ?>
						</div>
						<br />
						<div class="fieldset">
							<span class="legend"><span><?php echo __('Tags'); ?></span></span>
							<p class="field">
								<input type="checkbox" id="tags_delete_all" name="tags_delete_all" />
								<label for="tags_delete_all"><?php echo __('Supprimer tous les tags'); ?></label>
							</p>
							<p class="field_ftw tags_suggest">
								<label for="tags_remove"><?php echo __('Supprimer des tags (séparés par une virgule) :'); ?></label>
								<textarea class="textarea_tags" id="tags_remove" name="tags_remove" rows="2" cols="50"></textarea>
							</p>
							<p class="field_ftw tags_suggest">
								<label for="tags_add"><?php echo __('Ajouter des tags (séparés par une virgule) :'); ?></label>
								<textarea class="textarea_tags" id="tags_add" name="tags_add" rows="2" cols="50"></textarea>
							</p>
<?php if ($tpl->disTagsList()) : ?>
							<script type="text/javascript">
							//<![CDATA[
							var tags_suggest = [<?php echo $tpl->getTagsList(); ?>];
							//]]>
							</script>
<?php endif; ?>
						</div>
						<br />
						<div class="fieldset">
							<span class="legend"><span><?php echo __('Options'); ?></span></span>
							<p class="field">
								<label for="counter_start"><?php echo __('Démarrer le compteur à :'); ?></label>
								<input maxlength="16" size="6" id="counter_start" name="counter_start" class="text" type="text" value="1" />
							</p>
							<p class="field field_ftw">
								<label for="name_transform"><?php echo __('Pour le titre :'); ?></label>
								<select id="name_transform" name="name_transform">
									<option value="none"><?php echo __('ne rien faire'); ?></option>
									<option value="lowercase"><?php echo __('tout mettre en minuscule'); ?></option>
									<option value="uppercase"><?php echo __('tout mettre en majuscule'); ?></option>
									<option value="lowercase_ucfirst"><?php echo __('tout mettre en minuscule, première lettre en majuscule'); ?></option>
									<option value="lowercase_ucwords"><?php echo __('tout mettre en minuscule, première lettre de chaque mot en majuscule'); ?></option>
								</select>
							</p>
							<p class="field field_ftw">
								<label for="url_transform"><?php echo __('Pour le nom d\'URL :'); ?></label>
								<select id="url_transform" name="url_transform">
									<option value="none"><?php echo __('ne rien faire'); ?></option>
									<option value="lowercase"><?php echo __('tout mettre en minuscule'); ?></option>
									<option value="uppercase"><?php echo __('tout mettre en majuscule'); ?></option>
									<option value="lowercase_ucfirst"><?php echo __('tout mettre en minuscule, première lettre en majuscule'); ?></option>
									<option value="lowercase_ucwords"><?php echo __('tout mettre en minuscule, première lettre de chaque mot en majuscule'); ?></option>
								</select>
							</p>
							<p class="field field_ftw">
								<label for="desc_transform"><?php echo __('Pour la description :'); ?></label>
								<select id="desc_transform" name="desc_transform">
									<option value="none"><?php echo __('ne rien faire'); ?></option>
									<option value="lowercase"><?php echo __('tout mettre en minuscule'); ?></option>
									<option value="uppercase"><?php echo __('tout mettre en majuscule'); ?></option>
									<option value="lowercase_ucfirst"><?php echo __('tout mettre en minuscule, première lettre en majuscule'); ?></option>
									<option value="lowercase_ucwords"><?php echo __('tout mettre en minuscule, première lettre de chaque mot en majuscule'); ?></option>
								</select>
							</p>
						</div>
						<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
						<input name="orderby" type="hidden" />
						<input name="sortby" type="hidden" />
						<input name="selected_ids" type="hidden" />
						<input class="submit" name="save" type="submit" value="<?php echo __('Enregistrer'); ?>" />
					</div>
				</form>
			</div>
		</div>

		<script type="text/javascript">
		//<![CDATA[
		var locale_collapse = "<?php echo $tpl->getL10nJS(__('réduire')); ?>";
		var locale_expand = "<?php echo $tpl->getL10nJS(__('élargir')); ?>";
		var locale_page_first = "<?php echo $tpl->getL10nJS(__('Première page')); ?>";
		var locale_page_prev = "<?php echo $tpl->getL10nJS(__('Page précédente')); ?>";
		var locale_page_next = "<?php echo $tpl->getL10nJS(__('Page suivante')); ?>";
		var locale_page_last = "<?php echo $tpl->getL10nJS(__('Dernière page')); ?>";
		var locale_select_s = "<?php echo $tpl->getL10nJS(__('%s image sélectionnée')); ?>";
		var locale_select_p = "<?php echo $tpl->getL10nJS(__('%s images sélectionnées')); ?>";
		var q = "<?php echo $tpl->getAdmin('q'); ?>";
		//]]>
		</script>
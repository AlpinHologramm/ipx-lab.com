
		<form action="<?php echo $tpl->getSearch('section_link'); ?>" method="post" class="tool" id="search" style="display:none">
			<fieldset>
				<legend><?php echo __('Moteur de recherche'); ?></legend>
				<p class="field">
					<?php echo __('Rechercher :'); ?>
					<input<?php echo $tpl->getSearch('category'); ?> id="search_categories" type="radio" name="search_options[type]" value="category" />
					<label for="search_categories"><?php echo __('catégories'); ?></label>
					&nbsp;
					<input<?php echo $tpl->getSearch('album'); ?> id="search_images" type="radio" name="search_options[type]" value="album" />
					<label for="search_images"><?php echo __('images'); ?></label>
				</p>
				<p class="field">
					<input value="<?php echo $tpl->getSearch('query'); ?>" class="focus text" type="text" name="search_query" id="search_query" maxlength="255" size="50" />
<?php if ($tpl->disHelp()) : ?>
					<a rel="h_search" title="<?php echo __('Obtenir de l\'aide sur cette fonction'); ?>" class="help_link help_link_context" href="javascript:;"><img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/help-link.png" alt="<?php echo __('Aide'); ?>" /></a>
<?php endif; ?>
				</p>
				<p class="field">
					<span class="icon icon_search_adv" onclick="javascript:showhide('#adv_search');"><a href="javascript:;" class="js"><?php echo __('options de recherche'); ?></a></span>
				</p>
				<div id="adv_search" style="display:none">
					<p class="field checkbox">
						<input<?php echo $tpl->getSearch('all_words'); ?> type="checkbox" name="search_options[all_words]" id="search_all_words" />
						<span><label for="search_all_words"><?php echo __('Rechercher tous les mots'); ?></label></span>
					</p>
					<p class="field checkbox search_images_fields">
						<input<?php echo $tpl->getSearch('exclude'); ?> type="checkbox" name="search_options[exclude]" id="search_images_exclude" />
						<label for="search_images_exclude"><?php echo __('Rechercher uniquement les images qui ne possèdent pas de'); ?></label>
						<select name="search_options[exclude_filter]">
							<?php echo $tpl->getSearch('exclude_filters'); ?>

						</select>
					</p>
					<p class="field">
						<?php echo __('Rechercher dans :'); ?>
					</p>
					<div class="field_second search_categories_fields">
						<p class="field">
							<input<?php echo $tpl->getSearch('cat_name'); ?> type="checkbox" name="search_options[cat_name]" id="search_cat_name" />
							<label for="search_cat_name"><?php echo __('Titre'); ?></label>
							&nbsp;
							<input<?php echo $tpl->getSearch('cat_url'); ?> type="checkbox" name="search_options[cat_url]" id="search_cat_url" />
							<label for="search_cat_url"><?php echo __('Nom d\'URL'); ?></label>
							&nbsp;
							<input<?php echo $tpl->getSearch('cat_path'); ?> type="checkbox" name="search_options[cat_path]" id="search_cat_path" />
							<label for="search_cat_path"><?php echo __('Nom de répertoire'); ?></label>
							&nbsp;
							<input<?php echo $tpl->getSearch('cat_desc'); ?> type="checkbox" name="search_options[cat_desc]" id="search_cat_desc" />
							<label for="search_cat_desc"><?php echo __('Description'); ?></label>
						</p>
					</div>
					<div class="field_second search_images_fields">
						<p class="field">
							<input<?php echo $tpl->getSearch('image_name'); ?> type="checkbox" name="search_options[image_name]" id="search_image_name" />
							<label for="search_image_name"><?php echo __('Titre'); ?></label>
							&nbsp;
							<input<?php echo $tpl->getSearch('image_url'); ?> type="checkbox" name="search_options[image_url]" id="search_image_url" />
							<label for="search_image_url"><?php echo __('Nom d\'URL'); ?></label>
							&nbsp;
							<input<?php echo $tpl->getSearch('image_path'); ?> type="checkbox" name="search_options[image_path]" id="search_image_path" />
							<label for="search_image_path"><?php echo __('Nom de fichier'); ?></label>
							&nbsp;
							<input<?php echo $tpl->getSearch('image_desc'); ?> type="checkbox" name="search_options[image_desc]" id="search_image_desc" />
							<label for="search_image_desc"><?php echo __('Description'); ?></label>
							&nbsp;
							<input<?php echo $tpl->getSearch('image_tags'); ?> type="checkbox" name="search_options[image_tags]" id="search_image_tags" />
							<label for="search_image_tags"><?php echo __('Tags'); ?></label>
						</p>
					</div>
					<p class="field checkbox">
						<span><label for="search_status"><?php echo __('Rechercher par statut :'); ?></label></span>
						<select name="search_options[status]" id="search_status">
							<?php echo $tpl->getSearch('status'); ?>

						</select>
					</p>
					<p class="field checkbox">
						<span><label for="search_user"><?php echo __('Rechercher par propriétaire :'); ?></label></span>
						<select name="search_options[user]" id="search_user">
							<?php echo $tpl->getSearch('users'); ?>

						</select>
					</p>
					<p class="field checkbox">
						<input<?php echo $tpl->getSearch('date'); ?> id="search_date" type="checkbox" name="search_options[date]" />
						<span><label for="search_date"><?php echo __('Rechercher par date :'); ?></label></span>
					</p>
					<div class="field_second">
						<p class="field search_categories_fields">
							<input<?php echo $tpl->getSearch('date_field_cat_lastadddt'); ?> id="search_date_field_cat_lastadddt" type="radio" name="search_options[cat_date_field]" value="cat_lastadddt" />
							<label for="search_date_field_cat_lastadddt"><?php echo __('Date de dernier ajout'); ?></label>
							&nbsp;
							<input<?php echo $tpl->getSearch('date_field_cat_crtdt'); ?> id="search_date_field_cat_crtdt" type="radio" name="search_options[cat_date_field]" value="cat_crtdt" />
							<label for="search_date_field_cat_crtdt"><?php echo __('Date de création'); ?></label>
						</p>
						<p class="field search_images_fields">
							<input<?php echo $tpl->getSearch('date_field_image_adddt'); ?> id="search_date_field_image_adddt" type="radio" name="search_options[image_date_field]" value="image_adddt" />
							<label for="search_date_field_image_adddt"><?php echo __('Date d\'ajout'); ?></label>
							&nbsp;
							<input<?php echo $tpl->getSearch('date_field_image_crtdt'); ?> id="search_date_field_image_crtdt" type="radio" name="search_options[image_date_field]" value="image_crtdt" />
							<label for="search_date_field_image_crtdt"><?php echo __('Date de création'); ?></label>
						</p>
						<p class="field">
							<?php echo __('du'); ?>
							&nbsp;
							<select name="search_options[date_start_day]">
								<?php echo $tpl->getSearch('date_start_day'); ?>

							</select>
							<select name="search_options[date_start_month]">
								<?php echo $tpl->getSearch('date_start_month'); ?>

							</select>
							<select name="search_options[date_start_year]">
								<?php echo $tpl->getSearch('date_start_year'); ?>

							</select>
							&nbsp;
							<?php echo __('au'); ?>
							&nbsp;
							<select name="search_options[date_end_day]">
								<?php echo $tpl->getSearch('date_end_day'); ?>

							</select>
							<select name="search_options[date_end_month]">
								<?php echo $tpl->getSearch('date_end_month'); ?>

							</select>
							<select name="search_options[date_end_year]">
								<?php echo $tpl->getSearch('date_end_year'); ?>

							</select>
						</p>
					</div>
					<p class="field checkbox search_images_fields">
						<input<?php echo $tpl->getSearch('size'); ?> id="search_size" type="checkbox" name="search_options[size]" />
						<span><label for="search_size"><?php echo __('Rechercher par dimensions :'); ?></label></span>
					</p>
					<div class="field_second search_images_fields">
						<p class="field">
							<?php echo __('Largeur :'); ?>
							&nbsp;
							<label for="search_options_width_start"><?php echo __('entre'); ?></label>
							&nbsp;
							<input id="search_options_width_start" name="search_options[size_width_start]" class="text" type="text" size="6" maxlength="5" value="<?php echo $tpl->getSearch('size_width_start'); ?>" />
							&nbsp;
							<label for="search_options_width_end"><?php echo __('et'); ?></label>
							&nbsp;
							<input id="search_options_width_end" name="search_options[size_width_end]" class="text" type="text" size="6" maxlength="5" value="<?php echo $tpl->getSearch('size_width_end'); ?>" />
							&nbsp;
							<?php echo __('pixels'); ?>
						</p>
						<p class="field">
							<?php echo __('Hauteur :'); ?>
							&nbsp;
							<label for="search_options_height_start"><?php echo __('entre'); ?></label>
							&nbsp;
							<input id="search_options_height_start" name="search_options[size_height_start]" class="text" type="text" size="6" maxlength="5" value="<?php echo $tpl->getSearch('size_height_start'); ?>" />
							&nbsp;
							<label for="search_options_height_end"><?php echo __('et'); ?></label>
							&nbsp;
							<input id="search_options_height_end" name="search_options[size_height_end]" class="text" type="text" size="6" maxlength="5" value="<?php echo $tpl->getSearch('size_height_end'); ?>" />
							&nbsp;
							<?php echo __('pixels'); ?>
						</p>
					</div>
					<p class="field checkbox search_images_fields">
						<input<?php echo $tpl->getSearch('filesize'); ?> id="search_filesize" type="checkbox" name="search_options[filesize]" />
						<span><label for="search_filesize"><?php echo __('Rechercher par poids :'); ?></label></span>
					</p>
					<div class="field_second search_images_fields">
						<p class="field">
							<label for="search_options_filesize_start"><?php echo __('entre'); ?></label>
							&nbsp;
							<input id="search_options_filesize_start" name="search_options[filesize_start]" class="text" type="text" size="6" maxlength="5" value="<?php echo $tpl->getSearch('filesize_start'); ?>" />
							&nbsp;
							<label for="search_options_filesize_end"><?php echo __('et'); ?></label>
							&nbsp;
							<input id="search_options_filesize_end" name="search_options[filesize_end]" class="text" type="text" size="6" maxlength="5" value="<?php echo $tpl->getSearch('filesize_end'); ?>" />
							&nbsp;
							<?php echo __('Ko'); ?>

						</p>
					</div>
				</div>
				<p class="field">
					<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
					<input name="search" class="submit" type="submit" value="<?php echo __('Chercher'); ?>" />
				</p>
			</fieldset>
		</form>

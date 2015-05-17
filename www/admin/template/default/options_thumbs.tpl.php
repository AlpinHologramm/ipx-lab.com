
<?php include_once(dirname(__FILE__) . '/options_submenu.tpl.php'); ?>

		<div id="tools_browse">
			<div class="browse">
				<select id="functions_list" onchange="window.location.href='#'+this.options[this.selectedIndex].value">
					<option value=""><?php echo __('Parcourir :'); ?></option>
					<option value="general"><?php echo __('Général'); ?></option>
					<option value="categories"><?php echo __('Catégories'); ?></option>
					<option value="images"><?php echo __('Images'); ?></option>
					<option value="thumbs-infos"><?php echo __('Informations sous les vignettes'); ?></option>
				</select>
			</div>
		</div>

		<br />

<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

		<form action="#top" method="post">
			<div>
				<div class="browse_anchor" id="categories"></div>
				<fieldset>
					<legend><?php echo __('Général'); ?></legend>
					<div class="fielditems">
						<p class="field checkbox">
							<input<?php if ($tpl->disOption('thumbs_protect')) : ?> checked="checked"<?php endif; ?> id="thumbs_protect" name="thumbs_protect" type="checkbox" />
							<span><label for="thumbs_protect"><?php echo __('Interdire l\'accès direct aux vignettes'); ?></label></span>
<?php if ($tpl->disHelp()) : ?>
							<a rel="h_thumbs_cat_protect" title="<?php echo __('Obtenir de l\'aide sur cette fonction'); ?>" class="help_link help_link_context" href="javascript:;"><img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/help-link.png" alt="<?php echo __('Aide'); ?>" /></a>
<?php endif; ?>
						</p>
					</div>
				</fieldset>
				<br />
				<fieldset>
					<legend><?php echo __('Catégories'); ?></legend>
					<div class="fielditems">
						<p class="field">
							<label for="thumbs_cat_nb"><?php echo __('Nombre de vignettes par page :'); ?></label>
							<input maxlength="4" size="4" id="thumbs_cat_nb" name="thumbs_cat_nb" type="text" class="text" value="<?php echo $tpl->getOption('thumbs_cat_nb'); ?>" />
						</p>
						<p class="field">
							<label for="thumbs_cat_extended"><?php echo __('Présentation des vignettes :'); ?></label>
							<select id="thumbs_cat_extended" name="thumbs_cat_extended">
								<?php echo $tpl->getOption('thumbs_cat_extended'); ?>

							</select>
<?php if ($tpl->disHelp()) : ?>
							<a rel="h_thumbs_cat_extended" title="<?php echo __('Obtenir de l\'aide sur cette fonction'); ?>" class="help_link help_link_context" href="javascript:;"><img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/help-link.png" alt="<?php echo __('Aide'); ?>" /></a>
<?php endif; ?>
						</p>
						<p class="field"><?php echo __('Trier par :'); ?></p>
						<div class="field_second">
							<p class="field">
								<?php printf(__('Critère n°%s :'), 1); ?>
								<select name="thumbs_cat_order_by_1">
									<?php echo $tpl->getOption('thumbs_cat_order_by_1'); ?>

								</select>
								<select name="thumbs_cat_ascdesc_1">
									<?php echo $tpl->getOption('thumbs_cat_ascdesc_1'); ?>

								</select>
							</p>
							<p class="field">
								<?php printf(__('Critère n°%s :'), 2); ?>
								<select name="thumbs_cat_order_by_2">
									<?php echo $tpl->getOption('thumbs_cat_order_by_2'); ?>

								</select>
								<select name="thumbs_cat_ascdesc_2">
									<?php echo $tpl->getOption('thumbs_cat_ascdesc_2'); ?>

								</select>
							</p>
							<p class="field">
								<?php printf(__('Critère n°%s :'), 3); ?>
								<select name="thumbs_cat_order_by_3">
									<?php echo $tpl->getOption('thumbs_cat_order_by_3'); ?>

								</select>
								<select name="thumbs_cat_ascdesc_3">
									<?php echo $tpl->getOption('thumbs_cat_ascdesc_3'); ?>

								</select>
							</p>
						</div>
						<p class="field"><?php echo __('Distinction catégories/albums :'); ?></p>
						<div class="field_second">
							<p class="field">
								<input<?php if ($tpl->disOption('thumbs_cat_type_categories')) : ?> checked="checked"<?php endif; ?> id="thumbs_cat_type_categories" type="radio" name="thumbs_cat_type" value="categories" />
								<label for="thumbs_cat_type_categories"><?php echo __('Afficher les catégories avant les albums'); ?></label>
							</p>
							<p class="field">
								<input<?php if ($tpl->disOption('thumbs_cat_type_albums')) : ?> checked="checked"<?php endif; ?> id="thumbs_cat_type_albums" type="radio" name="thumbs_cat_type" value="albums" />
								<label for="thumbs_cat_type_albums"><?php echo __('Afficher les albums avant les catégories'); ?></label>
							</p>
							<p class="field">
								<input<?php if ($tpl->disOption('thumbs_cat_type_none')) : ?> checked="checked"<?php endif; ?> id="thumbs_cat_type_none" type="radio" name="thumbs_cat_type" value="none" />
								<label for="thumbs_cat_type_none"><?php echo __('Sans distinction entre albums et catégories'); ?></label>
							</p>
						</div>
						<p class="field"><?php echo __('Dimensions des vignettes (entre 50 et 300 pixels) :'); ?></p>
						<div class="field_second">
							<p class="field">
								<input<?php echo $tpl->getOption('thumbs_cat_method_prop'); ?> id="thumbs_cat_prop" type="radio" name="thumbs_cat_method" value="prop" />
								<label for="thumbs_cat_prop"><?php echo __('Taille maximale :'); ?></label>
								<input value="<?php echo $tpl->getOption('thumbs_cat_size'); ?>" type="text" class="text" maxlength="3" size="3" name="thumbs_cat_size" />
								<?php echo __('pixels'); ?>
							</p>
							<p class="field">
								<input<?php echo $tpl->getOption('thumbs_cat_method_crop'); ?> id="thumbs_cat" type="radio" name="thumbs_cat_method" value="crop" />
								<label for="thumbs_cat"><?php echo __('Rogner les vignettes :'); ?></label>
								<input value="<?php echo $tpl->getOption('thumbs_cat_width'); ?>" type="text" class="text" maxlength="3" size="3" name="thumbs_cat_width" />
								X
								<input value="<?php echo $tpl->getOption('thumbs_cat_height'); ?>" type="text" class="text" maxlength="3" size="3" name="thumbs_cat_height" />
								<?php echo __('pixels'); ?>
							</p>
						</div>
						<p class="field">
							<label for="thumbs_cat_quality"><?php echo __('Qualité (entre 0 et 100) :'); ?></label>
							<input value="<?php echo $tpl->getOption('thumbs_cat_quality'); ?>" id="thumbs_cat_quality" type="text" class="text" maxlength="3" size="3" name="thumbs_cat_quality" />
						</p>
					</div>
				</fieldset>
				<br />
				<div class="browse_anchor" id="images"></div>
				<fieldset>
					<legend><?php echo __('Images'); ?></legend>
					<div class="fielditems">
						<p class="field">
							<label for="thumbs_alb_nb"><?php echo __('Nombre de vignettes par page :'); ?></label>
							<input maxlength="4" size="4" id="thumbs_alb_nb" name="thumbs_alb_nb" type="text" class="text" value="<?php echo $tpl->getOption('thumbs_alb_nb'); ?>" />
						</p>
						<p class="field"><?php echo __('Trier par :'); ?></p>
						<div class="field_second">
							<p class="field">
								<?php printf(__('Critère n°%s :'), 1); ?>

								<select name="thumbs_img_order_by_1">
									<?php echo $tpl->getOption('thumbs_img_order_by_1'); ?>

								</select>
								<select name="thumbs_img_ascdesc_1">
									<?php echo $tpl->getOption('thumbs_img_ascdesc_1'); ?>

								</select>
							</p>
							<p class="field">
								<?php printf(__('Critère n°%s :'), 2); ?>

								<select name="thumbs_img_order_by_2">
									<?php echo $tpl->getOption('thumbs_img_order_by_2'); ?>

								</select>
								<select name="thumbs_img_ascdesc_2">
									<?php echo $tpl->getOption('thumbs_img_ascdesc_2'); ?>

								</select>
							</p>
							<p class="field">
								<?php printf(__('Critère n°%s :'), 3); ?>

								<select name="thumbs_img_order_by_3">
									<?php echo $tpl->getOption('thumbs_img_order_by_3'); ?>

								</select>
								<select name="thumbs_img_ascdesc_3">
									<?php echo $tpl->getOption('thumbs_img_ascdesc_3'); ?>

								</select>
							</p>
						</div>
						<p class="field"><?php echo __('Dimensions des vignettes (entre 50 et 300 pixels) :'); ?></p>
						<div class="field_second">
							<p class="field">
								<input<?php echo $tpl->getOption('thumbs_img_method_prop'); ?> id="thumbs_img_prop" type="radio" name="thumbs_img_method" value="prop" />
								<label for="thumbs_img_prop"><?php echo __('Taille maximale :'); ?></label>
								<input value="<?php echo $tpl->getOption('thumbs_img_size'); ?>" type="text" class="text" maxlength="3" size="3" name="thumbs_img_size" />
								<?php echo __('pixels'); ?>
							</p>
							<p class="field">
								<input<?php echo $tpl->getOption('thumbs_img_method_crop'); ?> id="thumbs_img" type="radio" name="thumbs_img_method" value="crop" />
								<label for="thumbs_img"><?php echo __('Rogner les vignettes :'); ?></label>
								<input value="<?php echo $tpl->getOption('thumbs_img_width'); ?>" type="text" class="text" maxlength="3" size="3" name="thumbs_img_width" />
								X
								<input value="<?php echo $tpl->getOption('thumbs_img_height'); ?>" type="text" class="text" maxlength="3" size="3" name="thumbs_img_height" />
								<?php echo __('pixels'); ?>
							</p>
						</div>
						<p class="field">
							<label for="thumbs_img_quality"><?php echo __('Qualité (entre 0 et 100) :'); ?></label>
							<input value="<?php echo $tpl->getOption('thumbs_img_quality'); ?>" id="thumbs_img_quality" type="text" class="text" maxlength="3" size="3" name="thumbs_img_quality" />
						</p>
					</div>
				</fieldset>
				<br />
				<div class="browse_anchor" id="thumbs-infos"></div>
				<fieldset>
					<legend><?php echo __('Informations sous les vignettes'); ?></legend>
					<div class="fielditems">
						<p class="field checkbox">
							<input<?php if ($tpl->disOption('thumbs_stats_category_title')) : ?> checked="checked"<?php endif; ?> id="thumbs_category_title" name="thumbs_stats_category_title" type="checkbox" />
							<span><label for="thumbs_category_title"><?php echo __('Titre des catégories'); ?></label></span>
						</p>
						<p class="field checkbox">
							<input<?php if ($tpl->disOption('thumbs_stats_images')) : ?> checked="checked"<?php endif; ?> id="thumbs_images" name="thumbs_stats_images" type="checkbox" />
							<span><label for="thumbs_images"><?php echo __('Nombre d\'images'); ?></label></span>
						</p>
						<p class="field checkbox">
							<input<?php if ($tpl->disOption('thumbs_stats_albums')) : ?> checked="checked"<?php endif; ?> id="thumbs_albums" name="thumbs_stats_albums" type="checkbox" />
							<span><label for="thumbs_albums"><?php echo __('Nombre d\'albums'); ?></label></span>
						</p>
						<p class="field checkbox">
							<input<?php if ($tpl->disOption('thumbs_stats_image_title')) : ?> checked="checked"<?php endif; ?> id="thumbs_image_title" name="thumbs_stats_image_title" type="checkbox" />
							<span><label for="thumbs_image_title"><?php echo __('Titre des images'); ?></label></span>
						</p>
						<p class="field checkbox">
							<input<?php if ($tpl->disOption('thumbs_stats_date')) : ?> checked="checked"<?php endif; ?> id="thumbs_date" name="thumbs_stats_date" type="checkbox" />
							<span><label for="thumbs_date"><?php echo __('Date d\'ajout'); ?></label></span>
						</p>
						<p class="field checkbox">
							<input<?php if ($tpl->disOption('thumbs_stats_size')) : ?> checked="checked"<?php endif; ?> id="thumbs_size" name="thumbs_stats_size" type="checkbox" />
							<span><label for="thumbs_size"><?php echo __('Taille'); ?></label></span>
						</p>
						<p class="field checkbox">
							<input<?php if ($tpl->disOption('thumbs_stats_filesize')) : ?> checked="checked"<?php endif; ?> id="thumbs_filesize" name="thumbs_stats_filesize" type="checkbox" />
							<span><label for="thumbs_filesize"><?php echo __('Poids'); ?></label></span>
						</p>
						<p class="field checkbox">
							<input<?php if ($tpl->disOption('thumbs_stats_hits')) : ?> checked="checked"<?php endif; ?> id="thumbs_hits" name="thumbs_stats_hits" type="checkbox" />
							<span><label for="thumbs_hits"><?php echo __('Nombre de visites'); ?></label></span>
						</p>
						<p class="field checkbox">
							<input<?php if ($tpl->disOption('thumbs_stats_comments')) : ?> checked="checked"<?php endif; ?> id="thumbs_comments" name="thumbs_stats_comments" type="checkbox" />
							<span><label for="thumbs_comments"><?php echo __('Nombre de commentaires'); ?></label></span>
						</p>
						<p class="field checkbox">
							<input<?php if ($tpl->disOption('thumbs_stats_votes')) : ?> checked="checked"<?php endif; ?> id="thumbs_votes" name="thumbs_stats_votes" type="checkbox" />
							<span><label for="thumbs_votes"><?php echo __('Note moyenne'); ?></label></span>
						</p>
					</div>
				</fieldset>
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
				<input type="submit" class="submit" value="<?php echo __('Enregistrer'); ?>" />
			</div>
		</form>

<?php include_once(dirname(__FILE__) . '/help_context.tpl.php'); ?>

<?php include_once(dirname(__FILE__) . '/options_submenu.tpl.php'); ?>

		<div id="tools_browse">
			<div class="browse">
				<select id="functions_list" onchange="window.location.href='#'+this.options[this.selectedIndex].value">
					<option value=""><?php echo __('Parcourir :'); ?></option>
					<option value="general"><?php echo __('Général'); ?></option>
					<option value="resize"><?php echo __('Redimensionnement'); ?></option>
					<option value="rotation"><?php echo __('Rotation'); ?></option>
					<option value="recent"><?php echo __('Images récentes'); ?></option>
				</select>
			</div>
		</div>

		<br />

<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

		<form action="#top" method="post">
			<div>
				<div class="browse_anchor" id="general"></div>
				<fieldset>
					<legend><?php echo __('Général'); ?></legend>
					<div class="fielditems">
						<p class="field checkbox">
							<input<?php echo $tpl->getOption('images_direct_link'); ?> id="images_direct_link" name="images_direct_link" type="checkbox" />
							<span><label for="images_direct_link"><?php echo __('Ne pas afficher les images dans une page de la galerie'); ?></label></span>
<?php if ($tpl->disHelp()) : ?>
							<a rel="h_direct_link" title="<?php echo __('Obtenir de l\'aide sur cette fonction'); ?>" class="help_link help_link_context" href="javascript:;"><img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/help-link.png" alt="<?php echo __('Aide'); ?>" /></a>
<?php endif; ?>
						</p>
						<p class="field checkbox">
							<input<?php echo $tpl->getOption('images_anti_copy'); ?> id="images_anti_copy" name="images_anti_copy" type="checkbox" />
							<span><label for="images_anti_copy"><?php echo __('Empêcher la copie des images'); ?></label></span>
<?php if ($tpl->disHelp()) : ?>
							<a rel="h_anti_copy" title="<?php echo __('Obtenir de l\'aide sur cette fonction'); ?>" class="help_link help_link_context" href="javascript:;"><img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/help-link.png" alt="<?php echo __('Aide'); ?>" /></a>
<?php endif; ?>
						</p>
					</div>
				</fieldset>
				<br />
				<div class="browse_anchor" id="resize"></div>
				<fieldset>
					<legend><?php echo __('Redimensionnement'); ?></legend>
					<div class="fielditems">
						<p class="field checkbox">
							<input<?php echo $tpl->getOption('images_resize'); ?> id="images_resize" name="images_resize" type="checkbox" />
							<span><label for="images_resize"><?php echo __('Redimensionner les images'); ?></label></span>
						</p>
						<div class="field_second">
							<p class="field checkbox">
								<input<?php echo $tpl->getOption('images_resize_html'); ?> id="images_resize_html" name="images_resize_method" type="radio" value="1" />
								<span><label for="images_resize_html"><?php printf(__('avec %s :'), 'HTML'); ?></label></span>
								<input value="<?php echo $tpl->getOption('images_resize_html_width'); ?>" name="images_resize_html_width" class="text" maxlength="4" type="text" size="4" />
								X
								<input value="<?php echo $tpl->getOption('images_resize_html_height'); ?>" name="images_resize_html_height" class="text" maxlength="4" type="text" size="4" />
								<?php echo __('pixels'); ?>
							</p>
							<p class="field checkbox">
								<input<?php echo $tpl->getOption('images_resize_gd'); ?> id="images_resize_gd" name="images_resize_method" type="radio" value="2" />
								<span><label for="images_resize_gd"><?php printf(__('avec %s :'), 'GD'); ?></label></span>
								<input value="<?php echo $tpl->getOption('images_resize_gd_width'); ?>" name="images_resize_gd_width" class="text" maxlength="4" type="text" size="4" />
								X
								<input value="<?php echo $tpl->getOption('images_resize_gd_height'); ?>" name="images_resize_gd_height" class="text" maxlength="4" type="text" size="4" />
								<?php echo __('pixels'); ?>
<?php if ($tpl->disHelp()) : ?>
							<a rel="h_resize_gd" title="<?php echo __('Obtenir de l\'aide sur cette fonction'); ?>" class="help_link help_link_context" href="javascript:;"><img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/help-link.png" alt="<?php echo __('Aide'); ?>" /></a>
<?php endif; ?>
							</p>
							<div class="field_second">
								<p class="field checkbox">
									<label for="images_resize_gd_quality"><?php echo __('Qualité (entre 0 et 100) :'); ?></label>
									<input value="<?php echo $tpl->getOption('images_resize_gd_quality'); ?>" id="images_resize_gd_quality" name="images_resize_gd_quality" class="text" maxlength="4" type="text" size="4" />
								</p>
							</div>
						</div>
					</div>
				</fieldset>
				<br />
				<div class="browse_anchor" id="rotation"></div>
				<fieldset>
					<legend><?php echo __('Rotation'); ?></legend>
					<div class="fielditems">
						<p class="field checkbox">
							<input<?php echo $tpl->getOption('images_orientation'); ?> id="images_orientation" name="images_orientation" type="checkbox" />
							<span><label for="images_orientation"><?php echo __('Changer l\'orientation des images (si possible)'); ?></label></span>
						</p>
						<div class="field_second">
							<p class="field checkbox">
								<label for="images_orientation_quality"><?php echo __('Qualité (entre 0 et 100) :'); ?></label>
								<input value="<?php echo $tpl->getOption('images_orientation_quality'); ?>" id="images_orientation_quality" name="images_orientation_quality" class="text" maxlength="4" type="text" size="4" />
							</p>
						</div>
					</div>
				</fieldset>
				<br />
				<div class="browse_anchor" id="recent"></div>
				<fieldset>
					<legend><?php echo __('Images récentes'); ?></legend>
					<div class="fielditems">
<?php if ($tpl->disDisabledConfig('recent_images')) : ?>
						<p class="field">
							<span class="report_msg report_info"><?php echo __('Cette fonctionnalité n\'est pas disponible avec le thème actuel.'); ?></span>
						</p>
<?php endif; ?>
						<p class="field checkbox<?php if ($tpl->disDisabledConfig('recent_images')) : ?> f_disabled<?php endif; ?>">
							<input<?php if ($tpl->disDisabledConfig('recent_images')) : ?> disabled="disabled"<?php endif; ?><?php echo $tpl->getOption('recent_images'); ?> id="recent_images" name="recent_images" type="checkbox" />
							<span><label for="recent_images"><?php echo __('Mettre en évidence les images récentes'); ?></label></span>
						</p>
						<div class="field_second<?php if ($tpl->disDisabledConfig('recent_images')) : ?> f_disabled<?php endif; ?>">
							<p class="field checkbox">
								<label for="recent_images_time"><?php echo __('Durée de nouveauté :'); ?></label>
								<input<?php if ($tpl->disDisabledConfig('recent_images')) : ?> disabled="disabled"<?php endif; ?> value="<?php echo $tpl->getOption('recent_images_time'); ?>" id="recent_images_time" name="recent_images_time" class="text" maxlength="4" type="text" size="4" />
								<?php echo __('jours'); ?>

							</p>
							<p class="field checkbox">
								<input<?php if ($tpl->disDisabledConfig('recent_images')) : ?> disabled="disabled"<?php endif; ?><?php echo $tpl->getOption('recent_images_nb'); ?> id="recent_images_nb" name="recent_images_nb" type="checkbox" />
								<span><label for="recent_images_nb"><?php echo __('Afficher le nombre d\'images récentes sous les vignettes des catégories'); ?></label></span>
							</p>
						</div>
					</div>
				</fieldset>
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
				<input type="submit" class="submit" value="<?php echo __('Enregistrer'); ?>" />
			</div>
		</form>

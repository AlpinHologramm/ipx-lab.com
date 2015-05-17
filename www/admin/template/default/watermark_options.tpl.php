					<div class="browse_anchor" id="w_text"></div>
					<fieldset>
						<legend><?php echo __('Texte'); ?></legend>
						<div class="fielditems">
<?php if (!$tpl->disWatermarkOption('imagettfbbox')) : ?>
							<p class="field">
								<span class="report_msg report_exclamation"><?php printf(__('Cette fonctionnalité n\'est pas disponible car la fonction %s n\'est pas activée.'), 'imagettfbbox()'); ?></span>
							</p>
<?php endif; ?>
							<p class="field checkbox<?php if (!$tpl->disWatermarkOption('imagettfbbox')) : ?> f_disabled<?php endif; ?>">
								<input<?php if (!$tpl->disWatermarkOption('imagettfbbox')) : ?> disabled="disabled"<?php endif; ?><?php echo $tpl->getWatermarkOption('text_active'); ?> id="text_active" name="watermark_options[text_active]" type="checkbox" class="show_part" rel="wtext" />
								<span><label for="text_active"><?php echo __('Ajouter un texte'); ?></label></span>
							</p>
							<div<?php if (!$tpl->disWatermarkOption('imagettfbbox') || !$tpl->disWatermarkOption('text_active')) : ?> style="display:none"<?php endif; ?> class="field_second<?php if (!$tpl->disWatermarkOption('imagettfbbox')) : ?> f_disabled<?php endif; ?>" id="wtext">
								<p class="field field_ftw">
									<input<?php if (!$tpl->disWatermarkOption('imagettfbbox')) : ?> disabled="disabled"<?php endif; ?> value="<?php echo $tpl->getWatermarkOption('text'); ?>" maxlength="64" id="text" name="watermark_options[text]" class="text" size="50" type="text" />
								</p>
								<p class="field">
									<label for="text_color"><?php echo __('Couleur :'); ?></label>
									<input<?php if (!$tpl->disWatermarkOption('imagettfbbox')) : ?> disabled="disabled"<?php endif; ?> value="<?php echo $tpl->getWatermarkOption('text_color'); ?>" maxlength="7" id="text_color" name="watermark_options[text_color]" class="text<?php if ($tpl->disWatermarkOption('imagettfbbox')) : ?> colorpicker<?php endif; ?>" size="7" type="text" />
								</p>
								<p class="field">
									<label for="text_alpha"><?php echo __('Transparence (entre 0 et 127) :'); ?></label>
									<input<?php if (!$tpl->disWatermarkOption('imagettfbbox')) : ?> disabled="disabled"<?php endif; ?> value="<?php echo $tpl->getWatermarkOption('text_alpha'); ?>" maxlength="3" id="text_alpha" name="watermark_options[text_alpha]" class="text" size="3" type="text" />
<?php if ($tpl->disHelp()) : ?>
									<a rel="h_alpha" title="<?php echo __('Obtenir de l\'aide sur cette fonction'); ?>" class="help_link help_link_context" href="javascript:;"><img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/help-link.png" alt="<?php echo __('Aide'); ?>" /></a>
<?php endif; ?>
								</p>
								<p class="field">
									<?php echo __('Taille :'); ?>

								</p>
								<div class="field_second">
									<p class="field">
										<input<?php if (!$tpl->disWatermarkOption('imagettfbbox')) : ?> disabled="disabled"<?php endif; ?><?php echo $tpl->getWatermarkOption('text_size_type_fixed'); ?> id="text_size_type_fixed" name="watermark_options[text_size_type]" value="fixed" type="radio" />
										<label for="text_size_type_fixed"><?php echo __('Fixe :'); ?></label>
										<input<?php if (!$tpl->disWatermarkOption('imagettfbbox')) : ?> disabled="disabled"<?php endif; ?> value="<?php echo $tpl->getWatermarkOption('text_size_fixed'); ?>" maxlength="3" id="text_size_fixed" name="watermark_options[text_size_fixed]" class="text" size="3" type="text" />
										<?php echo __('points'); ?>
									</p>
									<p class="field">
										<input<?php if (!$tpl->disWatermarkOption('imagettfbbox')) : ?> disabled="disabled"<?php endif; ?><?php echo $tpl->getWatermarkOption('text_size_type_pct'); ?> id="text_size_type_pct" name="watermark_options[text_size_type]" value="pct" type="radio" />
										<label for="text_size_type_pct"><?php echo __('Proportionnelle :'); ?></label>
										<input<?php if (!$tpl->disWatermarkOption('imagettfbbox')) : ?> disabled="disabled"<?php endif; ?> value="<?php echo $tpl->getWatermarkOption('text_size_pct'); ?>" maxlength="3" id="text_size_pct" name="watermark_options[text_size_pct]" class="text" size="3" type="text" />
										%
<?php if ($tpl->disHelp()) : ?>
										<a rel="h_size_prop" title="<?php echo __('Obtenir de l\'aide sur cette fonction'); ?>" class="help_link help_link_context" href="javascript:;"><img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/help-link.png" alt="<?php echo __('Aide'); ?>" /></a>
<?php endif; ?>
									</p>
								</div>
								<p class="field">
									<label for="text_font"><?php echo __('Fonte :'); ?></label>
									<select<?php if (!$tpl->disWatermarkOption('imagettfbbox')) : ?> disabled="disabled"<?php endif; ?> id="text_font" name="watermark_options[text_font]">
										<?php echo $tpl->getWatermarkOption('text_font'); ?>

									</select>
<?php if ($tpl->disHelp()) : ?>
									<a rel="h_font" title="<?php echo __('Obtenir de l\'aide sur cette fonction'); ?>" class="help_link help_link_context" href="javascript:;"><img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/help-link.png" alt="<?php echo __('Aide'); ?>" /></a>
<?php endif; ?>
								</p>
								<p class="field">
									<?php echo __('Position :'); ?>
								</p>
								<div class="field_second">
									<p class="field">
										<select<?php if (!$tpl->disWatermarkOption('imagettfbbox')) : ?> disabled="disabled"<?php endif; ?> id="text_position" name="watermark_options[text_position]">
											<?php echo $tpl->getWatermarkOption('text_position'); ?>

										</select>
										<select<?php if (!$tpl->disWatermarkOption('imagettfbbox')) : ?> disabled="disabled"<?php endif; ?> id="text_external" name="watermark_options[text_external]">
											<?php echo $tpl->getWatermarkOption('text_external'); ?>

										</select>
									</p>
									<p class="field">
										<label for="text_x"><?php echo __('à'); ?></label>
										<input<?php if (!$tpl->disWatermarkOption('imagettfbbox')) : ?> disabled="disabled"<?php endif; ?> value="<?php echo $tpl->getWatermarkOption('text_x'); ?>" maxlength="5" id="text_x" name="watermark_options[text_x]" class="text" size="5" type="text" />
										<label for="text_x"><?php echo __('pixels du bord vertical'); ?></label>
									</p>
									<p class="field">
										<label for="text_y"><?php echo __('à'); ?></label>
										<input<?php if (!$tpl->disWatermarkOption('imagettfbbox')) : ?> disabled="disabled"<?php endif; ?> value="<?php echo $tpl->getWatermarkOption('text_y'); ?>" maxlength="5" id="text_y" name="watermark_options[text_y]" class="text" size="5" type="text" />
										<label for="text_y"><?php echo __('pixels du bord horizontal'); ?></label>
									</p>
								</div>
								<br />
								<p class="field checkbox">
									<input<?php if (!$tpl->disWatermarkOption('imagettfbbox')) : ?> disabled="disabled"<?php endif; ?><?php echo $tpl->getWatermarkOption('text_shadow_active'); ?> id="text_shadow_active" name="watermark_options[text_shadow_active]" type="checkbox" />
									<span><label for="text_shadow_active"><?php echo __('Ajouter un ombrage :'); ?></label></span>
								</p>
								<div class="field_second">
									<p class="field">
										<label for="text_shadow_color"><?php echo __('Couleur :'); ?></label>
										<input<?php if (!$tpl->disWatermarkOption('imagettfbbox')) : ?> disabled="disabled"<?php endif; ?> value="<?php echo $tpl->getWatermarkOption('text_shadow_color'); ?>" maxlength="7" id="text_shadow_color" name="watermark_options[text_shadow_color]" class="text<?php if ($tpl->disWatermarkOption('imagettfbbox')) : ?> colorpicker<?php endif; ?>" size="7" type="text" />
									</p>
									<p class="field">
										<label for="text_shadow_alpha"><?php echo __('Transparence (entre 0 et 127) :'); ?></label>
										<input<?php if (!$tpl->disWatermarkOption('imagettfbbox')) : ?> disabled="disabled"<?php endif; ?> value="<?php echo $tpl->getWatermarkOption('text_shadow_alpha'); ?>" maxlength="3" id="text_shadow_alpha" name="watermark_options[text_shadow_alpha]" class="text" size="3" type="text" />
									</p>
									<p class="field">
										<label for="text_shadow_size"><?php echo __('Épaisseur :'); ?></label>
										<input<?php if (!$tpl->disWatermarkOption('imagettfbbox')) : ?> disabled="disabled"<?php endif; ?> value="<?php echo $tpl->getWatermarkOption('text_shadow_size'); ?>" maxlength="3" id="text_shadow_size" name="watermark_options[text_shadow_size]" class="text" size="3" type="text" />
										<?php echo __('pixels'); ?>
									</p>
								</div>
								<br />
								<p class="field checkbox">
									<input<?php if (!$tpl->disWatermarkOption('imagettfbbox')) : ?> disabled="disabled"<?php endif; ?><?php echo $tpl->getWatermarkOption('background_active'); ?> id="background_active" name="watermark_options[background_active]" type="checkbox" />
									<span><label for="background_active"><?php echo __('Ajouter un fond :'); ?></label></span>
								</p>
								<div class="field_second">
									<p class="field">
										<label for="background_color"><?php echo __('Couleur :'); ?></label>
										<input<?php if (!$tpl->disWatermarkOption('imagettfbbox')) : ?> disabled="disabled"<?php endif; ?> value="<?php echo $tpl->getWatermarkOption('background_color'); ?>" maxlength="7" id="background_color" name="watermark_options[background_color]" class="text<?php if ($tpl->disWatermarkOption('imagettfbbox')) : ?> colorpicker<?php endif; ?>" size="7" type="text" />
									</p>
									<p class="field">
										<label for="background_alpha"><?php echo __('Transparence (entre 0 et 127) :'); ?></label>
										<input<?php if (!$tpl->disWatermarkOption('imagettfbbox')) : ?> disabled="disabled"<?php endif; ?> value="<?php echo $tpl->getWatermarkOption('background_alpha'); ?>" maxlength="3" id="background_alpha" name="watermark_options[background_alpha]" class="text" size="3" type="text" />
									</p>
									<p class="field">
										<label for="background_padding"><?php echo __('Marge interne :'); ?></label>
										<input<?php if (!$tpl->disWatermarkOption('imagettfbbox')) : ?> disabled="disabled"<?php endif; ?> value="<?php echo $tpl->getWatermarkOption('background_padding'); ?>" maxlength="4" id="background_padding" name="watermark_options[background_padding]" class="text" size="4" type="text" />
										<?php echo __('pixels'); ?>
									</p>
									<p class="field checkbox">
										<input<?php if (!$tpl->disWatermarkOption('imagettfbbox')) : ?> disabled="disabled"<?php endif; ?><?php echo $tpl->getWatermarkOption('background_large'); ?> id="background_large" name="watermark_options[background_large]" type="checkbox" />
										<span><label for="background_large"><?php echo __('Occuper toute la largeur de l\'image'); ?></label></span>
									</p>
								</div>
								<br />
								<p class="field checkbox">
									<input<?php if (!$tpl->disWatermarkOption('imagettfbbox')) : ?> disabled="disabled"<?php endif; ?><?php echo $tpl->getWatermarkOption('border_active'); ?> id="border_active" name="watermark_options[border_active]" type="checkbox" />
									<span><label for="border_active"><?php echo __('Ajouter une bordure :'); ?></label></span>
								</p>
								<div class="field_second">
									<p class="field">
										<label for="border_color"><?php echo __('Couleur :'); ?></label>
										<input<?php if (!$tpl->disWatermarkOption('imagettfbbox')) : ?> disabled="disabled"<?php endif; ?> value="<?php echo $tpl->getWatermarkOption('border_color'); ?>" maxlength="7" id="border_color" name="watermark_options[border_color]" class="text<?php if ($tpl->disWatermarkOption('imagettfbbox')) : ?> colorpicker<?php endif; ?>" size="7" type="text" />
									</p>
									<p class="field">
										<label for="border_alpha"><?php echo __('Transparence (entre 0 et 127) :'); ?></label>
										<input<?php if (!$tpl->disWatermarkOption('imagettfbbox')) : ?> disabled="disabled"<?php endif; ?> value="<?php echo $tpl->getWatermarkOption('border_alpha'); ?>" maxlength="3" id="border_alpha" name="watermark_options[border_alpha]" class="text" size="3" type="text" />
									</p>
									<p class="field">
										<label for="border_size"><?php echo __('Épaisseur :'); ?></label>
										<input<?php if (!$tpl->disWatermarkOption('imagettfbbox')) : ?> disabled="disabled"<?php endif; ?> value="<?php echo $tpl->getWatermarkOption('border_size'); ?>" maxlength="3" id="border_size" name="watermark_options[border_size]" class="text" size="3" type="text" />
										<?php echo __('pixels'); ?>
									</p>
								</div>
							</div>
						</div>
					</fieldset>	
					<br />
					<div class="browse_anchor" id="w_image"></div>
					<fieldset>
						<legend><?php echo __('Image'); ?></legend>
						<div class="fielditems">
							<p class="field checkbox">
								<input<?php echo $tpl->getWatermarkOption('image_active'); ?> id="image_active" name="watermark_options[image_active]" type="checkbox" class="show_part" rel="wimage" />
								<span><label for="image_active"><?php echo __('Ajouter une image'); ?></label></span>
							</p>
							<div<?php if (!$tpl->disWatermarkOption('image_active')) : ?> style="display:none"<?php endif; ?> class="field_second" id="wimage">
								<p class="field">
									<?php echo __('Image actuelle :'); ?>
								</p>
								<div class="field_second">
									<p class="field">
<?php if ($tpl->disWatermarkOption('image_file')) : ?>
										<span id="watermark_image">
											<img <?php echo $tpl->getWatermarkOption('image_size'); ?> alt="<?php echo __('image de filigrane'); ?>" src="<?php echo $tpl->getWatermarkOption('image_file'); ?>" />
										</span>
<?php else : ?>
										<p class="report_msg report_info"><?php echo __('Aucune.'); ?></p>
<?php endif; ?>
									</p>
								</div>
								<p class="field">
									<?php printf(__('Nouvelle image (%s maximum) :'), $tpl->getAdmin('upload_max_filesize_formated')); ?>
								</p>
								<div class="field_second">
									<p class="field">
										<input class="text" name="file_upload" type="file" />
										<input name="MAX_FILE_SIZE" value="<?php echo $tpl->getAdmin('upload_max_filesize_value'); ?>" type="hidden" />
									</p>
								</div>
								<p class="field">
									<?php echo __('Taille :'); ?>
								</p>
								<div class="field_second">
									<p class="field">
										<input<?php echo $tpl->getWatermarkOption('image_size_type_fixed'); ?> id="image_size_type_fixed" name="watermark_options[image_size_type]" value="fixed" type="radio" />
										<label for="image_size_type_fixed"><?php echo __('Fixe'); ?></label>
									</p>
									<p class="field">
										<input<?php echo $tpl->getWatermarkOption('image_size_type_pct'); ?> id="image_size_type_pct" name="watermark_options[image_size_type]" value="pct" type="radio" />
										<label for="image_size_type_pct"><?php echo __('Proportionnelle :'); ?></label>
										<input value="<?php echo $tpl->getWatermarkOption('image_size_pct'); ?>" maxlength="3" id="image_size_pct" name="watermark_options[image_size_pct]" class="text" size="3" type="text" />
										%
									</p>
								</div>
								<p class="field">
									<?php echo __('Position :'); ?>
								</p>
								<div class="field_second">
									<p class="field">
										<select id="image_position" name="watermark_options[image_position]">
											<?php echo $tpl->getWatermarkOption('image_position'); ?>

										</select>
									</p>
									<p class="field">
										<label for="image_x"><?php echo __('à'); ?></label>
										<input value="<?php echo $tpl->getWatermarkOption('image_x'); ?>" maxlength="5" id="image_x" name="watermark_options[image_x]" class="text" size="5" type="text" />
										<label for="image_x"><?php echo __('pixels du bord vertical'); ?></label>
									</p>
									<p class="field">
										<label for="image_y"><?php echo __('à'); ?></label>
										<input value="<?php echo $tpl->getWatermarkOption('image_y'); ?>" maxlength="5" id="image_y" name="watermark_options[image_y]" class="text" size="5" type="text" />
										<label for="image_y"><?php echo __('pixels du bord horizontal'); ?></label>
									</p>
								</div>
								<p class="field">
									<label for="image_opacity"><?php echo __('Opacité :'); ?></label>
									<input value="<?php echo $tpl->getWatermarkOption('image_opacity'); ?>" maxlength="3" id="image_opacity" name="watermark_options[image_opacity]" class="text" size="3" type="text" />
									%
								</p>
							</div>
						</div>
					</fieldset>
					<br />
					<div class="browse_anchor" id="w_general"></div>
					<fieldset>
						<legend><?php echo __('Général'); ?></legend>
						<p class="field">
							<label for="quality"><?php echo __('Qualité (entre 0 et 100) :'); ?></label>
							<input value="<?php echo $tpl->getWatermarkOption('quality'); ?>" maxlength="3" id="quality" name="watermark_options[quality]" class="text" size="3" type="text" />
<?php if ($tpl->disHelp()) : ?>
							<a rel="h_quality" title="<?php echo __('Obtenir de l\'aide sur cette fonction'); ?>" class="help_link help_link_context" href="javascript:;"><img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/help-link.png" alt="<?php echo __('Aide'); ?>" /></a>
<?php endif; ?>
						</p>
					</fieldset>
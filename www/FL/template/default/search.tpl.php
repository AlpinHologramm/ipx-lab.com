		<div id="search_advanced" class="box box_large box_notools">
			<table>
				<tr><td class="box_title"><div><h2><?php echo __('Recherche avancée'); ?></h2></div></td></tr>
				<tr>
					<td class="box_edit aac">
						<form action="" method="post">
							<div>
								<fieldset>
									<legend><?php echo __('Recherche'); ?></legend>
									<p class="field">
										<input name="search_query" id="search_advanced_query" type="text" class="text focus" maxlength="255" size="60" />
									</p>
									<p class="igal_help">
										<?php echo __('Utilisez les jokers (?) ou (*) pour remplacer respectivement n\'importe quel caractère ou n\'importe quelle suite de caractères.'); ?>
										<br /><br />
										<?php echo __('Entourez une expression avec les guillemets double (") pour rechercher cette expression exacte.'); ?>
										<br /><br />
										<?php echo __('Préfixez un terme avec le signe moins (-) afin d\'exclure de la recherche tous les objets contenant ce terme dans l\'un des champs sélectionnés.'); ?>
									</p>
									<p class="field">
										<input type="checkbox" name="search_options[all_words]" id="search_all_words" />
										<label for="search_all_words"><?php echo __('Rechercher tous les mots'); ?></label>
									</p>
								</fieldset>
								<fieldset id="search_advanced_filters">
									<legend><?php echo __('Champs'); ?></legend>
									<p class="field">
										<input checked="checked" id="search_title" name="search_options[image_name]" type="checkbox" />
										<label for="search_title"><?php echo __('Titre'); ?></label>

										<input checked="checked" id="search_desc" name="search_options[image_desc]" type="checkbox" />
										<label for="search_desc"><?php echo __('Description'); ?></label>
									</p>
<?php if ($tpl->disGallery('tags') || $tpl->disGallery('comments')) : ?>
									<p class="field">
<?php if ($tpl->disGallery('tags')) : ?>
										<input checked="checked" id="search_tags" name="search_options[tags]" type="checkbox" />
										<label for="search_tags"><?php echo __('Tags'); ?></label>
<?php endif; ?>

<?php if ($tpl->disGallery('comments')) : ?>
										<input id="search_comments" name="search_options[comments]" type="checkbox" />
										<label for="search_comments"><?php echo __('Commentaires'); ?></label>
<?php endif; ?>
									</p>
<?php endif; ?>
									<p class="field">
										<input id="search_brands" name="search_options[brands]" type="checkbox" />
										<label for="search_brands"><?php echo __('Marque de l\'appareil'); ?></label>

										<input id="search_models" name="search_options[models]" type="checkbox" />
										<label for="search_models"><?php echo __('Modèle de l\'appareil'); ?></label>
									</p>
								</fieldset>
								<fieldset>
									<legend><?php echo __('Albums'); ?></legend>
									<p class="field">
										<select size="8" id="search_advanced_categories" name="search_options[categories][]" multiple="multiple">
											<?php echo $tpl->getSearchAdvanced('categories'); ?>

										</select>
									</p>
								</fieldset>
								<fieldset>
									<legend><?php echo __('Date'); ?></legend>
									<p class="field">
										<input id="search_date" type="checkbox" name="search_options[date]" />
										<label for="search_date"><?php echo __('Rechercher par date :'); ?></label>
									</p>
									<div class="field_second">
										<p class="field search_images_fields">
											<input checked="checked" id="search_date_field_image_adddt" type="radio" name="search_options[date_field]" value="image_adddt" />
											<label for="search_date_field_image_adddt"><?php echo __('Date d\'ajout'); ?></label>
											&nbsp;
											<input id="search_date_field_image_crtdt" type="radio" name="search_options[date_field]" value="image_crtdt" />
											<label for="search_date_field_image_crtdt"><?php echo __('Date de création'); ?></label>
										</p>
										<p class="field">
											<?php echo __('du'); ?>&nbsp;
											<?php echo $tpl->getSearchAdvanced('date_start'); ?>

										</p>
										<p class="field">
											<?php echo __('au'); ?>&nbsp;
											<?php echo $tpl->getSearchAdvanced('date_end'); ?>

										</p>
									</div>
								</fieldset>
								<fieldset>
									<legend><?php echo __('Dimensions'); ?></legend>
									<p class="field">
										<input id="search_size" type="checkbox" name="search_options[size]" />
										<label for="search_size"><?php echo __('Rechercher par dimensions :'); ?></label>
									</p>
									<div id="search_advanced_size" class="field_second">
										<p class="field">
											<?php echo __('Largeur :'); ?>&nbsp;
											<label for="search_options_width_start"><?php echo __('entre'); ?></label>
											<input id="search_options_width_start" name="search_options[size_width_start]" class="text" type="text" size="6" maxlength="5" />
											<label for="search_options_width_end"><?php echo __('et'); ?></label>
											<input id="search_options_width_end" name="search_options[size_width_end]" class="text" type="text" size="6" maxlength="5" />
											<?php echo __('pixels'); ?>
										</p>
										<p class="field">
											<?php echo __('Hauteur :'); ?>&nbsp;
											<label for="search_options_height_start"><?php echo __('entre'); ?></label>
											<input id="search_options_height_start" name="search_options[size_height_start]" class="text" type="text" size="6" maxlength="5" />
											<label for="search_options_height_end"><?php echo __('et'); ?></label>
											<input id="search_options_height_end" name="search_options[size_height_end]" class="text" type="text" size="6" maxlength="5" />
											<?php echo __('pixels'); ?>
										</p>
									</div>
								</fieldset>
								<fieldset>
									<legend><?php echo __('Poids'); ?></legend>
									<p class="field">
										<input id="search_filesize" type="checkbox" name="search_options[filesize]" />
										<label for="search_filesize"><?php echo __('Rechercher par poids :'); ?></label>
									</p>
									<div id="search_advanced_filesize" class="field_second">
										<p class="field">
											<label for="search_options_filesize_start"><?php echo __('entre'); ?></label>
											<input id="search_options_filesize_start" name="search_options[filesize_start]" class="text" type="text" size="6" maxlength="5" />
											<label for="search_options_filesize_end"><?php echo __('et'); ?></label>
											<input id="search_options_filesize_end" name="search_options[filesize_end]" class="text" type="text" size="6" maxlength="5" />
											<?php echo __('Ko'); ?>
										</p>
									</div>
								</fieldset>
								<input type="submit" class="submit" value="<?php echo __('Chercher'); ?>" />
							</div>
						</form>
					</td>
				</tr>
			</table>
		</div>
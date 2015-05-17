<?php include_once(dirname(__FILE__) . '/options_submenu.tpl.php'); ?>

		<div id="tools_browse">
			<div class="browse">
				<select id="functions_list" onchange="window.location.href='#'+this.options[this.selectedIndex].value">
					<option value=""><?php echo __('Parcourir :'); ?></option>
					<option value="general"><?php echo __('Général'); ?></option>
					<option value="hits"><?php echo __('Visites'); ?></option>
					<option value="url">URL</option>
					<option value="recaptcha">reCAPTCHA</option>
					<option value="navigation"><?php echo __('Éléments de navigation'); ?></option>
					<option value="langs"><?php echo __('Langues'); ?></option>
					<option value="dates"><?php echo __('Dates'); ?></option>
					<option value="gd">GD</option>
					<option value="add-images"><?php echo __('Ajout d\'images'); ?></option>
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
<?php while ($tpl->nextLang()) : ?>
						<p<?php if (!$tpl->disLang('selected')) : ?> style="display:none"<?php endif; ?> class="field field_ftw">
							<label class="icon icon_<?php echo $tpl->getLang('code'); ?>" for="gallery_title_<?php echo $tpl->getLang('code'); ?>"><?php echo __('Titre de la galerie :'); ?></label>
							<input value="<?php echo $tpl->getOption('gallery_title'); ?>" id="gallery_title_<?php echo $tpl->getLang('code'); ?>" name="gallery_title[<?php echo $tpl->getLang('code'); ?>]" class="text" type="text" maxlength="512" />
						</p>
<?php endwhile; ?>
						<p class="field checkbox">
							<input<?php echo $tpl->getOption('gallery_banner'); ?> id="gallery_banner" name="gallery_banner" type="checkbox" />
							<span><label for="gallery_banner"><?php echo __('Utiliser une bannière'); ?></label></span>
<?php if ($tpl->disHelp()) : ?>
							<a rel="h_banner" title="<?php echo __('Obtenir de l\'aide sur cette fonction'); ?>" class="help_link help_link_context" href="javascript:;"><img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/help-link.png" alt="<?php echo __('Aide'); ?>" /></a>
<?php endif; ?>
						</p>
						<div class="field_second">
							<p class="field">
								<label for="gallery_banner_file"><?php echo __('Bannière :'); ?></label>
								<select id="gallery_banner_file" name="gallery_banner_file">
<?php if ($tpl->disOption('gallery_banner_file')) : ?>
									<?php echo $tpl->getOption('gallery_banner_file'); ?>
<?php else : ?>
									<option disabled="disabled">&nbsp;</option>

<?php endif; ?>

								</select>
							</p>
						</div>
<?php while ($tpl->nextLang()) : ?>
						<p<?php if (!$tpl->disLang('selected')) : ?> style="display:none"<?php endif; ?> class="field field_ftw field_html">
							<label class="icon icon_<?php echo $tpl->getLang('code'); ?>" for="gallery_description_<?php echo $tpl->getLang('code'); ?>"><?php echo __('Description :'); ?></label>
							<span class="field_html_tag">
								<a title="<?php echo __('Cliquez pour obtenir la liste des balises autorisées'); ?>" href="javascript:;">HTML</a>
							</span>
							<span class="field_html_textarea">
								<textarea class="resizable" rows="5" cols="30" id="gallery_description_<?php echo $tpl->getLang('code'); ?>" name="gallery_description[<?php echo $tpl->getLang('code'); ?>]"><?php echo $tpl->getOption('gallery_description'); ?></textarea>
							</span>
						</p>
<?php endwhile; ?>
<?php while ($tpl->nextLang()) : ?>
						<p<?php if (!$tpl->disLang('selected')) : ?> style="display:none"<?php endif; ?> class="field field_ftw field_html">
							<label class="icon icon_<?php echo $tpl->getLang('code'); ?>" for="gallery_description_guest_<?php echo $tpl->getLang('code'); ?>"><?php echo __('Description pour invités :'); ?></label>
							<span class="field_html_tag">
								<a title="<?php echo __('Cliquez pour obtenir la liste des balises autorisées'); ?>" href="javascript:;">HTML</a>
							</span>
							<span class="field_html_textarea">
								<textarea class="resizable" rows="5" cols="30" id="gallery_description_guest_<?php echo $tpl->getLang('code'); ?>" name="gallery_description_guest[<?php echo $tpl->getLang('code'); ?>]"><?php echo $tpl->getOption('gallery_description_guest'); ?></textarea>
							</span>
						</p>
<?php endwhile; ?>
<?php while ($tpl->nextLang()) : ?>
						<p<?php if (!$tpl->disLang('selected')) : ?> style="display:none"<?php endif; ?> class="field field_ftw field_html">
							<label class="icon icon_<?php echo $tpl->getLang('code'); ?>" for="gallery_footer_message_<?php echo $tpl->getLang('code'); ?>"><?php echo __('Message de pied de page :'); ?></label>
							<span class="field_html_tag">
								<a title="<?php echo __('Cliquez pour obtenir la liste des balises autorisées'); ?>" href="javascript:;">HTML</a>
							</span>
							<span class="field_html_textarea">
								<textarea class="resizable" rows="3" cols="30" id="gallery_footer_message_<?php echo $tpl->getLang('code'); ?>" name="gallery_footer_message[<?php echo $tpl->getLang('code'); ?>]"><?php echo $tpl->getOption('gallery_footer_message'); ?></textarea>
							</span>
						</p>
<?php endwhile; ?>
						<p class="field checkbox">
							<input<?php echo $tpl->getOption('gallery_closure'); ?> id="gallery_closure" name="gallery_closure" type="checkbox" />
							<span><label for="gallery_closure"><?php echo __('Fermer la galerie'); ?></label></span>
						</p>
<?php while ($tpl->nextLang()) : ?>
						<p<?php if (!$tpl->disLang('selected')) : ?> style="display:none"<?php endif; ?> class="field field_ftw field_html field_second">
							<label class="icon icon_<?php echo $tpl->getLang('code'); ?>" for="gallery_closure_message_<?php echo $tpl->getLang('code'); ?>"><?php echo __('Message de fermeture :'); ?></label>
							<span class="field_html_tag">
								<a title="<?php echo __('Cliquez pour obtenir la liste des balises autorisées'); ?>" href="javascript:;">HTML</a>
							</span>
							<span class="field_html_textarea">
								<textarea class="resizable" rows="3" cols="30" id="gallery_closure_message_<?php echo $tpl->getLang('code'); ?>" name="gallery_closure_message[<?php echo $tpl->getLang('code'); ?>]"><?php echo $tpl->getOption('gallery_closure_message'); ?></textarea>
							</span>
						</p>
<?php endwhile; ?>
						<p class="field checkbox">
							<input<?php echo $tpl->getOption('html_filter'); ?> id="html_filter" name="html_filter" type="checkbox" />
							<span><label for="html_filter"><?php echo __('Autoriser les balises HTML dans les champs textes'); ?></label></span>
						</p>
					</div>
				</fieldset>
				<br />
				<div class="browse_anchor" id="hits"></div>
				<fieldset>
					<legend>
						<?php echo __('Visites'); ?>

<?php if ($tpl->disHelp()) : ?>
						<a rel="h_nohits_useragent" title="<?php echo __('Obtenir de l\'aide sur cette fonction'); ?>" class="help_link help_link_context" href="javascript:;"><img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/help-link.png" alt="<?php echo __('Aide'); ?>" /></a>
<?php endif; ?>
					</legend>
					<div class="fielditems">
						<p class="field checkbox">
							<input<?php echo $tpl->getOption('nohits_useragent'); ?> id="nohits_useragent" name="nohits_useragent" type="checkbox" />
							<span><label for="nohits_useragent"><?php echo __('Ne pas comptabiliser les visites des utilisateurs dont l\'User-Agent se trouve dans cette liste :'); ?></label></span>
						</p>
						<div class="field_second">
							<p class="field">
								<textarea class="resizable" name="nohits_useragent_list" rows="8" cols="30"><?php echo $tpl->getOption('nohits_useragent_list'); ?></textarea>
							</p>
						</div>
					</div>
				</fieldset>
				<br />
				<div class="browse_anchor" id="url"></div>
				<fieldset>
					<legend>URL</legend>
					<div class="fielditems">
						<p class="field checkbox">
							<input<?php echo $tpl->getOption('url_rewriting'); ?> id="url_rewriting" name="url_rewriting" type="checkbox" />
							<span><label for="url_rewriting"><?php echo __('Utiliser l\'URL rewriting'); ?></label></span>
<?php if ($tpl->disHelp()) : ?>
							<a rel="h_url_rewriting" title="<?php echo __('Obtenir de l\'aide sur cette fonction'); ?>" class="help_link help_link_context" href="javascript:;"><img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/help-link.png" alt="<?php echo __('Aide'); ?>" /></a>
<?php endif; ?>
						</p>
						<p class="field checkbox">
							<input<?php echo $tpl->getOption('gallery_integrated'); ?> id="gallery_integrated" name="gallery_integrated" type="checkbox" />
							<span><label for="gallery_integrated"><?php echo __('La galerie est intégrée au site'); ?></label></span>
<?php if ($tpl->disHelp()) : ?>
							<a rel="h_integrated" title="<?php echo __('Obtenir de l\'aide sur cette fonction'); ?>" class="help_link help_link_context" href="javascript:;"><img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/help-link.png" alt="<?php echo __('Aide'); ?>" /></a>
<?php endif; ?>
						</p>
					</div>
				</fieldset>
				<br />
				<div class="browse_anchor" id="recaptcha"></div>
				<fieldset>
					<legend>
						reCAPTCHA
<?php if ($tpl->disHelp()) : ?>
						<a rel="h_recaptcha" title="<?php echo __('Obtenir de l\'aide sur cette fonction'); ?>" class="help_link help_link_context" href="javascript:;"><img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/help-link.png" alt="<?php echo __('Aide'); ?>" /></a>
<?php endif; ?>
					</legend>
					<div class="fielditems">
						<p class="field field_ftw">
							<label for="recaptcha_public_key"><?php echo __('Clé publique :'); ?></label>
							<input value="<?php echo $tpl->getOption('recaptcha_public_key'); ?>" id="recaptcha_public_key" name="recaptcha_public_key" class="text" type="text" maxlength="64" size="6" />
						</p>
						<p class="field field_ftw">
							<label for="recaptcha_private_key"><?php echo __('Clé privée :'); ?></label>
							<input value="<?php echo $tpl->getOption('recaptcha_private_key'); ?>" id="recaptcha_private_key" name="recaptcha_private_key" class="text" type="text" maxlength="64" size="6" />
						</p>
						<p class="field checkbox">
							<input<?php echo $tpl->getOption('recaptcha_comments'); ?> id="recaptcha_comments" name="recaptcha_comments" type="checkbox" />
							<span><label for="recaptcha_comments"><?php echo __('Activer reCAPTCHA pour les commentaires'); ?></label></span>
						</p>
						<div class="field_second">
							<p class="field checkbox">
								<input<?php echo $tpl->getOption('recaptcha_comments_guest_only'); ?> id="recaptcha_comments_guest_only" name="recaptcha_comments_guest_only" type="checkbox" />
								<span><label for="recaptcha_comments_guest_only"><?php echo __('uniquement pour les invités'); ?></label></span>
							</p>
						</div>
						<p class="field checkbox">
							<input<?php echo $tpl->getOption('recaptcha_contact'); ?> id="recaptcha_contact" name="recaptcha_contact" type="checkbox" />
							<span><label for="recaptcha_contact"><?php echo __('Activer reCAPTCHA pour la page contact'); ?></label></span>
						</p>
						<p class="field checkbox">
							<input<?php echo $tpl->getOption('recaptcha_inscriptions'); ?> id="recaptcha_inscriptions" name="recaptcha_inscriptions" type="checkbox" />
							<span><label for="recaptcha_inscriptions"><?php echo __('Activer reCAPTCHA pour les inscriptions'); ?></label></span>
						</p>
					</div>
				</fieldset>
				<br />
				<div class="browse_anchor" id="navigation"></div>
				<fieldset>
					<legend><?php echo __('Éléments de navigation'); ?></legend>
					<div class="fielditems">
						<p class="field">
							<label for="level_separator"><?php echo __('Séparateur de niveau des catégories :'); ?></label>
							<input value="<?php echo $tpl->getOption('level_separator'); ?>" id="level_separator" name="level_separator" class="text" type="text" maxlength="64" size="6" />
						</p>
						<p class="field">
							<label for="nav_bar"><?php echo __('Barres de navigation entre les pages :'); ?></label>
							<select id="nav_bar" name="nav_bar">
								<?php echo $tpl->getOption('nav_bar'); ?>

							</select>
						</p>
					</div>
				</fieldset>
				<br />
				<div class="browse_anchor" id="langs"></div>
				<fieldset>
					<legend>
						<?php echo __('Langues'); ?>
<?php if ($tpl->disHelp()) : ?>
						<a rel="h_langs" title="<?php echo __('Obtenir de l\'aide sur cette fonction'); ?>" class="help_link help_link_context" href="javascript:;"><img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/help-link.png" alt="<?php echo __('Aide'); ?>" /></a>
<?php endif; ?>
					</legend>
					<div class="fielditems">
						<div id="options_langs">
							<p class="field">
								<label><?php echo __('Langues installées'); ?></label>
								<select name="uninstall_langs_select[]" class="multiple" multiple="multiple" size="6">
<?php if ($tpl->disOption('installed_langs')) : ?>
										<?php echo $tpl->getOption('installed_langs'); ?>
<?php else : ?>
										<option disabled="disabled">&nbsp;</option>

<?php endif; ?>
								</select>
								<input type="checkbox" name="uninstall_langs" id="uninstall_langs" />
								<label for="uninstall_langs"><?php echo __('Désinstaller les langues sélectionnées'); ?></label>
							</p>
							<p class="field">
								<label><?php echo __('Langues disponibles'); ?></label>
								<select name="install_langs_select[]" class="multiple" multiple="multiple" size="6">
<?php if ($tpl->disOption('available_langs')) : ?>
										<?php echo $tpl->getOption('available_langs'); ?>
<?php else : ?>
										<option disabled="disabled">&nbsp;</option>

<?php endif; ?>
								</select>
								<input type="checkbox" name="install_langs" id="install_langs" />
								<label for="install_langs"><?php echo __('Installer les langues sélectionnées'); ?></label>
							</p>
						</div>
						<p class="field checkbox">
							<input<?php echo $tpl->getOption('lang_switch'); ?> id="lang_switch" name="lang_switch" type="checkbox" />
							<span><label for="lang_switch"><?php echo __('Activer le menu des langues'); ?></label></span>
						</p>
						<p class="field checkbox">
							<input<?php echo $tpl->getOption('lang_client'); ?> id="lang_client" name="lang_client" type="checkbox" />
							<span><label for="lang_client"><?php echo __('Utiliser la langue du navigateur (si disponible)'); ?></label></span>
						</p>
						<p class="field">
							<label for="lang_default"><?php echo __('Langue par défaut :'); ?></label>
							<select id="lang_default" name="lang_default">
<?php if ($tpl->disOption('installed_langs')) : ?>
								<?php echo $tpl->getOption('default_langs'); ?>
<?php else : ?>
								<option disabled="disabled">&nbsp;</option>

<?php endif; ?>
							</select>
						</p>
					</div>
				</fieldset>
				<br />
				<div class="browse_anchor" id="dates"></div>
				<fieldset>
					<legend><?php echo __('Dates'); ?></legend>
					<div id="settings_date_format" class="fielditems">
						<p class="field">
							<label for="tz_default"><?php echo __('Fuseau horaire par défaut :'); ?></label>
							<select id="tz_default" name="tz_default">
								<?php echo $tpl->getOption('tz_list'); ?>

							</select>
						</p>
					</div>
				</fieldset>
				<br />
				<div class="browse_anchor" id="gd"></div>
				<fieldset>
					<legend>
						<span class="help_legend">GD</span>
<?php if ($tpl->disHelp()) : ?>
							<a rel="h_gd" title="<?php echo __('Obtenir de l\'aide sur cette fonction'); ?>" class="help_link help_link_context" href="javascript:;"><img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/help-link.png" alt="<?php echo __('Aide'); ?>" /></a>
<?php endif; ?>
					</legend>
					<div class="fielditems">
						<p class="field checkbox">
							<input<?php echo $tpl->getOption('gd_transparency'); ?> id="gd_transparency" name="gd_transparency" type="checkbox" />
							<span><label for="gd_transparency"><?php echo __('Activer la gestion de la transparence'); ?></label></span>
						</p>
					</div>
				</fieldset>
				<br />
				<div class="browse_anchor" id="add-images"></div>
				<fieldset>
					<legend>
						<span class="help_legend"><?php echo __('Ajout d\'images'); ?></span>
<?php if ($tpl->disHelp()) : ?>
						<a rel="h_add_images" title="<?php echo __('Obtenir de l\'aide sur cette fonction'); ?>" class="help_link help_link_context" href="javascript:;"><img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/help-link.png" alt="<?php echo __('Aide'); ?>" /></a>
<?php endif; ?>
					</legend>
					<div class="fielditems">
						<p class="field checkbox">
							<input<?php echo $tpl->getOption('upload_report_all_files'); ?> id="upload_report_all_files" name="upload_report_all_files" type="checkbox" />
							<span><label for="upload_report_all_files"><?php echo __('Ajouter tous les fichiers au rapport'); ?></label></span>
						</p>
						<p class="field checkbox">
							<input<?php echo $tpl->getOption('upload_update_images'); ?> id="upload_update_images" name="upload_update_images" type="checkbox" />
							<span><label for="upload_update_images"><?php echo __('Mettre à jour les informations des images'); ?></label></span>
						</p>
						<p class="field checkbox">
							<input<?php echo $tpl->getOption('upload_update_thumb_id'); ?> id="upload_update_thumb_id" name="upload_update_thumb_id" type="checkbox" />
							<span><label for="upload_update_thumb_id"><?php echo __('Choisir une nouvelle vignette pour les catégories mises à jour'); ?></label></span>
						</p>
					</div>
				</fieldset>
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
				<input type="submit" class="submit" value="<?php echo __('Enregistrer'); ?>" />
			</div>
		</form>

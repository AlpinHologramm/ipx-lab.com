
<?php include_once(dirname(__FILE__) . '/users_submenu.tpl.php'); ?>

		<div id="tools_browse">
			<div class="browse">
				<select id="functions_list" onchange="window.location.href='#'+this.options[this.selectedIndex].value">
					<option value=""><?php echo __('Parcourir :'); ?></option>
					<option value="general"><?php echo __('Général'); ?></option>
					<option value="inscriptions"><?php echo __('Inscriptions'); ?></option>
					<option value="profile-infos"><?php echo __('Informations de profil'); ?></option>
					<option value="avatars"><?php echo __('Avatars'); ?></option>
					<option value="upload-images"><?php echo __('Envoi d\'images'); ?></option>
					<option value="locked-albums"><?php echo __('Albums verrouillés'); ?></option>
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
							<input<?php echo $tpl->getOption('users_only_members'); ?> id="users_only_members" name="users_only_members" type="checkbox" />
							<span><label for="users_only_members"><?php echo __('Interdire l\'accès à la galerie pour les invités'); ?></label></span>
						</p>
						<p class="field checkbox">
							<input<?php echo $tpl->getOption('users_log_activity'); ?> id="users_log_activity" name="users_log_activity" type="checkbox" />
							<span><label for="users_log_activity"><?php echo __('Enregistrer l\'activité des utilisateurs'); ?></label></span>
<?php if ($tpl->disHelp()) : ?>
							<a rel="h_log_activity" title="<?php echo __('Obtenir de l\'aide sur cette fonction'); ?>" class="help_link help_link_context" href="javascript:;"><img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/help-link.png" alt="<?php echo __('Aide'); ?>" /></a>
<?php endif; ?>
						</p>
						<div class="field_second">
							<p class="field checkbox">
								<input<?php echo $tpl->getOption('users_log_activity_delete'); ?> id="users_log_activity_delete" name="users_log_activity_delete" type="checkbox" />
								<span><label for="users_log_activity_delete"><?php echo __('Supprimer les entrées au bout de :'); ?></label></span>
								<input value="<?php echo $tpl->getOption('users_log_activity_delete_days'); ?>" id="users_log_activity_delete_days" name="users_log_activity_delete_days" type="text" class="text" maxlength="4" size="5" />
								<?php echo __('jours'); ?>
							</p>
						</div>
					</div>
				</fieldset>
				<br />
				<div class="browse_anchor" id="inscriptions"></div>
				<fieldset>
					<legend><?php echo __('Inscriptions'); ?></legend>
					<div class="fielditems">
						<p class="field checkbox">
							<input<?php echo $tpl->getOption('users_inscription'); ?> id="users_inscription" name="users_inscription" type="checkbox" />
							<span><label for="users_inscription"><?php echo __('Autoriser les nouvelles inscriptions'); ?></label></span>
						</p>
						<p class="field checkbox">
							<input<?php echo $tpl->getOption('users_inscription_moderate'); ?> id="users_inscription_moderate" name="users_inscription_moderate" type="checkbox" />
							<span><label for="users_inscription_moderate"><?php echo __('Valider les inscriptions par un administrateur'); ?></label></span>
						</p>
						<p class="field checkbox">
							<input<?php echo $tpl->getOption('users_inscription_by_mail'); ?> id="users_inscription_by_mail" name="users_inscription_by_mail" type="checkbox" />
							<span><label for="users_inscription_by_mail"><?php echo __('Valider les inscriptions par courriel'); ?></label></span>
						</p>
						<p class="field checkbox">
							<input<?php echo $tpl->getOption('users_inscription_by_password'); ?> id="users_inscription_by_password" name="users_inscription_by_password" type="checkbox" />
							<span><label for="users_inscription_by_password"><?php echo __('Valider les inscriptions par mot de passe'); ?></label></span>
						</p>
						<div class="field_second">
							<p class="field">
								<label for="users_inscription_password"><?php echo __('Mot de passe :'); ?></label>
								<input value="<?php echo $tpl->getOption('users_inscription_password'); ?>" id="users_inscription_password" name="users_inscription_password" class="text" type="password" maxlength="1024" size="50" />
							</p>
<?php while ($tpl->nextLang()) : ?>
							<p<?php if (!$tpl->disLang('selected')) : ?> style="display:none"<?php endif; ?> class="field field_ftw">
								<label class="icon icon_<?php echo $tpl->getLang('code'); ?>" for="users_inscription_password_text_<?php echo $tpl->getLang('code'); ?>"><?php echo __('Astuce pour retrouver le mot de passe :'); ?></label>
								<textarea class="resizable" rows="3" cols="30" id="users_inscription_password_text_<?php echo $tpl->getLang('code'); ?>" name="users_inscription_password_text[<?php echo $tpl->getLang('code'); ?>]"><?php echo $tpl->getOption('users_inscription_password_text'); ?></textarea>
							</p>
<?php endwhile; ?>
						</div>
						<p class="field checkbox">
							<input<?php echo $tpl->getOption('users_inscription_autocat'); ?> id="users_inscription_autocat" name="users_inscription_autocat" type="checkbox" />
							<span><label for="users_inscription_autocat"><?php echo __('Lors de l\'inscription d\'un utilisateur, créer une catégorie qui lui sera attribuée'); ?></label></span>
						</p>
						<div class="field_second">
<?php while ($tpl->nextLang()) : ?>
							<p<?php if (!$tpl->disLang('selected')) : ?> style="display:none"<?php endif; ?> class="field field_ftw">
								<label class="icon icon_<?php echo $tpl->getLang('code'); ?>" for="users_inscription_autocat_title_<?php echo $tpl->getLang('code'); ?>"><?php echo __('Titre de la catégorie :'); ?></label>
								<input value="<?php echo $tpl->getOption('users_inscription_autocat_title'); ?>" id="users_inscription_autocat_title_<?php echo $tpl->getLang('code'); ?>" name="users_inscription_autocat_title[<?php echo $tpl->getLang('code'); ?>]" class="text" type="text" maxlength="512" />
							</p>
<?php endwhile; ?>
							<p class="field">
								<label for="users_inscription_autocat_type"><?php echo __('Type :'); ?></label>
								<select id="users_inscription_autocat_type" name="users_inscription_autocat_type">
<?php echo $tpl->getOption('users_inscription_autocat_type'); ?>

								</select>
							</p>
							<p class="field checkbox">
								<label for="users_inscription_autocat_category"><?php echo __('Catégorie parente :'); ?></label>
								<select id="users_inscription_autocat_category" name="users_inscription_autocat_category">
<?php echo $tpl->getOption('users_inscription_autocat_category'); ?>

								</select>
							</p>
						</div>
					</div>
				</fieldset>
				<br />
				<div class="browse_anchor" id="profile-infos"></div>
				<fieldset>
					<legend><?php echo __('Informations de profil'); ?></legend>
					<div class="fielditems">
						<script type="text/javascript">
						//<![CDATA[
						var delete_link = '<?php echo $tpl->getL10nJS(__('supprimer')); ?>';
						var delete_confirm = '<?php echo $tpl->getL10nJS(__('Les données entrées par les utilisateurs seront toutes perdues !')) . '\n' . $tpl->getL10nJS(__('Êtes-vous sûr de vouloir supprimer cette information de profil ?')); ?>';
						//]]>
						</script>
						<table id="profil_infos">
							<tr>
								<th class="name"><?php echo __('Information'); ?></th>
								<th><?php echo __('Activer ?'); ?></th>
								<th><?php echo __('Obligatoire ?'); ?></th>
							</tr>
<?php while ($tpl->nextProfileInfo()) : ?>
							<tr>
								<td class="name"><?php echo $tpl->getProfileInfo('name'); ?><input type="hidden" name="infos[<?php echo $tpl->getProfileInfo('id'); ?>]" /></td>
								<td><input<?php echo $tpl->getProfileInfo('activate'); ?> name="infos[<?php echo $tpl->getProfileInfo('id'); ?>][activate]" type="checkbox" /></td>
								<td><input<?php echo $tpl->getProfileInfo('required'); ?> name="infos[<?php echo $tpl->getProfileInfo('id'); ?>][required]" type="checkbox" /></td>
							</tr>
<?php endwhile; ?>
<?php while ($tpl->nextProfilePerso()) : ?>
							<tr>
								<td class="name perso">
<?php while ($tpl->nextLang()) : ?>
									<p <?php if (!$tpl->disLang('selected')) : ?> style="display:none"<?php endif; ?>>
										<label for="perso_<?php echo $tpl->getProfilePerso('id'); ?>_<?php echo $tpl->getLang('code'); ?>" class="icon_lang icon_<?php echo $tpl->getLang('code'); ?>"></label>
										<input id="perso_<?php echo $tpl->getProfilePerso('id'); ?>_<?php echo $tpl->getLang('code'); ?>" name="perso[<?php echo $tpl->getProfilePerso('id'); ?>][name][<?php echo $tpl->getLang('code'); ?>]" maxlength="64" size="25" class="text" type="text" value="<?php echo $tpl->getProfilePerso('name'); ?>" />
									</p>
<?php endwhile; ?>
								</td>
								<td><input<?php echo $tpl->getProfilePerso('activate'); ?> name="perso[<?php echo $tpl->getProfilePerso('id'); ?>][activate]" type="checkbox" /></td>
								<td><input<?php echo $tpl->getProfilePerso('required'); ?> name="perso[<?php echo $tpl->getProfilePerso('id'); ?>][required]" type="checkbox" /></td>
								<td><span class="icon icon_delete"><a class="js" href="javascript:;"><?php echo __('supprimer'); ?></a></span></td>
							</tr>
<?php endwhile; ?>
							<tr id="add_info"><td colspan="3"><span class="icon icon_add"><a class="js" href="javascript:;"><?php echo __('ajouter une nouvelle information'); ?></a></span></td></tr>
						</table>
						<p class="field">
							<label for="users_desc_maxlength"><?php echo __('Nombre de caractères maximum du champ "description" :'); ?></label>
							<input value="<?php echo $tpl->getOption('users_desc_maxlength'); ?>" id="users_desc_maxlength" name="users_desc_maxlength" class="text" type="text" maxlength="4" size="5" />
						</p>
					</div>
				</fieldset>
				<br />
				<div class="browse_anchor" id="avatars"></div>
				<fieldset>
					<legend><?php echo __('Avatars'); ?></legend>
					<div class="fielditems">
						<p class="field checkbox">
							<input<?php echo $tpl->getOption('avatars'); ?> id="users_avatars" name="avatars" type="checkbox" />
							<span><label for="users_avatars"><?php echo __('Activer les avatars'); ?></label></span>
						</p>
						<div class="field_second">
							<p class="field">
								<label for="avatars_maxfilesize"><?php echo __('Poids maximum des avatars :'); ?></label>
								<input value="<?php echo $tpl->getOption('avatars_maxfilesize'); ?>" id="avatars_maxfilesize" name="avatars_maxfilesize" type="text" class="text" maxlength="4" size="4" /> <?php echo __('Ko'); ?>
								&nbsp;
								(<?php printf(__('limite serveur : %s'), $tpl->getUploadMaxFilesize()); ?>)
							</p>
							<p class="field">
								<label for="avatars_maxsize"><?php echo __('Dimensions maximum des avatars :'); ?></label>
								<input value="<?php echo $tpl->getOption('avatars_maxsize'); ?>" id="avatars_maxsize" name="avatars_maxsize" type="text" class="text" maxlength="4" size="4" /> <?php echo __('pixels de coté'); ?>
							</p>
						</div>
					</div>
				</fieldset>
				<br />
				<div class="browse_anchor" id="upload-images"></div>
				<fieldset>
					<legend><?php echo __('Envoi d\'images'); ?></legend>
					<div class="fielditems">
						<p class="field checkbox">
							<input<?php echo $tpl->getOption('upload_categories_empty'); ?> id="upload_categories_empty" name="upload_categories_empty" type="checkbox" />
							<span><label for="upload_categories_empty"><?php echo __('Autoriser l\'ajout d\'images dans les albums vides et la création de catégories dans les catégories vides'); ?></label></span>
						</p>
						<p class="field">
							<?php echo __('Limites lors de l\'envoi des images :'); ?>
						</p>
						<div class="field_second">
							<p class="field">
								<label for="upload_maxfilesize"><?php echo __('Poids maximum des images :'); ?></label>
								<input value="<?php echo $tpl->getOption('upload_maxfilesize'); ?>" id="upload_maxfilesize" name="upload_maxfilesize" class="text" type="text" maxlength="5" size="5" /> <?php echo __('Ko'); ?>
								&nbsp;
								(<?php printf(__('limite serveur : %s'), $tpl->getUploadMaxFilesize()); ?>)
							</p>
							<p class="field">
								<?php echo __('Dimensions maximum des images :'); ?>
								<input value="<?php echo $tpl->getOption('upload_maxwidth'); ?>" id="upload_maxwidth" name="upload_maxwidth" type="text" class="text" maxlength="5" size="6" />
								X
								<input value="<?php echo $tpl->getOption('upload_maxheight'); ?>" id="upload_maxheight" name="upload_maxheight" type="text" class="text" maxlength="5" size="6" /> <?php echo __('pixels'); ?>
							</p>
						</div>
						<p class="field">
							<?php echo __('Après réception des images :'); ?>
						</p>
						<div class="field_second">
							<p class="field checkbox">
								<input<?php echo $tpl->getOption('upload_resize'); ?> id="upload_resize" name="upload_resize" type="checkbox" />
								<span><label for="upload_resize"><?php echo __('Redimensionner les images'); ?></label></span>
<?php if ($tpl->disHelp()) : ?>
								<a rel="h_upload_resize" title="<?php echo __('Obtenir de l\'aide sur cette fonction'); ?>" class="help_link help_link_context" href="javascript:;"><img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/help-link.png" alt="<?php echo __('Aide'); ?>" /></a>
<?php endif; ?>
							</p>
							<div class="field_second">
								<p class="field checkbox">
									<?php echo __('Dimensions maximum des images :'); ?>
									<input value="<?php echo $tpl->getOption('upload_resize_maxwidth'); ?>" id="upload_resize_maxwidth" name="upload_resize_maxwidth" type="text" class="text" maxlength="5" size="6" />
									X
									<input value="<?php echo $tpl->getOption('upload_resize_maxheight'); ?>" id="upload_resize_maxheight" name="upload_resize_maxheight" type="text" class="text" maxlength="5" size="6" /> <?php echo __('pixels'); ?>
								</p>
								<p class="field checkbox">
									<label for="upload_resize_quality"><?php echo __('Qualité (entre 0 et 100) :'); ?></label>
									<input value="<?php echo $tpl->getOption('upload_resize_quality'); ?>" id="upload_resize_quality" name="upload_resize_quality" class="text" maxlength="4" type="text" size="4" />
								</p>
							</div>
						</div>
					</div>
				</fieldset>
				<br />
				<div class="browse_anchor" id="locked-albums"></div>
				<fieldset>
					<legend><?php echo __('Albums verrouillés'); ?></legend>
					<div class="fielditems">
						<p class="field">
							<label for="users_locked_albums_access_mode"><?php echo __('Mode d\'accès aux albums verrouillés :'); ?></label>
							<select name="users_locked_albums_access_mode" id="users_locked_albums_access_mode">
								<?php echo $tpl->getOption('users_locked_albums_access_mode'); ?>

							</select>
						</p>
					</div>
				</fieldset>
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
				<input type="submit" class="submit" value="<?php echo __('Enregistrer'); ?>" />
			</div>
		</form>

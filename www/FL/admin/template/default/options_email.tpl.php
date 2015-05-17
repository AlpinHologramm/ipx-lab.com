
<?php include_once(dirname(__FILE__) . '/options_submenu.tpl.php'); ?>

		<div id="tools_browse">
			<div class="browse">
				<select id="functions_list" onchange="window.location.href='#'+this.options[this.selectedIndex].value">
					<option value=""><?php echo __('Parcourir :'); ?></option>
					<option value="auto"><?php echo __('Courriels automatiques'); ?></option>
					<option value="notify_comment_new"><?php echo __('Notifications : nouveau commentaire'); ?></option>
					<option value="notify_comment_follow"><?php echo __('Notifications : suivi de commentaire'); ?></option>
					<option value="notify_guestbook"><?php echo __('Notifications : livre d\'or'); ?></option>
					<option value="notify_images_add"><?php echo __('Notifications : nouvelles images'); ?></option>
					<option value="notify_register"><?php echo __('Notifications : inscription'); ?></option>
				</select>
			</div>
		</div>

		<br />

<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

		<form action="#top" method="post">
			<div>
				<div class="browse_anchor" id="auto"></div>
				<fieldset>
					<legend>
						<span><?php echo __('Courriels automatiques'); ?></span>
<?php if ($tpl->disHelp()) : ?>
						<a rel="mail_auto" title="<?php echo __('Obtenir de l\'aide sur cette fonction'); ?>" class="help_link help_link_context" href="javascript:;"><img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/help-link.png" alt="<?php echo __('Aide'); ?>" /></a>
<?php endif; ?></legend>
					<div class="fielditems">
						<p class="field field_ftw">
							<label for="mail_auto_sender_address"><?php echo __('Adresse de l\'expéditeur :'); ?></label>
							<input value="<?php echo $tpl->getOption('mail_auto_sender_address'); ?>" id="mail_auto_sender_address" name="mail_auto_sender_address" class="text" type="text" maxlength="512" />
						</p>
						<p class="field field_ftw">
							<label for="mail_auto_primary_recipient_address"><?php echo __('Adresse du destinataire principal :'); ?></label>
							<input value="<?php echo $tpl->getOption('mail_auto_primary_recipient_address'); ?>" id="mail_auto_primary_recipient_address" name="mail_auto_primary_recipient_address" class="text" type="text" maxlength="512" />
						</p>
						<p class="field checkbox">
							<input<?php echo $tpl->getOption('mail_auto_bcc'); ?> id="mail_auto_bcc" name="mail_auto_bcc" type="checkbox" />
							<span><label for="mail_auto_bcc"><?php echo __('Mettre toutes les adresses des destinataires dans le champ Bcc '); ?></label></span>
						</p>
					</div>
				</fieldset>
				<br />
				<div class="browse_anchor" id="notify_comment_new"></div>
				<fieldset>
					<legend><?php echo __('Notifications : nouveau commentaire'); ?></legend>
					<div class="fielditems">
						<p class="field">
							<?php echo __('Nouveau commentaire (utilisateur non enregistré)'); ?>

						</p>
						<div class="field_second">
							<p class="field field_ftw">
								<label for="mail_notify_comment_subject"><?php echo __('Sujet :'); ?></label>
								<input value="<?php echo $tpl->getOption('mail_notify_comment_subject'); ?>" id="mail_notify_comment_subject" name="mail_notify_comment_subject" class="text" type="text" maxlength="512" />
							</p>
							<p class="field field_ftw">
								<label for="mail_notify_comment_message"><?php echo __('Message :'); ?></label>
								<textarea class="resizable" rows="7" cols="30" id="mail_notify_comment_message" name="mail_notify_comment_message"><?php echo $tpl->getOption('mail_notify_comment_message'); ?></textarea>
								<span class="template_variable"><strong>{AUTHOR}</strong> : <?php echo __('auteur'); ?></span>
								<span class="template_variable"><strong>{GALLERY_TITLE}</strong> : <?php echo __('titre de la galerie'); ?></span>
								<span class="template_variable"><strong>{GALLERY_URL}</strong> : <?php echo __('URL de la galerie'); ?></span>
								<span class="template_variable"><strong>{IMAGE_URL}</strong> : <?php echo __('URL de l\'image'); ?></span>
								<span class="template_variable"><strong>{EMAIL}</strong> : <?php echo __('courriel'); ?></span>
								<span class="template_variable"><strong>{WEBSITE}</strong> : <?php echo __('site Web'); ?></span>
							</p>
						</div>
						<br />
						<p class="field">
							<?php echo __('Nouveau commentaire (utilisateur enregistré)'); ?>

						</p>
						<div class="field_second">
							<p class="field field_ftw">
								<label for="mail_notify_comment_auth_subject"><?php echo __('Sujet :'); ?></label>
								<input value="<?php echo $tpl->getOption('mail_notify_comment_auth_subject'); ?>" id="mail_notify_comment_auth_subject" name="mail_notify_comment_auth_subject" class="text" type="text" maxlength="512" />
							</p>
							<p class="field field_ftw">
								<label for="mail_notify_comment_auth_message"><?php echo __('Message :'); ?></label>
								<textarea class="resizable" rows="7" cols="30" id="mail_notify_comment_auth_message" name="mail_notify_comment_auth_message"><?php echo $tpl->getOption('mail_notify_comment_auth_message'); ?></textarea>
								<span class="template_variable"><strong>{GALLERY_TITLE}</strong> : <?php echo __('titre de la galerie'); ?></span>
								<span class="template_variable"><strong>{GALLERY_URL}</strong> : <?php echo __('URL de la galerie'); ?></span>
								<span class="template_variable"><strong>{IMAGE_URL}</strong> : <?php echo __('URL de l\'image'); ?></span>
								<span class="template_variable"><strong>{USER_LOGIN}</strong> : <?php echo __('identifiant de l\'utilisateur'); ?></span>
								<span class="template_variable"><strong>{USER_URL}</strong> : <?php echo __('URL du profil de l\'utilisateur'); ?></span>
							</p>
						</div>
						<br />
						<p class="field">
							<?php echo __('Nouveau commentaire en attente de validation (utilisateur non enregistré)'); ?>

						</p>
						<div class="field_second">
							<p class="field field_ftw">
								<label for="mail_notify_comment_pending_subject"><?php echo __('Sujet :'); ?></label>
								<input value="<?php echo $tpl->getOption('mail_notify_comment_pending_subject'); ?>" id="mail_notify_comment_pending_subject" name="mail_notify_comment_pending_subject" class="text" type="text" maxlength="512" />
							</p>
							<p class="field field_ftw">
								<label for="mail_notify_comment_pending_message"><?php echo __('Message :'); ?></label>
								<textarea class="resizable" rows="7" cols="30" id="mail_notify_comment_pending_message" name="mail_notify_comment_pending_message"><?php echo $tpl->getOption('mail_notify_comment_pending_message'); ?></textarea>
								<span class="template_variable"><strong>{AUTHOR}</strong> : <?php echo __('auteur'); ?></span>
								<span class="template_variable"><strong>{GALLERY_TITLE}</strong> : <?php echo __('titre de la galerie'); ?></span>
								<span class="template_variable"><strong>{GALLERY_URL}</strong> : <?php echo __('URL de la galerie'); ?></span>
								<span class="template_variable"><strong>{EMAIL}</strong> : <?php echo __('courriel'); ?></span>
								<span class="template_variable"><strong>{WEBSITE}</strong> : <?php echo __('site Web'); ?></span>
							</p>
						</div>
						<br />
						<p class="field">
							<?php echo __('Nouveau commentaire en attente de validation (utilisateur enregistré)'); ?>

						</p>
						<div class="field_second">
							<p class="field field_ftw">
								<label for="mail_notify_comment_pending_auth_subject"><?php echo __('Sujet :'); ?></label>
								<input value="<?php echo $tpl->getOption('mail_notify_comment_pending_auth_subject'); ?>" id="mail_notify_comment_pending_auth_subject" name="mail_notify_comment_pending_auth_subject" class="text" type="text" maxlength="512" />
							</p>
							<p class="field field_ftw">
								<label for="mail_notify_comment_pending_auth_message"><?php echo __('Message :'); ?></label>
								<textarea class="resizable" rows="7" cols="30" id="mail_notify_comment_pending_auth_message" name="mail_notify_comment_pending_auth_message"><?php echo $tpl->getOption('mail_notify_comment_pending_auth_message'); ?></textarea>
								<span class="template_variable"><strong>{GALLERY_TITLE}</strong> : <?php echo __('titre de la galerie'); ?></span>
								<span class="template_variable"><strong>{GALLERY_URL}</strong> : <?php echo __('URL de la galerie'); ?></span>
								<span class="template_variable"><strong>{USER_LOGIN}</strong> : <?php echo __('identifiant de l\'utilisateur'); ?></span>
								<span class="template_variable"><strong>{USER_URL}</strong> : <?php echo __('URL du profil de l\'utilisateur'); ?></span>
							</p>
						</div>
					</div>
				</fieldset>
				<br />
				<div class="browse_anchor" id="notify_comment_follow"></div>
				<fieldset>
					<legend><?php echo __('Notifications : suivi de commentaire'); ?></legend>
					<div class="fielditems">
						<p class="field">
							<?php echo __('Suivi de commentaire (utilisateur non enregistré)'); ?>

						</p>
						<div class="field_second">
							<p class="field field_ftw">
								<label for="mail_notify_comment_follow_subject"><?php echo __('Sujet :'); ?></label>
								<input value="<?php echo $tpl->getOption('mail_notify_comment_follow_subject'); ?>" id="mail_notify_comment_follow_subject" name="mail_notify_comment_follow_subject" class="text" type="text" maxlength="512" />
							</p>
							<p class="field field_ftw">
								<label for="mail_notify_comment_follow_message"><?php echo __('Message :'); ?></label>
								<textarea class="resizable" rows="7" cols="30" id="mail_notify_comment_follow_message" name="mail_notify_comment_follow_message"><?php echo $tpl->getOption('mail_notify_comment_follow_message'); ?></textarea>
								<span class="template_variable"><strong>{AUTHOR}</strong> : <?php echo __('auteur'); ?></span>
								<span class="template_variable"><strong>{GALLERY_TITLE}</strong> : <?php echo __('titre de la galerie'); ?></span>
								<span class="template_variable"><strong>{GALLERY_URL}</strong> : <?php echo __('URL de la galerie'); ?></span>
								<span class="template_variable"><strong>{IMAGE_URL}</strong> : <?php echo __('URL de l\'image'); ?></span>
								<span class="template_variable"><strong>{EMAIL}</strong> : <?php echo __('courriel'); ?></span>
								<span class="template_variable"><strong>{WEBSITE}</strong> : <?php echo __('site Web'); ?></span>
							</p>
						</div>
						<br />
						<p class="field">
							<?php echo __('Suivi de commentaire (utilisateur enregistré)'); ?>

						</p>
						<div class="field_second">
							<p class="field field_ftw">
								<label for="mail_notify_comment_follow_auth_subject"><?php echo __('Sujet :'); ?></label>
								<input value="<?php echo $tpl->getOption('mail_notify_comment_follow_auth_subject'); ?>" id="mail_notify_comment_follow_auth_subject" name="mail_notify_comment_follow_auth_subject" class="text" type="text" maxlength="512" />
							</p>
							<p class="field field_ftw">
								<label for="mail_notify_comment_follow_auth_message"><?php echo __('Message :'); ?></label>
								<textarea class="resizable" rows="7" cols="30" id="mail_notify_comment_follow_auth_message" name="mail_notify_comment_follow_auth_message"><?php echo $tpl->getOption('mail_notify_comment_follow_auth_message'); ?></textarea>
								<span class="template_variable"><strong>{GALLERY_TITLE}</strong> : <?php echo __('titre de la galerie'); ?></span>
								<span class="template_variable"><strong>{GALLERY_URL}</strong> : <?php echo __('URL de la galerie'); ?></span>
								<span class="template_variable"><strong>{IMAGE_URL}</strong> : <?php echo __('URL de l\'image'); ?></span>
								<span class="template_variable"><strong>{USER_LOGIN}</strong> : <?php echo __('identifiant de l\'utilisateur'); ?></span>
								<span class="template_variable"><strong>{USER_URL}</strong> : <?php echo __('URL du profil de l\'utilisateur'); ?></span>
							</p>
						</div>
					</div>
				</fieldset>
				<br />
				<div class="browse_anchor" id="notify_guestbook"></div>
				<fieldset>
					<legend><?php echo __('Notifications : livre d\'or'); ?></legend>
					<div class="fielditems">
						<p class="field">
							<?php echo __('Nouveau commentaire (utilisateur non enregistré)'); ?>

						</p>
						<div class="field_second">
							<p class="field field_ftw">
								<label for="mail_notify_guestbook_subject"><?php echo __('Sujet :'); ?></label>
								<input value="<?php echo $tpl->getOption('mail_notify_guestbook_subject'); ?>" id="mail_notify_guestbook_subject" name="mail_notify_guestbook_subject" class="text" type="text" maxlength="512" />
							</p>
							<p class="field field_ftw">
								<label for="mail_notify_guestbook_message"><?php echo __('Message :'); ?></label>
								<textarea class="resizable" rows="7" cols="30" id="mail_notify_guestbook_message" name="mail_notify_guestbook_message"><?php echo $tpl->getOption('mail_notify_guestbook_message'); ?></textarea>
								<span class="template_variable"><strong>{AUTHOR}</strong> : <?php echo __('auteur'); ?></span>
								<span class="template_variable"><strong>{GALLERY_TITLE}</strong> : <?php echo __('titre de la galerie'); ?></span>
								<span class="template_variable"><strong>{GALLERY_URL}</strong> : <?php echo __('URL de la galerie'); ?></span>
								<span class="template_variable"><strong>{EMAIL}</strong> : <?php echo __('courriel'); ?></span>
								<span class="template_variable"><strong>{WEBSITE}</strong> : <?php echo __('site Web'); ?></span>
							</p>
						</div>
						<br />
						<p class="field">
							<?php echo __('Nouveau commentaire (utilisateur enregistré)'); ?>

						</p>
						<div class="field_second">
							<p class="field field_ftw">
								<label for="mail_notify_guestbook_auth_subject"><?php echo __('Sujet :'); ?></label>
								<input value="<?php echo $tpl->getOption('mail_notify_guestbook_auth_subject'); ?>" id="mail_notify_guestbook_auth_subject" name="mail_notify_guestbook_auth_subject" class="text" type="text" maxlength="512" />
							</p>
							<p class="field field_ftw">
								<label for="mail_notify_guestbook_auth_message"><?php echo __('Message :'); ?></label>
								<textarea class="resizable" rows="7" cols="30" id="mail_notify_guestbook_auth_message" name="mail_notify_guestbook_auth_message"><?php echo $tpl->getOption('mail_notify_guestbook_auth_message'); ?></textarea>
								<span class="template_variable"><strong>{GALLERY_TITLE}</strong> : <?php echo __('titre de la galerie'); ?></span>
								<span class="template_variable"><strong>{GALLERY_URL}</strong> : <?php echo __('URL de la galerie'); ?></span>
								<span class="template_variable"><strong>{USER_LOGIN}</strong> : <?php echo __('identifiant de l\'utilisateur'); ?></span>
								<span class="template_variable"><strong>{USER_URL}</strong> : <?php echo __('URL du profil de l\'utilisateur'); ?></span>
							</p>
						</div>
						<br />
						<p class="field">
							<?php echo __('Nouveau commentaire en attente de validation (utilisateur non enregistré)'); ?>

						</p>
						<div class="field_second">
							<p class="field field_ftw">
								<label for="mail_notify_guestbook_pending_subject"><?php echo __('Sujet :'); ?></label>
								<input value="<?php echo $tpl->getOption('mail_notify_guestbook_pending_subject'); ?>" id="mail_notify_guestbook_pending_subject" name="mail_notify_guestbook_pending_subject" class="text" type="text" maxlength="512" />
							</p>
							<p class="field field_ftw">
								<label for="mail_notify_guestbook_pending_message"><?php echo __('Message :'); ?></label>
								<textarea class="resizable" rows="7" cols="30" id="mail_notify_guestbook_pending_message" name="mail_notify_guestbook_pending_message"><?php echo $tpl->getOption('mail_notify_guestbook_pending_message'); ?></textarea>
								<span class="template_variable"><strong>{AUTHOR}</strong> : <?php echo __('auteur'); ?></span>
								<span class="template_variable"><strong>{GALLERY_TITLE}</strong> : <?php echo __('titre de la galerie'); ?></span>
								<span class="template_variable"><strong>{GALLERY_URL}</strong> : <?php echo __('URL de la galerie'); ?></span>
								<span class="template_variable"><strong>{EMAIL}</strong> : <?php echo __('courriel'); ?></span>
								<span class="template_variable"><strong>{WEBSITE}</strong> : <?php echo __('site Web'); ?></span>
							</p>
						</div>
						<br />
						<p class="field">
							<?php echo __('Nouveau commentaire en attente de validation (utilisateur enregistré)'); ?>

						</p>
						<div class="field_second">
							<p class="field field_ftw">
								<label for="mail_notify_guestbook_pending_auth_subject"><?php echo __('Sujet :'); ?></label>
								<input value="<?php echo $tpl->getOption('mail_notify_guestbook_pending_auth_subject'); ?>" id="mail_notify_guestbook_pending_auth_subject" name="mail_notify_guestbook_pending_auth_subject" class="text" type="text" maxlength="512" />
							</p>
							<p class="field field_ftw">
								<label for="mail_notify_guestbook_pending_auth_message"><?php echo __('Message :'); ?></label>
								<textarea class="resizable" rows="7" cols="30" id="mail_notify_guestbook_pending_auth_message" name="mail_notify_guestbook_pending_auth_message"><?php echo $tpl->getOption('mail_notify_guestbook_pending_auth_message'); ?></textarea>
								<span class="template_variable"><strong>{GALLERY_TITLE}</strong> : <?php echo __('titre de la galerie'); ?></span>
								<span class="template_variable"><strong>{GALLERY_URL}</strong> : <?php echo __('URL de la galerie'); ?></span>
								<span class="template_variable"><strong>{USER_LOGIN}</strong> : <?php echo __('identifiant de l\'utilisateur'); ?></span>
								<span class="template_variable"><strong>{USER_URL}</strong> : <?php echo __('URL du profil de l\'utilisateur'); ?></span>
							</p>
						</div>
					</div>
				</fieldset>
				<br />
				<div class="browse_anchor" id="notify_images_add"></div>
				<fieldset>
					<legend><?php echo __('Notifications : nouvelles images'); ?></legend>
					<div class="fielditems">
						<p class="field">
							<?php echo __('Nouvelles images'); ?>

						</p>
						<div class="field_second">
							<p class="field field_ftw">
								<label for="mail_notify_images_subject"><?php echo __('Sujet :'); ?></label>
								<input value="<?php echo $tpl->getOption('mail_notify_images_subject'); ?>" id="mail_notify_images_subject" name="mail_notify_images_subject" class="text" type="text" maxlength="512" />
							</p>
							<p class="field field_ftw">
								<label for="mail_notify_images_message"><?php echo __('Message :'); ?></label>
								<textarea class="resizable" rows="7" cols="30" id="mail_notify_images_message" name="mail_notify_images_message"><?php echo $tpl->getOption('mail_notify_images_message'); ?></textarea>
								<span class="template_variable"><strong>{GALLERY_TITLE}</strong> : <?php echo __('titre de la galerie'); ?></span>
								<span class="template_variable"><strong>{GALLERY_URL}</strong> : <?php echo __('URL de la galerie'); ?></span>
								<span class="template_variable"><strong>{USER_LOGIN}</strong> : <?php echo __('identifiant de l\'utilisateur'); ?></span>
								<span class="template_variable"><strong>{USER_URL}</strong> : <?php echo __('URL du profil de l\'utilisateur'); ?></span>
							</p>
						</div>
						<br />
						<p class="field">
							<?php echo __('Nouvelles images en attente de validation'); ?>

						</p>
						<div class="field_second">
							<p class="field field_ftw">
								<label for="mail_notify_images_pending_subject"><?php echo __('Sujet :'); ?></label>
								<input value="<?php echo $tpl->getOption('mail_notify_images_pending_subject'); ?>" id="mail_notify_images_pending_subject" name="mail_notify_images_pending_subject" class="text" type="text" maxlength="512" />
							</p>
							<p class="field field_ftw">
								<label for="mail_notify_images_pending_message"><?php echo __('Message :'); ?></label>
								<textarea class="resizable" rows="7" cols="30" id="mail_notify_images_pending_message" name="mail_notify_images_pending_message"><?php echo $tpl->getOption('mail_notify_images_pending_message'); ?></textarea>
								<span class="template_variable"><strong>{GALLERY_TITLE}</strong> : <?php echo __('titre de la galerie'); ?></span>
								<span class="template_variable"><strong>{GALLERY_URL}</strong> : <?php echo __('URL de la galerie'); ?></span>
								<span class="template_variable"><strong>{USER_LOGIN}</strong> : <?php echo __('identifiant de l\'utilisateur'); ?></span>
								<span class="template_variable"><strong>{USER_URL}</strong> : <?php echo __('URL du profil de l\'utilisateur'); ?></span>
							</p>
						</div>
					</div>
				</fieldset>
				<br />
				<div class="browse_anchor" id="notify_register"></div>
				<fieldset>
					<legend><?php echo __('Notifications : inscription'); ?></legend>
					<div class="fielditems">
						<p class="field">
							<?php echo __('Nouvelle inscription'); ?>

						</p>
						<div class="field_second">
							<p class="field field_ftw">
								<label for="mail_notify_inscription_subject"><?php echo __('Sujet :'); ?></label>
								<input value="<?php echo $tpl->getOption('mail_notify_inscription_subject'); ?>" id="mail_notify_inscription_subject" name="mail_notify_inscription_subject" class="text" type="text" maxlength="512" />
							</p>
							<p class="field field_ftw">
								<label for="mail_notify_inscription_message"><?php echo __('Message :'); ?></label>
								<textarea class="resizable" rows="7" cols="30" id="mail_notify_inscription_message" name="mail_notify_inscription_message"><?php echo $tpl->getOption('mail_notify_inscription_message'); ?></textarea>
								<span class="template_variable"><strong>{GALLERY_TITLE}</strong> : <?php echo __('titre de la galerie'); ?></span>
								<span class="template_variable"><strong>{GALLERY_URL}</strong> : <?php echo __('URL de la galerie'); ?></span>
								<span class="template_variable"><strong>{USER_LOGIN}</strong> : <?php echo __('identifiant de l\'utilisateur'); ?></span>
								<span class="template_variable"><strong>{USER_URL}</strong> : <?php echo __('URL du profil de l\'utilisateur'); ?></span>
							</p>
						</div>
						<br />
						<p class="field">
							<?php echo __('Nouvelle inscription en attente de validation'); ?>

						</p>
						<div class="field_second">
							<p class="field field_ftw">
								<label for="mail_notify_inscription_pending_subject"><?php echo __('Sujet :'); ?></label>
								<input value="<?php echo $tpl->getOption('mail_notify_inscription_pending_subject'); ?>" id="mail_notify_inscription_pending_subject" name="mail_notify_inscription_pending_subject" class="text" type="text" maxlength="512" />
							</p>
							<p class="field field_ftw">
								<label for="mail_notify_inscription_pending_message"><?php echo __('Message :'); ?></label>
								<textarea class="resizable" rows="7" cols="30" id="mail_notify_inscription_pending_message" name="mail_notify_inscription_pending_message"><?php echo $tpl->getOption('mail_notify_inscription_pending_message'); ?></textarea>
								<span class="template_variable"><strong>{GALLERY_TITLE}</strong> : <?php echo __('titre de la galerie'); ?></span>
								<span class="template_variable"><strong>{GALLERY_URL}</strong> : <?php echo __('URL de la galerie'); ?></span>
								<span class="template_variable"><strong>{USER_LOGIN}</strong> : <?php echo __('identifiant de l\'utilisateur'); ?></span>
							</p>
						</div>
					</div>
				</fieldset>
<?php if ($tpl->disAdmin('password_protect')) : ?>
				<br />
				<fieldset>
					<p id="current_pwd_required" class="field field_ftw">
						<label for="current_pwd"><strong><?php echo __('Votre mot de passe :'); ?></strong></label>
						<input maxlength="512" id="current_pwd" name="current_pwd" type="password" class="text" />
					</p>
				</fieldset>
<?php endif; ?>
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
				<input type="submit" class="submit" value="<?php echo __('Enregistrer'); ?>" />
			</div>
		</form>
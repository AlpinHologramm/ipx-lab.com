<?php if ($tpl->disUser('modif')) : ?>
				<fieldset>
					<legend><?php echo __('Général'); ?></legend>
					<p class="field checkbox">
						<label for="status"><?php echo __('Statut :'); ?></label>
						<select id="status" name="status">
<?php echo $tpl->getProfile('status_list'); ?>

						</select>
					</p>
					<p class="field checkbox">
						<label for="group"><?php echo __('Groupe :'); ?></label>
						<select id="group" name="group">
<?php echo $tpl->getProfile('groups_list'); ?>

						</select>
					</p>
				</fieldset>
				<br />
<?php endif; ?>
				<fieldset>
					<legend><?php echo __('Informations de connexion'); ?></legend>
					<p class="field field_ftw">
						<label for="login"><?php echo __('Nom d\'utilisateur :'); ?></label>
						<input value="<?php echo $tpl->getProfile('login'); ?>" maxlength="64" id="login" name="login" type="text" class="text<?php if ($_GET['section'] == 'new-user') : ?> onload_focus<?php endif; ?>" />
					</p>
					<p class="field field_ftw">
						<label for="pwd"><?php printf(($_GET['section'] == 'user') ? __('Nouveau mot de passe (%s caractères minimum) :') : __('Mot de passe (%s caractères minimum) :'), $tpl->getProfile('password_minlength')); ?></label>
						<input maxlength="512" id="pwd" name="pwd" type="password" class="text" />
					</p>
					<p class="field field_ftw">
						<label for="pwd_confirm"><?php echo __('Confirmation du mot de passe :'); ?></label>
						<input maxlength="512" id="pwd_confirm" name="pwd_confirm" type="password" class="text" />
					</p>
				</fieldset>
				<br />
				<fieldset>
					<legend><?php echo __('Informations personnelles'); ?></legend>
					<p class="field field_ftw">
						<label for="name"><?php echo __('Nom :'); ?></label>
						<input value="<?php echo $tpl->getProfile('name'); ?>" maxlength="255" id="name" name="name" type="text" class="text" />
					</p>
					<p class="field field_ftw">
						<label for="firstname"><?php echo __('Prénom :'); ?></label>
						<input value="<?php echo $tpl->getProfile('firstname'); ?>" maxlength="255" id="firstname" name="firstname" type="text" class="text" />
					</p>
					<p class="field field_ftw">
						<label for="sex"><?php echo __('Sexe :'); ?></label>
						<select name="sex" id="sex">
							<?php echo $tpl->getProfile('sex'); ?>

						</select>
					</p>
					<p class="field">
						<label><?php echo __('Date de naissance :'); ?></label>
						<br />
						<?php echo $tpl->getProfile('birthdate'); ?>

					</p>
					<p class="field field_ftw">
						<label for="loc"><?php echo __('Localisation :'); ?></label>
						<input value="<?php echo $tpl->getProfile('loc'); ?>" maxlength="255" id="loc" name="loc" type="text" class="text" />
					</p>
					<p class="field field_ftw">
						<label for="email"><?php echo __('Courriel :'); ?></label>
						<input value="<?php echo $tpl->getProfile('email'); ?>" maxlength="255" id="email" name="email" type="text" class="text" />
					</p>
					<p class="field field_ftw">
						<label for="website"><?php echo __('Site Web :'); ?></label>
						<input value="<?php echo $tpl->getProfile('website'); ?>" maxlength="255" id="website" name="website" type="text" class="text" />
					</p>
					<p class="field field_ftw">
						<label for="desc"><?php printf(__('Description (%s caractères maximum) :'), $tpl->getProfile('desc_maxlength')); ?></label>
						<textarea class="resizable" rows="6" cols="50" id="desc" name="desc"><?php echo $tpl->getProfile('desc'); ?></textarea>
					</p>
				</fieldset>
				<br />
<?php if ($tpl->disProfilePerso()) : ?>
				<fieldset>
					<legend><?php echo __('Informations complémentaires'); ?></legend>
<?php while ($tpl->nextProfilePerso()) : ?>
					<p class="field field_ftw">
						<label for="<?php echo $tpl->getProfilePerso('id'); ?>"><?php echo $tpl->getProfilePerso('name'); ?></label>
						<input value="<?php echo $tpl->getProfilePerso('value'); ?>" maxlength="255" id="<?php echo $tpl->getProfilePerso('id'); ?>" name="<?php echo $tpl->getProfilePerso('id'); ?>" type="text" class="text" />
					</p>
<?php endwhile; ?>
				</fieldset>
				<br />
<?php endif; ?>
				<fieldset>
					<legend><?php echo __('Options'); ?></legend>
					<p class="field field_ftw">
						<label for="lang"><?php echo __('Langue :'); ?></label>
						<select name="lang" id="lang">
							<?php echo $tpl->getProfile('lang'); ?>

						</select>
					</p>
					<p class="field field_ftw">
						<label for="lang"><?php echo __('Fuseau horaire :'); ?></label>
						<select name="tz" id="tz">
							<?php echo $tpl->getProfile('tz'); ?>

						</select>
					</p>
					<p class="field">
						<?php echo __('Notification par courriel pour :'); ?>
					</p>
					<div class="field_second">
<?php if ($tpl->disProfile('alert_inscriptions')) : ?>
						<p class="field checkbox">
							<input<?php echo $tpl->getProfile('alert_inscriptions'); ?> type="checkbox" name="alert[0]" id="alert_inscriptions" />
							<span><label for="alert_inscriptions"><?php echo __('Nouvelles inscriptions'); ?></label></span>
						</p>
<?php endif; ?>
<?php if ($tpl->disProfile('alert_comments')) : ?>
						<p class="field checkbox">
							<input<?php echo $tpl->getProfile('alert_comments'); ?> type="checkbox" name="alert[1]" id="alert_comments" />
							<span><label for="alert_comments"><?php echo __('Nouveaux commentaires'); ?></label></span>
						</p>
						<p class="field checkbox">
							<input<?php echo $tpl->getProfile('alert_comments_pending'); ?> type="checkbox" name="alert[2]" id="alert_comments_pending" />
							<span><label for="alert_comments_pending"><?php echo __('Commentaires en attente de validation'); ?></label></span>
						</p>
<?php endif; ?>
						<p class="field checkbox">
							<input<?php echo $tpl->getProfile('alert_comments_follow'); ?> type="checkbox" name="alert[5]" id="alert_comments_follow" />
							<span><label for="alert_comments_follow">
								<?php echo __('Nouveaux commentaires sur les images où j\'ai posté'); ?>
							</label></span>
						</p>
						<p class="field checkbox">
							<input<?php echo $tpl->getProfile('alert_images'); ?> type="checkbox" name="alert[3]" id="alert_images" />
							<span><label for="alert_images"><?php echo __('Nouvelles images'); ?></label></span>
						</p>
<?php if ($tpl->disProfile('alert_images_pending')) : ?>
						<p class="field checkbox">
							<input<?php echo $tpl->getProfile('alert_images_pending'); ?> type="checkbox" name="alert[4]" id="alert_images_pending" />
							<span><label for="alert_images_pending"><?php echo __('Images en attente de validation'); ?></label></span>
						</p>
<?php endif; ?>
					</div>
<?php if ($tpl->disUser('admin')) : ?>
					<p class="field checkbox">
						<input<?php echo $tpl->getProfile('nohits'); ?> type="checkbox" name="nohits" id="nohits" />
						<span><label for="nohits"><?php echo __('Ne pas comptabiliser mes visites'); ?></label></span>
<?php if ($tpl->disHelp()) : ?>
						<a rel="h_nohits" title="<?php echo __('Obtenir de l\'aide sur cette fonction'); ?>" class="help_link help_link_context" href="javascript:;"><img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/help-link.png" alt="<?php echo __('Aide'); ?>" /></a>
<?php endif; ?>
					</p>
<?php endif; ?>
				</fieldset>
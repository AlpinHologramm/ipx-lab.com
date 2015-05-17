		<div class="box">
<?php if ($tpl->disProfile('field_error')) : ?>
			<script type="text/javascript">var field_error = '<?php echo $tpl->getProfile('field_error'); ?>';</script>
<?php endif; ?>
			<table>
				<tr>
					<td class="box_title" colspan="<?php if ($tpl->disGallery('avatars')) : ?>2<?php else : ?>1<?php endif; ?>">
						<div>
							<h2><?php echo $tpl->getProfile('login'); ?></h2>
<?php if ($tpl->disPerm('members_list')) : ?>
							<span>(<a href="<?php echo $tpl->getProfile('group_link'); ?>"><?php echo $tpl->getProfile('group_title'); ?></a>)</span>
<?php endif; ?>
						</div>
					</td>
				</tr>
				<tr>
<?php if ($tpl->disGallery('avatars')) : ?>
					<td class="box_avatar">
						<img <?php echo $tpl->getProfile('avatar_size'); ?> alt="<?php printf(__('Avatar de %s'), $tpl->getProfile('login')); ?>" src="<?php echo $tpl->getProfile('avatar_src'); ?>" />
					</td>
<?php endif; ?>
					<td class="box_edit">
						<form action="" method="post">
							<div>
<?php if ($tpl->disReport()) : ?>
								<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

<?php endif; ?>
								<p style="display:none" class="field field_ftw">
									<label for="email">Email :</label>
									<input value="" maxlength="255" class="text" id="email" name="f_email" type="text" />
								</p>
<?php if ($tpl->disProfile('infos')) : ?>
								<fieldset class="box_infos">
									<legend><?php echo __('Informations'); ?></legend>
									<div class="fielditems">
<?php if ($tpl->disProfile('name')) : ?>
										<p class="field field_ftw">
											<?php if ($tpl->disProfile('required_name')) : ?><span class="required">*</span><?php endif; ?>
											<label for="f_name"><?php echo __('Nom :'); ?></label>
											<input maxlength="255" id="f_name" name="name" type="text" class="text" value="<?php echo $tpl->getProfile('name'); ?>" />
										</p>
<?php endif; ?>
<?php if ($tpl->disProfile('firstname')) : ?>
										<p class="field field_ftw">
											<?php if ($tpl->disProfile('required_firstname')) : ?><span class="required">*</span><?php endif; ?>
											<label for="f_firstname"><?php echo __('Prénom :'); ?></label>
											<input maxlength="255" id="f_firstname" name="firstname" type="text" class="text" value="<?php echo $tpl->getProfile('firstname'); ?>" />
										</p>
<?php endif; ?>
<?php if ($tpl->disProfile('sex')) : ?>
										<p class="field field_ftw">
											<?php if ($tpl->disProfile('required_sex')) : ?><span class="required">*</span><?php endif; ?>
											<label for="f_sex"><?php echo __('Sexe :'); ?></label>
											<select name="sex" id="f_sex">
												<?php echo $tpl->getProfile('sex'); ?>

											</select>
										</p>
<?php endif; ?>
<?php if ($tpl->disProfile('birthdate')) : ?>
										<p class="field field_selects">
											<?php if ($tpl->disProfile('required_birthdate')) : ?><span class="required">*</span><?php endif; ?>
											<label id="f_birthdate"><?php echo __('Date de naissance :'); ?></label>
											<?php echo $tpl->getProfile('birthdate'); ?>

											<a id="birthdate_reset" href="javascript:;"><?php echo __('effacer'); ?></a>
										</p>
<?php endif; ?>
<?php if ($tpl->disProfile('email')) : ?>
										<p class="field field_ftw">
											<?php if ($tpl->disProfile('required_email')) : ?><span class="required">*</span><?php endif; ?>
											<label for="f_email"><?php echo __('Courriel (ne sera pas publié) :'); ?></label>
											<input maxlength="255" id="f_email" name="email" type="text" class="text" value="<?php echo $tpl->getProfile('email'); ?>" />
										</p>
<?php endif; ?>
<?php if ($tpl->disProfile('website')) : ?>
										<p class="field field_ftw">
											<?php if ($tpl->disProfile('required_website')) : ?><span class="required">*</span><?php endif; ?>
											<label for="f_website"><?php echo __('Site Web :'); ?></label>
											<input maxlength="255" id="f_website" name="website" type="text" class="text" value="<?php echo $tpl->getProfile('website'); ?>" />
										</p>
<?php endif; ?>
<?php if ($tpl->disProfile('loc')) : ?>
										<p class="field field_ftw">
											<?php if ($tpl->disProfile('required_loc')) : ?><span class="required">*</span><?php endif; ?>
											<label for="f_loc"><?php echo __('Localisation :'); ?></label>
											<input maxlength="255" id="f_loc" name="loc" type="text" class="text" value="<?php echo $tpl->getProfile('loc'); ?>" />
										</p>
<?php endif; ?>
<?php if ($tpl->disProfile('desc')) : ?>
										<p class="field field_ftw">
											<?php if ($tpl->disProfile('required_desc')) : ?><span class="required">*</span><?php endif; ?>
											<label for="f_desc"><?php printf(__('Description (%s caractères maximum) :'), $tpl->getProfile('desc_maxlength')); ?></label>
											<textarea rows="4" cols="25" id="f_desc" name="desc"><?php echo $tpl->getProfile('desc'); ?></textarea>
										</p>
<?php endif; ?>
<?php if ($tpl->disProfilePerso()) : ?>
<?php while ($tpl->nextProfilePerso()) : ?>
										<p class="field field_ftw">
											<?php if ($tpl->disProfilePerso('required')) : ?><span class="required">*</span><?php endif; ?>
											<label for="f_<?php echo $tpl->getProfilePerso('id'); ?>"><?php echo $tpl->getProfilePerso('name'); ?></label>
											<input value="<?php echo $tpl->getProfilePerso('value'); ?>" maxlength="255" id="f_<?php echo $tpl->getProfilePerso('id'); ?>" name="<?php echo $tpl->getProfilePerso('id'); ?>" type="text" class="text" />
										</p>
<?php endwhile; ?>
<?php endif; ?>
									</div>
<?php if ($tpl->disProfile('required_fields')) : ?>
									<p class="message message_info"><?php echo __('Les champs marqués d\'un astérisque sont obligatoires.'); ?></p>
<?php endif; ?>
								</fieldset>
								<br />
<?php endif; ?>
								<fieldset class="box_password">
									<legend><?php echo __('Mot de passe'); ?></legend>
									<div class="fielditems">
										<p class="field field_ftw">
											<label for="f_pwd"><?php printf(__('Nouveau mot de passe (%s caractères minimum) :'), $tpl->getProfile('password_minlength')); ?></label>
											<input id="f_pwd" name="pwd" type="password" class="text" value="" />
										</p>
										<p class="field field_ftw">
											<label for="f_pwd_confirm"><?php echo __('Confirmez le mot de passe :'); ?></label>
											<input id="f_pwd_confirm" name="pwd_confirm" type="password" class="text" value="" />
										</p>
									</div>
								</fieldset>
								<br />
								<fieldset class="box_options">
									<legend><?php echo __('Options'); ?></legend>
									<div class="fielditems">
										<p class="field field_ftw">
											<label for="lang"><?php echo __('Langue :'); ?></label>
											<select name="lang" id="lang">
												<?php echo $tpl->getProfile('lang'); ?>

											</select>
										</p>
										<p class="field field_ftw">
											<label for="tz"><?php echo __('Fuseau horaire :'); ?></label>
											<select name="tz" id="tz">
												<?php echo $tpl->getProfile('tz'); ?>

											</select>
										</p>
<?php if ($tpl->disProfile('alert_inscriptions') || $tpl->disProfile('alert_inscriptions') || $tpl->disProfile('alert_comments') || $tpl->disProfile('alert_images') || $tpl->disProfile('alert_images_pending') || $tpl->disProfile('watermark')) : ?>
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
<?php if ($tpl->disProfile('alert_comments_follow')) : ?>
											<p class="field checkbox">
												<input<?php echo $tpl->getProfile('alert_comments_follow'); ?> type="checkbox" name="alert[5]" id="alert_comments_follow" />
												<span><label for="alert_comments_follow"><?php echo __('Nouveaux commentaires sur les images où j\'ai posté'); ?></label></span>
											</p>
<?php endif; ?>
<?php if ($tpl->disProfile('alert_images')) : ?>
											<p class="field checkbox">
												<input<?php echo $tpl->getProfile('alert_images'); ?> id="alert_images" name="alert[3]" type="checkbox" />
												<span><label for="alert_images"><?php echo __('Nouvelles images'); ?></label></span>
											</p>
<?php endif; ?>
<?php if ($tpl->disProfile('alert_images_pending')) : ?>
											<p class="field checkbox">
												<input<?php echo $tpl->getProfile('alert_images_pending'); ?> id="alert_images_pending" name="alert[4]" type="checkbox" />
												<span><label for="alert_images_pending"><?php echo __('Images en attente de validation'); ?></label></span>
											</p>
<?php endif; ?>
										</div>
<?php if ($tpl->disAuthUser('admin')) : ?>
										<p class="field checkbox">
											<input<?php echo $tpl->getProfile('nohits'); ?> type="checkbox" name="nohits" id="nohits" />
											<span><label for="nohits"><?php echo __('Ne pas comptabiliser mes visites'); ?></label></span>
										</p>
<?php endif; ?>
<?php endif; ?>
									</div>
								</fieldset>
								<br />
								<fieldset class="box_current_password">
									<legend><?php echo __('Votre mot de passe actuel'); ?></legend>
									<div class="fielditems">
										<p class="field field_ftw">
											<input id="f_current_pwd" name="current_pwd" type="password" class="text" value="" />
										</p>
									</div>
								</fieldset>
								<input name="anticsrf" type="hidden" value="<?php echo $tpl->getGallery('anticsrf'); ?>" />
								<input type="submit" class="submit" value="<?php echo __('Enregistrer'); ?>" />
							</div>
						</form>
					</td>
				</tr>
			</table>
		</div>
<?php include(dirname(__FILE__) . '/user_menu.tpl.php'); ?>

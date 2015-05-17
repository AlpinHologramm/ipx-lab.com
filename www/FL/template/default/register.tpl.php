		<div class="box box_notools">
<?php if ($tpl->disRegister('field_error')) : ?>
			<script type="text/javascript">var field_error = '<?php echo $tpl->getRegister('field_error'); ?>';</script>
<?php endif; ?>
			<table>
				<tr><td class="box_title"><div><h2><?php echo ucfirst(__('créer un compte')); ?></h2></div></td></tr>
<?php if ($tpl->disRegister()) : ?>
				<tr><td class="box_success"><?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?></td></tr>
<?php else : ?>
				<tr>
					<td class="box_edit">
						<form action="" method="post">
							<div>
<?php if ($tpl->disReport() && !$tpl->disRegister()) : ?>
								<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

<?php endif; ?>
								<fieldset class="box_password">
									<legend><?php echo __('Informations de connexion'); ?></legend>
									<div class="fielditems">
										<p style="display:none" class="field field_ftw">
											<label for="email">Email :</label>
											<input value="" maxlength="255" class="text" id="email" name="f_email" type="text" />
										</p>
										<p class="field field_ftw">
											<span class="required">*</span>
											<label for="f_login"><?php echo __('Nom d\'utilisateur :'); ?></label>
											<input maxlength="255" id="f_login" name="login" type="text" class="<?php if (empty($_POST)) : ?>focus <?php endif; ?>text" value="<?php echo $tpl->getRegister('login'); ?>" />
										</p>
										<p class="field field_ftw">
											<span class="required">*</span>
											<label for="f_pwd"><?php printf(__('Mot de passe (%s caractères minimum) :'), $tpl->getRegister('password_minlength')); ?></label>
											<input maxlength="1024" id="f_pwd" name="pwd" type="password" class="text" />
										</p>
										<p class="field field_ftw">
											<span class="required">*</span>
											<label for="f_pwd_confirm"><?php echo __('Confirmez le mot de passe :'); ?></label>
											<input maxlength="1024" id="f_pwd_confirm" name="pwd_confirm" type="password" class="text" />
										</p>
									</div>
								</fieldset>
<?php if ($tpl->disRegister('infos')) : ?>
								<br />
								<fieldset class="box_perso">
									<legend><?php echo __('Informations personnelles'); ?></legend>
									<div class="fielditems">
<?php if ($tpl->disRegister('name')) : ?>
										<p class="field field_ftw">
											<?php if ($tpl->disRegister('required_name')) : ?><span class="required">*</span><?php endif; ?>
											<label for="f_name"><?php echo __('Nom :'); ?></label>
											<input maxlength="255" id="f_name" name="name" type="text" class="text" value="<?php echo $tpl->getRegister('name'); ?>" />
										</p>
<?php endif; ?>
<?php if ($tpl->disRegister('firstname')) : ?>
										<p class="field field_ftw">
											<?php if ($tpl->disRegister('required_firstname')) : ?><span class="required">*</span><?php endif; ?>
											<label for="f_firstname"><?php echo __('Prénom :'); ?></label>
											<input maxlength="255" id="f_firstname" name="firstname" type="text" class="text" value="<?php echo $tpl->getRegister('firstname'); ?>" />
										</p>
<?php endif; ?>
<?php if ($tpl->disRegister('sex')) : ?>
										<p class="field field_ftw">
											<?php if ($tpl->disRegister('required_sex')) : ?><span class="required">*</span><?php endif; ?>
											<label for="f_sex"><?php echo __('Sexe :'); ?></label>
											<select name="sex" id="f_sex">
												<?php echo $tpl->getRegister('sex'); ?>

											</select>
										</p>
<?php endif; ?>
<?php if ($tpl->disRegister('birthdate')) : ?>
										<p class="field field_selects">
											<?php if ($tpl->disRegister('required_birthdate')) : ?><span class="required">*</span><?php endif; ?>
											<label id="f_birthdate"><?php echo __('Date de naissance :'); ?></label>
											<?php echo $tpl->getRegister('birthdate'); ?>

											<a id="birthdate_reset" href="javascript:;"><?php echo __('effacer'); ?></a>
										</p>
<?php endif; ?>

<?php if ($tpl->disRegister('email')) : ?>
										<p class="field field_ftw">
											<?php if ($tpl->disRegister('required_email')) : ?><span class="required">*</span><?php endif; ?>
											<label for="f_email"><?php echo __('Courriel (ne sera pas publié) :'); ?></label>
											<input maxlength="255" id="f_email" name="email" type="text" class="text" value="<?php echo $tpl->getRegister('email'); ?>" />
										</p>
<?php endif; ?>
<?php if ($tpl->disRegister('website')) : ?>
										<p class="field field_ftw">
											<?php if ($tpl->disRegister('required_website')) : ?><span class="required">*</span><?php endif; ?>
											<label for="f_website"><?php echo __('Site Web :'); ?></label>
											<input maxlength="255" id="f_website" name="website" type="text" class="text" value="<?php echo $tpl->getRegister('website'); ?>" />
										</p>
<?php endif; ?>
<?php if ($tpl->disRegister('loc')) : ?>
										<p class="field field_ftw">
											<?php if ($tpl->disRegister('required_loc')) : ?><span class="required">*</span><?php endif; ?>
											<label for="f_loc"><?php echo __('Localisation :'); ?></label>
											<input maxlength="255" id="f_loc" name="loc" type="text" class="text" value="<?php echo $tpl->getRegister('loc'); ?>" />
										</p>
<?php endif; ?>
<?php if ($tpl->disRegister('desc')) : ?>
										<p class="field field_ftw">
											<?php if ($tpl->disRegister('required_desc')) : ?><span class="required">*</span><?php endif; ?>
											<label for="f_desc"><?php printf(__('Description (%s caractères maximum) :'), $tpl->getRegister('desc_maxlength')); ?></label>
											<textarea rows="4" cols="25" id="f_desc" name="desc"><?php echo $tpl->getRegister('desc'); ?></textarea>
										</p>
<?php endif; ?>
<?php if ($tpl->disRegisterPerso()) : ?>
<?php while ($tpl->nextRegisterPerso()) : ?>
										<p class="field field_ftw">
											<?php if ($tpl->disRegisterPerso('required')) : ?><span class="required">*</span><?php endif; ?>
											<label for="f_<?php echo $tpl->getRegisterPerso('id'); ?>"><?php echo $tpl->getRegisterPerso('name'); ?></label>
											<input value="<?php echo $tpl->getRegisterPerso('value'); ?>" maxlength="255" id="f_<?php echo $tpl->getRegisterPerso('id'); ?>" name="<?php echo $tpl->getRegisterPerso('id'); ?>" type="text" class="text" />
										</p>
<?php endwhile; ?>
<?php endif; ?>
									</div>
								</fieldset>
<?php endif; ?>
								<br />
								<fieldset class="box_options">
									<legend><?php echo __('Options'); ?></legend>
									<div class="fielditems">
										<p class="field field_ftw">
											<label for="lang"><?php echo __('Langue :'); ?></label>
											<select name="lang" id="lang">
												<?php echo $tpl->getRegister('lang'); ?>

											</select>
										</p>
										<p class="field field_ftw">
											<label for="tz"><?php echo __('Fuseau horaire :'); ?></label>
											<select name="tz" id="tz">
												<?php echo $tpl->getRegister('tz'); ?>

											</select>
										</p>
									</div>
								</fieldset>
<?php if ($tpl->disRegister('password_validate') || $tpl->disRegister('captcha_validate')) : ?>
								<br />
								<fieldset class="box_protect">
									<legend><?php echo __('Validation'); ?></legend>
									<div class="fielditems">
<?php if ($tpl->disRegister('password_validate')) : ?>
										<p class="field field_ftw">
											<span class="required">*</span>
											<label for="f_pwd_validate"><?php echo __('Pour valider votre inscription, vous devez entrer un mot de passe :'); ?></label>
											<input maxlength="1024" id="f_pwd_validate" name="pwd_validate" type="password" class="text" />
										</p>
<?php if ($tpl->disRegister('password_validate_text')) : ?>
										<p id="validate_text" class="field">
											<?php echo __('Indice pour le mot de passe :'); ?>
											<span><?php echo $tpl->getRegister('password_validate_text'); ?></span>
										</p>
<?php endif; ?>
<?php endif; ?>
<?php if ($tpl->disRegister('captcha_validate')) : ?>
										<p class="field">
											<span class="required">*</span>
											<label><?php echo __('Prouvez que vous êtes un humain :'); ?></label>
											<?php echo $tpl->getRegister('captcha'); ?>

										</p>
<?php endif; ?>
									</div>
								</fieldset>
<?php endif; ?>
								<br />
								<p class="message message_info"><?php echo __('Les champs marqués d\'un astérisque sont obligatoires.'); ?></p>
								<input type="submit" class="submit" value="<?php echo __('Enregistrer'); ?>" />
							</div>
						</form>
					</td>
				</tr>
<?php endif; ?>
			</table>
		</div>

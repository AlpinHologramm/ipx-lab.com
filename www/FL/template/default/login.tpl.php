
		<div class="box box_notools">
			<table>
				<tr><td class="box_title"><div><h2><?php echo __('Connexion'); ?></h2></div></td></tr>
				<tr>
					<td class="box_edit">
<?php if ($tpl->disGallery('users_only_members')) : ?>
						<p id="users_only_members" class="message message_info"><?php echo __('Vous devez vous identifier pour accéder à la galerie.'); ?></p>
<?php endif; ?>
						<form action="" method="post">
							<div>
<?php if (isset($_POST['auth_login'])) : ?>
								<p class="report message message_error"><?php echo __('Informations incorrectes'); ?></p>
<?php endif; ?>
								<p class="field field_ftw">
									<label for="login"><?php echo __('Nom d\'utilisateur :'); ?></label>
									<input class="focus text" id="login" name="auth_login" size="30" maxlength="128" type="text" />
								</p>
								<p class="field field_ftw">
									<label for="pwd"><?php echo __('Mot de passe :'); ?></label>
									<input class="text" id="pwd" name="auth_password" size="30" maxlength="1024" type="password" />
								</p>
								<p class="field">
									<input type="checkbox" id="remember" name="auth_remember" />
									<label for="remember"><?php echo __('Se souvenir de moi ?'); ?></label>
								</p>
								<input class="submit" type="submit" value="<?php echo __('Valider'); ?>" />
							</div>
						</form>
						<br />
						<p id="forgot_link">
							<a href="<?php echo $tpl->getLink('forgot'); ?>"><?php echo __('Mot de passe oublié ?'); ?></a>
						</p>
					</td>
				</tr>
			</table>
		</div>

	<p id="position"><a href="<?php echo $tpl->getGallery('gallery_path'); ?>/"><?php echo __('retour à la galerie'); ?></a></p>
		<div id="password_auth">
			<form action="" method="post">
				<div>
					<label for="pwd"><?php echo __('Veuillez entrer le mot de passe pour accéder à cette partie de la galerie :'); ?></label>
					<p>
						<input class="focus text" size="40" maxlength="1024" type="password" id="pwd" name="password" />
						<input class="submit" type="submit" value="<?php echo __('Valider'); ?>" />
					</p>
				</div>
			</form>
<?php if (!empty($_POST)) : ?>
			<p class="report message message_error" id="password_auth_bad"><?php echo __('Mauvais mot de passe !'); ?></p>
<?php endif; ?>
		</div>

<?php if ($tpl->disGalleryStep('install_success')) : ?>
		<h2><?php echo __('Fin de l\'installation'); ?></h2>
<?php else : ?>
		<h2><a href="?q=step/3"><?php echo __('Étape 3 : informations galerie'); ?></a></h2>
<?php endif; ?>
		<div id="content">
<?php if ($tpl->disGalleryStep('form')) : ?>
<?php if ($tpl->disReport()) : ?>
			<div id="report">
<?php if ($tpl->disReport('field_error')) : ?>
				<script type="text/javascript">var field_error = <?php echo $tpl->getReport('field_error'); ?>;</script>
<?php endif; ?>
<?php if ($tpl->disReport('error')) : ?>
				<p class="icon icon_error"><?php echo $tpl->getReport('error'); ?></p>
<?php endif; ?>
<?php if ($tpl->disReport('warning')) : ?>
				<p class="icon icon_warning"><?php echo $tpl->getReport('warning'); ?></p>
<?php endif; ?>
			</div>
<?php endif; ?>
			<form action="" method="post">
				<div>
					<fieldset>
						<h3><?php echo __('Compte super-administrateur'); ?></h3>
						<p class="field field_ftw">
							<label for="login"><?php echo __('Nom d\'utilisateur :'); ?></label>
							<input value="<?php echo $tpl->getGalleryStep('login'); ?>" maxlength="64" class="text" type="text" name="login" id="login" />
						</p>
						<p class="field field_ftw">
							<label for="password"><?php printf(__('Mot de passe (%s caractères minimum) :'), $tpl->getGalleryStep('password_minlength')); ?></label>
							<input value="<?php echo $tpl->getGalleryStep('password'); ?>" maxlength="128" class="text" type="password" name="password" id="password" />
						</p>
						<p class="field field_ftw">
							<label for="password_confirm"><?php echo __('Confirmez le mot de passe :'); ?></label>
							<input value="<?php echo $tpl->getGalleryStep('password_confirm'); ?>" maxlength="128" class="text" type="password" name="password_confirm" id="password_confirm" />
						</p>
						<p class="field field_ftw">
							<label for="email"><?php echo __('Adresse de courriel :'); ?></label>
							<input value="<?php echo $tpl->getGalleryStep('email'); ?>" maxlength="128" class="text" type="text" name="email" id="email" />
						</p>
					</fieldset>
					<br />
					<fieldset>
						<h3><?php echo __('Paramètres de la galerie'); ?></h3>
						<p class="field field_ftw">
							<label for="title"><?php echo __('Titre de la galerie :'); ?></label>
							<input value="<?php echo $tpl->getGalleryStep('title'); ?>" maxlength="128" class="text" type="text" name="title" id="title" />
						</p>
						<p class="field field_ftw">
							<label for="url"><?php echo __('URL de la galerie :'); ?></label>
							<input value="<?php echo $tpl->getGalleryStep('url'); ?>" maxlength="256" class="text" type="text" name="url" id="url" />
						</p>
						<p class="field field_ftw">
							<label for="lang_default"><?php echo __('Langue par défaut :'); ?></label>
							<select name="lang_default" id="lang_default">
								<?php echo $tpl->getGalleryStep('langs_list'); ?>

							</select>
						</p>
						<p class="field field_ftw">
							<label for="tz_default"><?php echo __('Fuseau horaire par défaut :'); ?></label>
							<select name="tz_default" id="tz_default">
								<?php echo $tpl->getGalleryStep('tz_list'); ?>

							</select>
						</p>
					</fieldset>
					<input type="submit" class="submit" value="<?php echo __('Valider'); ?>" />
				</div>
			</form>
<?php elseif (!$tpl->disGalleryStep('install_success')) : ?>
			<p class="icon icon_error"><?php echo __('L\'installation a échouée pour la raison suivante :'); ?></p>
			<p class="install_msg install_error">
				<?php echo $tpl->getGalleryStep('error'); ?>

			</p>
<?php else : ?>
			<p id="msg_install_ok" class="icon icon_ok"><?php echo __('Félicitations, l\'installation a réussie !'); ?></p>
			<p class="icon"><?php echo __('Vous pouvez maintenant utiliser iGalerie.'); ?></p>
			<p class="icon icon_exclamation"><?php echo __('Pour des raisons de sécurité, il est fortement recommandé de supprimer le répertoire "install" avant de vous connecter.'); ?></p>
			<p class="icon icon_information"><?php echo __('N\'oubliez pas que vous trouverez aide et documentation sur le site d\'iGalerie.'); ?></p>
			<p id="admin_link"><a href="<?php echo $tpl->getInstall('admin_link'); ?>connexion.php"><?php echo __('connexion à l\'administration'); ?></a></p>
<?php endif; ?>
		</div>

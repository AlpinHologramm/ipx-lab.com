		<h2><?php echo __('Étape 2 : informations MySQL'); ?></h2>
		<div id="content">
<?php if ($tpl->disMySQLStep('form')) : ?>
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
						<p class="field field_ftw">
							<label for="server"><?php echo __('Adresse du serveur :'); ?></label>
							<input value="<?php echo $tpl->getMySQLStep('server'); ?>" maxlength="256" class="text" type="text" name="server" id="server" />
						</p>
						<p class="field field_ftw">
							<label for="user"><?php echo __('Nom d\'utilisateur :'); ?></label>
							<input value="<?php echo $tpl->getMySQLStep('user'); ?>" maxlength="128" class="text" type="text" name="user" id="user" />
						</p>
						<p class="field field_ftw">
							<label for="password"><?php echo __('Mot de passe :'); ?></label>
							<input value="<?php echo $tpl->getMySQLStep('password'); ?>" maxlength="128" class="text" type="password" name="password" id="password" />
						</p>
						<p class="field field_ftw">
							<label for="database"><?php echo __('Base de données :'); ?></label>
							<input value="<?php echo $tpl->getMySQLStep('database'); ?>" maxlength="128" class="text" type="text" name="database" id="database" />
						</p>
						<p class="field field_ftw">
							<label for="prefix"><?php echo __('Préfixe des tables :'); ?></label>
							<input value="<?php echo $tpl->getMySQLStep('prefix'); ?>" maxlength="32" class="text" type="text" name="prefix" id="prefix" />
						</p>
					</fieldset>
					<input type="submit" class="submit" value="<?php echo __('Valider'); ?>" />
				</div>
			</form>
<?php else : ?>
			<p class="icon icon_<?php echo $tpl->getSystemInfo('mysql_version_compatible'); ?>"><?php printf(__('Version de %s :'), 'MySQL'); ?> <span><?php echo $tpl->getSystemInfo('mysql_version'); ?></span></p>
<?php if ($tpl->getSystemInfo('mysql_version_compatible') == 'ok') : ?>
			<p class="icon icon_<?php echo $tpl->getMySQLStep('config_file_status'); ?>"><?php echo __('Mise à jour du fichier de configuration :'); ?> <span><?php echo $tpl->getMySQLStep('config_file'); ?></span></p>
<?php if ($tpl->getMySQLStep('config_file_status') == 'ok') : ?>
			<p class="icon icon_<?php echo $tpl->getMySQLStep('mysql_schema_status'); ?>"><?php echo __('Création des tables :'); ?> <span><?php echo $tpl->getMySQLStep('mysql_schema_create'); ?></span></p>
<?php if ($tpl->getMySQLStep('mysql_schema_status') == 'ok') : ?>
			<p id="next"><a href="?q=step/3"><?php printf(__('étape %s'), 3); ?></a></p>
<?php else : ?>
			<p class="install_msg install_error">
				<?php echo __('L\'installation a échouée pour la raison suivante :'); ?>
				<br />
				<?php echo $tpl->getMySQLStep('mysql_schema_error'); ?>

			</p>
<?php endif; ?>
<?php else : ?>
			<p class="install_msg install_error">
				<?php echo __('L\'installation a échouée pour la raison suivante :'); ?>
				<br />
				<?php echo __('Impossible de modifier le fichier de configuration.'); ?>

			</p>
<?php endif; ?>
<?php else : ?>
			<p class="install_msg install_error">
				<?php printf(__('Désolé, iGalerie nécessite la version %s ou supérieure de %s.'), $tpl->getSystemInfo('mysql_version_min'), 'MySQL'); ?>
				<br />
				<?php echo __('Vous ne pouvez poursuivre l\'installation.'); ?>

			</p>
<?php endif; ?>
<?php endif; ?>
		</div>

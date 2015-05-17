		<h2><?php echo __('Étape 1 : vérification système'); ?></h2>
		<div id="content">
			<h3><?php echo __('Configuration serveur'); ?></h3>
			<p class="icon icon_<?php echo $tpl->getSystemInfo('php_version_compatible'); ?>"><?php printf(__('Version de %s :'), 'PHP'); ?> <span><?php echo $tpl->getSystemInfo('php_version'); ?></span></p>
<?php if ($tpl->getSystemInfo('php_version_compatible') == 'ok') : ?>
			<p class="icon icon_<?php echo $tpl->getSystemInfo('gd_version_compatible'); ?>"><?php printf(__('Version de %s :'), 'GD'); ?> <span><?php echo $tpl->getSystemInfo('gd_version'); ?></span></p>
<?php if ($tpl->getSystemInfo('gd_version') != '?') : ?>
<?php if ($tpl->getSystemInfo('gd_version_compatible') == 'ok') : ?>
			<p class="icon icon_<?php echo $tpl->getSystemInfo('extension_exif_status'); ?>"><?php printf(__('Extension %s :'), 'exif'); ?> <span><?php echo $tpl->getSystemInfo('extension_exif'); ?></span></p>
<?php if ($tpl->getSystemInfo('extension_exif_status') != 'ok') : ?>
			<p class="install_msg install_warning">
				<?php echo __('iGalerie ne pourra pas gérer les informations Exif de vos images.'); ?>

			</p>
<?php endif; ?>
			<p class="icon icon_<?php echo $tpl->getSystemInfo('extension_mbstring_status'); ?>"><?php printf(__('Extension %s :'), 'mbstring'); ?> <span><?php echo $tpl->getSystemInfo('extension_mbstring'); ?></span></p>
<?php if ($tpl->getSystemInfo('extension_mbstring_status') == 'ok') : ?>
			<p class="icon icon_<?php echo $tpl->getSystemInfo('extension_pdo_status'); ?>"><?php printf(__('Extension %s :'), 'PDO'); ?> <span><?php echo $tpl->getSystemInfo('extension_pdo'); ?></span></p>
<?php if ($tpl->getSystemInfo('extension_pdo_status') == 'ok') : ?>
			<p class="icon icon_<?php echo $tpl->getSystemInfo('extension_pdo_mysql_status'); ?>"><?php printf(__('Extension %s :'), 'pdo_mysql'); ?> <span><?php echo $tpl->getSystemInfo('extension_pdo_mysql'); ?></span></p>
<?php if ($tpl->getSystemInfo('extension_pdo_mysql_status') == 'ok') : ?>
			<p class="icon icon_<?php echo $tpl->getSystemInfo('extension_simplexml_status'); ?>"><?php printf(__('Extension %s :'), 'SimpleXML'); ?> <span><?php echo $tpl->getSystemInfo('extension_simplexml'); ?></span></p>
<?php if ($tpl->getSystemInfo('extension_simplexml_status') == 'ok') : ?>
			<p class="icon icon_<?php echo $tpl->getSystemInfo('extension_zip_status'); ?>"><?php printf(__('Extension %s :'), 'zip'); ?> <span><?php echo $tpl->getSystemInfo('extension_zip'); ?></span></p>
<?php if ($tpl->getSystemInfo('extension_zip_status') != 'ok') : ?>
			<p class="install_msg install_warning">
				<?php echo __('Certaines fonctionnalités utilisant cette extension ne seront pas disponibles.'); ?>

			</p>
<?php endif; ?>

			<br />
			<h3><?php echo __('Configuration navigateur'); ?></h3>
			<p class="icon icon_<?php echo $tpl->getSystemInfo('cookies_status'); ?>"><?php echo __('Cookies :'); ?> <span><?php echo $tpl->getSystemInfo('cookies'); ?></span></p>
<?php if ($tpl->getSystemInfo('cookies_status') != 'ok') : ?>
			<p class="install_msg install_warning">
				<?php echo __('Vous devez activer les cookies et accepter les cookies de iGalerie pour pouvoir envoyer des images et administrer la galerie.'); ?>

			</p>
<?php endif; ?>
			<p style="display:none" class="javascript_test_show icon icon_ok"><?php printf(__('%s :'), 'JavaScript'); ?><span> <?php echo __('activé'); ?></span></p>
			<p class="javascript_test_hide icon icon_warning"><?php printf(__('%s :'), 'JavaScript'); ?><span> <?php echo __('désactivé'); ?></span></p>
			<p class="javascript_test_hide install_msg install_warning">
				<?php echo __('Certaines parties de l\'administration ne seront pas accessible sans Javascript.'); ?>

			</p>

			<br />
			<h3><?php echo __('Droits d\'accès en écriture'); ?></h3>
<?php while ($tpl->nextWritePermission()) : ?>
			<p class="icon icon_<?php echo $tpl->getWritePermission('status'); ?>"><?php printf(__('%s :'), $tpl->getWritePermission('name')); ?><span> <?php echo $tpl->getWritePermission('value'); ?></span></p>
<?php endwhile; ?>
<?php if ($tpl->getSystemInfo('write_permissions_status') == 'ok') : ?>
			<p id="next"><a href="?q=step/2"><?php printf(__('étape %s'), 2); ?></a></p>
<?php else : ?>
			<p class="install_msg install_error">
				<?php echo __('Certains répertoires ou fichiers ne sont pas accessibles en écriture.'); ?>
				<br />
				<?php echo __('Vous devez accorder les permissions en écriture avec la commande CHMOD sur tous les répertoires et fichiers listés ci-dessus pour pouvoir poursuivre l\'installation.'); ?>
			</p>
<?php endif; ?>
<?php else : ?>
			<p class="install_msg install_error">
				<?php printf(__('Désolé, iGalerie nécessite l\'extension %s.'), 'SimpleXML'); ?>
				<br />
				<?php echo __('Vous ne pouvez poursuivre l\'installation.'); ?>

			</p>
<?php endif; ?>
<?php else : ?>
			<p class="install_msg install_error">
				<?php printf(__('Désolé, iGalerie nécessite l\'extension %s.'), 'pdo_mysql'); ?>
				<br />
				<?php echo __('Vous ne pouvez poursuivre l\'installation.'); ?>

			</p>
<?php endif; ?>
<?php else : ?>
			<p class="install_msg install_error">
				<?php printf(__('Désolé, iGalerie nécessite l\'extension %s.'), 'PDO'); ?>
				<br />
				<?php echo __('Vous ne pouvez poursuivre l\'installation.'); ?>

			</p>
<?php endif; ?>
<?php else : ?>
			<p class="install_msg install_error">
				<?php printf(__('Désolé, iGalerie nécessite l\'extension %s.'), 'mbstring'); ?>
				<br />
				<?php echo __('Vous ne pouvez poursuivre l\'installation.'); ?>

			</p>
<?php endif; ?>
<?php else : ?>
			<p class="install_msg install_error">
				<?php printf(__('Désolé, iGalerie nécessite la version %s ou supérieure de %s.'), $tpl->getSystemInfo('gd_version_min'), 'GD'); ?>
				<br />
				<?php echo __('Vous ne pouvez poursuivre l\'installation.'); ?>

			</p>
<?php endif; ?>
<?php else : ?>
			<p class="install_msg install_error">
				<?php printf(__('Désolé, iGalerie nécessite l\'extension %s.'), 'GD'); ?>
				<br />
				<?php echo __('Vous ne pouvez poursuivre l\'installation.'); ?>

			</p>
<?php endif; ?>
<?php else : ?>
			<p class="install_msg install_error">
				<?php printf(__('Désolé, iGalerie nécessite la version %s ou supérieure de %s.'), $tpl->getSystemInfo('php_version_min'), 'PHP'); ?>
				<br />
				<?php echo __('Vous ne pouvez poursuivre l\'installation.'); ?>

			</p>
<?php endif; ?>
		</div>

		<h2><?php echo __('Introduction'); ?></h2>
		<div id="content">
			<p id="welcome"><?php printf(__('Bienvenue dans l\'installation de %s !'), '<br /><strong>iGalerie ' . $tpl->getInstall('gallery_version') . '</strong>'); ?></p>
			<form action="" method="post" id="langs_switch">
				<div>
					<label for="langs"><?php echo __('Langue d\'installation :'); ?></label>
					<select id="langs" name="lang">
						<?php echo $tpl->getInstall('langs_switch'); ?>

					</select>
				</div>
			</form>
			<p><?php echo __('L\'installation va se dérouler en trois étapes :'); ?></p>
			<ul id="steps">
				<li><?php echo __('Introduction'); ?></li>
				<li><?php echo __('Étape 1 : vérification système'); ?></li>
				<li><?php echo __('Étape 2 : informations MySQL'); ?></li>
				<li><?php echo __('Étape 3 : informations galerie'); ?></li>
				<li><?php echo __('Fin de l\'installation'); ?></li>
			</ul>
			<p id="next"><a href="?q=step/1"><?php echo __('commencer l\'installation'); ?></a></p>
		</div>

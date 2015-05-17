
		<h2><a href="<?php echo $tpl->getLink('system'); ?>"><?php echo __('Système'); ?></a></h2>

		<div id="sub_menu_line"></div><div id="sub_menu_bg"></div>

		<div id="tools_browse">
			<div class="browse">
				<select id="functions_list" onchange="window.location.href='#'+this.options[this.selectedIndex].value">
					<option value=""><?php echo __('Parcourir :'); ?></option>
					<option value="gallery">iGalerie</option>
					<option value="server"><?php echo __('Serveur'); ?></option>
					<option value="php">PHP</option>
					<option value="php-directives"><?php echo __('Directives PHP'); ?></option>
					<option value="php-extensions"><?php echo __('Extensions PHP'); ?></option>
					<option value="php-functions"><?php echo __('Fonctions PHP'); ?></option>
					<option value="gd">GD</option>
					<option value="mysql">MySQL</option>
					<option value="perms"><?php echo __('Droits d\'accès en écriture'); ?></option>
				</select>
			</div>
		</div>

		<br />

		<div class="browse_anchor" id="gallery"></div>
		<fieldset><legend>iGalerie</legend></fieldset>
		<div class="system_infos">
			<p class="info"><?php echo __('Version :'); ?> <span><?php echo $tpl->getSystemInfo('gallery_version'); ?></span></p>
			<div>
				<p class="info"><?php echo __('Historique :'); ?></p>
				<?php echo $tpl->getSystemInfo('history'); ?>

			</div>
			<p class="<?php echo $tpl->getSystemInfo('gallery_errors_status'); ?>"><?php echo __('Incidents :'); ?> <span><?php echo $tpl->getSystemInfo('gallery_errors'); ?></span></p>
		</div>

		<div class="browse_anchor" id="server"></div>
		<fieldset><legend><?php echo __('Serveur'); ?></legend></fieldset>
		<div class="system_infos">
			<p class="info"><?php echo __('Type de serveur :'); ?> <span><?php echo $tpl->getSystemInfo('server_type'); ?></span></p>
			<p class="info"><?php echo __('Système d\'exploitation :'); ?> <span><?php echo $tpl->getSystemInfo('server_os'); ?></span></p>
			<p class="info"><?php echo __('Temps serveur :'); ?> <span><?php echo $tpl->getSystemInfo('server_time'); ?></span></p>
		</div>

		<div class="browse_anchor" id="php"></div>
		<fieldset><legend>PHP</legend></fieldset>
		<div class="system_infos">
			<p class="<?php echo $tpl->getSystemInfo('php_version_compatible'); ?>"><?php echo __('Version :'); ?> <span><?php echo $tpl->getSystemInfo('php_version'); ?></span></p>
			<p class="info">SAPI : <span><?php echo $tpl->getSystemInfo('php_sapi'); ?></span></p>
		</div>

		<div class="browse_anchor" id="php-directives"></div>
		<fieldset><legend><?php echo __('Directives PHP'); ?></legend></fieldset>
		<div class="system_infos">
<?php while ($tpl->nextDirective()) : ?>
			<p class="<?php echo $tpl->getDirective('status'); ?>"><?php echo $tpl->getDirective('name'); ?> : <span><?php echo $tpl->getDirective('value'); ?></span></p>
<?php endwhile; ?>
		</div>

		<div class="browse_anchor" id="php-extensions"></div>
		<fieldset><legend><?php echo __('Extensions PHP'); ?></legend></fieldset>
		<div class="system_infos">
<?php while ($tpl->nextExtension()) : ?>
			<p class="<?php echo $tpl->getExtension('status'); ?>"><?php echo $tpl->getExtension('name'); ?> : <span><?php echo $tpl->getExtension('value'); ?></span></p>
<?php endwhile; ?>
		</div>

		<div class="browse_anchor" id="php-functions"></div>
		<fieldset><legend><?php echo __('Fonctions PHP'); ?></legend></fieldset>
		<div class="system_infos">
<?php while ($tpl->nextFunction()) : ?>
			<p class="<?php echo $tpl->getFunction('status'); ?>"><?php echo $tpl->getFunction('name'); ?> : <span><?php echo $tpl->getFunction('value'); ?></span></p>
<?php endwhile; ?>
		</div>

		<div class="browse_anchor" id="gd"></div>
		<fieldset><legend>GD</legend></fieldset>
		<div class="system_infos">
			<p class="<?php echo $tpl->getSystemInfo('gd_version_compatible'); ?>"><?php echo __('Version :'); ?> <span><?php echo $tpl->getSystemInfo('gd_version'); ?></span></p>
			<p class="<?php echo $tpl->getSystemInfo('gd_freetype_status'); ?>">FreeType : <span><?php echo $tpl->getSystemInfo('gd_freetype'); ?></span></p>
			<p class="<?php echo $tpl->getSystemInfo('gd_gif_status'); ?>">GIF : <span><?php echo $tpl->getSystemInfo('gd_gif'); ?></span></p>
			<p class="<?php echo $tpl->getSystemInfo('gd_jpg_status'); ?>">JPG : <span><?php echo $tpl->getSystemInfo('gd_jpg'); ?></span></p>
			<p class="<?php echo $tpl->getSystemInfo('gd_png_status'); ?>">PNG : <span><?php echo $tpl->getSystemInfo('gd_png'); ?></span></p>
		</div>

		<div class="browse_anchor" id="mysql"></div>
		<fieldset><legend>MySQL</legend></fieldset>
		<div class="system_infos">
			<p class="<?php echo $tpl->getSystemInfo('mysql_version_compatible'); ?>"><?php echo __('Version :'); ?> <span><?php echo $tpl->getSystemInfo('mysql_version'); ?></span></p>
			<p class="info">have_innodb : <span><?php echo $tpl->getSystemInfo('mysql_variable_have_innodb'); ?></span></p>
			<p class="info">max_user_connections : <span><?php echo $tpl->getSystemInfo('mysql_variable_max_user_connections'); ?></span></p>
			<p class="info">sql_mode : <span><?php echo $tpl->getSystemInfo('mysql_variable_sql_mode'); ?></span></p>
		</div>

		<div class="browse_anchor" id="perms"></div>
		<fieldset><legend><?php echo __('Droits d\'accès en écriture'); ?></legend></fieldset>
		<div class="system_infos">
<?php while ($tpl->nextWritePermission()) : ?>
			<p class="<?php echo $tpl->getWritePermission('status'); ?>"><?php echo $tpl->getWritePermission('name'); ?> : <span><?php echo $tpl->getWritePermission('value'); ?> (<?php echo $tpl->getWritePermission('perms'); ?>)</span></p>
<?php endwhile; ?>
		</div>

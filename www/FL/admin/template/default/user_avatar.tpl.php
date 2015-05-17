
<?php include_once(dirname(__FILE__) . '/users_submenu.tpl.php'); ?>

<?php include_once(dirname(__FILE__) . '/user_related.tpl.php'); ?>

		<form id="avatar_new" class="form_page" enctype="multipart/form-data" method="post" action="">
			<fieldset>
				<legend><?php echo __('Avatar actuel'); ?></legend>
				<p class="field">
					<img src="<?php echo $tpl->getProfile('avatar_src'); ?>" <?php echo $tpl->getProfile('avatar_size'); ?> alt="<?php echo sprintf(__('Avatar de %s'), $tpl->getProfile('login')); ?>" />
				</p>
			</fieldset>
			<br />
			<fieldset>
				<legend><?php echo __('Nouvel avatar'); ?></legend>
				<p class="field field_ftw">
					<label for="file">
						<?php printf(__('Image au format JPEG, GIF ou PNG de %s Ko et %s pixels de coté maximum.'), $tpl->getProfile('avatar_maxfilesize')/1024, $tpl->getProfile('avatar_maxsize')); ?>

					</label>
					<input name="MAX_FILE_SIZE" value="<?php echo $tpl->getProfile('avatar_maxfilesize'); ?>" type="hidden" />
					<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
					<input class="text" id="file" name="new" size="35" maxlength="2048" type="file" />
					<input name="action" value="new" type="hidden" />
					<input type="submit" class="submit" value="<?php echo __('Envoyer'); ?>" />
				</p>
			</fieldset>
		</form>

<?php if ($tpl->disUser('avatar')) : ?>
		<form id="avatar_delete" action="" method="post">
			<div>
				<fieldset>
					<legend><?php echo __('Suppression'); ?></legend>
					<p class="field">
						<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
						<input name="action" value="delete" type="hidden" />
						<input type="submit" class="submit" value="<?php echo __('Supprimer l\'avatar'); ?>" />
					</p>
				</fieldset>
			</div>
		</form>
<?php endif; ?>

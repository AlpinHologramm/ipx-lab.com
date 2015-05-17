
<?php include_once(dirname(__FILE__) . '/albums_submenu.tpl.php'); ?>

<?php include_once(dirname(__FILE__) . '/image_related.tpl.php'); ?>

		<h3><?php echo __('Édition de l\'image') ?></h3>

		<div class="browse browse_wlimit">
			<label><?php echo __('Parcourir :'); ?></label>
			<select name="browse" onchange="window.location.href='<?php echo $tpl->getAdmin('admin_base_url'); ?>'+this.options[this.selectedIndex].value">
				<?php echo $tpl->getImagesList(); ?>

			</select>
		</div>

		<p id="position"><?php echo $tpl->getPosition(); ?></p>

<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

		<form class="form_page" action="" method="post">
			<div>
<?php if ($tpl->disPerm('albums_modif')) : ?>
				<fieldset>
					<legend><?php echo __('Général'); ?></legend>
					<p class="field checkbox">
						<label for="status"><?php echo __('Statut :'); ?></label>
						<select id="status" name="status">
<?php echo $tpl->getImageInfo('status_list'); ?>

						</select>
					</p>
					<p class="field checkbox">
						<label for="category"><?php echo __('Album :'); ?></label>
						<select id="category" name="destination_cat">
<?php echo $tpl->getImageInfo('albums_list'); ?>

						</select>
					</p>
					<p class="field">
						<label for="hits"><?php echo __('Nombre de visites :'); ?></label>
						<input type="text" class="text" size="12" maxlength="11" name="hits" id="hits" value="<?php echo $tpl->getImageInfo('hits'); ?>" />
					</p>
				</fieldset>
				<br />
<?php endif; ?>
				<fieldset>
					<legend><?php echo __('Informations'); ?></legend>
<?php while ($tpl->nextLang()) : ?>
					<p<?php if (!$tpl->disLang('selected')) : ?> style="display:none"<?php endif; ?> class="field_ftw">
						<label class="icon_lang icon_<?php echo $tpl->getLang('code'); ?>" for="title_<?php echo $tpl->getImageInfo('id'); ?>_<?php echo $tpl->getLang('code'); ?>"><?php echo __('Titre :'); ?></label>
						<input name="<?php echo $tpl->getImageInfo('id'); ?>[title][<?php echo $tpl->getLang('code'); ?>]" size="40" maxlength="255" id="title_<?php echo $tpl->getImageInfo('id'); ?>_<?php echo $tpl->getLang('code'); ?>" type="text" class="text" value="<?php echo $tpl->getImageInfo('title_lang'); ?>" />
					</p>
<?php endwhile; ?>
					<p class="field_ftw">
						<label for="urlname_<?php echo $tpl->getImageInfo('id'); ?>"><?php echo __('Nom d\'URL :'); ?></label>
						<input name="<?php echo $tpl->getImageInfo('id'); ?>[urlname]" size="40" maxlength="255" id="urlname_<?php echo $tpl->getImageInfo('id'); ?>" type="text" class="text" value="<?php echo $tpl->getImageInfo('urlname'); ?>" />
					</p>
					<p class="field_ftw">
						<label for="filename_<?php echo $tpl->getImageInfo('id'); ?>"><?php echo __('Nom de fichier :'); ?></label>
						<input name="<?php echo $tpl->getImageInfo('id'); ?>[filename]" size="40" maxlength="255" id="filename_<?php echo $tpl->getImageInfo('id'); ?>" type="text" class="text" value="<?php echo $tpl->getImageInfo('filename'); ?>" />
					</p>
					<p class="field_ftw">
						<label><?php echo __('Date de création :'); ?></label>
						<br />
						<?php echo $tpl->getImageInfo('crtdt'); ?>

						<a class="js date_reset" href="javascript:;"><?php echo __('effacer'); ?></a>
					</p>
					<p class="field_ftw tags_suggest">
						<label for="tags_<?php echo $tpl->getImageInfo('id'); ?>"><?php echo __('Tags (séparés par une virgule) :'); ?></label>
						<textarea class="textarea_tags" id="tags_<?php echo $tpl->getImageInfo('id'); ?>" name="<?php echo $tpl->getImageInfo('id'); ?>[tags]" rows="4" cols="50"><?php echo $tpl->getImageInfo('tags'); ?></textarea>
					</p>
<?php while ($tpl->nextLang()) : ?>
					<p<?php if (!$tpl->disLang('selected')) : ?> style="display:none"<?php endif; ?> class="field_ftw field_html">
						<label class="icon_lang icon_<?php echo $tpl->getLang('code'); ?>" for="description_<?php echo $tpl->getImageInfo('id'); ?>_<?php echo $tpl->getLang('code'); ?>"><?php echo __('Description :'); ?></label>
						<span class="field_html_tag">
							<a title="<?php echo __('Cliquez pour obtenir la liste des balises autorisées'); ?>" href="javascript:;">HTML</a>
						</span>
						<span class="field_html_textarea">
							<textarea class="resizable" name="<?php echo $tpl->getImageInfo('id'); ?>[description][<?php echo $tpl->getLang('code'); ?>]" rows="10" cols="50" id="description_<?php echo $tpl->getImageInfo('id'); ?>_<?php echo $tpl->getLang('code'); ?>"><?php echo $tpl->getImageInfo('description_lang'); ?></textarea>
						</span>
					</p>
<?php endwhile; ?>
				</fieldset>
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
				<input class="submit" name="save" type="submit" value="<?php echo __('Enregistrer'); ?>" />
			</div>
		</form>

<?php if ($tpl->disTagsList()) : ?>
		<script type="text/javascript">
		//<![CDATA[
		var tags_suggest = [<?php echo $tpl->getTagsList(); ?>];
		//]]>
		</script>
<?php endif; ?>

<?php if ($tpl->disPerm('albums_modif')) : ?>
		<form class="form_page" action="" method="post">
			<div>
				<fieldset>
					<legend>
						<?php echo __('Mise à jour'); ?>

<?php if ($tpl->disHelp()) : ?>
						<a rel="h_update" title="<?php echo __('Obtenir de l\'aide sur cette fonction'); ?>" class="help_link help_link_context" href="javascript:;"><img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/help-link.png" alt="<?php echo __('Aide'); ?>" /></a>
<?php endif; ?>
					</legend>
				</fieldset>
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
				<input class="submit" name="update" type="submit" value="<?php echo __('Mettre à jour l\'image'); ?>" />
			</div>
		</form>

		<script type="text/javascript">
		//<![CDATA[
		var confirm_delete = "<?php echo $tpl->getL10nJS(__('Étes-vous sûr de vouloir supprimer cette image, ainsi que tous les tags, votes et commentaires liés ?')); ?>";
		//]]>
		</script>
		<form id="confirm_delete" class="form_page" action="" method="post">
			<div>
				<fieldset>
					<legend><?php echo __('Suppression'); ?></legend>
				</fieldset>
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
				<input disabled="disabled" class="submit js_required" name="delete" type="submit" value="<?php echo __('Supprimer l\'image'); ?>" />
			</div>
		</form>
<?php endif; ?>
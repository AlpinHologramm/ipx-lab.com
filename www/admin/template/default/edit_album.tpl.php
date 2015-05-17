
<?php include_once(dirname(__FILE__) . '/albums_submenu.tpl.php'); ?>

<?php include_once(dirname(__FILE__) . '/album_related.tpl.php'); ?>

		<h3><?php echo __('Édition de l\'album') ?></h3>

		<div class="browse browse_wlimit">
			<label><?php echo __('Parcourir :'); ?></label>
			<select name="browse" onchange="window.location.href='<?php echo $tpl->getAdmin('admin_base_url'); ?>'+this.options[this.selectedIndex].value">
				<?php echo $tpl->getMap(); ?>

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
<?php echo $tpl->getCategoryInfo('status_list'); ?>

						</select>
					</p>
					<p class="field checkbox">
						<label for="category"><?php echo __('Catégorie :'); ?></label>
						<select id="category" name="destination_cat">
<?php echo $tpl->getCategoryInfo('categories_list'); ?>

						</select>
					</p>
					<p class="field checkbox">
						<label for="owner"><?php echo __('Propriétaire :'); ?></label>
						<select id="owner" name="owner">
<?php echo $tpl->getCategoryInfo('users_list'); ?>

						</select>
					</p>
					<p class="field checkbox">
						<input type="checkbox" name="reset_hits" id="reset_hits" />
						<label for="reset_hits"><?php echo __('Nombre de visites à zéro'); ?></label>
					</p>
				</fieldset>
				<br />
<?php endif; ?>
				<fieldset>
					<legend><?php echo __('Informations'); ?></legend>
<?php while ($tpl->nextLang()) : ?>
					<p<?php if (!$tpl->disLang('selected')) : ?> style="display:none"<?php endif; ?> class="field field_ftw">
						<label class="icon_lang icon_<?php echo $tpl->getLang('code'); ?>" for="title_<?php echo $tpl->getCategoryInfo('id'); ?>_<?php echo $tpl->getLang('code'); ?>"><?php echo __('Titre :'); ?></label>
						<input name="<?php echo $tpl->getCategoryInfo('id'); ?>[title][<?php echo $tpl->getLang('code'); ?>]" size="40" maxlength="255" id="title_<?php echo $tpl->getCategoryInfo('id'); ?>_<?php echo $tpl->getLang('code'); ?>" type="text" class="text" value="<?php echo $tpl->getCategoryInfo('title_lang'); ?>" />
					</p>
<?php endwhile; ?>
					<p class="field field_ftw">
						<label for="urlname_<?php echo $tpl->getCategoryInfo('id'); ?>"><?php echo __('Nom d\'URL :'); ?></label>
						<input name="<?php echo $tpl->getCategoryInfo('id'); ?>[urlname]" size="40" maxlength="64" id="urlname_<?php echo $tpl->getCategoryInfo('id'); ?>" type="text" class="text" value="<?php echo $tpl->getCategoryInfo('urlname'); ?>" />
					</p>
					<p class="field field_ftw">
						<label for="dirname_<?php echo $tpl->getCategoryInfo('id'); ?>"><?php echo __('Nom de répertoire :'); ?></label>
						<input name="<?php echo $tpl->getCategoryInfo('id'); ?>[dirname]" size="40" maxlength="64" id="dirname_<?php echo $tpl->getCategoryInfo('id'); ?>" type="text" class="text" value="<?php echo $tpl->getCategoryInfo('dirname'); ?>" />
					</p>
<?php while ($tpl->nextLang()) : ?>
					<p<?php if (!$tpl->disLang('selected')) : ?> style="display:none"<?php endif; ?> class="field field_ftw field_html">
						<label class="icon_lang icon_<?php echo $tpl->getLang('code'); ?>" for="description_<?php echo $tpl->getCategoryInfo('id'); ?>_<?php echo $tpl->getLang('code'); ?>"><?php echo __('Description :'); ?></label>
						<span class="field_html_tag">
							<a title="<?php echo __('Cliquez pour obtenir la liste des balises autorisées'); ?>" href="javascript:;">HTML</a>
						</span>
						<span class="field_html_textarea">
							<textarea class="resizable" name="<?php echo $tpl->getCategoryInfo('id'); ?>[description][<?php echo $tpl->getLang('code'); ?>]" rows="10" cols="50" id="description_<?php echo $tpl->getCategoryInfo('id'); ?>_<?php echo $tpl->getLang('code'); ?>"><?php echo $tpl->getCategoryInfo('description_lang'); ?></textarea>
						</span>
					</p>
<?php endwhile; ?>
				</fieldset>
<?php if ($tpl->disPerm('albums_modif')) : ?>
				<br />
				<fieldset>
					<legend><?php echo __('Réglages'); ?></legend>
<?php if ($tpl->disPerm('access_all')) : ?>
					<p class="field field_ftw">
						<label for="password_<?php echo $tpl->getCategoryInfo('id'); ?>"><?php echo __('Mot de passe :'); ?></label>
						<input name="<?php echo $tpl->getCategoryInfo('id'); ?>[password]" size="40" maxlength="1024" id="password_<?php echo $tpl->getCategoryInfo('id'); ?>" type="password" class="text" value="<?php echo $tpl->getCategoryInfo('password'); ?>" />
					</p>
<?php endif; ?>
					<p class="field checkbox">
						<input<?php if (!$tpl->disCategoryInfo('commentable_parent')) : ?> disabled="disabled"<?php endif; ?><?php if ($tpl->disCategoryInfo('commentable')) : ?> checked="checked"<?php endif; ?> id="allow_comments_<?php echo $tpl->getCategoryInfo('id'); ?>" name="<?php echo $tpl->getCategoryInfo('id'); ?>[commentable]" type="checkbox" />
						<span><label for="allow_comments_<?php echo $tpl->getCategoryInfo('id'); ?>"><?php echo __('Autoriser les commentaires'); ?></label></span>
					</p>
					<p class="field checkbox">
						<input<?php if (!$tpl->disCategoryInfo('votable_parent')) : ?> disabled="disabled"<?php endif; ?><?php if ($tpl->disCategoryInfo('votable')) : ?> checked="checked"<?php endif; ?> id="allow_votes_<?php echo $tpl->getCategoryInfo('id'); ?>" name="<?php echo $tpl->getCategoryInfo('id'); ?>[votable]" type="checkbox" />
						<span><label for="allow_votes_<?php echo $tpl->getCategoryInfo('id'); ?>"><?php echo __('Autoriser les votes'); ?></label></span>
					</p>
					<p class="field checkbox">
						<input<?php if (!$tpl->disCategoryInfo('uploadable_parent')) : ?> disabled="disabled"<?php endif; ?><?php if ($tpl->disCategoryInfo('uploadable')) : ?> checked="checked"<?php endif; ?> id="allow_upload_<?php echo $tpl->getCategoryInfo('id'); ?>" name="<?php echo $tpl->getCategoryInfo('id'); ?>[uploadable]" type="checkbox" />
						<span><label for="allow_upload_<?php echo $tpl->getCategoryInfo('id'); ?>"><?php echo __('Autoriser l\'ajout d\'images'); ?></label></span>
					</p>
					<p class="field">
						<label for="style_<?php echo $tpl->getCategoryInfo('id'); ?>"><?php echo __('Style :'); ?></label>
						<select id="style_<?php echo $tpl->getCategoryInfo('id'); ?>" name="<?php echo $tpl->getCategoryInfo('id'); ?>[style]">
							<?php echo $tpl->getCategoryInfo('styles'); ?>

						</select>
					</p>
					<p class="field">
						<label><?php echo __('Trier les images par :'); ?></label>
					</p>
					<div class="field_second">
						<p class="field">
							<?php printf(__('Critère n°%s :'), 1); ?>
							<select name="<?php echo $tpl->getCategoryInfo('id'); ?>[orderby_1]">
								<?php echo $tpl->getCategoryInfo('orderby_1'); ?>

							</select>
							<select name="<?php echo $tpl->getCategoryInfo('id'); ?>[ascdesc_1]">
								<?php echo $tpl->getCategoryInfo('ascdesc_1'); ?>

							</select>
						</p>
						<p class="field">
							<?php printf(__('Critère n°%s :'), 2); ?>
							<select name="<?php echo $tpl->getCategoryInfo('id'); ?>[orderby_2]">
								<?php echo $tpl->getCategoryInfo('orderby_2'); ?>

							</select>
							<select name="<?php echo $tpl->getCategoryInfo('id'); ?>[ascdesc_2]">
								<?php echo $tpl->getCategoryInfo('ascdesc_2'); ?>

							</select>
						</p>
						<p class="field">
							<?php printf(__('Critère n°%s :'), 3); ?>
							<select name="<?php echo $tpl->getCategoryInfo('id'); ?>[orderby_3]">
								<?php echo $tpl->getCategoryInfo('orderby_3'); ?>

							</select>
							<select name="<?php echo $tpl->getCategoryInfo('id'); ?>[ascdesc_3]">
								<?php echo $tpl->getCategoryInfo('ascdesc_3'); ?>

							</select>
						</p>
					</div>
				</fieldset>
<?php endif; ?>
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
				<input class="submit" name="save" type="submit" value="<?php echo __('Enregistrer'); ?>" />
			</div>
		</form>

<?php if ($tpl->disPerm('albums_modif')) : ?>
		<script type="text/javascript">
		//<![CDATA[
		var confirm_delete = "<?php echo $tpl->getL10nJS(__('Êtes-vous sûr de vouloir supprimer cet album, ainsi que toutes les images, commentaires, votes et tags qui s\'y trouvent ?')); ?>";
		//]]>
		</script>
		<form id="confirm_delete" class="form_page" action="" method="post">
			<div>
				<fieldset>
					<legend><?php echo __('Suppression'); ?></legend>
				</fieldset>
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
				<input class="submit" name="delete" type="submit" value="<?php echo __('Supprimer l\'album'); ?>" />
			</div>
		</form>
<?php endif; ?>
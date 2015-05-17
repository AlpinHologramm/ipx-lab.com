
<?php include_once(dirname(__FILE__) . '/comments_submenu.tpl.php'); ?>

		<div id="tools_browse">
			<div class="browse">
				<select id="functions_list" onchange="window.location.href='#'+this.options[this.selectedIndex].value">
					<option value=""><?php echo __('Parcourir :'); ?></option>
					<option value="general"><?php echo __('Général'); ?></option>
					<option value="message"><?php echo __('Message'); ?></option>
				</select>
			</div>
		</div>

		<br />

<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

		<form id="comments_options" action="#top" method="post">
			<div>
				<div class="browse_anchor" id="general"></div>
				<fieldset>
					<legend><?php echo __('Général'); ?></legend>
					<div class="fielditems">
						<p class="field checkbox">
							<input<?php echo $tpl->getOption('comments_smilies'); ?> id="comments_smilies" name="comments_smilies" type="checkbox" />
							<span><label for="comments_smilies"><?php echo __('Activer les smilies'); ?></label></span>
						</p>
						<div class="field_second">
							<p class="field">
								<label for="comments_smilies_icons_pack"><?php echo __('Jeu d\'icônes à utiliser :'); ?></label>
								<select id="comments_smilies_icons_pack" name="comments_smilies_icons_pack">
									<?php echo $tpl->getOption('comments_smilies_icons_pack'); ?>

								</select>
<?php if ($tpl->disHelp()) : ?>
								<a rel="h_smilies" title="<?php echo __('Obtenir de l\'aide sur cette fonction'); ?>" class="help_link help_link_context" href="javascript:;"><img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/help-link.png" alt="<?php echo __('Aide'); ?>" /></a>
<?php endif; ?>
							</p>
						</div>
						<p class="field">
							<label for="comments_order"><?php echo __('Ordre d\'affichage des commentaires sur la page des images :'); ?></label>
							<select id="comments_order" name="comments_order">
								<?php echo $tpl->getOption('comments_order'); ?>

							</select>
						</p>
						<p class="field">
							<?php echo __('Renseignements obligatoires :'); ?>
						</p>
						<div class="field_second">
							<p class="field checkbox">
								<input<?php echo $tpl->getOption('comments_required_email'); ?> id="comments_required_email" name="comments_required_email" type="checkbox" />
								<span><label for="comments_required_email"><?php echo ucfirst(__('courriel')); ?></label></span>
							</p>
							<p class="field checkbox">
								<input<?php echo $tpl->getOption('comments_required_website'); ?> id="comments_required_website" name="comments_required_website" type="checkbox" />
								<span><label for="comments_required_website"><?php echo ucfirst(__('site Web')); ?></label></span>
							</p>
						</div>
						<p class="field">
							<label for="comments_antiflood"><?php echo __('Anti-flood :'); ?></label>
							<input value="<?php echo $tpl->getOption('comments_antiflood'); ?>" maxlength="5" id="comments_antiflood" name="comments_antiflood" class="text" size="5" type="text" />
							<?php echo __('secondes'); ?>
<?php if ($tpl->disHelp()) : ?>
							<a rel="h_antiflood" title="<?php echo __('Obtenir de l\'aide sur cette fonction'); ?>" class="help_link help_link_context" href="javascript:;"><img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/help-link.png" alt="<?php echo __('Aide'); ?>" /></a>
<?php endif; ?>
						</p>
						<p class="field checkbox<?php if (strstr($tpl->getOption('comments_moderate'), 'disabled')) : ?> f_disabled<?php endif; ?>">
							<input<?php echo $tpl->getOption('comments_moderate'); ?> id="comments_moderate" name="comments_moderate" type="checkbox" />
							<span><label for="comments_moderate"><?php echo __('Modérer les commentaires'); ?></label></span>
<?php if ($tpl->disHelp()) : ?>
							<a rel="h_moderate" title="<?php echo __('Obtenir de l\'aide sur cette fonction'); ?>" class="help_link help_link_context" href="javascript:;"><img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/help-link.png" alt="<?php echo __('Aide'); ?>" /></a>
<?php endif; ?>
						</p>
					</div>
				</fieldset>	
				<br />
				<div class="browse_anchor" id="message"></div>
				<fieldset>
					<legend><?php echo __('Message'); ?></legend>
					<div class="fielditems">
						<p class="field">
							<label for="comments_maxchars"><?php printf(__('Nombre maximum de caractères (ne peut dépasser %s) :'), 5000); ?></label>
							<input value="<?php echo $tpl->getOption('comments_maxchars'); ?>" maxlength="6" id="comments_maxchars" name="comments_maxchars" class="text" size="6" type="text" />
						</p>
						<p class="field">
							<label for="comments_maxlines"><?php echo __('Nombre maximum de lignes :'); ?></label>
							<input value="<?php echo $tpl->getOption('comments_maxlines'); ?>" maxlength="3" id="comments_maxlines" name="comments_maxlines" class="text" size="3" type="text" />
						</p>
						<p class="field">
							<label for="comments_maxurls"><?php echo __('Nombre maximum d\'URLs :'); ?></label>
							<input value="<?php echo $tpl->getOption('comments_maxurls'); ?>" maxlength="3" id="comments_maxurls" name="comments_maxurls" class="text" size="3" type="text" />
<?php if ($tpl->disHelp()) : ?>
							<a rel="h_maxurls" title="<?php echo __('Obtenir de l\'aide sur cette fonction'); ?>" class="help_link help_link_context" href="javascript:;"><img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/help-link.png" alt="<?php echo __('Aide'); ?>" /></a>
<?php endif; ?>
						</p>
						<p class="field checkbox">
							<input<?php echo $tpl->getOption('comments_words_limit'); ?> id="comments_words_limit" name="comments_words_limit" type="checkbox" />
							<span><label for="comments_words_limit"><?php echo __('Limiter la taille des mots :'); ?></label></span>
						</p>
						<div class="field_second">
							<p class="field">
								<label for="comments_words_maxlength"><?php echo __('Longueur maximale des mots :'); ?></label>
								<input value="<?php echo $tpl->getOption('comments_words_maxlength'); ?>" maxlength="3" id="comments_words_maxlength" name="comments_words_maxlength" class="text" size="3" type="text" />
								<?php echo __('caractères'); ?>
							</p>
						</div>
						<p class="field checkbox">
							<input<?php echo $tpl->getOption('comments_convert_urls'); ?> id="comments_convert_urls" name="comments_convert_urls" type="checkbox" />
							<span><label for="comments_convert_urls"><?php echo __('Convertir les URLs en liens cliquables :'); ?></label></span>
						</p>
						<div class="field_second">
							<p class="field">
								<label for="comments_links_maxlength"><?php echo __('Longueur maximale des liens :'); ?></label>
								<input value="<?php echo $tpl->getOption('comments_links_maxlength'); ?>" maxlength="3" id="comments_links_maxlength" name="comments_links_maxlength" class="text" size="3" type="text" />
								<?php echo __('caractères'); ?>

							</p>
						</div>
					</div>
				</fieldset>
				<p class="field">
					<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
					<input type="submit" class="submit" value="<?php echo __('Enregistrer'); ?>" />
				</p>
			</div>
		</form>

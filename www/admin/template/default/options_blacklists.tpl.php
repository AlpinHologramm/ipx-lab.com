
<?php include_once(dirname(__FILE__) . '/options_submenu.tpl.php'); ?>

<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

		<form action="" method="post">
			<div>
				<fieldset>
					<legend>
						<span class="help_legend"><?php echo __('Listes noires'); ?></span>
<?php if ($tpl->disHelp()) : ?>
						<a rel="h_blacklists" title="<?php echo __('Obtenir de l\'aide sur cette fonction'); ?>" class="help_link help_link_context" href="javascript:;"><img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/help-link.png" alt="<?php echo __('Aide'); ?>" /></a>
<?php endif; ?>
					</legend>
					<div class="fielditems">
						<div class="blacklist">
							<h3><?php echo __('Mots'); ?></h3>
							<textarea name="blacklist_words" rows="15" cols="30"><?php echo $tpl->getBlacklist('words'); ?></textarea>
						</div>
						<div class="blacklist">
							<h3><?php echo __('Noms d\'utilisateur'); ?></h3>
							<textarea name="blacklist_names" rows="15" cols="30"><?php echo $tpl->getBlacklist('names'); ?></textarea>
						</div>
						<div class="blacklist">
							<h3><?php echo __('Adresses IP'); ?></h3>
							<textarea name="blacklist_ips" rows="15" cols="30"><?php echo $tpl->getBlacklist('ips'); ?></textarea>
						</div>
						<div class="blacklist">
							<h3><?php echo __('Adresses de courriel'); ?></h3>
							<textarea name="blacklist_emails" rows="15" cols="30"><?php echo $tpl->getBlacklist('emails'); ?></textarea>
						</div>
						<p class="field" id="blacklist_bottom">
							<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
							<input class="submit" type="submit" value="<?php echo __('Enregistrer'); ?>" />
						</p>
					</div>
				</fieldset>
			</div>
		</form>

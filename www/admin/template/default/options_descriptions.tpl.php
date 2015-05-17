
<?php include_once(dirname(__FILE__) . '/options_submenu.tpl.php'); ?>

<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

		<form action="" method="post">
			<div>
				<fieldset>
					<legend>
						<span class="help_legend"><?php echo __('Modèles de description'); ?></span>
<?php if ($tpl->disHelp()) : ?>
						<a rel="h_descriptions" title="<?php echo __('Obtenir de l\'aide sur cette fonction'); ?>" class="help_link help_link_context" href="javascript:;"><img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/help-link.png" alt="<?php echo __('Aide'); ?>" /></a>
<?php endif; ?>
					</legend>
					<div class="fielditems">
						<p class="field checkbox">
							<input<?php echo $tpl->getOption('desc_template_categories_active'); ?> id="desc_template_categories_active" name="desc_template_categories_active" type="checkbox" />
							<span><label for="desc_template_categories_active"><?php echo __('Utiliser un modèle de description pour les catégories'); ?></label></span>
						</p>
						<div class="field_second">
<?php while ($tpl->nextLang()) : ?>
							<p<?php if (!$tpl->disLang('selected')) : ?> style="display:none"<?php endif; ?> class="field field_ftw field_html">
								<label class="icon icon_<?php echo $tpl->getLang('code'); ?>" for="desc_template_categories_text_<?php echo $tpl->getLang('code'); ?>"><?php echo __('Modèle de description :'); ?></label>
								<span class="field_html_tag">
									<a title="<?php echo __('Cliquez pour obtenir la liste des balises autorisées'); ?>" href="javascript:;">HTML</a>
								</span>
								<span class="field_html_textarea">
									<textarea class="resizable" rows="8" cols="30" id="desc_template_categories_text_<?php echo $tpl->getLang('code'); ?>" name="desc_template_categories_text[<?php echo $tpl->getLang('code'); ?>]"><?php echo $tpl->getOption('desc_template_categories_text'); ?></textarea>
								</span>
							</p>
<?php endwhile; ?>
						</div>
						<br />
						<p class="field checkbox">
							<input<?php echo $tpl->getOption('desc_template_images_active'); ?> id="desc_template_images_active" name="desc_template_images_active" type="checkbox" />
							<span><label for="desc_template_images_active"><?php echo __('Utiliser un modèle de description pour les images'); ?></label></span>
						</p>
						<div class="field_second">
<?php while ($tpl->nextLang()) : ?>
							<p<?php if (!$tpl->disLang('selected')) : ?> style="display:none"<?php endif; ?> class="field field_ftw field_html">
								<label class="icon icon_<?php echo $tpl->getLang('code'); ?>" for="desc_template_images_text_<?php echo $tpl->getLang('code'); ?>"><?php echo __('Modèle de description :'); ?></label>
								<span class="field_html_tag">
									<a title="<?php echo __('Cliquez pour obtenir la liste des balises autorisées'); ?>" href="javascript:;">HTML</a>
								</span>
								<span class="field_html_textarea">
									<textarea class="resizable" rows="8" cols="30" id="desc_template_images_text_<?php echo $tpl->getLang('code'); ?>" name="desc_template_images_text[<?php echo $tpl->getLang('code'); ?>]"><?php echo $tpl->getOption('desc_template_images_text'); ?></textarea>
								</span>
							</p>
<?php endwhile; ?>
						</div>
					</div>
				</fieldset>
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
				<input type="submit" class="submit" value="<?php echo __('Enregistrer'); ?>" />
			</div>
		</form>

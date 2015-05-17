		<form class="obj_w_form" action="<?php echo $tpl->getPagePerso('form_action'); ?>" method="post">
			<div>
<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

				<fieldset>
<?php while ($tpl->nextLang()) : ?>
					<p<?php if (!$tpl->disLang('selected')) : ?> style="display:none"<?php endif; ?> class="field field_ftw">
						<label class="icon_lang icon_<?php echo $tpl->getLang('code'); ?>" for="title_<?php echo $tpl->getLang('code'); ?>"><?php echo __('Titre :'); ?></label>
						<input value="<?php echo $tpl->getPagePerso('title_lang'); ?>" id="title_<?php echo $tpl->getLang('code'); ?>" name="title[<?php echo $tpl->getLang('code'); ?>]" type="text" class="text onload_focus" maxlength="64" size="50" />
					</p>
<?php endwhile; ?>
<?php while ($tpl->nextLang()) : ?>
					<p<?php if (!$tpl->disLang('selected')) : ?> style="display:none"<?php endif; ?> class="field field_ftw field_html">
						<label class="icon_lang icon_<?php echo $tpl->getLang('code'); ?>" for="text_<?php echo $tpl->getLang('code'); ?>"><?php printf(__('Contenu (%s caractères maximum) :'), $tpl->getPagePerso('content_maxlength')); ?></label>
						<span class="field_html_tag">
							<a title="<?php echo __('Cliquez pour obtenir la liste des balises autorisées'); ?>" href="javascript:;">HTML</a>
						</span>
						<span class="field_html_textarea">
							<textarea class="resizable" rows="12" cols="50" id="text_<?php echo $tpl->getLang('code'); ?>" name="text[<?php echo $tpl->getLang('code'); ?>]"><?php echo $tpl->getPagePerso('text'); ?></textarea>
						</span>
					</p>
<?php endwhile; ?>
					<p class="field">
						<?php echo __('OU'); ?>
					</p>
					<p class="field checkbox">
						<input<?php echo $tpl->getPagePerso('file'); ?> type="checkbox" name="file" id="file" />
						<span><label for="file"><?php echo __('Utiliser ce fichier :'); ?></label></span>
					</p>
					<div class="field_second">
						<p class="field">
							files/pages/<select name="filename">
<?php if ($tpl->disContentFiles()) : ?>
								<?php echo $tpl->getContentFiles(); ?>
<?php else : ?>
								<option disabled="disabled">&nbsp;</option>

<?php endif; ?>
							</select>
						</p>
					</div>
				</fieldset>
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
				<input type="submit" class="submit" value="<?php echo __('Enregistrer'); ?>" />
			</div>
		</form>

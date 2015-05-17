
<?php include_once(dirname(__FILE__) . '/users_submenu.tpl.php'); ?>

<?php include_once(dirname(__FILE__) . '/user_related.tpl.php'); ?>

		<form id="watermark_options" enctype="multipart/form-data" action="" method="post" class="form_page">
			<div>
				<fieldset>
					<legend><?php echo __('Filigrane'); ?></legend>
					<div class="fielditems">
						<p class="field">
							<input<?php if ($tpl->disWatermarkOption('watermark_none')) : ?> checked="checked"<?php endif; ?> id="watermark_none" type="radio" name="watermark_options[watermark]" value="none" />
							<label for="watermark_none"><?php echo __('Aucun filigrane'); ?></label>
						</p>
						<p class="field">
							<input<?php if ($tpl->disWatermarkOption('watermark_default')) : ?> checked="checked"<?php endif; ?> id="watermark_default" type="radio" name="watermark_options[watermark]" value="default" />
							<label for="watermark_default"><?php echo __('Utiliser le filigrane par défaut'); ?></label>
						</p>
						<p class="field">
							<input<?php if ($tpl->disWatermarkOption('watermark_specific')) : ?> checked="checked"<?php endif; ?> id="watermark_specific" type="radio" name="watermark_options[watermark]" value="specific" />
							<label for="watermark_specific"><?php echo __('Utiliser un filigrane spécifique'); ?></label>
						</p>
					</div>
				</fieldset>
				<div<?php if (!$tpl->disWatermarkOption('watermark_specific')) : ?> style="display:none"<?php endif; ?> id="watermark_options_common">
					<br />
<?php include_once(dirname(__FILE__) . '/watermark_options.tpl.php'); ?>

				</div>
				<p class="field">
					<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
					<input type="submit" class="submit" value="<?php echo __('Enregistrer'); ?>" />
				</p>
			</div>
		</form>

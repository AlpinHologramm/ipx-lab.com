
<?php include_once(dirname(__FILE__) . '/thumb_jcrop.tpl.php'); ?>

<?php include_once(dirname(__FILE__) . '/albums_submenu.tpl.php'); ?>

<?php include_once(dirname(__FILE__) . '/image_related.tpl.php'); ?>

		<h3><?php echo __('Modification de la vignette') ?></h3>

		<div class="browse browse_wlimit">
			<label><?php echo __('Parcourir :'); ?></label>
			<select name="browse" onchange="window.location.href='<?php echo $tpl->getAdmin('admin_base_url'); ?>'+this.options[this.selectedIndex].value">
				<?php echo $tpl->getImagesList(); ?>

			</select>
		</div>

		<p id="position" class="pos2"><?php echo $tpl->getPosition(); ?></p>

<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

		<form class="form_page" id="edit" action="" method="post">
			<div>
				<fieldset>
					<legend><?php echo __('Rognage'); ?></legend>
					<div class="field">
						<div id="image_container">
							<div id="image" style="margin:<?php echo $tpl->getImagePreview('center'); ?>;">
								<img width="<?php echo $tpl->getImagePreview('width'); ?>"
									height="<?php echo $tpl->getImagePreview('height'); ?>"
									alt="<?php echo $tpl->getImageInfo('title'); ?>"
									src="<?php echo $tpl->getImagePreview('src'); ?>" />
							</div>
						</div>
						<div id="thumbs">
							<div id="current">
								<p><?php echo __('Vignette actuelle'); ?></p>
								<img <?php echo $tpl->getThumb('size'); ?>

									style="padding:<?php echo $tpl->getThumb('center'); ?>;"
									alt="<?php echo __('Vignette actuelle'); ?>"
									src="<?php echo $tpl->getThumb('src'); ?>" />
							</div>
							<p><?php echo __('Aperçu'); ?></p>
							<div style="width:<?php echo $tpl->getConf('thumb_width'); ?>px;height:<?php echo $tpl->getConf('thumb_height'); ?>px" id="preview_container">
								<div style="width:<?php echo $tpl->getConf('thumb_width'); ?>px;height:<?php echo $tpl->getConf('thumb_height'); ?>px" id="preview">
									<img alt="<?php echo __('Aperçu'); ?>"
										src="<?php echo $tpl->getImagePreview('src'); ?>" />
								</div>
							</div>
						</div>
					</div>

					<div class="clear"></div>

<?php if ($tpl->getConf('thumb_ratio') == 0 || $tpl->getConf('thumb_ratio') == 1) : ?>
					<div id="crop_tools" class="tools">
						<span class="icon icon_select_all"><a id="jcrop_all" class="js" href="javascript:;"><?php echo __('tout sélectionner'); ?></a></span>
					</div>
<?php endif; ?>
				</fieldset>
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
				<input name="action" value="crop" type="hidden" />
				<input id="crop_coords" name="coords" type="hidden" value="" />
				<input type="submit" class="submit" value="<?php echo __('Enregistrer'); ?>" />
			</div>
		</form>

		<form class="form_page" id="thumb_delete" action="" method="post">
			<div>
				<fieldset>
					<legend><?php echo __('Suppression'); ?></legend>
				</fieldset>
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
				<input name="action" value="delete" type="hidden" />
				<input type="submit" class="submit" value="<?php echo __('Supprimer la vignette'); ?>" />
			</div>
		</form>


<?php include_once(dirname(__FILE__) . '/albums_submenu.tpl.php'); ?>

<?php include_once(dirname(__FILE__) . '/image_related.tpl.php'); ?>

		<h3><?php echo __('Modification de l\'image') ?></h3>

		<div class="browse browse_wlimit">
			<label><?php echo __('Parcourir :'); ?></label>
			<select name="browse" onchange="window.location.href='<?php echo $tpl->getAdmin('admin_base_url'); ?>'+this.options[this.selectedIndex].value">
				<?php echo $tpl->getImagesList(); ?>

			</select>
		</div>

		<p id="position" class="pos2"><?php echo $tpl->getPosition(); ?></p>

<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

		<script type="text/javascript">
		var image_width = <?php echo $tpl->getImageInfo('width'); ?>;
		var image_height = <?php echo $tpl->getImageInfo('height'); ?>;
		var width = <?php echo $tpl->getImagePreview('width'); ?>;
		var height = <?php echo $tpl->getImagePreview('height'); ?>;
		var current_coords = '0,0,' + width + ',' + height;
		var orientation = 0;
		</script>

		<form class="form_page" id="edit" action="" method="post">
			<div>
				<fieldset>
					<legend><?php echo __('Retouche'); ?></legend>
					<div class="field">
						<div id="image_container">
							<div id="image_container_inner">
								<div class="image_preview" id="image_rotate"
									 style="display:none;padding:<?php echo $tpl->getImagePreview('center'); ?>">
									<img id="img_rotate" width="<?php echo $tpl->getImagePreview('width'); ?>"
										height="<?php echo $tpl->getImagePreview('height'); ?>"
										alt="<?php echo $tpl->getImageInfo('title'); ?>"
										src="<?php echo $tpl->getImagePreview('src'); ?>" />
								</div>
								<div class="image_preview" id="image_resize"
									 style="display:none;padding:<?php echo $tpl->getImagePreview('center'); ?>">
									<img width="<?php echo $tpl->getImagePreview('width'); ?>"
										height="<?php echo $tpl->getImagePreview('height'); ?>"
										alt="<?php echo $tpl->getImageInfo('title'); ?>"
										src="<?php echo $tpl->getImagePreview('src'); ?>" />
								</div>
								<div class="image_preview" id="image_crop"
									 style="padding:<?php echo $tpl->getImagePreview('center'); ?>">
									<img width="<?php echo $tpl->getImagePreview('width'); ?>"
										height="<?php echo $tpl->getImagePreview('height'); ?>"
										alt="<?php echo $tpl->getImageInfo('title'); ?>"
										src="<?php echo $tpl->getImagePreview('src'); ?>" />
								</div>
							</div>
						</div>
						<ul id="tools">
							<li id="current_tool"><span class="icon icon_crop" id="crop"><a class="js" href="javascript:;"><?php echo __('rognage'); ?></a></span></li>
							<li><span class="icon icon_rotate" id="rotate"><a class="js" href="javascript:;"><?php echo __('rotation'); ?></a></span></li>
							<li><span class="icon icon_resize" id="resize"><a class="js" href="javascript:;"><?php echo __('dimensions'); ?></a></span></li>
						</ul>
						<p id="see"><a href="<?php echo $tpl->getImageInfo('link'); ?>"><?php echo __('Voir l\'image'); ?></a> (<?php echo $tpl->getImageInfo('filesize'); ?>)</p>
					</div>

					<div class="clear"></div>

					<div id="crop_tools" class="tools">
						<span class="icon icon_select_all"><a id="jcrop_all" class="js" href="javascript:;"><?php echo __('tout sélectionner'); ?></a></span>
					</div>
					<div style="display:none;" id="rotate_tools" class="tools">
						<span class="icon icon_rotate_left" id="rotate_left"><a class="js" href="javascript:;"><?php echo __('rotation à gauche de 90°'); ?></a></span>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<span class="icon icon_rotate_right" id="rotate_right"><a class="js" href="javascript:;"><?php echo __('rotation à droite de 90°'); ?></a></span>
					</div>
					<div style="display:none;" id="resize_tools" class="tools">
						<input size="4" maxlength="3" id="percent" name="percent" type="text" class="text" value="100" /> %
						<label for="width"><?php echo __('Largeur :'); ?></label>
						<input size="6" maxlength="5" id="width" name="width" type="text" class="text" value="<?php echo $tpl->getImageInfo('image_width'); ?>" /> <?php echo __('pixels'); ?>
						<label for="height"><?php echo __('Hauteur :'); ?></label>
						<input size="6" maxlength="5" id="height" name="height" type="text" class="text" value="<?php echo $tpl->getImageInfo('image_height'); ?>" /> <?php echo __('pixels'); ?>
					</div>

					<p class="field checkbox">
						<label for="gd_quality"><?php echo __('Qualité (entre 0 et 100) :'); ?></label>
						<input value="85" id="gd_quality" name="gd_quality" class="text" maxlength="4" type="text" size="4" />
					</p>
				</fieldset>
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
				<input type="hidden" name="action" value="modify" />
				<input type="hidden" name="crop" value="" />
				<input type="hidden" name="rotate" value="" />
				<input type="submit" class="submit" value="<?php echo __('Enregistrer'); ?>" />
			</div>
		</form>

<?php if ($tpl->disPerm('albums_add')) : ?>
		<form id="image_replace" class="form_page" enctype="multipart/form-data" action="" method="post">
			<div>
				<fieldset>
					<legend><?php echo __('Remplacement'); ?></legend>
					<p class="field field_ftw">
						<?php echo __('Nouvelle image :'); ?>

						<input class="text" type="file" name="file" size="40" maxlength="2048" />
						<input name="MAX_FILE_SIZE" value="<?php echo $tpl->getMaxFileSize(); ?>" type="hidden" />
					</p>
					<p class="field limits"><?php printf(__('Votre image doit être au format JPEG, GIF ou PNG uniquement et faire %s Ko et %s pixels maximum (ces valeurs peuvent être changées dans la section "Utilisateurs / Options").'), $tpl->getLimits('maxfilesize'), $tpl->getLimits('maxsize')); ?></p>
				</fieldset>
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
				<input type="hidden" name="action" value="replace" />
				<input type="submit" class="submit" value="<?php echo __('Envoyer'); ?>" />
			</div>
		</form>
<?php endif; ?>

<?php if ($tpl->disEdit('restore')) : ?>
		<form class="form_page" action="" method="post">
			<div>
				<fieldset>
					<legend><?php echo __('Restauration'); ?></legend>
				</fieldset>
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
				<input type="hidden" name="action" value="restore" />
				<input type="submit" class="submit" value="<?php echo __('Rétablir l\'image originale'); ?>" />
			</div>
		</form>
<?php endif; ?>

<?php if ($tpl->disPerm('albums_modif')) : ?>
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
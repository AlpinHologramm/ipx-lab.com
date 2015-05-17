
<?php include_once(dirname(__FILE__) . '/albums_submenu.tpl.php'); ?>

<?php include_once(dirname(__FILE__) . '/' . ($tpl->disCategoryInfo('type_album') ? 'album' : 'category') . '_related.tpl.php'); ?>

		<h3 class="h3_help_link">
			<span><?php echo $tpl->disCategoryInfo('type_album') ? __('Géolocalisation de l\'album') : __('Géolocalisation de la catégorie') ?></span>
<?php if ($tpl->disHelp()) : ?>
			<a rel="h_coords" title="<?php echo __('Obtenir de l\'aide sur cette fonction'); ?>" class="help_link help_link_context" href="javascript:;"><img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/help-link.png" alt="<?php echo __('Aide'); ?>" /></a>
<?php endif; ?>
		</h3>

		<div class="browse browse_wlimit">
			<label><?php echo __('Parcourir :'); ?></label>
			<select name="browse" onchange="window.location.href='<?php echo $tpl->getAdmin('admin_base_url'); ?>'+this.options[this.selectedIndex].value">
				<?php echo $tpl->getMap(); ?>

			</select>
		</div>

		<p id="position"><?php echo $tpl->getPosition(); ?></p>

<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

		<form id="geoloc_form" class="form_page" action="" method="post">
			<div>
				<div id="geoloc_map">
					<div id="gmap_canvas"></div>
					<script type="text/javascript">
					//<![CDATA[
					var type = google.maps.MapTypeId.<?php echo $tpl->getAdmin('geoloc_type'); ?>;
					var latitude = '<?php echo $tpl->getCategoryInfo('latitude'); ?>';
					var longitude = '<?php echo $tpl->getCategoryInfo('longitude'); ?>';
					var unknown_address = "<?php echo $tpl->getL10nJS(__('Adresse inconnue.'), 70, "\n"); ?>";
					//]]>
					</script>
					<p class="field">
						<span<?php if ($tpl->getCategoryInfo('latitude') !== '' && $tpl->getCategoryInfo('longitude') !== '') : ?> style="display:none"<?php endif; ?> id="add_marker" class="icon icon_geomarker_add"><a href="javascript:;" class="js"><?php echo __('Ajouter le marqueur'); ?></a></span>
						<span<?php if ($tpl->getCategoryInfo('latitude') === '' || $tpl->getCategoryInfo('longitude') === '') : ?> style="display:none"<?php endif; ?> id="del_marker" class="icon icon_geomarker_del"><a href="javascript:;" class="js"><?php echo __('Supprimer le marqueur'); ?></a></span>
					</p>
				</div>
				<div id="geoloc_coords">
					<div id="geoloc_coords_tools">
						<p class="field field_ftw">
							<label for="places"><?php echo __('Lieux connus :'); ?></label>
							&nbsp;
							<select id="places">
								<?php echo $tpl->getCategoryInfo('places'); ?>

							</select>
						</p>
						<p class="field field_ftw">
							<label for="address"><?php echo __('Rechercher une adresse :'); ?></label>
							<textarea id="address" rows="3" cols="25"></textarea>
							<span class="icon icon_search"><a id="address_search" class="js" href="javascript:;"><?php echo __('rechercher'); ?></a></span>
						</p>
					</div>
					<p class="field field_ftw">
						<label for="latitude"><?php echo __('Latitude :'); ?></label>
						<input name="<?php echo $tpl->getCategoryInfo('id'); ?>[latitude]" size="40" maxlength="25" id="latitude" type="text" class="text" value="<?php echo $tpl->getCategoryInfo('latitude'); ?>" />
					</p>
					<p class="field field_ftw">
						<label for="longitude"><?php echo __('Longitude :'); ?></label>
						<input name="<?php echo $tpl->getCategoryInfo('id'); ?>[longitude]" size="40" maxlength="25" id="longitude" type="text" class="text" value="<?php echo $tpl->getCategoryInfo('longitude'); ?>" />
					</p>
					<p class="field field_ftw">
						<label for="place"><?php echo __('Lieu :'); ?></label>
						<input name="<?php echo $tpl->getCategoryInfo('id'); ?>[place]" size="40" maxlength="100" id="place" type="text" class="text" value="<?php echo $tpl->getCategoryInfo('place'); ?>" />
					</p>
				</div>
				<div class="clear"></div>
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
				<input class="submit" name="save" type="submit" value="<?php echo __('Enregistrer'); ?>" />
			</div>
		</form>

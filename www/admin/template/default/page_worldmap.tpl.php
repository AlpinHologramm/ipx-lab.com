
<?php include_once(dirname(__FILE__) . '/pages_submenu.tpl.php'); ?>

		<p id="position"><a href="<?php echo $tpl->getLink('pages'); ?>"><?php echo __('Pages'); ?></a> / <span class="current"><a href="<?php echo $tpl->getLink('page/worldmap'); ?>"><?php echo $tpl->getPageWorldmap('title_default'); ?></a></span></p>

		<form class="obj_w_form" id="page_worldmap" action="" method="post">
			<div>
<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

				<fieldset>
					<p class="field">
						<?php echo __('Centrage et zoom de la carte :'); ?>
					</p>
					<br />
					<div id="gmap_canvas"></div>
					<script type="text/javascript">
					//<![CDATA[
					var center_lat = <?php echo $tpl->getPageWorldmap('center_lat'); ?>;
					var center_long = <?php echo $tpl->getPageWorldmap('center_long'); ?>;
					var type = google.maps.MapTypeId.<?php echo $tpl->getAdmin('geoloc_type'); ?>;
					var zoom = <?php echo $tpl->getPageWorldmap('zoom'); ?>;
					//]]>
					</script>
				</fieldset>
				<input name="center_lat" type="hidden" value="<?php echo $tpl->getPageWorldmap('center_lat'); ?>" />
				<input name="center_long" type="hidden" value="<?php echo $tpl->getPageWorldmap('center_long'); ?>" />
				<input name="zoom" type="hidden" value="<?php echo $tpl->getPageWorldmap('zoom'); ?>" />
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
				<input type="submit" class="submit" value="<?php echo __('Enregistrer'); ?>" />
			</div>
		</form>

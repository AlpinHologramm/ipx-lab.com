<?php include_once(dirname(__FILE__) . '/functions_submenu.tpl.php'); ?>

		<div id="tools_browse">
			<div class="browse">
				<select id="functions_list" onchange="window.location.href='#'+this.options[this.selectedIndex].value">
					<option value="section_functions"><?php echo __('Parcourir :'); ?></option>
					<option value="w_text"><?php echo __('Texte'); ?></option>
					<option value="w_image"><?php echo __('Image'); ?></option>
					<option value="w_general"><?php echo __('Général'); ?></option>
				</select>
			</div>
		</div>

		<p id="position"><a href="<?php echo $tpl->getLink('functions'); ?>"><?php echo __('Fonctionnalités'); ?></a> / <span class="current"><a href="<?php echo $tpl->getLink('watermark'); ?>"><?php echo __('Filigrane'); ?></a></span></p>

<?php if ($tpl->disReport()) : ?>
<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

<?php else : ?>
				<br />
<?php endif; ?>

		<form id="watermark_options" enctype="multipart/form-data" action="" method="post">
			<div>
<?php include_once(dirname(__FILE__) . '/watermark_options.tpl.php'); ?>

				<p class="field">
					<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
					<input type="submit" class="submit" value="<?php echo __('Enregistrer'); ?>" />
				</p>
			</div>
		</form>

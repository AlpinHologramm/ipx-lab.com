
		<h2><a href="<?php echo $tpl->getLink('ftp'); ?>"><?php echo __('Ajout d\'images par FTP'); ?></a></h2>

		<div id="sub_menu_line"></div><div id="sub_menu_bg"></div>

		<form action="" method="post">
			<div>
<?php if ($tpl->disFTPReport('time_exceeded')) : ?>
				<input name="time_exceeded" type="hidden" value="<?php echo $tpl->getFTPReport('notify_groups_exclude'); ?>" />
<?php endif; ?>
				<p class="field checkbox">
					<input checked="checked" id="publish_images" name="publish_images" type="checkbox" />
					<span><label for="publish_images"><?php echo __('Publier les nouvelles images'); ?></label></span>
				</p>
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
				<input type="hidden" name="action" value="scan" />
				<input type="submit" class="submit" value="<?php echo __('Scanner le répertoire des albums'); ?>" />
<?php if ($tpl->disHelp()) : ?>
				<a rel="h_ftp" title="<?php echo __('Obtenir de l\'aide sur cette fonction'); ?>" class="help_link help_link_context" href="javascript:;"><img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/help-link.png" alt="<?php echo __('Aide'); ?>" /></a>
<?php endif; ?>
			</div>
		</form>

<?php if ($tpl->disFTPReport('report_sum')) : ?>
		<div id="ftp_report">
<?php if ($tpl->disFTPReport('error')) : ?>
			<p class="report_msg report_error"><?php echo $tpl->getFTPReport('error'); ?></p>
<?php endif; ?>
<?php if ($tpl->disFTPReport('time_exceeded')) : ?>
			<p class="report_msg report_info"><?php echo $tpl->getFTPReport('time_exceeded'); ?></p>
			<br />
<?php endif; ?>
<?php if ($tpl->disFTPReport('report_sum')) : ?>
			<h3><?php echo __('Rapport résumé'); ?></h3><?php echo $tpl->getFTPReport('report_sum'); ?>
<?php endif; ?>

<?php if ($tpl->disFTPReport('report_details')) : ?>
			<h3 id="report_details">
				<a class="js" href="javascript:showhide('#ftp_report_details');">
					<span><?php echo __('Rapport détaillé'); ?></span>
				</a>
			</h3>
			<div style="display:none" id="ftp_report_details">
				<?php echo $tpl->getFTPReport('report_details'); ?>

			</div>
<?php endif; ?>
		</div>
<?php endif; ?>

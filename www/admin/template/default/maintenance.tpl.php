		<h2><a href="<?php echo $tpl->getLink('maintenance'); ?>"><?php echo __('Maintenance'); ?></a></h2>

		<div id="sub_menu_line"></div><div id="sub_menu_bg"></div>

		<script type="text/javascript">
		//<![CDATA[
		var confirm_delete = "<?php echo $tpl->getL10nJS(__('Êtes-vous sûr de vouloir supprimer ces fichiers ?')); ?>";
		//]]>
		</script>

<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>
<?php if ($tpl->disReport('success') && $tpl->disDetailsReport()) : ?>
		<h3 id="report_details">
			<a class="js" href="javascript:showhide('#stats_report_details');">
				<span><?php echo __('Rapport détaillé'); ?></span>
			</a>
		</h3>
		<div style="display:none" id="stats_report_details">
			<?php echo $tpl->getDetailsReport(); ?>

		</div>
<?php endif; ?>
		<p class="report_msg report_exclamation"><?php echo __('Notez que certaines de ces opérations peuvent être longues si vous avez un grand nombre d\'images.'); ?></p>
		<br />
		<form action="" method="post">
			<div>
				<fieldset>
					<legend><?php echo __('Base de données'); ?></legend>
					<div class="fielditems">
						<p class="field">
							<a rel="db_optimize" href="javascript:;"><?php echo __('Optimiser les tables'); ?></a>
						</p>
						<p class="field">
							<a rel="db_stats" href="javascript:;"><?php echo __('Vérifier et corriger les statistiques des images et des catégories'); ?></a>
						</p>
					</div>
				</fieldset>
				<br />
				<fieldset>
					<legend><?php echo __('Opérations sur le disque'); ?></legend>
					<div class="fielditems">
						<p class="field">
							<a class="confirm" rel="delete_tb_img" href="javascript:;"><?php echo __('Supprimer les vignettes des images'); ?></a>
						</p>
						<p class="field">
							<a class="confirm" rel="delete_tb_cat" href="javascript:;"><?php echo __('Supprimer les vignettes des catégories'); ?></a>
						</p>
						<p class="field">
							<a class="confirm" rel="delete_im_resize" href="javascript:;"><?php echo __('Supprimer les images de taille intermédiaire'); ?></a>
						</p>
						<p class="field">
							<a class="confirm" rel="delete_im_watermark" href="javascript:;"><?php echo __('Supprimer les images avec filigrane'); ?></a>
						</p>
						<p class="field">
							<a class="confirm" rel="delete_im_edit" href="javascript:;"><?php echo __('Supprimer les images d\'édition'); ?></a>
						</p>
						<p class="field">
							<a rel="delete_up_temp" href="javascript:;"><?php echo __('Supprimer les fichiers du répertoire temporaire'); ?></a>
						</p>
<?php if ((defined('PHP_OS') && PHP_OS == 'WINNT' && defined('PHP_VERSION') && PHP_VERSION < 5.3) === FALSE) : ?>
						<p class="field">
							<a rel="change_filemtime" href="javascript:;"><?php echo __('Changer la date de dernière modification des répertoires de catégories'); ?></a>
						</p>
<?php endif; ?>
					</div>
				</fieldset>
				<p class="field" style="display:none">
					<input type="hidden" name="tool" value="" />
					<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
					<input type="submit" class="submit" value="" />
				</p>
			</div>
		</form>

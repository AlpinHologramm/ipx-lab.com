		<div class="box">
			<table>
				<tr><td class="box_title"><div><h2><?php echo __('Ajouter des images'); ?></h2></div></td></tr>
				<tr>
					<td class="box_edit aac">
						<form action="" method="post">
							<div>
<?php if ($tpl->disReport()) : ?>
								<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

<?php endif; ?>
								<fieldset>
									<p><?php echo __('Choisissez l\'album dans lequel vous souhaitez envoyer des images :'); ?></p>
									<div id="upload_categories"></div>

									<p class="message message_info" id="select_path"><?php echo __('Aucun album sélectionné.'); ?></p>
									<p class="field"><?php echo __('Images à ajouter à l\'album :'); ?></p>
									<div class="field">
										<div id="fileUploadQueue"></div>
									</div>
<?php if ($tpl->disValidate()) : ?>
									<p class="message message_info"><?php echo __('Vos images n\'apparaîtront dans la galerie qu\'après validation par un administrateur.'); ?></p>
<?php endif; ?>
									<p class="message message_info"><?php printf(__('Vos images doivent être au format JPEG, GIF ou PNG uniquement et faire %s Ko et %s pixels maximum par fichier.'), $tpl->getLimits('maxfilesize'), $tpl->getLimits('maxsize')); ?></p>
									<span id="uploadify"></span>
									<div id="fileUploadButtons">
										<input name="anticsrf" type="hidden" value="<?php echo $tpl->getGallery('anticsrf'); ?>" />
										<input name="tempdir" type="hidden" value="<?php echo $tpl->getTempDir(); ?>" />
										<input id="fileUploadAdd" class="submit" type="button" value="<?php echo __('Ajouter des fichiers'); ?>" />
										<input id="fileUploadClear" class="submit" type="button" value="<?php echo __('Vider la liste'); ?>" />
										<input id="fileUploadStart" class="submit" type="button" value="<?php echo __('Envoyer'); ?>" />
									</div>
									<script type="text/javascript">
									//<![CDATA[
									<?php echo $tpl->getAlbumsList(); ?>

									var l10n_none = "<?php echo $tpl->getL10nJS(__('Aucun album sélectionné.')); ?>";
									var l10n_upload_album = "<?php echo $tpl->getL10nJS(__('Vous devez d\'abord sélectionner un album.')); ?>";
									var cat_separator = '<?php echo $tpl->getGallery('level_separator'); ?>';
									var max_file_size = <?php echo $tpl->getMaxFileSize(); ?>;
									var upload_session_token = '<?php echo $tpl->getAuthUser('session_token'); ?>';
									var upload_tempdir = '<?php echo $tpl->getTempDir(); ?>';
									//]]>
									</script>
								</fieldset>
							</div>
						</form>
					</td>
				</tr>
			</table>
		</div>
<?php include(dirname(__FILE__) . '/user_menu.tpl.php'); ?>

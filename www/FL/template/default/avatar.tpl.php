		<div class="box">
			<table>
				<tr>
					<td class="box_title" colspan="2">
						<div>
							<h2><?php echo $tpl->getProfile('login'); ?></h2>
<?php if ($tpl->disPerm('members_list')) : ?>
							<span>(<a href="<?php echo $tpl->getProfile('group_link'); ?>"><?php echo $tpl->getProfile('group_title'); ?></a>)</span>
<?php endif; ?>
						</div>
					</td>
				</tr>
				<tr>
					<td class="box_avatar">
						<img <?php echo $tpl->getProfile('avatar_size'); ?> alt="<?php printf(__('Avatar de %s'), $tpl->getProfile('login')); ?>" src="<?php echo $tpl->getProfile('avatar_src'); ?>" />
					</td>
					<td class="box_edit">
						<form id="new_avatar" enctype="multipart/form-data" method="post" action="">
							<div>
<?php if ($tpl->disReport()) : ?>
								<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

<?php endif; ?>
								<input name="MAX_FILE_SIZE" value="<?php echo $tpl->getProfile('avatar_maxfilesize'); ?>" type="hidden" />
								<label for="file">
									<?php echo __('Nouvel avatar :'); ?>
									<br />
									<?php printf(__('(%s Ko et %s pixels de coté maximum)'), $tpl->getProfile('avatar_maxfilesize')/1024, $tpl->getProfile('avatar_maxsize')); ?>

								</label>
								<input class="text" id="file" name="new" size="35" maxlength="2048" type="file" />
								<input name="action" value="new" type="hidden" />
								<input name="anticsrf" type="hidden" value="<?php echo $tpl->getGallery('anticsrf'); ?>" />
								<input type="submit" class="submit" value="<?php echo __('Envoyer'); ?>" />
							</div>
						</form>
<?php if ($tpl->disProfile('avatar')) : ?>
						<form id="delete_avatar" method="post" action="">
							<div>
								<input name="action" value="delete" type="hidden" />
								<input name="anticsrf" type="hidden" value="<?php echo $tpl->getGallery('anticsrf'); ?>" />
								<input type="submit" class="submit" value="<?php echo __('Supprimer votre avatar'); ?>" />
							</div>
						</form>
<?php endif; ?>
					</td>
				</tr>
			</table>
		</div>
<?php include(dirname(__FILE__) . '/user_menu.tpl.php'); ?>

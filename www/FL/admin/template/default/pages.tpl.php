
<?php include_once(dirname(__FILE__) . '/pages_submenu.tpl.php'); ?>

		<script type="text/javascript">
		//<![CDATA[
		var confirm_delete = "<?php echo $tpl->getL10nJS(__('Voulez-vous vraiment supprimer cette page ?')); ?>";
		//]]>
		</script>

		<a id="obj_w_new" href="<?php echo $tpl->getLink('new-page'); ?>"><?php echo __('créer une nouvelle page'); ?></a>

		<p id="position" class="position_help">
			<span class="current"><a href="<?php echo $tpl->getLink('pages'); ?>"><?php echo __('Pages'); ?></a></span>
		</p>

		<form class="obj_w_form" id="obj_w" action="" method="post">
			<div id="widgets">
<?php if ($tpl->disReport()) : ?>
<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

<?php else : ?>
				<br />
<?php endif; ?>

<?php while ($tpl->nextPage()) : ?>
					<div id="i_<?php echo $tpl->getPage('id'); ?>" class="obj_w <?php if (!$tpl->disPage('status')) : ?>un<?php endif; ?>selected selectable_class<?php if ($tpl->disPage('disabled')) : ?> disabled<?php endif; ?>">
						<div class="obj_w_inner<?php if ($tpl->disPage('disabled')) : ?> f_disabled<?php endif; ?>">
							<input type="hidden" name="w[<?php echo $tpl->getPage('name'); ?>]" />
							<p class="obj_w_checkbox selectable_zone"><span><input<?php if ($tpl->disPage('disabled')) : ?> disabled="disabled"<?php endif; ?> class="selectable"<?php if ($tpl->disPage('status')) : ?> checked="checked"<?php endif; ?> name="w[<?php echo $tpl->getPage('name'); ?>][activate]" type="checkbox" /></span></p>
							<p class="obj_w_sortable"><span></span></p>
							<p class="obj_w_body">
								<span>
<?php if ($tpl->disPage('link')) : ?>
									<a href="<?php echo $tpl->getPage('link'); ?>">
										<?php echo $tpl->getPage('title'); ?>

									</a>
<?php else : ?>
									<?php echo $tpl->getPage('title'); ?>

<?php endif; ?>
								</span>
							</p>
<?php if ($tpl->disPage('perso')) : ?>
							<p class="obj_w_action obj_w_delete"><span><span class="icon icon_delete"><a class="js" href="javascript:;"><?php echo __('supprimer'); ?></a></span></span></p>
<?php endif; ?>
						</div>
					</div>
<?php endwhile; ?>
			</div>
			<div>
				<input type="hidden" name="serial" id="serial" />
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
				<input type="submit" class="submit" value="<?php echo __('Enregistrer'); ?>" />
			</div>
		</form>

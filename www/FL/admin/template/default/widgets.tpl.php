
<?php include_once(dirname(__FILE__) . '/widgets_submenu.tpl.php'); ?>

		<script type="text/javascript">
		//<![CDATA[
		var confirm_delete = "<?php echo $tpl->getL10nJS(__('Voulez-vous vraiment supprimer ce widget ?')); ?>";
		//]]>
		</script>

		<a id="obj_w_new" href="<?php echo $tpl->getLink('new-widget'); ?>"><?php echo __('créer un nouveau widget'); ?></a>

		<p id="position" class="position_help">
			<span class="current"><a href="<?php echo $tpl->getLink('widgets'); ?>"><?php echo __('Widgets'); ?></a></span>
		</p>

		<form class="obj_w_form" id="obj_w" action="" method="post">
			<div id="widgets">
<?php if ($tpl->disReport()) : ?>
<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

<?php else : ?>
				<br />
<?php endif; ?>

<?php while ($tpl->nextWidget()) : ?>
					<div id="i_<?php echo $tpl->getWidget('id'); ?>" class="obj_w <?php if (!$tpl->disWidget('status')) : ?>un<?php endif; ?>selected selectable_class<?php if ($tpl->disWidget('fixed')) : ?> fixed<?php endif; ?><?php if ($tpl->disWidget('disabled')) : ?> disabled<?php endif; ?>">
						<div class="obj_w_inner<?php if ($tpl->disWidget('disabled')) : ?> f_disabled<?php endif; ?>">
							<input type="hidden" name="w[<?php echo $tpl->getWidget('name'); ?>]" />
							<p class="obj_w_checkbox selectable_zone"><span><input<?php if ($tpl->disWidget('disabled')) : ?> disabled="disabled"<?php endif; ?> class="selectable"<?php if ($tpl->disWidget('status')) : ?> checked="checked"<?php endif; ?> name="w[<?php echo $tpl->getWidget('name'); ?>][activate]" type="checkbox" /></span></p>
<?php if (!$tpl->disWidget('fixed')) : ?>
							<p class="obj_w_sortable"><span></span></p>
<?php endif; ?>
							<p class="obj_w_body">
								<span>
									<a href="<?php echo $tpl->getWidget('link'); ?>">
										<?php echo $tpl->getWidget('title'); ?>

									</a>
								</span>
							</p>
<?php if ($tpl->disWidget('perso')) : ?>
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

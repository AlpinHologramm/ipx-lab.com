<?php include_once(dirname(__FILE__) . '/functions_submenu.tpl.php'); ?>

		<p id="position">
			<a href="<?php echo $tpl->getLink('functions'); ?>"><?php echo __('Fonctionnalités'); ?></a> / <span class="current"><a href="<?php echo $tpl->getLink('exif'); ?>"><?php printf(__('Informations %s'), 'EXIF'); ?></a></span>
<?php if ($tpl->disHelp()) : ?>
			<a rel="h_exif" title="<?php echo __('Obtenir de l\'aide sur cette fonction'); ?>" class="help_link help_link_context" href="javascript:;"><img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/help-link.png" alt="<?php echo __('Aide'); ?>" /></a>
<?php endif; ?>
		</p>

		<form class="obj_w_form" action="" method="post">
			<div id="obj_w">
<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

				<br />
				<div id="links_js_select">
					<a class="js" href="javascript:select_all();"><?php echo __('tout sélectionner'); ?></a>
					-
					<a class="js" href="javascript:select_invert();"><?php echo __('inverser la sélection'); ?></a>
				</div>
				<br />
<?php while ($tpl->nextInfo()) : ?>
				<div id="i_<?php echo $tpl->getInfo('id'); ?>" class="obj_w <?php if (!$tpl->disInfo('activate')) : ?>un<?php endif; ?>selected selectable_class">
					<input type="hidden" name="w[<?php echo $tpl->getInfo('param'); ?>]" />
					<p class="obj_w_checkbox selectable_zone"><span><input class="selectable" name="w[<?php echo $tpl->getInfo('param'); ?>][activate]" type="checkbox" /></span></p>
					<p class="obj_w_sortable"><span></span></p>
					<p class="obj_w_body">
						<span class="obj_w_title"><?php echo $tpl->getInfo('name'); ?></span>
					</p>
<?php if ($tpl->disInfo('format')) : ?>
					<p class="obj_w_action obj_w_edit"><span><span class="icon icon_edit"><a class="js" href="javascript:;"><?php echo __('format'); ?></a></span></span></p>
					<div id="obj_w_edition_i_<?php echo $tpl->getInfo('id'); ?>" class="obj_w_fold obj_w_edition">
						<div class="obj_w_edition_inner">
							<p class="field field_ftw">
								<input value="<?php echo $tpl->getInfo('format'); ?>" id="obj_format_<?php echo $tpl->getInfo('id'); ?>" name="w[<?php echo $tpl->getInfo('param'); ?>][format]" type="text" class="text" maxlength="512" size="40" />
							</p>
						</div>
					</div>
<?php endif; ?>
				</div>
<?php endwhile; ?>

				<div id="obj_w_submit">
					<input type="hidden" name="serial" id="serial" />
					<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
					<input type="submit" class="submit" value="<?php echo __('Enregistrer'); ?>" />
				</div>
			</div>
		</form>

<?php include_once(dirname(__FILE__) . '/functions_submenu.tpl.php'); ?>

		<p id="position"><a href="<?php echo $tpl->getLink('functions'); ?>"><?php echo __('Fonctionnalités'); ?></a> / <span class="current"><a href="<?php echo $tpl->getLink('xmp'); ?>"><?php printf(__('Informations %s'), 'XMP'); ?></a></span></p>

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
				</div>
<?php endwhile; ?>

				<div id="obj_w_submit">
					<input type="hidden" name="serial" id="serial" />
					<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
					<input type="submit" class="submit" value="<?php echo __('Enregistrer'); ?>" />
				</div>
			</div>
		</form>

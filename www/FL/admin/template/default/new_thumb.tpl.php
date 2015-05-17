
<?php include_once(dirname(__FILE__) . '/thumb_jcrop.tpl.php'); ?>

<?php include_once(dirname(__FILE__) . '/albums_submenu.tpl.php'); ?>

<?php include_once(dirname(__FILE__) . '/' . ($tpl->disCategoryInfo('type_album') ? 'album' : 'category') . '_related.tpl.php'); ?>

		<h3><?php echo __('Nouvelle vignette') ?></h3>

		<div class="browse browse_wlimit">
			<label><?php echo __('Parcourir :'); ?></label>
			<select name="browse" onchange="window.location.href='<?php echo $tpl->getAdmin('admin_base_url'); ?>'+this.options[this.selectedIndex].value">
				<?php echo $tpl->getMap(); ?>

			</select>
		</div>

		<p id="position"><?php echo $tpl->getPosition(); ?></p>

<?php if ($tpl->disSubMap()) : ?>
		<div id="submap" class="browse browse_wlimit">
			<label><?php echo __('Afficher toutes les images de :') ?></label>

			<select name="browse" onchange="window.location.href='<?php echo $tpl->getAdmin('admin_base_url'); ?>'+this.options[this.selectedIndex].value">
				<?php echo $tpl->getSubMap(); ?>

			</select>
		</div>
<?php endif; ?>

<?php if ($tpl->disNavigation()) : ?>
		<div class="nav" id="nav_top">
			<div class="nav_left"></div>
			<div class="nav_right"><?php printf(__('page %s|%s'), $_GET['page'], $tpl->getInfo('nbPages')); ?></div>

<?php include(dirname(__FILE__) . '/nav.tpl.php'); ?>

		</div>
<?php endif; ?>

		<form id="form_new_thumb" class="thumbs" method="post" action="<?php echo $tpl->getLink('thumb-' . ($tpl->disCategoryInfo('type_album') ? 'album' : 'category') . '/' . $_GET['object_id']); ?>">
			<div>
<?php $size = 100; while ($tpl->nextThumb($size)) : ?>
				<dl>
					<dt style="width:<?php echo $size; ?>px">
						<input value="<?php echo $tpl->getThumb('id'); ?>" id="select_<?php echo $tpl->getThumb('id'); ?>" name="image_id" type="radio" />
						<script type="text/javascript">$('#select_<?php echo $tpl->getThumb('id'); ?>').hide();</script>
						<span style="width:<?php echo $size; ?>px;height:<?php echo $size; ?>px;">
							<img <?php echo $tpl->getThumb('thumb_size'); ?>

								style="padding:<?php echo $tpl->getThumb('center'); ?>;"
								alt="<?php echo $tpl->getThumb('title'); ?>"
								src="<?php echo $tpl->getThumb('src'); ?>" />
						</span>
					</dt>
				</dl>
<?php endwhile; ?>
				<p id="submit" class="clear">
					<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
					<input type="hidden" name="action" value="new_by_select" />
					<input value="<?php echo __('Enregistrer') ?>" type="submit" class="submit" />
				</p>
				<script type="text/javascript">$('#submit').hide();</script>
			</div>
		</form>

		<div class="clear"></div>

<?php if ($tpl->disNavigation()) : ?>
		<div class="nav" id="nav_bottom">
			<div class="nav_left"></div>
			<div class="nav_right"><?php printf(__('page %s|%s'), $_GET['page'], $tpl->getInfo('nbPages')); ?></div>

<?php include(dirname(__FILE__) . '/nav.tpl.php'); ?>

		</div>
<?php endif; ?>

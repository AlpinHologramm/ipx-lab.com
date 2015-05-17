
<?php include_once(dirname(__FILE__) . '/albums_submenu.tpl.php'); ?>

<?php include_once(dirname(__FILE__) . '/album_related.tpl.php'); ?>

		<h3 class="h3_help_link">
			<span><?php echo __('Tri des images'); ?></span>

<?php if ($tpl->disHelp()) : ?>
			<a rel="h_sort_images" title="<?php echo __('Obtenir de l\'aide sur cette fonction'); ?>" class="help_link help_link_context" href="javascript:;"><img width="16" height="16" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/help-link.png" alt="<?php echo __('Aide'); ?>" /></a>
<?php endif; ?>
		</h3>

		<div class="browse browse_wlimit">
			<label><?php echo __('Parcourir :'); ?></label>
			<select name="browse" onchange="window.location.href='<?php echo $tpl->getAdmin('admin_base_url'); ?>'+this.options[this.selectedIndex].value">
				<?php echo $tpl->getMap(); ?>

			</select>
		</div>

		<p id="position"><?php echo $tpl->getPosition(); ?></p>

		<div id="sort_thumbs" class="thumbs">

<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

<?php $size = 100; while ($tpl->nextThumb($size)) : ?>
			<dl id="i_<?php echo $tpl->getThumb('id'); ?>">
				<dt style="width:<?php echo $size; ?>px">
					<span style="width:<?php echo $size; ?>px;height:<?php echo $size; ?>px;">
						<img <?php echo $tpl->getThumb('thumb_size'); ?>

							style="padding:<?php echo $tpl->getThumb('center'); ?>;"
							alt="<?php echo $tpl->getThumb('title'); ?>"
							src="<?php echo $tpl->getThumb('src'); ?>" />
					</span>
				</dt>
			</dl>
<?php endwhile; ?>
		</div>

		<form id="form_sort" action="" method="post">
			<div>
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
				<input id="serial" value="" name="serial" type="hidden" />
				<input class="submit" name="sort" type="submit" value="<?php echo __('Enregistrer'); ?>" />
			</div>
		</form>

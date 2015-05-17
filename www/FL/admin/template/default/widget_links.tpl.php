<?php include_once(dirname(__FILE__) . '/widgets_submenu.tpl.php'); ?>

		<script type="text/javascript">
		//<![CDATA[
		var new_title = "<?php echo $tpl->getL10nJS(__('Nouveau lien')); ?>";
		var new_edition = "<?php echo $tpl->getL10nJS(__('éditer')); ?>";
		var new_delete = "<?php echo $tpl->getL10nJS(__('supprimer')); ?>";
		var new_field_title = "<?php echo $tpl->getL10nJS(__('Titre :')); ?>";
		var new_field_desc = "<?php echo $tpl->getL10nJS(__('Description :')); ?>";
		//]]>
		</script>

		<p id="position"><a href="<?php echo $tpl->getLink('widgets'); ?>"><?php echo __('Widgets'); ?></a> / <span class="current"><a href="<?php echo $tpl->getLink('widget/links'); ?>"><?php echo $tpl->getWidgetLinks('title_default'); ?></a></span></p>

		<form class="obj_w_form" id="widget_links" action="" method="post">
			<div id="obj_w">
<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

				<fieldset>
<?php while ($tpl->nextLang()) : ?>
					<p<?php if (!$tpl->disLang('selected')) : ?> style="display:none"<?php endif; ?> class="field field_ftw">
						<label class="icon_lang icon_<?php echo $tpl->getLang('code'); ?>" for="title_<?php echo $tpl->getLang('code'); ?>"><?php echo __('Titre (laissez vide pour utiliser le titre par défaut) :'); ?></label>
						<input value="<?php echo $tpl->getWidgetLinks('title'); ?>" id="title_<?php echo $tpl->getLang('code'); ?>" name="title[<?php echo $tpl->getLang('code'); ?>]" type="text" class="text onload_focus" maxlength="128" size="40" />
					</p>
<?php endwhile; ?>
				</fieldset>
				<p id="tools">
					<span class="icon icon_add"><a class="js" href="javascript:;"><?php echo __('ajouter un nouveau lien'); ?></a></span>
					-
					<a class="js" href="javascript:show_all();"><?php echo __('tout montrer'); ?></a>
					-
					<a class="js" href="javascript:hide_all();"><?php echo __('tout cacher'); ?></a>
				</p>

<?php while ($tpl->nextWidgetLink()) : ?>
				<div id="i_<?php echo $tpl->getWidgetLink('id'); ?>" class="obj_w <?php if (!$tpl->disWidgetLink('activate')) : ?>un<?php endif; ?>selected selectable_class">
					<input type="hidden" name="links[<?php echo $tpl->getWidgetLink('id'); ?>]" />
					<p class="obj_w_checkbox selectable_zone"><span><input class="selectable" name="links[<?php echo $tpl->getWidgetLink('id'); ?>][activate]" type="checkbox" /></span></p>
					<p class="obj_w_sortable"><span></span></p>
					<p class="obj_w_body">
						<span class="obj_w_title"><?php echo $tpl->getWidgetLink('title'); ?></span>
					</p>
					<p class="obj_w_action obj_w_edit"><span><span class="icon icon_edit"><a class="js" href="javascript:;"><?php echo __('éditer'); ?></a></span></span></p>
					<p class="obj_w_action obj_w_delete"><span><span class="icon icon_delete"><a class="js" href="javascript:;"><?php echo __('supprimer'); ?></a></span></span></p>
					<div id="obj_w_edition_i_<?php echo $tpl->getWidgetLink('id'); ?>" class="obj_w_fold obj_w_edition">
						<div class="obj_w_edition_inner">
<?php while ($tpl->nextLang()) : ?>
							<p<?php if (!$tpl->disLang('selected')) : ?> style="display:none"<?php endif; ?> class="field field_ftw">
								<label class="icon_lang icon_<?php echo $tpl->getLang('code'); ?>" for="obj_title_<?php echo $tpl->getWidgetLink('id'); ?>_<?php echo $tpl->getLang('code'); ?>"><?php echo __('Titre :'); ?></label>
								<input value="<?php echo $tpl->getWidgetLink('title_lang'); ?>" id="obj_title_<?php echo $tpl->getWidgetLink('id'); ?>_<?php echo $tpl->getLang('code'); ?>" name="links[<?php echo $tpl->getWidgetLink('id'); ?>][title][<?php echo $tpl->getLang('code'); ?>]" type="text" class="text" maxlength="128" size="40" />
							</p>
<?php endwhile; ?>
							<p class="field field_ftw">
								<label for="obj_url_<?php echo $tpl->getWidgetLink('id'); ?>"><?php echo __('URL :'); ?></label>
								<input value="<?php echo $tpl->getWidgetLink('url'); ?>" id="obj_url_<?php echo $tpl->getWidgetLink('id'); ?>" name="links[<?php echo $tpl->getWidgetLink('id'); ?>][url]" type="text" class="text" maxlength="512" size="40" />
							</p>
<?php while ($tpl->nextLang()) : ?>
							<p<?php if (!$tpl->disLang('selected')) : ?> style="display:none"<?php endif; ?> class="field field_ftw">
								<label class="icon_lang icon_<?php echo $tpl->getLang('code'); ?>" for="obj_desc_<?php echo $tpl->getWidgetLink('id'); ?>_<?php echo $tpl->getLang('code'); ?>"><?php echo __('Description :'); ?></label>
								<textarea onkeyup="this.value=this.value.slice(0,128)" id="obj_desc_<?php echo $tpl->getWidgetLink('id'); ?>_<?php echo $tpl->getLang('code'); ?>" name="links[<?php echo $tpl->getWidgetLink('id'); ?>][desc][<?php echo $tpl->getLang('code'); ?>]" rows="4" cols="40"><?php echo $tpl->getWidgetLink('desc_lang'); ?></textarea>
							</p>
<?php endwhile; ?>
						</div>
					</div>
				</div>
<?php endwhile; ?>

				<div id="obj_w_submit">
					<input type="hidden" name="serial" id="serial" />
					<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
					<input type="submit" class="submit" value="<?php echo __('Enregistrer'); ?>" />
				</div>
			</div>
		</form>

			<div class="obj_tool_box obj_tool" id="obj_tool_edit">
				<p class="obj_tool_title"><span><?php echo __('Édition'); ?></span></p>
				<form class="obj_tool_body" method="post" action="">
					<div class="fielditems">
						<p class="field" id="edit_langs">
							<label for="tool_edit_langs"><?php echo __('Langue d\'édition :'); ?></label>
							<select id="tool_edit_langs">
<?php while ($tpl->nextLang()) : ?>
								<option value="<?php echo $tpl->getLang('code'); ?>"<?php if ($tpl->disLang('default')) : ?> selected="selected"<?php endif; ?>><?php echo $tpl->getLang('name'); ?></option>
<?php endwhile; ?>
							</select>
						</p>
<?php if ($_GET['object_id'] > 1) : ?>
<?php while ($tpl->nextLang()) : ?>
						<p<?php if (!$tpl->disLang('default')) : ?> style="display:none"<?php endif; ?> class="field field_ftw">
							<label class="icon_lang icon_<?php echo $tpl->getLang('code'); ?>" for="edit_title_<?php echo $tpl->getLang('code'); ?>"><?php echo __('Titre :'); ?></label>
							<input class="obj_tool_focus text edit_title" name="title[<?php echo $tpl->getLang('code'); ?>]" maxlength="255" id="edit_title_<?php echo $tpl->getLang('code'); ?>" type="text" value="<?php echo $tpl->getCategory('title_lang'); ?>" />
						</p>
<?php endwhile; ?>
						<p class="field field_ftw">
							<label for="edit_urlname"><?php echo __('Nom d\'URL :'); ?></label>
							<input maxlength="255" id="edit_urlname" type="text" class="text" value="<?php echo $tpl->getCategory('urlname'); ?>" />
						</p>
<?php endif; ?>
<?php while ($tpl->nextLang()) : ?>
						<p<?php if (!$tpl->disLang('default')) : ?> style="display:none"<?php endif; ?> class="field field_ftw">
							<label class="icon_lang icon_<?php echo $tpl->getLang('code'); ?>" for="edit_desc_<?php echo $tpl->getLang('code'); ?>"><?php echo __('Description :'); ?></label>
							<textarea class="edit_desc" name="desc[<?php echo $tpl->getLang('code'); ?>]" rows="6" cols="30" id="edit_desc_<?php echo $tpl->getLang('code'); ?>"><?php echo $tpl->getCategory('desc_lang'); ?></textarea>
						</p>
<?php endwhile; ?>
						<p class="buttons">
							<input type="submit" class="submit" value="<?php echo __('Enregistrer'); ?>" />
							<input type="reset" class="cancel" value="<?php echo __('Annuler'); ?>" />
						</p>
						<p class="ajax_report message message_success">
							<span><?php echo __('Modifications enregistrées.'); ?></span>
						</p>
						<p class="ajax_report message message_error"><span></span></p>
					</div>
				</form>
			</div>
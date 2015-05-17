			<div class="obj_tool_box obj_tool" id="obj_tool_select">
				<p class="obj_tool_title"><span><?php echo __('Action sur la sélection'); ?></span></p>
				<form class="obj_tool_body" method="post" action="">
					<div class="fielditems">
						<p class="field message message_info" id="msg_select_nb_images">
							<?php printf(__('%s image sélectionnée'), 0); ?>

						</p>
						<p class="field">
							<?php echo __('Pour la sélection :'); ?>
							<select id="selection_action" name="action">
<?php if ($tpl->disGallery('basket')) : ?>
<?php if ($_GET['section'] != 'basket') : ?>
								<option value="basket_add"><?php echo __('ajouter au panier'); ?></option>
<?php endif; ?>
								<option value="basket_remove"><?php echo __('retirer du panier'); ?></option>
<?php endif; ?>
<?php if ($tpl->disAuthUser()) : ?>
<?php if ($_GET['section'] != 'user-favorites') : ?>
								<option value="fav_add"><?php echo __('ajouter aux favoris'); ?></option>
<?php endif; ?>
								<option value="fav_remove"><?php echo __('retirer des favoris'); ?></option>
<?php endif; ?>
<?php if ($tpl->disTagsEdit()) : ?>
								<option value="tags_add"><?php echo __('ajouter des tags'); ?></option>
								<option value="tags_remove"><?php echo __('retirer des tags'); ?></option>
<?php endif; ?>
							</select>
						</p>
						<p class="field field_ftw" id="tags_add_field">
							<label for="tags_add"><?php echo __('Tags à ajouter (séparés par une virgule) :'); ?></label>
							<textarea name="tags_add" id="tags_add" rows="3" cols="50"></textarea>
						</p>
						<p class="field field_ftw" id="tags_remove_field">
							<label for="tags_remove"><?php echo __('Tags à retirer (séparés par une virgule) :'); ?></label>
							<textarea name="tags_remove" id="tags_remove" rows="3" cols="50"></textarea>
						</p>
						<p class="buttons">
							<input disabled="disabled" type="submit" class="submit" value="<?php echo __('Valider'); ?>" />
							<input type="reset" class="cancel" value="<?php echo __('Annuler'); ?>" />
						</p>
						<p class="ajax_report message message_success"><span></span></p>
					</div>
				</form>
			</div>
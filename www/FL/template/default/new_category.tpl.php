		<div class="box">
			<table>
				<tr><td class="box_title"><div><h2><?php echo __('Nouvelle catégorie'); ?></h2></div></td></tr>
				<tr>
					<td class="box_edit aac">
						<form action="" method="post">
							<div>
<?php if ($tpl->disReport()) : ?>
								<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

<?php endif; ?>
								<fieldset>
									<p><?php echo __('Choisissez la catégorie dans laquelle vous souhaitez créer un album ou une catégorie :'); ?></p>
									<p class="field">
										<select name="category">
											<?php echo $tpl->getCategoriesList(); ?>

										</select>
									</p>
									<p class="field">
										<?php echo __('Type :'); ?>
										<input id="new_cat_cat" value="cat" name="type" type="radio" />
										<label for="new_cat_cat"><?php echo __('catégorie'); ?></label>
										&nbsp;
										<input checked="checked" id="new_cat_alb" value="alb" name="type" type="radio" />
										<label for="new_cat_alb"><?php echo __('album'); ?></label>
									</p>
									<p class="field field_ftw">
										<label for="new_cat_name"><?php echo __('Titre :'); ?></label>
										<input size="40" maxlength="128" id="new_cat_name" type="text" class="focus text" name="name" />
									</p>
									<p class="field field_ftw">
										<label for="new_cat_desc"><a rel="cat_desc" class="js_link show_parts" href="javascript:;"><?php echo __('Description :'); ?></a></label>
										<span id="cat_desc" style="display:none;">
											<textarea id="new_cat_desc" name="desc" class="show_parts_focus" cols="45" rows="5"></textarea>
										</span>
									</p>
									<input name="anticsrf" type="hidden" value="<?php echo $tpl->getGallery('anticsrf'); ?>" />
									<input type="submit" class="submit" value="<?php echo __('Créer'); ?>" />
								</fieldset>
							</div>
						</form>
					</td>
				</tr>
			</table>
		</div>
<?php include(dirname(__FILE__) . '/user_menu.tpl.php'); ?>

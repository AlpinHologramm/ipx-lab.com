				<td id="options">
					<p class="title">
<?php if ($tpl->disOptions('title')) : ?>
						<?php echo $tpl->getOptions('title'); ?>

<?php else : ?>
						<?php echo __('Options'); ?>

<?php endif; ?>
					</p>
					<form action="" method="post">
<?php if ($tpl->disOptions('styles')) : ?>
						<div class="option" id="style">
							<p><?php echo __('Style :'); ?></p>
							<select name="style">
								<option value="*">* <?php echo __('par défaut'); ?></option>
								<?php echo $tpl->getOptions('styles'); ?>

							</select>
						</div>
<?php endif; ?>
<?php if ($tpl->disOptions('image_size')) : ?>
						<div class="option" id="image_size">
							<p><?php echo __('Dimensions de l\'image :'); ?></p>
							<div id="original_size">
								<input<?php echo $tpl->getOptions('original_size'); ?> value="0" name="image_size" type="radio" id="original" />
								<label for="original"><?php echo __('Taille originale'); ?></label>
							</div>
							<div id="fixed_size">
								<input<?php echo $tpl->getOptions('fixed_size'); ?> value="1" name="image_size" type="radio" id="fixed" />
								<label for="fixed"><?php echo __('Taille maximale :'); ?></label>
								<div id="fixed_hl">
									<label for="image_width"><?php echo __('L :'); ?></label>
									<input value="<?php echo $tpl->getOptions('image_width'); ?>" size="6" maxlength="6" name="image_width" id="image_width" type="text" class="text" />
									&nbsp;
									<label for="image_height"><?php echo __('H :'); ?></label>
									<input value="<?php echo $tpl->getOptions('image_height'); ?>" size="6" maxlength="6" name="image_height" id="image_height" type="text" class="text" />
								</div>
							</div>
						</div>
<?php endif; ?>
<?php if ($tpl->disOptions('nb_thumbs')) : ?>
						<div class="option" id="nb_thumbs">
							<p><?php echo __('Nombre d\'images par page :'); ?></p>
							<input maxlength="4" size="4" name="thumbs_alb_nb" type="text" class="text" value="<?php echo $tpl->getOptions('thumbs_alb_nb'); ?>" />
						</div>
<?php endif; ?>
<?php if ($tpl->disOptions('thumbs_infos')) : ?>
						<div class="option" id="display">
							<input type="hidden" name="thumbs_infos" />
							<p><?php echo __('Montrer :'); ?></p>
<?php if ($tpl->disOptions('thumbs_image_title')) : ?>
							<p class="checkbox">
								<input<?php echo $tpl->getOptions('thumbs_stats_image_title'); ?> name="thumbs_image_title" type="checkbox" id="thumbs_image_title" />
								<span><label for="thumbs_image_title"><?php echo __('Titre des images'); ?></label></span>
							</p>
<?php endif; ?>
<?php if ($tpl->disOptions('thumbs_category_title')) : ?>
							<p class="checkbox">
								<input<?php echo $tpl->getOptions('thumbs_stats_category_title'); ?> name="thumbs_category_title" type="checkbox" id="thumbs_category_title" />
								<span><label for="thumbs_category_title"><?php echo __('Titre des catégories'); ?></label></span>
							</p>
<?php endif; ?>
<?php if ($tpl->disOptions('thumbs_images')) : ?>
							<p class="checkbox">
								<input<?php echo $tpl->getOptions('thumbs_stats_images'); ?> name="thumbs_images" type="checkbox" id="thumbs_images" />
								<span><label for="thumbs_images"><?php echo __('Nombre d\'images'); ?></label></span>
							</p>
<?php endif; ?>
<?php if ($tpl->disOptions('thumbs_albums')) : ?>
							<p class="checkbox">
								<input<?php echo $tpl->getOptions('thumbs_stats_albums'); ?> name="thumbs_albums" type="checkbox" id="thumbs_albums" />
								<span><label for="thumbs_albums"><?php echo __('Nombre d\'albums'); ?></label></span>
							</p>
<?php endif; ?>
<?php if ($tpl->disOptions('thumbs_filesize')) : ?>
							<p class="checkbox">
								<input<?php echo $tpl->getOptions('thumbs_stats_filesize'); ?> name="thumbs_filesize" type="checkbox" id="thumbs_filesize" />
								<span><label for="thumbs_filesize"><?php echo __('Poids des images'); ?></label></span>
							</p>
<?php endif; ?>
<?php if ($tpl->disOptions('thumbs_size')) : ?>
							<p class="checkbox">
								<input<?php echo $tpl->getOptions('thumbs_stats_size'); ?> name="thumbs_size" type="checkbox" id="thumbs_size" />
								<span><label for="thumbs_size"><?php echo __('Taille des images'); ?></label></span>
							</p>
<?php endif; ?>
<?php if ($tpl->disOptions('thumbs_date')) : ?>
							<p class="checkbox">
								<input<?php echo $tpl->getOptions('thumbs_stats_date'); ?> name="thumbs_date" type="checkbox" id="thumbs_date" />
								<span><label for="thumbs_date"><?php echo __('Date de mise en ligne'); ?></label></span>
							</p>
<?php endif; ?>
<?php if ($tpl->disOptions('thumbs_hits')) : ?>
							<p class="checkbox">
								<input<?php echo $tpl->getOptions('thumbs_stats_hits'); ?> name="thumbs_hits" type="checkbox" id="thumbs_hits" />
								<span><label for="thumbs_hits"><?php echo __('Nombre de visites'); ?></label></span>
							</p>
<?php endif; ?>
<?php if ($tpl->disOptions('thumbs_votes')) : ?>
							<p class="checkbox">
								<input<?php echo $tpl->getOptions('thumbs_stats_votes'); ?> name="thumbs_votes" type="checkbox" id="thumbs_votes" />
								<span><label for="thumbs_votes"><?php echo __('Note moyenne'); ?></label></span>
							</p>
<?php endif; ?>
<?php if ($tpl->disOptions('thumbs_comments')) : ?>
							<p class="checkbox">
								<input<?php echo $tpl->getOptions('thumbs_stats_comments'); ?> name="thumbs_comments" type="checkbox" id="thumbs_comments" />
								<span><label for="thumbs_comments"><?php echo __('Nombre de commentaires'); ?></label></span>
							</p>
<?php endif; ?>
<?php if ($tpl->disOptions('thumbs_recent')) : ?>
							<p class="checkbox">
								<input<?php echo $tpl->getOptions('recent_images'); ?> name="thumbs_recent" type="checkbox" id="thumbs_recent" />
								<span><label for="thumbs_recent"><?php echo __('Nouvelles images :'); ?></label></span>
							</p>
							<p>
								<input class="text" maxlength="3" size="3" name="recent_days" type="text" value="<?php echo $tpl->getOptions('recent_days'); ?>" id="recent_days" />
								<label for="recent_days"><?php echo __('derniers jours'); ?></label>
							</p>
<?php endif; ?>
						</div>
<?php endif; ?>
<?php if ($tpl->disOptions('order_by')) : ?>
						<div class="option" id="images_order">
							<p><?php echo __('Trier les images par :'); ?></p>
							<select id="order_by" name="order_by">
								<?php echo $tpl->getOptions('images_order_by'); ?>

							</select>
							<select id="asc_desc" name="asc_desc">
								<?php echo $tpl->getOptions('images_asc_desc'); ?>

							</select>

						</div>
<?php endif; ?>
						<div id="options_submit"><input class="submit" type="submit" value="<?php echo __('Valider'); ?>"/></div>
					</form>
				</td>

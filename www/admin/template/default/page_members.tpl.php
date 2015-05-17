
<?php include_once(dirname(__FILE__) . '/pages_submenu.tpl.php'); ?>

		<p id="position"><a href="<?php echo $tpl->getLink('pages'); ?>"><?php echo __('Pages'); ?></a> / <span class="current"><a href="<?php echo $tpl->getLink('page/members'); ?>"><?php echo $tpl->getPageMembers('title_default'); ?></a></span></p>

		<form class="obj_w_form" id="page_members" action="" method="post">
			<div>
<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

				<fieldset>
					<p class="field">
						<label for="nb_per_page"><?php echo __('Nombre de membres par page :'); ?></label>
						<input value="<?php echo $tpl->getPageMembers('nb_per_page'); ?>" maxlength="3" id="nb_per_page" name="nb_per_page" class="text" size="3" type="text" />
					</p>
					<p class="field">
						<label for="order_by"><?php echo __('Trier par :'); ?></label>
						<select id="order_by" name="order_by">
							<?php echo $tpl->getPageMembers('order_by_1'); ?>

						</select>
						<select name="ascdesc">
							<?php echo $tpl->getPageMembers('ascdesc_1'); ?>

						</select>
					</p>
					<p class="field">
						<?php echo __('Afficher :'); ?>
					</p>
					<div class="field_second">
						<p class="field checkbox">
							<input<?php if ($tpl->disPageMembers('show_title')) : ?> checked="checked"<?php endif; ?> id="show_title" name="show_title" type="checkbox" />
							<span><label for="show_title"><?php echo __('Titre du membre'); ?></label></span>
						</p>
						<p class="field checkbox">
							<input<?php if ($tpl->disPageMembers('show_lastvstdt')) : ?> checked="checked"<?php endif; ?> id="show_lastvstdt" name="show_lastvstdt" type="checkbox" />
							<span><label for="show_lastvstdt"><?php echo __('Date de dernière visite'); ?></label></span>
						</p>
						<p class="field checkbox">
							<input<?php if ($tpl->disPageMembers('show_crtdt')) : ?> checked="checked"<?php endif; ?> id="show_crtdt" name="show_crtdt" type="checkbox" />
							<span><label for="show_crtdt"><?php echo __('Date d\'inscription'); ?></label></span>
						</p>
					</div>
				</fieldset>
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
				<input type="submit" class="submit" value="<?php echo __('Enregistrer'); ?>" />
			</div>
		</form>

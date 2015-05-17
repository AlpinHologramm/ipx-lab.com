
<?php include_once(dirname(__FILE__) . '/pages_submenu.tpl.php'); ?>

		<p id="position"><a href="<?php echo $tpl->getLink('pages'); ?>"><?php echo __('Pages'); ?></a> / <span class="current"><a href="<?php echo $tpl->getLink('page/comments'); ?>"><?php echo $tpl->getPageComments('title_default'); ?></a></span></p>

		<form class="obj_w_form" id="page_comments" action="" method="post">
			<div>
<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

				<fieldset>
					<p class="field">
						<label for="nb_per_page"><?php echo __('Nombre de commentaires par page :'); ?></label>
						<input value="<?php echo $tpl->getPageComments('nb_per_page'); ?>" maxlength="3" id="nb_per_page" name="nb_per_page" class="text" size="3" type="text" />
					</p>
				</fieldset>
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
				<input type="submit" class="submit" value="<?php echo __('Enregistrer'); ?>" />
			</div>
		</form>

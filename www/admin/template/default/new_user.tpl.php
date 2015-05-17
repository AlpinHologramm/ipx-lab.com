
<?php include_once(dirname(__FILE__) . '/users_submenu.tpl.php'); ?>

		<span id="back"><a href="<?php echo $tpl->getLink('users'); ?>"><?php echo __('retour'); ?></a></span>
		<h3><?php echo __('Nouvel utilisateur'); ?></h3>

<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

		<form class="form_page" action="" method="post">
			<div>
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
<?php include_once(dirname(__FILE__) . '/user_profile.tpl.php'); ?>

				<input type="submit" class="submit" value="<?php echo __('Enregistrer'); ?>" />
			</div>
		</form>

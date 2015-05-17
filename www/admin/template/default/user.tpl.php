
<?php include_once(dirname(__FILE__) . '/users_submenu.tpl.php'); ?>

<?php include_once(dirname(__FILE__) . '/user_related.tpl.php'); ?>

		<form class="form_page" action="<?php echo $tpl->getUser('form_action'); ?>" method="post">
			<div>
<?php include_once(dirname(__FILE__) . '/user_profile.tpl.php'); ?>

<?php if ($tpl->disUser('edit')) : ?>
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
				<input type="submit" class="submit" value="<?php echo __('Enregistrer'); ?>" />
<?php else : ?>
				<br />
				<p id="not_allowed" class="icon icon_information"><?php echo __('Vous n\'avez pas la permission de modifier ce profil.'); ?></p>
<?php endif; ?>
			</div>
		</form>

<?php if ($tpl->disUser('modif') && !$tpl->disUser('superadmin')) : ?>
		<script type="text/javascript">
		//<![CDATA[
		var confirm_delete = "<?php echo $tpl->getL10nJS(__('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')); ?>";
		//]]>
		</script>
		<form id="confirm_delete" class="form_page" action="" method="post">
			<div>
				<fieldset>
					<legend><?php echo __('Suppression'); ?></legend>
				</fieldset>
				<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
				<input disabled="disabled" class="submit js_required" name="delete" type="submit" value="<?php echo __('Supprimer l\'utilisateur'); ?>" />
			</div>
		</form>
<?php endif; ?>
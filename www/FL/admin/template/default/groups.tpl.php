
<?php include_once(dirname(__FILE__) . '/users_submenu.tpl.php'); ?>

<?php if ($tpl->disPerm('users_groups')) : ?>
		<script type="text/javascript">
		//<![CDATA[
		var confirm_delete = "<?php echo $tpl->getL10nJS(__('Êtes-vous sûr de vouloir supprimer les groupes sélectionnés ?')); ?>";
		//]]>
		</script>

		<a id="new_group" href="<?php echo $tpl->getLink('new-group'); ?>"><?php echo __('créer un nouveau groupe'); ?></a>

		<br /><!--[if IE 7]><br /><![endif]-->
		<div id="links_js">
			<p id="links_js_select">
				<a class="js" href="javascript:select_all();"><?php echo __('tout sélectionner'); ?></a>
				-
				<a class="js" href="javascript:select_invert();"><?php echo __('inverser la sélection'); ?></a>
			</p>
		</div>

<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

		<form id="form_edit" action="" method="post">
<?php endif; ?>
			<div id="groups">
				<table>
					<tr>
						<th><?php echo __('Nom du groupe'); ?></th>
						<th><?php echo __('Titre'); ?></th>
						<th><?php echo __('Description'); ?></th>
						<th><?php echo __('Nombre de membres'); ?></th>
<?php if ($tpl->disPerm('users_groups')) : ?>
						<th class="null"></th>
<?php endif; ?>
					</tr>
<?php $n = 1; while ($tpl->nextGroup()) : ?>
					<tr class="selectable_class <?php if ($tpl->disGroup('special')) : ?> special<?php endif; ?><?php if (is_integer($n++ / 2)) : ?> even<?php endif; ?>">
						<td class="name">
<?php if ($tpl->disPerm('users_groups')) : ?>
							<a title="<?php echo __('Modifier le groupe'); ?>" href="<?php echo $tpl->getLink('group/' . $tpl->getGroup('id')); ?>">
<?php endif; ?>
								<?php echo $tpl->getGroup('name'); ?>

<?php if ($tpl->disPerm('users_groups')) : ?>
							</a>
<?php endif; ?>							
						</td>
						<td class="title"><?php echo $tpl->getGroup('title'); ?></td>
						<td class="desc"><?php echo $tpl->getGroup('desc'); ?></td>
						<td class="nb_members"><?php echo $tpl->getGroup('nb_members'); ?></td>
<?php if ($tpl->disPerm('users_groups')) : ?>
						<td class="selectable_zone"><?php if (!$tpl->disGroup('special')) : ?><input class="selectable" id="obj_check_<?php echo $tpl->getGroup('id'); ?>" name="select[<?php echo $tpl->getGroup('id'); ?>]" type="checkbox" /><?php endif; ?></td>
<?php endif; ?>
					</tr>
<?php endwhile; ?>
				</table>

			</div>

<?php if ($tpl->disPerm('users_groups')) : ?>
			<div id="actions">
				<input name="token" type="hidden" value="<?php echo $tpl->getAdmin('token'); ?>" />
				<p>
					<label for="selection_action"><?php echo __('Pour la sélection :'); ?></label>
					<select id="selection_action" name="action">
						<option value="delete"><?php echo __('supprimer'); ?></option>
					</select>
					<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
					<input class="submit" id="action_submit" name="selection" type="submit" value="<?php echo __('Valider'); ?>" />
				</p>
			</div>

		</form>
<?php endif; ?>

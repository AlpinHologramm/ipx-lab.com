
		<h2><a href="<?php echo $tpl->getLink('incidents'); ?>"><?php echo __('Incidents'); ?></a></h2>

		<div id="sub_menu_line"></div><div id="sub_menu_bg"></div>

<?php if ($tpl->disIncidents()) : ?>
		<script type="text/javascript">
		//<![CDATA[
		var confirm_delete = "<?php echo $tpl->getL10nJS(__('Êtes-vous sûr de vouloir supprimer les incidents sélectionnés ?')); ?>";
		//]]>
		</script>

		<p id="position"><span class="current"><?php echo __('incidents'); ?></span> [<?php echo $tpl->getNbIncidents(); ?>]</p>

		<div id="links_js">
			<p id="links_js_select">
				<a class="js" href="javascript:select_all();"><?php echo __('tout sélectionner'); ?></a>
				-
				<a class="js" href="javascript:select_invert();"><?php echo __('inverser la sélection'); ?></a>
			</p>
			<p id="link_forum">
				<a rel="forum" class="js" href="javascript:;"><?php echo __('format forum'); ?></a>
			</p>
		</div>

<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

<textarea id="forum" style="display:none" cols="50" rows="20">[code]<?php $n = 1; while ($tpl->nextIncident()) : ?>

# : <?php echo ($tpl->getNbIncidents()-$n)+1; ?>

<?php echo __('Date'); ?> : <?php echo $tpl->getIncident('date'); ?>

<?php echo __('Version'); ?> : <?php echo $tpl->getIncident('version'); ?>

<?php echo __('Type'); ?> : <?php echo $tpl->getIncident('type'); ?>

<?php echo __('Fichier'); ?> : <?php echo $tpl->getIncident('file'); ?>

<?php echo __('Ligne'); ?> : <?php echo $tpl->getIncident('line'); ?>

<?php echo __('Page'); ?> : <?php echo $tpl->getIncident('page'); ?>

<?php echo __('Message'); ?> : <?php echo $tpl->getIncident('message'); ?>

<?php echo __('Détails'); ?> :
<?php print_r($tpl->getIncident('details')); ?>
<?php $n++; endwhile; ?>
[/code]
</textarea>

		<form id="incidents" action="" method="post">
<?php $n = 1; while ($tpl->nextIncident()) : ?>
			<table class="selectable_class">
				<tr class="top">
					<th class="number">#</th>
					<td class="number"><?php echo ($tpl->getNbIncidents()-$n)+1; ?></td>
					<td class="details_link"><span class="icon icon_details"><a rel="details_<?php echo ($tpl->getNbIncidents()-$n)+1; ?>" class="js" href="javascript:;"><?php echo mb_strtolower(__('Détails')); ?></a></span></td>
					<td class="selectable_zone" rowspan="9"><input name="select[<?php echo $tpl->getIncident('error_file'); ?>]" class="selectable" type="checkbox" /></td>
				</tr>
				<tr class="date">
					<th><?php echo __('Date'); ?></th>
					<td colspan="2"><?php echo $tpl->getIncident('date'); ?></td>
				</tr>
				<tr class="version">
					<th><?php echo __('Version'); ?></th>
					<td colspan="2"><?php echo $tpl->getIncident('version'); ?></td>
				</tr>
				<tr class="type">
					<th><?php echo __('Type'); ?></th>
					<td colspan="2"><?php echo $tpl->getIncident('type'); ?></td>
				</tr>
				<tr class="file">
					<th><?php echo __('Fichier'); ?></th>
					<td colspan="2"><?php echo $tpl->getIncident('file'); ?></td>
				</tr>
				<tr class="line">
					<th><?php echo __('Ligne'); ?></th>
					<td colspan="2"><?php echo $tpl->getIncident('line'); ?></td>
				</tr>
				<tr class="page">
					<th><?php echo __('Page'); ?></th>
					<td colspan="2"><?php echo $tpl->getIncident('page'); ?></td>
				</tr>
				<tr class="message">
					<th><?php echo __('Message'); ?></th>
					<td colspan="2"><?php echo $tpl->getIncident('message'); ?></td>
				</tr>
				<tr id="details_<?php echo ($tpl->getNbIncidents()-$n)+1; ?>" class="details">
					<th><?php echo __('Détails'); ?></th>
					<td colspan="2"><pre><?php print_r($tpl->getIncident('details')); ?></pre></td>
				</tr>
			</table>
<?php $n++; endwhile; ?>

			<div id="actions">
				<p>
					<label for="selection_action"><?php echo __('Pour la sélection :'); ?></label>
					<select id="selection_action" name="action">
<?php if ($tpl->disIncidents('export')) : ?>
						<option value="export"><?php echo __('exporter'); ?></option>
<?php endif; ?>
						<option value="delete"><?php echo __('supprimer'); ?></option>
					</select>
					<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
					<input disabled="disabled" class="submit js_required" id="action_submit" name="selection" type="submit" value="<?php echo __('Valider'); ?>" />
				</p>
			</div>

		</form>
<?php else : ?>
<?php include_once(dirname(__FILE__) . '/report.tpl.php'); ?>

		<p class="report_msg report_info"><?php echo __('Aucun incident.'); ?></p>

<?php endif; ?>

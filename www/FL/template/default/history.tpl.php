		<p id="position"><?php echo $tpl->getPosition(); ?></p>

		<div id="history">
<?php if ($tpl->disHistoryAdddt()) : ?>
			<div id="history_added">
				<h2>
					<span><?php echo __('Dates d\'ajout'); ?></span>
					<select onchange="window.location.href='#'+this.options[this.selectedIndex].value">
<?php while ($tpl->nextHistoryAdddt('year')) : ?>
						<option value="adddt-<?php echo $tpl->getHistoryAdddt('year'); ?>"><?php echo $tpl->getHistoryAdddt('year'); ?></option>
<?php while ($tpl->nextHistoryAdddt('month')) : ?>
						<option value="adddt-<?php echo $tpl->getHistoryAdddt('year'); ?>-<?php echo $tpl->getHistoryAdddt('month'); ?>"><?php echo $tpl->getHistoryAdddt('year'); ?>-<?php echo $tpl->getHistoryAdddt('month'); ?></option>
<?php endwhile; ?>
<?php endwhile; ?>
					</select>
				</h2>


<?php while ($tpl->nextHistoryAdddt('year')) : ?>
				<table summary="<?php echo $tpl->getHistoryAdddt('year'); ?>" class="default">
					<caption><a name="adddt-<?php echo $tpl->getHistoryAdddt('year'); ?>" href="<?php echo $tpl->getHistoryAdddt('year_link'); ?>"><?php echo $tpl->getHistoryAdddt('year'); ?></a></caption>
					<thead>
						<tr>
							<th><?php echo __('Date'); ?></th>
							<th><?php echo __('Nombre d\'images'); ?></th>
						</tr>
					</thead>
					<tbody>
<?php while ($tpl->nextHistoryAdddt('month')) : ?>
						<tr>
							<td class="month" colspan="2">
								<a name="adddt-<?php echo $tpl->getHistoryAdddt('year'); ?>-<?php echo $tpl->getHistoryAdddt('month'); ?>" href="<?php echo $tpl->getHistoryAdddt('month_link'); ?>">
									<?php echo $tpl->getHistoryAdddt('month_name'); ?>

								</a>
							</td>
						</tr>
<?php while ($tpl->nextHistoryAdddt('day')) : ?>
						<tr>
							<td><a href="<?php echo $tpl->getHistoryAdddt('day_link'); ?>"><?php echo $tpl->getHistoryAdddt('date'); ?></a></td>
							<td><?php echo $tpl->getHistoryAdddt('nb_images'); ?></td>
						</tr>
<?php endwhile; ?>
<?php endwhile; ?>
					</tbody>
				</table>
<?php endwhile; ?>

			</div>
<?php endif; ?>

<?php if ($tpl->disHistoryCrtdt()) : ?>
			<div id="history_created">
				<h2>
					<span><?php echo __('Dates de création'); ?></span>
					<select onchange="window.location.href='#'+this.options[this.selectedIndex].value">
<?php while ($tpl->nextHistorycrtdt('year')) : ?>
						<option value="crtdt-<?php echo $tpl->getHistoryCrtdt('year'); ?>"><?php echo $tpl->getHistoryCrtdt('year'); ?></option>
<?php while ($tpl->nextHistorycrtdt('month')) : ?>
						<option value="crtdt-<?php echo $tpl->getHistoryCrtdt('year'); ?>-<?php echo $tpl->getHistoryCrtdt('month'); ?>"><?php echo $tpl->getHistoryCrtdt('year'); ?>-<?php echo $tpl->getHistoryCrtdt('month'); ?></option>
<?php endwhile; ?>
<?php endwhile; ?>
					</select>
				</h2>


<?php while ($tpl->nextHistoryCrtdt('year')) : ?>
				<table summary="<?php echo $tpl->getHistoryCrtdt('year'); ?>" class="default">
					<caption><a name="crtdt-<?php echo $tpl->getHistoryCrtdt('year'); ?>" href="<?php echo $tpl->getHistoryCrtdt('year_link'); ?>"><?php echo $tpl->getHistoryCrtdt('year'); ?></a></caption>
					<thead>
						<tr>
							<th><?php echo __('Date'); ?></th>
							<th><?php echo __('Nombre d\'images'); ?></th>
						</tr>
					</thead>
					<tbody>
<?php while ($tpl->nextHistoryCrtdt('month')) : ?>
						<tr>
							<td class="month" colspan="2">
								<a name="crtdt-<?php echo $tpl->getHistoryCrtdt('year'); ?>-<?php echo $tpl->getHistoryCrtdt('month'); ?>" href="<?php echo $tpl->getHistoryCrtdt('month_link'); ?>">
									<?php echo $tpl->getHistoryCrtdt('month_name'); ?>

								</a>
							</td>
						</tr>
<?php while ($tpl->nextHistoryCrtdt('day')) : ?>
						<tr>
							<td><a href="<?php echo $tpl->getHistoryCrtdt('day_link'); ?>"><?php echo $tpl->getHistoryCrtdt('date'); ?></a></td>
							<td><?php echo $tpl->getHistoryCrtdt('nb_images'); ?></td>
						</tr>
<?php endwhile; ?>
<?php endwhile; ?>
					</tbody>
				</table>
<?php endwhile; ?>

			</div>
<?php endif; ?>
		</div>
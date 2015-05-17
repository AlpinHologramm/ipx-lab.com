<?php if ($tpl->disReport()) : ?>
		<div id="report">
<?php if ($tpl->disReport('error')) : ?>
			<div class="report_msg report_error">
<?php while ($tpl->nextReport('error')) : ?>
				<p><?php echo $tpl->getReport('error'); ?></p>
<?php endwhile; ?>
			</div>
<?php endif; ?>
<?php if ($tpl->disReport('warning')) : ?>
			<div class="report_msg report_warning">
<?php while ($tpl->nextReport('warning')) : ?>
				<p><?php echo $tpl->getReport('warning'); ?></p>
<?php endwhile; ?>
			</div>
<?php endif; ?>
<?php if ($tpl->disReport('success')) : ?>
			<p class="report_msg report_success<?php if (isset($_POST['action']) && $_POST['action'] == 'crop') : ?> report_crop<?php endif; ?>"><?php echo $tpl->getReport('success'); ?></p>
<?php endif; ?>
		</div>
<?php endif; ?>

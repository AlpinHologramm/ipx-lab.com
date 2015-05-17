<?php if ($tpl->disReport('error')) : ?>
		<p class="report message message_error"><?php echo $tpl->getReport('error'); ?></p>
<?php endif; ?>
<?php if ($tpl->disReport('warning')) : ?>
		<div class="report message message_error">
<?php while ($tpl->nextReport('warning')) : ?>
			<p><?php echo $tpl->getReport('warning'); ?></p>
<?php endwhile; ?>
		</div>
<?php endif; ?>
<?php if ($tpl->disReport('success')) : ?>
		<p class="report message message_success"><?php echo $tpl->getReport('success'); ?></p>
<?php endif; ?>


<?php if ($tpl->disHelp()) : ?>
		<div id="help">
			<div id="help_handle">
				<span class="icon icon_help" id="help_title"><?php echo __('Aide'); ?></span>
				<a id="help_close" href="javascript:;"></a>
			</div>
			<div id="help_content">
				<?php $tpl->getHelp(); ?>

			</div>
		</div>
<?php endif; ?>
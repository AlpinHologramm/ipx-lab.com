<?php if ($tpl->disNavigation('prev_inactive')) : ?>
			<span class="page first inactive"><?php echo $tpl->getNavigation('first'); ?></span>
			<span class="page prev inactive"><?php echo $tpl->getNavigation('prev'); ?></span>
<?php endif; ?>

<?php if ($tpl->disNavigation('prev_active')) : ?>
			<span class="page first"><a title="<?php echo __('Première page'); ?>" href="<?php echo $tpl->getNavigation('first_link'); ?>"><?php echo $tpl->getNavigation('first'); ?></a></span>
			<span class="page prev"><a title="<?php echo __('Page précédente'); ?>" href="<?php echo $tpl->getNavigation('prev_link'); ?>"><?php echo $tpl->getNavigation('prev'); ?></a></span>
<?php endif; ?>

			<form action="" method="get">
				<div>
					<select name="page" onchange="window.location.href='<?php echo $tpl->getNavigation('link'); ?>'+this.options[this.selectedIndex].value">
						<?php echo $tpl->getNavigation('html_options'); ?>

					</select>
				</div>
			</form>

<?php if ($tpl->disNavigation('next_active')) : ?>
			<span class="page next"><a title="<?php echo __('Page suivante'); ?>" href="<?php echo $tpl->getNavigation('next_link'); ?>"><?php echo $tpl->getNavigation('next'); ?></a></span>
			<span class="page last"><a title="<?php echo __('Dernière page'); ?>" href="<?php echo $tpl->getNavigation('last_link'); ?>"><?php echo $tpl->getNavigation('last'); ?></a></span>
<?php endif; ?>

<?php if ($tpl->disNavigation('next_inactive')) : ?>
			<span class="page next inactive"><?php echo $tpl->getNavigation('next'); ?></span>
			<span class="page last inactive"><?php echo $tpl->getNavigation('last'); ?></span>
<?php endif; ?>

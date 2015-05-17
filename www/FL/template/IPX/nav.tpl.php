
<?php if ($tpl->disNavigation('prev_inactive')) : ?>
			<span class="first inactive"><?php echo $tpl->getNavigation('first'); ?></span>
			<span class="prev inactive"><?php echo $tpl->getNavigation('prev'); ?></span>
<?php endif; ?>

<?php if ($tpl->disNavigation('prev_active')) : ?>
			<span class="first"><a title="<?php echo __('Première page'); ?>" href="<?php echo $tpl->getNavigation('first_link'); ?>"><?php echo $tpl->getNavigation('first'); ?></a></span>
			<span class="prev"><a title="<?php echo __('Page précédente'); ?>" href="<?php echo $tpl->getNavigation('prev_link'); ?>"><?php echo $tpl->getNavigation('prev'); ?></a></span>
<?php endif; ?>

<?php if ($tpl->disNavigation('next_active')) : ?>
			<span class="next"><a title="<?php echo __('Page suivante'); ?>" href="<?php echo $tpl->getNavigation('next_link'); ?>"><?php echo $tpl->getNavigation('next'); ?></a></span>
			<span class="last"><a title="<?php echo __('Dernière page'); ?>" href="<?php echo $tpl->getNavigation('last_link'); ?>"><?php echo $tpl->getNavigation('last'); ?></a></span>
<?php endif; ?>

<?php if ($tpl->disNavigation('next_inactive')) : ?>
			<span class="next inactive"><?php echo $tpl->getNavigation('next'); ?></span>
			<span class="last inactive"><?php echo $tpl->getNavigation('last'); ?></span>
<?php endif; ?>

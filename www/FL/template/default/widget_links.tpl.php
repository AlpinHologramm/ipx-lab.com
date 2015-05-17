				<td id="external_links" class="widget_links">
					<p class="title">
<?php if ($tpl->disExternalLinks('widget_title')) : ?>
						<?php echo $tpl->getExternalLinks('widget_title'); ?>

<?php else : ?>
						<?php echo __('Liens'); ?>

<?php endif; ?>
					</p>
					<ul>
<?php while ($tpl->nextExternalLinks()) : ?>
<?php if ($tpl->disExternalLinks()) : ?>
						<li>
							<a title="<?php echo $tpl->getExternalLinks('desc'); ?>"
								href="<?php echo $tpl->getExternalLinks('url'); ?>">
								<?php echo $tpl->getExternalLinks('title'); ?>

							</a>
						</li>
<?php endif; ?>
<?php endwhile; ?>
					</ul>
				</td>

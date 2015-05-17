				<td id="tags">
					<p class="title">
<?php if ($tpl->disWidgetTags('title')) : ?>
						<?php echo $tpl->getWidgetTags('title'); ?>

<?php else : ?>
						<?php echo __('Tags'); ?>

<?php endif; ?>
					</p>
					<ul>
<?php while ($tpl->nextWidgetTag()) : ?>
						<li><a title="<?php echo $tpl->getWidgetTag('title'); ?>" class="tag_weight_<?php echo $tpl->getWidgetTag('weight'); ?>" href="<?php echo $tpl->getWidgetTag('link'); ?>"><?php echo $tpl->getWidgetTag('name'); ?></a></li>
<?php endwhile; ?>
					</ul>
					<p id="all_tags"><a href="<?php echo $tpl->getWidgetTags('all_tags_link'); ?>"><?php echo __('tous les tags'); ?></a></p>
				</td>

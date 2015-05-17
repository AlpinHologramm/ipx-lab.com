<?php if ($tpl->disTags()) : ?>
		<p id="position"><?php echo $tpl->getPosition(); ?></p>

		<div id="tags_cloud">
			<ul>
<?php while ($tpl->nextTag()) : ?>
				<li><a title="<?php echo $tpl->getTag('title'); ?>" class="tag_weight_<?php echo $tpl->getTag('weight'); ?>" href="<?php echo $tpl->getTag('link'); ?>"><?php echo $tpl->getTag('name'); ?></a></li>
<?php endwhile; ?>
			</ul>
		</div>
<?php else :?>
		<p id="zero_item" class="message message_info"><?php echo __('La galerie ne contient aucun tag.'); ?></p>
<?php endif;?>
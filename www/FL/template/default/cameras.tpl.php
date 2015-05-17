		<p id="position"><?php echo $tpl->getPosition(); ?></p>
<?php if ($tpl->disCameras()) : ?>
		<table summary="<?php echo __('appareils photos'); ?>" class="default sorter" id="cameras_list">
			<thead>
				<tr>
					<th><?php echo __('Marque'); ?></th>
					<th><?php echo __('Modèle'); ?></th>
					<th><?php echo __('Nombre d\'images'); ?></th>
				</tr>
			</thead>
			<tbody>
<?php $n = 1; while ($tpl->nextCamera()) : ?>
				<tr>
					<td><a href="<?php echo $tpl->getCamera('brand_link'); ?>"><?php echo $tpl->getCamera('brand_name'); ?></a></td>
					<td><a href="<?php echo $tpl->getCamera('model_link'); ?>"><?php echo $tpl->getCamera('model_name'); ?></a></td>
					<td class="num"><?php echo $tpl->getCamera('nb_images'); ?></td>
				</tr>
<?php endwhile; ?>
			</tbody>
		</table>
<?php endif;?>
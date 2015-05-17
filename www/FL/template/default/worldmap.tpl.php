
		<h2><?php echo __('Carte du monde'); ?></h2>

		<div id="worldmap_canvas"></div>

		<script type="text/javascript">
		//<![CDATA[
		var geoloc_center_lat = <?php echo $tpl->getOption('center_lat'); ?>;
		var geoloc_center_long = <?php echo $tpl->getOption('center_long'); ?>;
		var geoloc_type = google.maps.MapTypeId.<?php echo $tpl->getGallery('geoloc_type'); ?>;
		var geoloc_zoom = <?php echo $tpl->getOption('zoom'); ?>;
		var geoloc_images = [];
<?php if ($tpl->disImages()) : ?>
<?php $n = 0; while ($tpl->nextImageCoords()) : ?>
		geoloc_images[<?php echo $n; ?>] = [];
		geoloc_images[<?php echo $n; ?>]['latitude'] = '<?php echo $tpl->getImage('latitude'); ?>';
		geoloc_images[<?php echo $n; ?>]['longitude'] = '<?php echo $tpl->getImage('longitude'); ?>';
		geoloc_images[<?php echo $n; ?>]['html'] = '<div class="geoloc_bloc">';
<?php if ($tpl->getImages('nb_images') > 1) : ?>
		geoloc_images[<?php echo $n; ?>]['html'] += '<div class="geoloc_nav">';
		geoloc_images[<?php echo $n; ?>]['html'] += '<a rel="image" class="geoloc_prev" href="javascript:;"></a>';
		geoloc_images[<?php echo $n; ?>]['html'] += '<a rel="image" class="geoloc_next" href="javascript:;"></a>';
		geoloc_images[<?php echo $n; ?>]['html'] += '<span>1/<?php echo $tpl->getImages('nb_images'); ?></span>';
		geoloc_images[<?php echo $n; ?>]['html'] += '</div>';
<?php endif; ?>
<?php $i = 1; while ($tpl->nextImage()) : ?>
		geoloc_images[<?php echo $n; ?>]['html'] += '<div id="geoloc_image_<?php echo $i; ?>" class="geoloc_thumb geoloc_image"';
		geoloc_images[<?php echo $n; ?>]['html'] += '<dl>';
		geoloc_images[<?php echo $n; ?>]['html'] += '<dt style="width:<?php echo $tpl->getImage('thumb_width'); ?>px;">';
		geoloc_images[<?php echo $n; ?>]['html'] += '<a style="width:<?php echo $tpl->getImage('thumb_width'); ?>px;height:<?php echo $tpl->getImage('thumb_height'); ?>px;" title="<?php printf($tpl->getL10nJS(__('Image : %s')), $tpl->getImage('title')); ?>" href="<?php echo $tpl->getImage('link'); ?>">';
		geoloc_images[<?php echo $n; ?>]['html'] += '<img <?php echo $tpl->getImage('thumb_size'); ?> style="padding:<?php echo $tpl->getImage('thumb_center'); ?>;" src="<?php echo $tpl->getImage('thumb_src'); ?>" alt="<?php echo $tpl->getImage('title'); ?>" />';
		geoloc_images[<?php echo $n; ?>]['html'] += '</a>';
		geoloc_images[<?php echo $n; ?>]['html'] += '</dt>';
		geoloc_images[<?php echo $n; ?>]['html'] += '</dl>';
<?php if ($tpl->disImage('place')) : ?>
		geoloc_images[<?php echo $n; ?>]['html'] += '<dd><ul style="width:<?php echo $tpl->getImage('thumb_width'); ?>px;"><li><?php printf(__('Lieu : %s'), $tpl->getImage('place')); ?></li></ul></dd>';
<?php endif; ?>
		geoloc_images[<?php echo $n; ?>]['html'] += '</div>';
<?php $i++; endwhile; ?>
		geoloc_images[<?php echo $n; ?>]['html'] += '</div>';
<?php $n++; endwhile; ?>
<?php endif; ?>

		var geoloc_categories = [];
<?php if ($tpl->disCategories()) : ?>
<?php $n = 0; while ($tpl->nextCategoryCoords()) : ?>
		geoloc_categories[<?php echo $n; ?>] = [];
		geoloc_categories[<?php echo $n; ?>]['latitude'] = '<?php echo $tpl->getCategory('latitude'); ?>';
		geoloc_categories[<?php echo $n; ?>]['longitude'] = '<?php echo $tpl->getCategory('longitude'); ?>';
		geoloc_categories[<?php echo $n; ?>]['html'] = '<div class="geoloc_bloc">';
<?php if ($tpl->getCategories('nb_categories') > 1) : ?>
		geoloc_categories[<?php echo $n; ?>]['html'] += '<div class="geoloc_nav">';
		geoloc_categories[<?php echo $n; ?>]['html'] += '<a rel="image" class="geoloc_prev" href="javascript:;"></a>';
		geoloc_categories[<?php echo $n; ?>]['html'] += '<a rel="image" class="geoloc_next" href="javascript:;"></a>';
		geoloc_categories[<?php echo $n; ?>]['html'] += '<span>1/<?php echo $tpl->getCategories('nb_categories'); ?></span>';
		geoloc_categories[<?php echo $n; ?>]['html'] += '</div>';
<?php endif; ?>
<?php $i = 1; while ($tpl->nextCategory()) : ?>
		geoloc_categories[<?php echo $n; ?>]['html'] += '<div id="geoloc_category_<?php echo $i; ?>" class="geoloc_thumb geoloc_category"';
		geoloc_categories[<?php echo $n; ?>]['html'] += '<dl>';
		geoloc_categories[<?php echo $n; ?>]['html'] += '<dt style="width:<?php echo $tpl->getCategory('thumb_width'); ?>px;">';
		geoloc_categories[<?php echo $n; ?>]['html'] += '<a title="<?php printf(($tpl->getCategory('type') == 'category') ? $tpl->getL10nJS(__('Catégorie : %s')) : $tpl->getL10nJS(__('Album : %s')), $tpl->getCategory('title')); ?>" href="<?php echo $tpl->getCategory('link'); ?>">';
		geoloc_categories[<?php echo $n; ?>]['html'] += '<img src="<?php echo $tpl->getCategory('thumb_src'); ?>" alt="<?php echo $tpl->getCategory('title'); ?>" <?php echo $tpl->getCategory('thumb_size'); ?> />';
		geoloc_categories[<?php echo $n; ?>]['html'] += '</a>';
		geoloc_categories[<?php echo $n; ?>]['html'] += '</dt>';
		geoloc_categories[<?php echo $n; ?>]['html'] += '</dl>';
<?php if ($tpl->disCategory('place')) : ?>
		geoloc_categories[<?php echo $n; ?>]['html'] += '<dd><ul style="width:<?php echo $tpl->getCategory('thumb_width'); ?>px;"><li><?php printf(__('Lieu : %s'), $tpl->getCategory('place')); ?></li></ul></dd>';
<?php endif; ?>
		geoloc_categories[<?php echo $n; ?>]['html'] += '</div>';
<?php $i++; endwhile; ?>
		geoloc_categories[<?php echo $n; ?>]['html'] += '</div>';
<?php $n++; endwhile; ?>
<?php endif; ?>
		//]]>
		</script>

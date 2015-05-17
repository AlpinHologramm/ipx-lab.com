<?php if (method_exists($tpl, 'getConf')) : ?>
<script type="text/javascript">
$(function()
{
	// Rognage des vignettes : aperçu.
	var show_preview = function(coords)
	{
		var thumb_w = <?php echo $tpl->getConf('thumb_width'); ?>;
		var thumb_h = <?php echo $tpl->getConf('thumb_height'); ?>;
		var rx = thumb_w / coords.w;
		var ry = thumb_h / coords.h;

		if (coords.w == 0 && coords.h == 0)
		{
			return;
		}

<?php if ($tpl->getConf('thumb_ratio') == 0) : ?>
		// Rognage libre.
		// Dimensions du cadre contenant l'image de l'aperçu.
		var width = thumb_w;
		var height = thumb_h;
		if (coords.w < coords.h)
		{
			width = ry * coords.w;
		}
		else
		{
			height = rx * coords.h;
		}
		rx = width / coords.w;
		ry = height / coords.h;
		$('#preview').css(
		{
			width: width + 'px',
			height: height + 'px'
		});

		// Marges externes du cadre contenant l'image de l'aperçu.
		var top = 0;
		var right = 0;
		var bottom = 0;
		var left = 0;
		var max = (thumb_w > thumb_h) ? thumb_w : thumb_h;
		if (width < max)
		{
			right = (max - width) / 2;
			left = right;
		}
		if (height < max)
		{
			top = (max - height) / 2;
			bottom = top;
		}
		$('#preview').css(
		{
			marginTop: top + 'px',
			marginRight: right + 'px',
			marginBottom: bottom + 'px',
			marginLeft: left + 'px'
		});
<?php endif; ?>

		$('#preview img').css(
		{
			width: Math.round(rx * <?php echo $tpl->getImagePreview('width'); ?>) + 'px',
			height: Math.round(ry * <?php echo $tpl->getImagePreview('height'); ?>) + 'px',
			marginLeft: '-' + Math.round(rx * coords.x) + 'px',
			marginTop: '-' + Math.round(ry * coords.y) + 'px'
		});
	};

	// Rognage des vignettes : Jcrop.
	var api = $.Jcrop('#image img', {
		aspectRatio: <?php echo $tpl->getConf('thumb_ratio'); ?>,
		bgOpacity: .4,
		dragEdges: false,
		onChange: show_preview,
		onSelect: show_preview,
		minSize: [20, 20],
		setSelect: [<?php echo $tpl->getImagePreview('set_select'); ?>],
		sideHandles: <?php echo ($tpl->getConf('thumb_ratio') == 0) ? 'true' : 'false'; ?>
	});
	$('#jcrop_all').click(function()
	{
		api.animateTo([0, 0, <?php echo $tpl->getImagePreview('width'); ?>, <?php echo $tpl->getImagePreview('height'); ?>]);
	});
	$('#edit').submit(function()
	{
		var coords = api.tellScaled();
		var new_coords = coords.x + ',' + coords.y + ',' + coords.w + ',' + coords.h;
		var old_coords = '<?php echo $tpl->getImagePreview('set_select'); ?>';
		$('#crop_coords').val(old_coords + '.' + new_coords);
	});
});
</script>
<?php endif; ?>

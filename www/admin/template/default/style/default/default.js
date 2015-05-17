jQuery(function($)
{
	$('#form_new_thumb dl a').mouseover(function()
	{
		$(this).css('cursor', 'pointer');
	});

	// Étiquette HTML.
	$('textarea')
		.focus(function()
		{
			$(this).parents('.field_html').find('.field_html_tag a').addClass('f_html_active');
		})
		.blur(function()
		{
			$(this).parents('.field_html').find('.field_html_tag a').removeClass('f_html_active');
		});
});

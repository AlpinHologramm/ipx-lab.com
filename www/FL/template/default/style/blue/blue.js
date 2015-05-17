// reCaptcha
var RecaptchaOptions = {theme: 'white'};

// Hover sur les éléments du menu d'outils, pour IE8.0 uniquement.
jQuery(function($)
{
	if (!$.browser.msie || $.browser.version != '8.0')
	{
		return;
	}
	$('#obj_tool_menu li').mouseover(function()
	{
		$(this).css('backgroundColor', '#F0E9DA');
	});
	$('#obj_tool_menu').mouseout(function()
	{
		$('#obj_tool_menu li').css('backgroundColor', '#F8FAFC');
	});
});

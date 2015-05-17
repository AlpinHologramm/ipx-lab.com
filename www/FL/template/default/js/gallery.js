var addEvent = function(obj, evType, fn)
{
	if (obj.addEventListener)
	{
		obj.addEventListener(evType, fn, false);
	}
	else if (obj.attachEvent)
	{
		obj.attachEvent('on' + evType, fn);
	};
};

// Affichage d'erreurs.
function alert_error(r)
{
	if (r == '')
	{
		r = 'unknown error.';
	}
	if (r.length > 500)
	{
		r = r.slice(0, 500) + '...';
	}
	alert('Error: ' + r);
}

// Outils : rapports.
var ajax_session = false;
var ajax_report_timeout;
function ajax_report(e)
{
	$('#obj_tool_' + e + ' .ajax_report.message_success').ajaxStart(function()
	{
		if (ajax_session == e)
		{
			clearTimeout(ajax_report_timeout);
			$(this).addClass('loading');
			$('#obj_tool_' + e + ' .ajax_report.message_success span').hide();
			$('#obj_tool_' + e + ' .ajax_report.message_success').show();
			$('#obj_tool_' + e + ' input').attr('disabled', 'disabled');
			$('#obj_tool_' + e + ' textarea').attr('disabled', 'disabled');
		}
	});
	$('#obj_tool_' + e + ' .ajax_report.message_success').ajaxComplete(function()
	{
		if (ajax_session == e)
		{
			$(this).removeClass('loading');
			$('#obj_tool_' + e + ' input').removeAttr('disabled');
			$('#obj_tool_' + e + ' textarea').removeAttr('disabled');
			ajax_session = false;
		}
	});
};
function ajax_message_error(e, msg)
{
	if (typeof msg != 'undefined')
	{
		$('#obj_tool_' + e + ' .ajax_report.message').hide();
		$('#obj_tool_' + e + ' .ajax_report.message_error span').text(msg);
	}
	$('#obj_tool_' + e + ' .ajax_report.message_error').show();
	ajax_report_timeout = setTimeout(
		function() { $('#obj_tool_' + e + ' .ajax_report.message_error').hide(); },
		4000
	);
};
function ajax_message_success(e, msg)
{
	if (typeof msg != 'undefined')
	{
		$('#obj_tool_' + e + ' .ajax_report.message_success span').text(msg);
	}
	$('#obj_tool_' + e + ' .ajax_report.message_success').show();
	$('#obj_tool_' + e + ' .ajax_report.message_success span').show();
	ajax_report_timeout = setTimeout(
		function() { $('#obj_tool_' + e + ' .ajax_report.message_success').hide(); },
		3000
	);
};

// Global.
jQuery(function($)
{
	// Focus sur les champs de formulaires.
	$('.focus').focus();

	// Erreur sur un champ de formulaire.
	if (typeof field_error != 'undefined')
	{
		$('.box #f_' + field_error).parent().addClass('field_error');
	}

	// Liens de déconnexion.
	$('#deconnect_user_link').click(function()
	{
		$('#deconnect_user_input').click();
	});
	$('#deconnect_object_link').click(function()
	{
		$('#deconnect_object_input').click();
	});

	// Affichage d'éléments de la page courante.
	$('a[rel].show_parts').click(function()
	{
		var id = '#' + $(this).attr('rel');
		if ($(id).is(':hidden'))
		{
			$(id).show();
			$(id + ' .show_parts_focus').focus();
		}
		else
		{
			$(id).hide();
		}
	});

	// Affichage d'une partie de la page avec une case à cocher.
	$('input[type="checkbox"].show_part').each(function()
	{
		if ($(this).attr('checked'))
		{
			$('#' + $(this).attr('rel')).show();
		}
		else
		{
			$('#' + $(this).attr('rel')).hide();
		}
	});
	$('input[type="checkbox"].show_part').change(function()
	{
		if ($(this).attr('checked'))
		{
			$('#' + $(this).attr('rel')).show();
		}
		else
		{
			$('#' + $(this).attr('rel')).hide();
		}
	});

	// Options du filigrane.
	if ($('#watermark_options').is('div'))
	{
		if ($('#watermark_specific').attr('checked'))
		{
			$('#watermark_options').show();
		}
		else
		{
			$('#watermark_options').hide();
		}
		$('#watermark_none').change(function()
		{
			if ($(this).attr('checked'))
			{
				$('#watermark_options').hide();
			}
		});
		$('#watermark_default').change(function()
		{
			if ($(this).attr('checked'))
			{
				$('#watermark_options').hide();
			}
		});
		$('#watermark_specific').change(function()
		{
			if ($(this).attr('checked'))
			{
				$('#watermark_options').show();
			}
		});
	}

	// Édition du profil.
	$('#birthdate_reset').click(function()
	{
		$('.date_title').attr('selected', 'selected');
	});

	// Menu des langues.
	if ($('#lang_change').is('img'))
	{
		var timeout_lang_list;
		$('#lang_change, #lang_list span').click(function()
		{
			if ($('#lang_list div').is(':hidden'))
			{
				$('#lang_list div').fadeIn('fast');
			}
			else
			{
				$('#lang_list div').fadeOut('fast');
			}
		});
		$('#lang_change, #lang_list')
			.bind('mouseleave', function()
			{
				timeout_lang_list = setTimeout(function()
				{
					$('#lang_list div').fadeOut('fast');
				}, 500);
			})
			.bind('mouseenter', function()
			{
				clearTimeout(timeout_lang_list);
			});
		$('#lang_list li').click(function()
		{
			$('#new_lang').val($(this).find('a').attr('rel'));
			$('#change_lang').click();
		});
	}

	// Smilies.
	if ($('#smilies').is('span'))
	{
		$('#smilies img').click(function()
		{
			var start, end, scrollPos, smiley = ' ' + $(this).attr('alt') + ' ';
			textarea = document.getElementById('message');
			textarea.focus();
			if (typeof(document['selection']) != 'undefined')
			{
				document.selection.createRange().text = smiley;
				textarea.caretPos += smiley.length;
			}
			else if (typeof(textarea['setSelectionRange']) != 'undefined')
			{
				start = textarea.selectionStart;
				end = textarea.selectionEnd;
				scrollPos = textarea.scrollTop;
				textarea.value = textarea.value.substring(0, start)
					+ smiley+textarea.value.substring(end);
				textarea.setSelectionRange(start + smiley.length, start + smiley.length);
				textarea.scrollTop = scrollPos;
			}
		});
	}

	// Tablesorter.
	if ($('.sorter').is('table'))
	{
		$('table.sorter').tablesorter({
			sortInitialOrder: 'desc',
			sortList: [[0,0]]
		});
	}

	// Diaporama.
	if ($('#tool_diaporama').is('li'))
	{
		var diaporama = new Diaporama;
		//diaporama.start($('#tool_diaporama').find('a').attr('rel'));

		$('#tool_diaporama').click(function()
		{
			$('#obj_tool_menu').hide();
			$('#obj_tools_link a').removeClass('tool_menu_open');
			diaporama.start($(this).find('a').attr('rel'));
		});
	}

	// Outils de vignettes.
	$('#tool_thumbs_tools').click(function()
	{
		var value = 0;
		if ($('.thumb_icons').is(':hidden'))
		{
			$('.thumb_icons').show();
			$('#tool_thumbs_tools span').attr('class', 'icon icon_thumbs_less');
			value = 1;
		}
		else
		{
			$('.thumb_icons').hide();
			$('#tool_thumbs_tools span').attr('class', 'icon icon_thumbs_more');
		}
		$.post(
			gallery_path + '/ajax.php',
			{ section: 'prefs', cookie_param: 'thumb_icons', cookie_value: value }
		);
	});

});

// Page d'options utilisateur "filigrane".
jQuery(function($)
{
	if (!$('#watermark_options').is('div'))
	{
		return;
	}

	addEvent(document, 'mousedown', function(e)
	{
		$('.colorpicker_canvas').hide();
	});

	$('.colorpicker').each(function()
	{
		var id = $(this).attr('id');

		$(this).after('<img id="colorpicker_icon_'
			+ id + '" width="16" height="16" alt="" src="'
			+ style_path + '/icons/color-pencil.png" />');
		$(this).parent('p').after('<div class="colorpicker_canvas" id="colorpicker_'
			+ id + '"></div>');

		$(this).parents('p').find('img').click(function()
		{
			$('#colorpicker_' + id).show();
			$(this).parent().find('input').focus();
		});

		$('#colorpicker_' + id).farbtastic('#' + id);
	});
});

// Géolocalisation sur la page "carte du monde".
jQuery(function($)
{
	if (!$('#igalerie #worldmap_canvas').is('div'))
	{
		return;
	}

	// Création de la map.
	var map_options = {
		center: new google.maps.LatLng(geoloc_center_lat, geoloc_center_long),
		mapTypeId: geoloc_type,
		zoom: geoloc_zoom
    };
	var map = new google.maps.Map(document.getElementById('worldmap_canvas'), map_options);

	// Création des marqueurs.
	var infowindow;
	var create_marker = function(lat, lng, html, icon)
	{
		var marker = new google.maps.Marker(
		{
			icon: gallery_path + '/images/markers/' + icon,
			position: new google.maps.LatLng(lat, lng),
			map: map,
			shadow: gallery_path + '/images/markers/marker-shadow.png'
		});

		google.maps.event.addListener(marker, 'click', function()
		{
			if (infowindow)
			{
				infowindow.close();
			}
			else
			{
				infowindow = new google.maps.InfoWindow();
			}
			infowindow.setContent(html);
			infowindow.open(map, marker);
		});
	};

	// Images.
	for (var i = 0; i < geoloc_images.length; i++)
	{
		create_marker(
			geoloc_images[i]['latitude'],
			geoloc_images[i]['longitude'],
			geoloc_images[i]['html'],
			'marker-image.png'
		);
	}

	// Catégories.
	for (var i = 0; i < geoloc_categories.length; i++)
	{
		create_marker(
			geoloc_categories[i]['latitude'],
			geoloc_categories[i]['longitude'],
			geoloc_categories[i]['html'],
			'marker-album.png'
		);
	}

	// Navigation entre éléments d'un bloc.
	$('#worldmap_canvas').delegate('.geoloc_nav a', 'click', function()
	{
		var prev_or_next = $(this).attr('class').replace('geoloc_', '');
		var nb_items = $(this).parents('.geoloc_bloc')
			.find('.geoloc_' + $(this).attr('rel')).length;
		var current = $(this).parents('.geoloc_bloc')
			.find('.geoloc_' + $(this).attr('rel') + ':visible');
		var current_n = current.attr('id').replace('geoloc_' + $(this).attr('rel') + '_', '');
		var current_n_new = (prev_or_next == 'next' ?
				parseInt(current_n) + 1 : parseInt(current_n) - 1);

		// Élément courant.
		current.hide();
		$(this).parents('.geoloc_bloc')
			.find('#geoloc_' + $(this).attr('rel') + '_' + current_n_new).show();

		// Position courante.
		$(this).parents('.geoloc_bloc').find('.geoloc_nav span')
			.text(current_n_new + '/' + nb_items);

		// Liens de navigation.
		if (current_n == 1)
		{
			$(this).parents('.geoloc_bloc').find('.geoloc_prev')
				.css('visibility', 'visible');
		}
		if (current_n == 2 && prev_or_next == 'prev')
		{
			$(this).parents('.geoloc_bloc').find('.geoloc_prev')
				.css('visibility','hidden');
		}
		if (parseInt(current_n) + 1 == nb_items && prev_or_next == 'next')
		{
			$(this).parents('.geoloc_bloc').find('.geoloc_next')
				.css('visibility', 'hidden');
		}
		if (current_n == nb_items)
		{
			$(this).parents('.geoloc_bloc').find('.geoloc_next')
				.css('visibility', 'visible');
		}
	});
});

// Géolocalisation sur la page des images.
jQuery(function($)
{
	if (!$('#igalerie #image_infos #gmap_canvas').is('div'))
	{
		return;
	}

	// Création de la map.
	var latlng = new google.maps.LatLng(geoloc_lat, geoloc_long);
	var map_options = {
		center: latlng,
		mapTypeId: geoloc_type,
		zoom: 10
    };
	var map = new google.maps.Map(document.getElementById('gmap_canvas'), map_options);

	// Création du marqueur.
	var marker = new google.maps.Marker(
	{
		icon: gallery_path + '/images/markers/marker-image.png',
		position: latlng,
		map: map,
		shadow: gallery_path + '/images/markers/marker-shadow.png'
	});
	google.maps.event.addListener(marker, 'click', function()
	{
		map.setCenter(latlng);
	});
});

// Vote.
jQuery(function($)
{
	if (!$('#image_rate').is('div'))
	{
		return;
	}

	// Initialisation.
	var rate_init = [];
	var rate_c = 0;
	var rate_i = 0;
	$('#image_rate img').each(function()
	{
		rate_init[rate_i] = $(this).attr('src');
		$(this)
			.attr('id', 'rate_' + rate_i)
			.attr('style', 'cursor:pointer')
			.bind('mouseenter', {id: $(this).attr('id')}, rate_over)
			.bind('mouseleave', {id: $(this).attr('id')}, rate_out)
			.bind('click', {id: $(this).attr('id')}, rate_click);
		rate_i++;
	});

	// Enregistrement du vote.
	function rate_click(e)
	{
		rate_c = 1;
		rate_i = 0;
		var rate = parseInt(e.data.id.replace(/[^\d]+/, '')) + 1;
		var temp = [];
		$('#image_rate img').each(function()
		{
			temp[rate_i] = $(this).attr('src');
			rate_i++;
		});
		$.post(gallery_path + '/ajax.php', {
			section: 'rate',
			rate: rate,
			id: img_id,
			q: q,
			q_md5: q_md5
		},
		function(r)
		{
			if (r == null)
			{
				return;
			}
			switch (r.status)
			{
				case 'success' :
					$('#image_stats #rate').html(image_stat_rate
						.replace(/%1\$s/, r.rate_visual)
						.replace(/%2\$s/, r.rate)
						.replace(/%3\$s/, r.votes)
					);
					rate_init = temp;
					break;

				case 'error' :
					alert_error(r.msg);
			}
			rate_c = 0;
		}, 'json');
	}

	// Survol par la souris.
	function rate_over(e)
	{
		if (rate_c)
		{
			return;
		}
		var rate = e.data.id.replace(/[^\d]+/, '');
		rate_i = 0;
		$('#image_rate img').each(function()
		{
			if (rate_i <= rate) {
				$(this).attr('src', $(this).attr('src').replace(/empty/, 'full'));
			}
			else
			{
				$(this).attr('src', $(this).attr('src').replace(/full/, 'empty'));
			}
			rate_i++;
		});
	}
	function rate_out(e)
	{
		if (rate_c)
		{
			return;
		}
		rate_i = 0;
		$('#image_rate img').each(function()
		{
			$(this).attr('src', rate_init[rate_i]);
			rate_i++;
		});
	}
});

// Outils.
jQuery(function($)
{
	var anim_speed = 0;
	var current_tool = '#obj_tool_menu';
	var change_icon = function()
	{
		$('#obj_tools_link').removeClass().addClass('obj_tool_menu_icon');
	};
	var hide_box = function()
	{
		clear_menu();
		$('.obj_tool_box').hide(anim_speed, change_icon);
		$('#obj_tools_link a').removeClass('tool_menu_open');
	};

	// Outils : menu.
	var timeout_menu;
	var hide_menu = function()
	{
		if ($('#obj_tool_menu').is(':hidden'))
		{
			return;
		}
		clear_menu();
		timeout_menu = setTimeout(function()
		{
			$('#obj_tool_menu').hide(anim_speed);
			if ($('#obj_tools_link').hasClass('obj_tool_menu_icon'))
			{
				$('#obj_tools_link a').removeClass('tool_menu_open');
			}
		}, 500);
	};
	var clear_menu = function()
	{
		clearTimeout(timeout_menu);
	};
	$('#obj_tool_menu').bind('mouseenter', clear_menu).bind('mouseleave', hide_menu);
	$('#obj_tools_link').bind('mouseenter', clear_menu).bind('mouseleave', hide_menu);
	$('#obj_tools_link').click(function()
	{
		clear_menu();
		if ($('#obj_tool_menu').is(':hidden'))
		{
			$(current_tool).hide(anim_speed, function()
			{
				$('#obj_tool_menu').show(anim_speed, change_icon);
				$('#obj_tools_link a').addClass('tool_menu_open');
			});
		}
		else
		{
			$('#obj_tool_menu').hide(anim_speed, function()
			{
				$('#obj_tools_link a').removeClass('tool_menu_open');
			});
		}
	});
	$('#obj_tool_menu .js_link').removeAttr('href');

	// Outils : boîtes.
	$('.obj_tool_title').click(function(){ $('#obj_tools_link').trigger('click'); });
	$('.obj_tool_box .cancel').click(hide_box);
	$('.obj_tool_box_link').each(function()
	{
		$(this).click(function()
		{
			current_tool = '#obj_' + $(this).attr('id');
			$('#obj_tool_menu').hide(anim_speed, function()
			{
				$(current_tool).show(anim_speed, function()
				{
					$('#obj_tools_link').removeClass().addClass($(this).attr('id') + '_icon');
					$(current_tool + ' .obj_tool_focus:visible:first').focus();
				});
			});
		});
	});
	$('.obj_tool_box form').submit(function()
	{
		return false;
	});

	// Outils : administrer, ajouter des images.
	$('#tool_admin,#tool_upload').click(function()
	{
		window.location = $('#' + $(this).attr('id') + ' a').attr('href');
	});

	// Outil : ajout aux favoris et au panier.
	var o = ['fav', 'basket'];
	for (var i = 0; i < o.length; i++)
	{
		$('#obj_tool_menu li#tool_' + o[i]).click(function()
		{
			var type = $(this).attr('id').replace(/tool_/, '');
			var action = $('#tool_' + type + ' .icon')
				.hasClass('icon_' + type + '_add') ? '-add' : '-remove';
			ajax_session = type;

			$.post(gallery_path + '/ajax.php',
			{
				anticsrf: anticsrf,
				section: (type == 'fav' ? 'favorites' : 'basket') + action,
				images_id: img_id,
				q: q,
				q_md5: q_md5
			},
			function(r)
			{
				if (r == null)
				{
					return;
				}
				switch (r.status)
				{
					case 'error' :
						alert_error(r.msg);
						break;

					case 'full' :
						alert(r.msg);
						break;

					case 'success' :
						if (action == '-add')
						{
							if (type == 'fav')
							{
								$('#position .current').addClass('favorite');
							}
							$('#tool_' + type + ' a').text(eval('msg_' + type + '_del'));
							$('#tool_' + type + ' .icon')
								.removeClass('icon_' + type + '_add')
								.addClass('icon_' + type + '_remove');
						}
						else
						{
							if (type == 'fav')
							{
								$('#position .current').removeClass('favorite');
							}
							$('#tool_' + type + ' a').text(eval('msg_' + type + '_add'));
							$('#tool_' + type + ' .icon')
								.removeClass('icon_' + type + '_remove')
								.addClass('icon_' + type + '_add');
						}
						break;
				}
			}, 'json');
		});
	}

	// Outil : édition.
	if ($('#obj_tool_edit').is('div'))
	{
		// Langues d'édition.
		if ($('#edit_langs select').is('select'))
		{
			$('#edit_langs select').change(function()
			{
				var lang = $(this).find(':selected').val();
				$('#obj_tool_edit label.icon_lang').parents('p').hide();
				$('#obj_tool_edit label.icon_' + lang).parents('p').show();
			});
		}

		// Initialisation.
		ajax_report('edit');
		var edit_data = [];
		edit_data['title'] = [];
		edit_data['desc'] = [];
		var edit_title = [];
		var edit_desc = [];
		$('.edit_desc').each(function()
		{
			var lang = $(this).attr('name').replace(/(title|desc|\[|\])/g, '');
			edit_title[lang] = $('#edit_title_' + lang).val();
			edit_desc[lang] = $('#edit_desc_' + lang).val();
			edit_data['title'][lang] = $('#edit_title_' + lang).val();
			edit_data['desc'][lang] = $('#edit_desc_' + lang).val();
		});

		// Envoi du formulaire.
		$('#obj_tool_edit .submit').click(function()
		{
			var edit_urlname = $('#edit_urlname').val();
			var edit_tags = $('#edit_tags').val();
			$('.edit_desc').each(function()
			{
				var lang = $(this).attr('name').replace(/(title|desc|\[|\])/g, '');
				edit_title[lang] = $('#edit_title_' + lang).val();
				edit_desc[lang] = $('#edit_desc_' + lang).val();
			});
			ajax_session = 'edit';
			$.post(gallery_path + '/ajax.php', {
				section: typeof img_id != 'undefined' ? 'edit-image' : 'edit-category',
				id: typeof img_id != 'undefined' ? img_id : cat_id,
				data: $('#obj_tool_edit form').serialize(),
				urlname: edit_urlname,
				tags: edit_tags,
				anticsrf: anticsrf,
				q: q,
				q_md5: q_md5
			},
			function(r)
			{
				if (r == null)
				{
					return;
				}
				switch (r.status)
				{
					// Aucun changement.
					case 'nochange' :
						$('#obj_tool_edit .message').hide();
						break;

					// Modification réussie.
					case 'success' :

						// Description des images.
						if (typeof img_id != 'undefined')
						{
							if ($('#image_description').is('div')
							&& r.desc == '')
							{
								$('#image_description').slideUp('slow', function()
								{
									$('#image_description').remove();
									if (!$('#image_infos').children().is('div'))
									{
										$('#image_infos').remove();
										$('#image_ratecom').removeClass('image_column');
									}
								});
							}
							else if (!$('#image_description').is('div')
							&& r.desc != '')
							{
								var image_description = '<div style="display:none"' 
									+ 'class="image_column_bloc" id="image_description">'
									+ '<h2>' + text_desc + '</h2><p></p></div>';
								if ($('#image_infos').is('div'))
								{
									if ($('#image_tags').is('div'))
									{
										$('#image_tags').after(image_description);
									}
									else
									{
										$('#image_infos').prepend(image_description);
									}
								}
								else
								{
									var image_column;
									if ($('#image_ratecom').is('div'))
									{
										image_column = ' class="image_column"';
										$('#image_ratecom').addClass('image_column');
									}
									$('#image_container').after(
										'<div' + image_column  + ' id="image_infos">'
										+ image_description + '</div>'
									);
								}
							}
							$('#image_description p').html(r.desc);
							$('#image_description').slideDown('slow');
						}

						// Description des catégories.
						else if (page == 1 && (!thumbs_cat_extended || cat_id == 1))
						{
							if ($('#cat_description').is('div')
							&& r.desc == '')
							{
								$('#cat_description').slideUp('slow', function()
								{
									$('#cat_description').remove();
								});
							}
							else if (!$('#cat_description').is('div')
							&& r.desc != '')
							{
								$('#position').after(
									'<div style="display:none" id="cat_description"><p></p></div>'
								);
							}
							$('#cat_description p').html(r.desc);
							$('#cat_description').slideDown('slow');
						}

						// Titre.
						if (r.title)
						{
							$('#position .current a').html(r.title);
						}

						// Nom d'URL.
						$('#position .current a').attr(
							'href',
							$('#position .current a').attr('href').replace(
								/((?:album|category|image)\/\d+-).+$/,
								'$1' + edit_urlname
							)
						);

						// Tags.
						if (typeof text_tags != 'undefined' && typeof r.tags == 'object')
						{
							// Si aucun tag, on supprime le bloc des tags,
							// et le bloc d'infomations de l'image si nécessaire.
							if ($('#image_tags').is('div') && r.tags == '')
							{
								$('#image_tags').slideUp('slow', function()
								{
									$('#image_tags').remove();
									if (!$('#image_infos').children().is('div'))
									{
										$('#image_infos').remove();
										$('#image_ratecom').removeClass('image_column');
									}
								});
							}

							// Sinon, si le bloc de tags n'existe pas, on le crée, de même
							// que le bloc des informations de l'image si nécessaire.
							else if (!$('#image_tags').is('div') && r.tags != '')
							{
								var image_tags = '<div style="display:none"' 
									+ 'class="image_column_bloc" id="image_tags">'
									+ '<h2>' + text_tags + '</h2><ul></ul></div>';
								if ($('#image_infos').is('div'))
								{
									$('#image_infos').prepend(image_tags);
								}
								else
								{
									var image_column;
									if ($('#image_ratecom').is('div'))
									{
										image_column = ' class="image_column"';
										$('#image_ratecom').addClass('image_column');
									}
									$('#image_container').after(
										'<div' + image_column  + ' id="image_infos">'
										+ image_tags + '</div>'
									);
								}
							}

							// Construction de la liste des tags.
							if (r.tags != null)
							{
								var tags_list = '';
								for (var i = 0; i < r.tags.length; i++)
								{
									tags_list += '<li class="icon icon_tag"><a href="'
										+ r.tags[i].tag_link + '">'
										+  r.tags[i].tag_name + '</a></li>' + "\n"
								}
								$('#image_tags ul').html(tags_list);
							}

							$('#image_tags').slideDown('slow');
						}

						$('.edit_desc').each(function()
						{
							var lang = $(this).attr('name').replace(/(title|desc|\[|\])/g, '');
							if (typeof r.desc_langs != 'undefined'
							 && typeof r.desc_langs[lang] != 'undefined')
							{
								edit_data['desc'][lang] = r.desc_langs[lang];
								$('#edit_desc_' + lang).val(r.desc_langs[lang]);
							}
							if (typeof r.title_langs != 'undefined'
							 && typeof r.title_langs[lang] != 'undefined')
							{
								edit_data['title'][lang] = r.title_langs[lang];
								$('#edit_title_' + lang).val(r.title_langs[lang]);
							}
						});
						ajax_message_success('edit');
						break;

					// Avertissement.
					case 'warning' :
						ajax_message_error('edit', r.msg);
						break;

					// Erreur.
					case 'error' :
						$('#obj_tool_edit .message').hide();
						alert_error(r.msg);
				}
			}, 'json');
		});

		// Annulation.
		$('#obj_tool_edit .cancel').click(function()
		{
			$('.edit_desc').each(function()
			{
				var lang = $(this).attr('name').replace(/(title|desc|\[|\])/g, '');
				$('#edit_title_' + lang).val(edit_data['title'][lang]);
				$('#edit_desc_' + lang).val(edit_data['desc'][lang]);
			});
			return false;
		});
	}

	// Outil : téléchargement d'une archive Zip.
	$('#obj_tool_menu li#tool_download').click(function()
	{
		window.location = $('#tool_download a').attr('rel');
	});

	// Outil : vider le panier.
	$('#obj_tool_menu li#tool_basket_empty').click(function()
	{
		if (confirm(confirm_basket_empty))
		{
			$('#obj_tools').after(
				'<form style="display:none" id="basket_empty_form" action="" method="post">'
				+ '<p><input name="anticsrf" type="hidden" value="' + anticsrf + '" />'
				+ '<input name="basket_empty" type="submit" value="1" /></p></form>'
			);
			$('#basket_empty_form input').click();
		}
	});

	// Outil : supprimer une image.
	$('#obj_tool_menu li#tool_delete_image').click(function()
	{
		if (confirm(confirm_delete_image))
		{
			$.post(gallery_path + '/ajax.php',
			{
				anticsrf: anticsrf,
				section: 'delete-image',
				image_id: img_id,
				q: q,
				q_md5: q_md5
			},
			function(r)
			{
				if (r == null)
				{
					return;
				}
				switch (r.status)
				{
					case 'error' :
						alert_error(r.msg);
						break;

					case 'success' :
						alert(r.msg);
						window.location = ($('#position_special').is('p'))
							? $('#position a').eq(-1).attr('href').replace(/image\/.+/, '')
								+ q.replace(/image\/\d+-[^\/]+\//, '')
							: $('#position a').eq(-2).attr('href');
						break;
				}
			}, 'json');
		}
	});
});

// Outils de vignettes.
jQuery(function($)
{
	if (!$('.thumb_icons').is('span'))
	{
		return;
	}

	// Rend une icône de vignette active.
	var icon_active = function(type, e)
	{
		e.find('img').attr(
			'src',
			e.find('img').attr('src').replace(/(-active)?.png$/, '-active.png')
		);
		e.attr('title', eval('msg_' + type + '_del'));
	};

	// Rend une icône de vignette inactive.
	var icon_inactive = function(type, e)
	{
		e.find('img').attr(
			'src',
			e.find('img').attr('src').replace(/(-active)?.png$/, '.png')
		);
		e.attr('title', eval('msg_' + type + '_add'));
	};

	// Favoris et panier.
	var o = ['fav', 'basket'];
	for (var i = 0; i < o.length; i++)
	{
		$('.thumb_icon_' + o[i]).click(function()
		{
			var e = $(this);
			var type = e.attr('class').replace(/thumb_icon_/, '');
			var action = e.find('img').attr('src').match(/-active/) ? '-remove' : '-add';

			ajax_session = o[i];

			$.post(gallery_path + '/ajax.php',
			{
				anticsrf: anticsrf,
				section: (type == 'fav' ? 'favorites' : 'basket') + action,
				images_id: e.attr('rel'),
				q: q,
				q_md5: q_md5
			},
			function(r)
			{
				if (r == null)
				{
					return;
				}
				switch (r.status)
				{
					case 'error' :
						alert_error(r.msg);
						break;

					case 'full' :
						alert(r.msg);
						break;

					case 'success' :
						if (action == '-add')
						{
							icon_active(type, e);
						}
						else
						{
							icon_inactive(type, e);
						}
						break;
				}
			}, 'json');
		});
	}

	// Sélection.
	if ($('#obj_tool_select').is('div'))
	{
		var selection = [];
		var nb_thumbs = $('.thumbs dl').length;
		ajax_report('select');

		$('#obj_tool_select form input[type="reset"]').click();
		$('#obj_tool_select .submit').attr('disabled', 'disabled');

		$('#obj_tool_select form input[type="reset"]').click(function()
		{
			$('#obj_tool_select #selection_action option:first').attr('selected', 'selected');
			$('#obj_tool_select textarea,#obj_tool_select input.text').text();
		});

		// Sélection d'une image.
		$('.thumb_icon_select').click(function()
		{
			var e = $(this);
			var id = e.attr('rel');

			if ($.inArray(id, selection) == -1)
			{
				selection.push(id);
				icon_active('select', e);
				e.parents('dl').addClass('select');
			}
			else
			{
				for (var i = 0; i < selection.length; i++)
				{
					if (selection[i] == id)
					{
						selection.splice(i, 1);
					}
				}
				icon_inactive('select', e);
				e.parents('dl').removeClass('select');
			}

			if (selection.length == nb_thumbs)
			{
				$('#tool_select_all a').text(msg_select_all_del);
			}
			else
			{
				$('#tool_select_all a').text(msg_select_all_add);
			}

			var msg = (selection.length > 1) ? msg_select_nb_images : msg_select_nb_image;
			$('#msg_select_nb_images').text(msg.replace(/%s/, selection.length));

			if (selection.length > 0)
			{
				$('#obj_tool_select .submit').removeAttr('disabled');
			}
			else
			{
				$('#obj_tool_select .submit').attr('disabled', 'disabled');
			}
		});

		// Tout (dé)sélectionner.
		$('#tool_select_all').click(function()
		{
			var selection_count = selection.length;
			$('.thumbs dl').each(function()
			{
				if (selection_count == nb_thumbs
				|| (selection_count != nb_thumbs && !$(this).hasClass('select')))
				{
					$(this).find('.thumb_icon_select').click();
				}
			});
		});

		// Éléments à faire apparaître lors du choix d'une action.
		$('#obj_tool_select #selection_action').change(function()
		{
			if ($(this).children('option[value="tags_add"]').attr('selected'))
			{
				$('#tags_add_field').show();
				$('#tags_add_field textarea').focus();
			}
			else
			{
				$('#tags_add_field').hide();
			}
			if ($(this).children('option[value="tags_remove"]').attr('selected'))
			{
				$('#tags_remove_field').show();
				$('#tags_remove_field textarea').focus();
			}
			else
			{
				$('#tags_remove_field').hide();
			}
		});

		// Effectue une action sur la sélection.
		$('#obj_tool_select .submit').click(function()
		{
			ajax_session = 'select';
			var post = {
				anticsrf: anticsrf,
				images_id: selection.join(','),
				q: q,
				q_md5: q_md5
			};
			var selected = $('#obj_tool_select #selection_action option:selected').val()
						|| $('#obj_tool_select #selection_action option:first').val();
			switch (selected)
			{
				// Ajout au panier.
				case 'basket_add' :
					post.section = 'basket-add';
					break;

				// Retrait du panier.
				case 'basket_remove' :
					post.section = 'basket-remove';
					break;

				// Ajout aux favoris.
				case 'fav_add' :
					post.section = 'favorites-add';
					break;

				// Retrait des favoris.
				case 'fav_remove' :
					post.section = 'favorites-remove';
					break;

				// Ajout de tags.
				case 'tags_add' :
					post.section = 'tags-add';
					post.tags = $('#tags_add').val();
					break;

				// Suppression de tags.
				case 'tags_remove' :
					post.section = 'tags-remove';
					post.tags = $('#tags_remove').val();
					break;

				default :
					return;
			}

			// Requête ajax.
			$.post(gallery_path + '/ajax.php', post,
			function(r)
			{
				if (r == null)
				{
					return;
				}
				switch (r.status)
				{
					case 'error' :
						$('#obj_tool_select .ajax_report.message_success').hide();
						alert_error(r.msg);
						break;

					case 'full' :
						$('#obj_tool_select .ajax_report.message_success').hide();
						alert(r.msg);
						break;

					case 'success' :
						ajax_message_success('select', r.msg);
						switch (post.section)
						{
							// Ajout aux favoris et au panier.
							case 'basket-add' :
							case 'favorites-add' :
								var type = (post.section.match(/fav/)) ? 'fav' : 'basket';
								for (var i = 0; i < selection.length; i++)
								{
									icon_active(
										type,
										$('a[rel="' + selection[i] + '"].thumb_icon_' + type)
									);
								}
								break;

							// Retrait des favoris et du panier.
							case 'basket-remove' :
							case 'favorites-remove' :
								var type = (post.section.match(/fav/)) ? 'fav' : 'basket';
								for (var i = 0; i < selection.length; i++)
								{
									icon_inactive(
										type,
										$('a[rel="' + selection[i] + '"].thumb_icon_' + type)
									);
								}
								break;
						}
						break;
				}
			}, 'json');
		});
	}
});

// Ajout d'images.
jQuery(function($)
{
	if (typeof upload_tempdir == 'undefined')
	{
		return;
	}

	// Identifiant de l'album sélectionné.
	var alb_id;

	// Gestion des listes d'albums.
	var select_cat_change = function()
	{
		$('#upload_categories select').change(function()
		{
			var id = $(this).children('option:selected').val();

			// Supprime toutes les listes enfants à partir de la liste courante.
			var i = parseInt($(this).attr('id').replace(/upload_categories_/, ''));
			var n = i + 1;
			while ($('#upload_categories_' + n).is('select'))
			{
				$('#upload_categories_' + n).remove();
				$('#upload_sep_' + n).remove();
				n++;
			}

			// Crée la nouvelle liste enfant si c'est une catégorie.
			if (typeof albums_list[id] != 'undefined' && albums_list[id].c == 1)
			{
				select_cat_create(id, i + 1);
			}

			// Album sélectionné.
			var n = 1;
			while ($('#upload_categories_' + n).is('select'))
			{
				n++;
			}
			n--;
			var id = $('#upload_categories_' + n + ' option:selected').val();
			if (typeof albums_list[id] != 'undefined' && albums_list[id].c == 0)
			{
				alb_id = id;
				var text = albums_list[id].t;
				var parent_id = albums_list[id].p;
				while (typeof albums_list[parent_id] != 'undefined' && parent_id > 1)
				{
					text = albums_list[parent_id].t + cat_separator + text;
					parent_id = albums_list[parent_id].p;
				}
				$('#select_path').removeClass().addClass('message message_success').html(text);
			}
			else
			{
				alb_id = null;
				$('#select_path').removeClass().addClass('message message_info').html(l10n_none);
			}

			// On repositionne le bouton d'Uploadify.
			uploader_init();
		});
	};
	var select_cat_create = function(cat_id, i)
	{
		var opt_class;
		var list = '<select id="upload_categories_' + i + '"><option>---</option>';
		var sep = (i > 1) ? '<span id="upload_sep_' + i + '">' + cat_separator + '</span>' : '';
		for (var id in albums_list)
		{
			if (albums_list[id].p == cat_id)
			{
				list += '<option value="' + id + '">'
					+ albums_list[id].t + '</option>';
			}
		}
		list += '</select>';
		$('#upload_categories').append(sep + list);
		select_cat_change();
	};

	setTimeout(function()
	{
		// Chargement d'Uploadify.
		$('head').append('<script type="text/javascript" src="'
			+ gallery_path + '/js/jquery/uploadify.js"></script>');

		// Uploadify.
		$('#uploadify').uploadify(
		{
			'auto' : false,
			'cancelImg' : style_path + '/icons/cancel.png',
			'fileDesc' : 'Images (*.jpg, *.gif, *.png)',
			'fileExt': '*.jpg;*.jpeg;*.gif;*.png;*.JPG;*.JPEG;*.GIF;*.PNG',
			'height': 50,
			'hideButton': true,
			'multi' : true,
			'queueID' : 'fileUploadQueue',
			'script' : gallery_path + '/uploader.php',
			'sizeLimit': max_file_size,
			'uploader' : gallery_path + '/uploader.swf',
			'width': 200,
			'wmode': 'transparent',
			'onComplete': function(event, queueID, fileObj, response, data)
			{
				if (response.substr(0, 8) == 'success:')
				{
					$('#fileUploadAdd').before(
						'<input name="files[]" type="hidden" value="'
							+ encodeURI(response.substr(8)) + '" />'
					);
				}
				else if (response.substr(0, 8) == 'warning:')
				{
					$('#fileUploadAdd').before(
						'<input name="warning[]" type="hidden" value="'
							+ encodeURI(response.substr(8)) + '" />'
					);
				}
				else if (response.substr(0, 6) == 'error:')
				{
					$('#fileUploadAdd').before(
						'<input name="error[]" type="hidden" value="'
							+ encodeURI(response.substr(6)) + '" />'
					);
				}
				else if (response != '')
				{
					alert(response);
					$('#uploadify').uploadifyClearQueue();
				}
			},
			'onAllComplete': function(event, data)
			{
				$('#fileUploadAdd').before(
					'<input name="cat_id" type="hidden" value="' + alb_id + '" />'
				);
				$('#fileUploadAdd').parents('form').submit();
			}
		});
	}, 100);

	$('#fileUploadClear').click(function()
	{
		$('#uploadify').uploadifyClearQueue();
	});
	$('#fileUploadStart').click(function()
	{
		if (!alb_id)
		{
			alert(l10n_upload_album);
			return;
		}

		$('#fileUploadButtons').slideUp('slow');
		$('#uploadify').uploadifySettings('scriptData',
		{
			'from': 'gallery',
			'id': alb_id,
			'session_token': upload_session_token,
			'tempdir': upload_tempdir
		});
		$('#uploadify').uploadifyUpload();
	});
	var uploader_init = function()
	{
		var offset = $('#fileUploadAdd').offset();
		$('#uploadifyUploader')
			.height($('#fileUploadAdd').outerHeight())
			.width($('#fileUploadAdd').outerWidth())
			.css({
				top: offset.top + 'px',
				left: offset.left + 'px'
			});
	};
	setTimeout(function(){uploader_init();}, 200);

	// Initialisation.
	$('.box form')[0].reset();
	select_cat_create(1, 1);

	// Album auto-sélectionné.
	var autoselect = $(location).attr('href').replace(/^.+?(\d*)$/, '$1');
	if (typeof albums_list[autoselect] != 'undefined')
	{
		var parent_id = albums_list[autoselect].p;
		var parents = [parent_id];
		while (typeof albums_list[parent_id] != 'undefined' && parent_id > 1)
		{
			parents.push(parent_id);
			parent_id = albums_list[parent_id].p;
		}
		var n = 2;
		for (var i = parents.length - 1; i > 0; i--)
		{
			select_cat_create(parents[i], n);
			$('#upload_categories_' + (n - 1) + ' option[value="' + parents[i] + '"]')
				.attr('selected', 'selected');
			n++;
		}
		$('#upload_categories_' + parents.length + ' option[value="' + autoselect + '"]')
			.attr('selected', 'selected');
		$('#upload_categories_' + parents.length).change();
	}
});

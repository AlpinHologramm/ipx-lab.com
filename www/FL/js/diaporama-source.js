/**
 * Gestion complète du diaporama.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @link http://www.igalerie.org/
 */
function Diaporama()
{
	// Activer les animations par défaut ?
	this.animate = true;

	// Durée d'affichage par défaut d'une image en lecture automatique.
	// En secondes.
	this.autoDuration = 3.0;

	// Durée d'affichage minimale des images en lecture automatique.
	// En secondes.
	this.autoDurationMin = 1;

	// Durée d'affichage maximale des images en lecture automatique.
	// En secondes.
	this.autoDurationMax = 60;

	// Précision pour la durée d'affichage des images en lecture automatique.
	// En secondes.
	this.autoDurationPrecision = 0.5;

	// En lecture automatique, recommencer la lecture depuis
	// la première image lorsqu'on est arrivé à la dernière ?
	this.autoLoop = false;

	// Démarrer la lecture automatique au lancement du diaporama ?
	this.autoStart = false;

	// Afficher par défaut le carrousel ?
	this.carousel = true;

	// Épaisseur de la bordure du haut du carrousel.
	this.carouselBorderTop = 2;

	// Durée de l'effet de transition entre les pages du carrousel.
	this.carouselNavDuration = 600;

	// Marge externe des vignettes du carrousel.
	this.carouselThumbsMargin = 8;

	// Épaisseur de la bordure des vignettes du carrousel.
	this.carouselThumbsBorder = 1;

	// Dimensions des vignettes du carrousel.
	// En pixels (valeur minimale: 50).
	this.carouselThumbsSize = 80;

	// Durée de temporisation, en millisecondes, avant la création
	// de chaque image préchargée au lancement du diaporama.
	// Plus le paramètre MySQL 'max_user_connections' est petit,
	// plus cette durée doit être grande.
	// Si 'max_user_connections' est à 0,
	// alors 'createImageTempTime' peut être à 0.
	this.createImageTempTime = 400;

	// Cacher les barres de contrôle par défaut ?
	this.hideControlBars = false;

	// Durée de l'effet de redimensionnement de l'image
	// pour le switch taille réelle / taille redimensionnée.
	// 0 pour désactiver l'animation.
	// En millisecondes.
	this.imageResizeDuration = 250;

	// Autoriser la navigation au clavier ?
	this.keyboardNav = true;

	// Nombre d'images autour de l'image courante
	// (premières, précédentes, suivantes, dernières)
	// à récupérer depuis diaporama.php.
	this.preload = 2;

	// La sidebar doit-elle ne pas masquer l'image ?
	this.sidebarImageResize = true;

	// Durée de l'effet d'apparition des sidebars.
	// 0 pour désactiver l'animation.
	// En millisecondes.
	this.sidebarShowDuration = 250;

	// Durée de l'effet de transition entre images par défaut.
	// En millisecondes.
	this.transitionDuration = 500;

	// Effet de transition entre images par défaut.
	this.transitionEffect = 'fade';



	// Jeton anti-CSRF utilisé pour l'édition des images.
	this._anticsrf;

	// Indique si le bouton de la souris est enfoncé
	// pour la modification de la durée d'affichage
	// des images en lecture automatique.
	this._autoDurationMouseDown = false;

	// Timer pour le changement d'images en lecture automatique.
	this._autoTimer;

	// Indique si une animation du carrousel est en cours.
	this._carouselAnimation = false;

	// Numéro de la page courante du carrousel.
	this._carouselCurrentPage = 0;

	// Position courante dans le carrousel.
	this._carouselCurrentPosition = 1;

	// Informations utiles des images du carrousel.
	this._carouselImages;

	// Nombre maximal de vignettes à afficher dans le carrousel.
	this._carouselMaxThumbs = 0;

	// Plus grande position des images du carrousel.
	this._carouselMaxPosition = 0;

	// Nombre de pages du carrousel.
	this._carouselNbPages = 0;

	// Indique si une animation sur les barres de contrôle est en cours.
	this._controlBarsAnimation = false;

	// Le pointeur de la souris se trouve-t-il sur une barre de contrôle ?
	this._controlBarsOver = false;

	// Position de l'image courante.
	this._currentPosition;

	// Verrou à true lorsqu'un effet de transition entre images est en cours.
	this._imageTransitionLock = false;

	// Informations utiles des images.
	this._images = {};

	// Le diaporama a-t-il déjà été initialisé ?
	this._init = false;

	// Contrôle au clavier actif ?
	this._keyboardActive = true;

	// Nombre d'images de la section courante.
	this._nbImages = 0;

	// Précédentes dimensions du diaporama
	// (avant un changement de dimensions de la zone d'affichage).
	this._prevDiaporamaSize;

	// Paramètre GET "q" pour diaporama.php.
	this._q;

	// Image en taille réelle ?
	this._realsize = false;

	// Verrou à true lorsqu'une sidebar est en mouvement.
	this._sidebarMoveLock = false;



	/**
	 * Ouvre et prépare le diaporama.
	 *
	 * @param string q
	 * @param object options
	 * @return void
	 */
	this.start = function(q, options)
	{
		var This = this;

		if (q === '')
		{
			return;
		}

		// Options.
		if (typeof options == 'object')
		{
			for (option in options)
			{
				if (this[option] !== undefined && option.substr(1, 1) != '_')
				{
					this[option] = options[option];
				}
			}
		}

		// Paramètre GET "q" pour diaporama.php.
		if (this._q === undefined)
		{
			this._q = q;
		}

		// On supprime les barres de défilement du navigateur
		// et on désactive l'affichage de la galerie.
		$('html,body').css('overflow', 'hidden');
		$('#igalerie_gallery').hide();

		// Si le diaporama a déjà été chargé.
		if (this._init)
		{
			// Si la position de l'image courante n'est pas la même,
			// on recrée les images.
			if (this._q != q)
			{
				var position = q.match(/position\/(\d+)/);
				this._currentPosition = parseInt(position[1]);
				this._images = {};
				this._nbImages = 0;
				this._deleteImages(true);
				this._realsize = false;
				$('#diaporama_icon_switch img').attr(
					'src',
					$('#diaporama_icon_switch img').attr('src')
						.replace(/noresize(-grey)?\.png$/, 'resize$1.png')
				);
				this._q = q;
				this._getImages(true);
			}

			// On affiche le diaporama.
			$('#diaporama').show();

			// Dimensions du diaporama.
			this._changeDiaporamaSize();

			// Dimensions et position de l'image.
			this._changeImageSizePosition(this._currentPosition, false, true);

			// Contrôle au clavier.
			this._keyboardActive = true;
			this._keyboard();

			return;
		}

		// Options.
		this._options();

		// Redimensionnement du diaporama lors
		// du redimensionnement de la fenêtre du navigateur.
		$(window).resize(function()
		{
			This._resize();
		});

		// Téléchargement de l'image.
		this._downloadImage();

		// Gestion des sidebars.
		this._sidebarsEvents();

		// Événements pour l'ajout aux favoris et au panier.
		this._favoritesBasketEvents();

		// Fermeture du diaporama.
		this._closeDiaporama();

		// Récupération des informations depuis diaporama.php.
		this._getImages(true);

		// Durée d'affichage des images en lecture automatique.
		this._autoAddDuration();

		// On affiche le diaporama.
		$('#diaporama').show();

		// Gestion de l'affichage des barres du haut et bas.
		this._bars();

		// Dimensions du diaporama.
		this._changeDiaporamaSize();

		// Change la position de l'icône de chargement.
		this._changeLoadingPosition(0, true);

		// Dimensions actuelles du diaporama.
		this._prevDiaporamaSize = this._getDiaporamaSize();

		// Position de l'image courante.
		var position = This._q.match(/position\/(\d+)/);
		this._currentPosition = parseInt(position[1]);

		// Contrôle au clavier.
		this._keyboard();

		// Lecture automatique.
		this._autoEvents();

		// Événements sur les éléments d'édition.
		this._editSidebar();

		// On indique que le diaporama a déjà été initialisé.
		this._init = true;
	};



	/**
	 * Génère une alerte pour afficher un message d'erreur.
	 *
	 * @param string msg
	 * @return void
	 */
	this._alertError = function(msg)
	{
		if (msg == '')
		{
			msg = 'unknown error.';
		}
		else if (msg.length > 500)
		{
			msg = msg.slice(0, 500) + '...';
		}
		alert('Error: ' + msg);
	};

	/**
	 * Ajoute au diaporama la durée d'affichage
	 * des images lors de la lecture automatique.
	 *
	 * @return void
	 */
	this._autoAddDuration = function()
	{
		var duration = this.autoDuration.toString();
		if (!duration.match(/\./))
		{
			duration += '.0';
		}
		$('#diaporama_seconds').text(duration + ' s');
	};

	/**
	 * Change la durée d'affichage des images en lecture automatique.
	 *
	 * @param float i
	 *	Intervalle entre chaque palier de durée.
	 * @param integer d
	 *	Durée entre chaque palier de durée
	 *	lorsqu'on reste appuyé sur le bouton.
	 * @return void
	 */
	this._autoChangeDuration = function(i, d)
	{
		var This = this;

		// Si le bouton n'est plus appuyé, on ne va pas plus loin.
		if (!this._autoDurationMouseDown)
		{
			return;
		}

		// Accélération.
		d = (!d) ? 170 : (d > 20) ? d - 5 : d;

		// La durée doit-être comprise entre une valeur minimale et une valeur maximale.
		if (this.autoDuration + i >= this.autoDurationMin
		 && this.autoDuration + i <= this.autoDurationMax)
		{
			this.autoDuration = Math.round((this.autoDuration + i) * 10) / 10;

			this._autoAddDuration();

			setTimeout(function(){ This._autoChangeDuration(i, d); }, d);
		}

		// Sauvegarde.
		this._savePrefs();
	};

	/**
	 * Gère le changement d'image de la lecture automatique.
	 *
	 * @param boolean start
	 *	Démarre-t-on la lecture automatique ?
	 * @return void
	 */
	this._autoChangeImage = function(start)
	{
		var This = this;

		// Si la lecture automatique a été stoppé, on casse la boucle.
		if ($('#diaporama_icon_stop img').attr('src').match(/stop-active\.png$/))
		{
			return;
		}

		if (!start)
		{
			// Tant que l'on est pas à la dernière image, on continue.
			if (this._currentPosition != this._nbImages)
			{
				this._changeImage($('#diaporama_icon_next'));
			}

			// Si l'on est arrivé à la dernière image, on recommence
			// à la première image si l'option pour boucler est à true.
			else if (this.autoLoop)
			{
				this._changeImage($('#diaporama_icon_first'));
			}
		}

		// On attend le chargement de l'image avant de passer à la suivante.
		var img = '#diaporama_image_' + this._currentPosition;
		var change = function()
		{
			$('#diaporama_loading').css('visibility', 'hidden');
			var transition_duration = (This.transitionEffect != 'none' && !start)
				? parseInt(This.transitionDuration)
				: 0;
			This._autoTimer = setTimeout(
				function(){ This._autoChangeImage(); },
				(This.autoDuration * 1000) + transition_duration
			);
		};
		if ($.browser.msie)
		{
			var src = $(img).attr('src');
			$(img).attr('src', '');
			$(img).attr('src', src);
		}
		$('#diaporama_loading').css('visibility', 'visible');
		$(img)
			.one('load', function()
			{
				change();
			})
			.each(function()
			{
				if (this.complete || this.readyState == 4 || this.readyState == 'complete')
				{
					$(this).trigger('load');
				}
			});
	};

	/**
	 * Gestionnaires d'événements pour la lecture automatique.
	 *
	 * @return void
	 */
	this._autoEvents = function()
	{
		var This = this;
		var msg_timer;

		$('.diaporama_messages').css('opacity', 0.9);

		// Lecture.
		$('#diaporama_icon_start').click(function()
		{
			// Si la lecture automatique a déjà été démarré, inutile d'aller plus loin.
			if ($('#diaporama_icon_start img').attr('src').match(/start-active\.png$/))
			{
				return;
			}

			// S'il n'y a pas plus d'une image, inutile de démarrer la lecture automatique.
			if (This._nbImages < 2)
			{
				return;
			}

			// Si l'on est à la dernière image,
			// et que l'option pour boucler est désactivée,
			// on ne démarre pas la lecture automatique.
			if (This._currentPosition == This._nbImages && !This.autoLoop)
			{
				return;
			}

			// Changement des icônes.
			$('#diaporama_icon_start img').attr('src', $('#diaporama_icon_start img').attr('src')
				.replace(/start\.png$/, 'start-active.png'));
			$('#diaporama_icon_stop img').attr('src', $('#diaporama_icon_stop img').attr('src')
				.replace(/stop-active\.png$/, 'stop.png'));

			// Message indiquant le démarrage de la lecture automatique.
			clearTimeout(msg_timer);
			$('#diaporama_msg_stop').css('visibility', 'hidden');
			$('#diaporama_msg_play')
				.css('visibility', 'visible')
				.fadeIn(1000, function()
				{
					msg_timer = setTimeout(
						function(){ $('#diaporama_msg_play').fadeOut(1000); }, 2000
					);
				});

			// On démarre la lecture automatique.
			This._autoChangeImage(true);
		});

		// Arrêt.
		$('#diaporama_icon_stop').click(function()
		{
			// Si la lecture automatique n'a pas été démarré, inutile d'aller plus loin.
			if ($('#diaporama_icon_stop img').attr('src').match(/stop-active\.png$/))
			{
				return;
			}

			// On stoppe la lecture automatique.
			clearTimeout(This._autoTimer);
			This._autoTimer = undefined;

			// Message indiquant l'arrêt de la lecture automatique.
			clearTimeout(msg_timer);
			$('#diaporama_msg_play').css('visibility', 'hidden');
			$('#diaporama_msg_stop')
				.css('visibility', 'visible')
				.fadeIn(1000, function()
				{
					msg_timer = setTimeout(
						function(){ $('#diaporama_msg_stop').fadeOut(1000); }, 2000
					);
				});

			// Changement des icônes.
			$('#diaporama_icon_stop img').attr('src', $('#diaporama_icon_stop img').attr('src')
				.replace(/stop\.png$/, 'stop-active.png'));
			$('#diaporama_icon_start img').attr('src', $('#diaporama_icon_start img').attr('src')
				.replace(/start-active\.png$/, 'start.png'));
		});

		// Augmenter la durée d'affichage.
		$('#diaporama_icon_more')
			.mousedown(function()
			{
				This._autoDurationMouseDown = true;
				This._autoChangeDuration(This.autoDurationPrecision);
			})
			.mouseup(function()
			{
				This._autoDurationMouseDown = false;
			})
			.focus(function()
			{
				$('#diaporama_icon_more').trigger('mouseover');
			})
			.blur(function()
			{
				$('#diaporama_icon_more').trigger('mouseout');
			})
			.mouseover(function()
			{
				$(this).find('img').attr('src', $(this).find('img').attr('src')
					.replace(/more\.png$/, 'more-hover.png'));
			})
			.mouseout(function()
			{
				$(this).find('img').attr('src', $(this).find('img').attr('src')
					.replace(/more-hover\.png$/, 'more.png'));
			});

		// Diminuer la durée d'affichage.
		$('#diaporama_icon_less')
			.mousedown(function()
			{
				This._autoDurationMouseDown = true;
				This._autoChangeDuration(-This.autoDurationPrecision);
			})
			.mouseup(function()
			{
				This._autoDurationMouseDown = false;
			})
			.focus(function()
			{
				$('#diaporama_icon_less').trigger('mouseover');
			})
			.blur(function()
			{
				$('#diaporama_icon_less').trigger('mouseout');
			})
			.mouseover(function()
			{
				$(this).find('img').attr('src', $(this).find('img').attr('src')
					.replace(/less\.png$/, 'less-hover.png'));
			})
			.mouseout(function()
			{
				$(this).find('img').attr('src', $(this).find('img').attr('src')
					.replace(/less-hover\.png$/, 'less.png'));
			});
	};

	/**
	 * Gestion de l'affichage des barres de contrôle (barres du haut et du bas).
	 *
	 * @return void
	 */
	this._bars = function(e)
	{
		var This = this;
		var hide_timer;

		// Pour éviter les caprices de certains navigateurs.
		var x, y;

		if (this.hideControlBars)
		{
			$('.diaporama_bar,#diaporama_carousel').hide();
		}

		$('#diaporama').mousemove(function(e)
		{
			if (!This.hideControlBars || This._isSidebarVisible() || This._controlBarsOver
			|| This._controlBarsAnimation || (x == e.pageX && y == e.pageY))
			{
				return;
			}

			clearTimeout(hide_timer);

			x = e.pageX;
			y = e.pageY;

			// Affiche les barres.
			var duration = 500;

			This._controlBarsAnimation = true;
			setTimeout(function() { This._controlBarsAnimation = false; }, duration);

			$('.diaporama_bar' + (This.carousel ? ',#diaporama_carousel' : '')).fadeIn(duration);
			if (This.carousel)
			{
				This._changeCarouselSizePosition();
			}

			// Cache les barres.
			hide_timer = setTimeout(function()
			{
				if (This.hideControlBars && !This._isSidebarVisible() && !This._controlBarsOver)
				{
					var duration = 1000;

					This._controlBarsAnimation = true;
					setTimeout(function() { This._controlBarsAnimation = false; }, duration);

					$('.diaporama_bar,#diaporama_carousel').fadeOut(duration);
				}
			}, 1500);
		});

		// On ne cache pas les barres de contrôle
		// si la souris se trouve sur celles-ci.
		$('.diaporama_bar,#diaporama_carousel')
			.mouseover(function()
			{
				This._controlBarsOver = true;
			})
			.mouseout(function()
			{
				This._controlBarsOver = false;
			});
	};

	/**
	 * Change les dimensions du diaporama
	 * en fonction de l'espace disponible dans le navigateur.
	 *
	 * @return void
	 */
	this._changeDiaporamaSize = function()
	{
		// Zone du diaporama.
		$('#diaporama').css(
		{
			height: $(window).height() + 'px',
			width: $(window).width() + 'px'
		});

		// Sidebars.
		$('.diaporama_sidebar').css(
		{
			height: ($(window).height()
				- $('#diaporama_top').height()
				- $('#diaporama_bottom').height()) + 'px'
		});

		// Carrousel.
		this._changeCarouselSizePosition();

		// Icône de chargement.
		this._changeLoadingPosition();
	};

	/**
	 * Change l'image en fonction du bouton de navigation cliqué.
	 *
	 * @param object button
	 *	Bouton de navigation qui a été cliqué.
	 * @param boolean click
	 *	Indique si la fonction a été appelée par un click de souris.
	 * @return void
	 */
	this._changeImage = function(button, click)
	{
		var This = this;

		// On détermine la position de l'image à afficher.
		var new_current_position;
		button = $(button).attr('id').replace(/diaporama_icon_/, '');
		switch (button)
		{
			case 'first' :
				new_current_position = 1;
				break;

			case 'prev' :
				new_current_position = this._currentPosition - 1;
				break;

			case 'next' :
				new_current_position = this._currentPosition + 1;
				break;

			case 'last' :
				new_current_position = this._nbImages;
				break;
		}

		// Si l'image n'existe pas
		// ou correspondant à une valeur impossible
		// on bien si un effet de transition entre images est en cours,
		// on ne va pas plus loin.
		if (!$('#diaporama_image_' + new_current_position).is('img')
		|| new_current_position < 1 || new_current_position > this._nbImages
		|| this._imageTransitionLock
		|| this._sidebarMoveLock)
		{
			return;
		}

		// On redimensionne l'image à afficher.
		this._realsize = false;
		$('#diaporama_icon_switch img').attr(
			'src',
			$('#diaporama_icon_switch img').attr('src')
				.replace(/noresize(-grey)?\.png$/, 'resize$1.png')
		);

		// On indique les bonnes dimensions et position à l'image que l'on va afficher.
		this._changeImageSizePosition(new_current_position);

		// On cache l'image actuelle et on affiche l'image demandée.
		var old_current_position = this._currentPosition;
		this._currentPosition = new_current_position;

		// Transition entre l'image courante et l'image demandée.
		this._transition(old_current_position, new_current_position, button);

		// Nouvelle requête sur diaporama.php.
		this._q = this._q.replace(/position\/\d+$/, 'position/' + new_current_position);

		// On effectue une nouvelle requête ajax, mais seulement
		// après l'effet de transition si un effet de transition est activé,
		// afin d'éviter que l'effet de transition ne soit saccadé.
		if (this.transitionEffect == 'none')
		{
			this._getImages();
		}
		else
		{
			setTimeout(function(){ This._getImages(); }, this.transitionDuration);
		}

		// Change les boutons de navigation.
		this._changeNavigationButtons();

		// Change le bouton de switch.
		this._changeSwitchButton();

		// Si l'on est arrivé à la dernière image en lecture automatique,
		// et que l'option pour boucler est désactivée, on stoppe la lecture
		// automatique.
		if (typeof this._autoTimer == 'number'
		&& new_current_position == this._nbImages
		&& !this.autoLoop)
		{
			$('#diaporama_icon_stop').trigger('click');
		}

		// Si le changement d'image s'est fait manuellement,
		// et que la lecture automatique est en cours,
		// alors on réinitialise la lecture automatique.
		if (click && typeof this._autoTimer == 'number')
		{
			// On stoppe la lecture automatique.
			clearTimeout(this._autoTimer);
			this._autoTimer = undefined;

			// On redémarre la lecture automatique.
			this._autoChangeImage(true);
		}
	};

	/**
	 * Change les informations de l'image courante
	 * affichées dans le diaporama.
	 *
	 * @return void
	 */
	this._changeImageInfos = function()
	{
		if (this._images[this._currentPosition] === undefined)
		{
			return;
		}

		// Position de l'image dans la section courante.
		$('#diaporama_image_position').text(this._images[this._currentPosition].current_image);

		// Position de l'image dans la galerie (fil d'ariane).
		$('#diaporama_top_left').html(this._images[this._currentPosition].image_position);

		var current = this._images[this._currentPosition];
		var infos = current.infos;

		// Informations : description.
		if (infos.desc.title === undefined)
		{
			$('#diaporama_sidebar_desc').hide();
			$('#diaporama_sidebar_desc .diaporama_sidebar_title2 span').empty();
		}
		else
		{
			$('#diaporama_sidebar_desc').show();
			$('#diaporama_sidebar_desc .diaporama_sidebar_title2 span').text(infos.desc.title);
			$('#diaporama_sidebar_desc .diaporama_sidebar_content p').html(infos.desc.content);
		}

		// Informations : statistiques.
		if (infos.stats.title === undefined)
		{
			$('#diaporama_sidebar_stats').hide();
			$('#diaporama_sidebar_stats .diaporama_sidebar_title2 span').empty();
			$('#diaporama_sidebar_stats .diaporama_sidebar_content').empty();
		}
		else
		{
			$('#diaporama_sidebar_stats').show();
			$('#diaporama_sidebar_stats .diaporama_sidebar_title2 span').text(infos.stats.title);
			var list = '<ul>';
			for (stat in infos.stats.items)
			{
				list += '<li>' + infos.stats.items[stat] + '</li>';
			}
			list += '</ul>';
			$('#diaporama_sidebar_stats .diaporama_sidebar_content').html(list);
		}

		// Informations : tags.
		if (infos.tags.title === undefined)
		{
			$('#diaporama_sidebar_tags').hide();
			$('#diaporama_sidebar_tags .diaporama_sidebar_title2 span').empty();
			$('#diaporama_sidebar_tags .diaporama_sidebar_content').empty();
		}
		else
		{
			$('#diaporama_sidebar_tags').show();
			$('#diaporama_sidebar_tags .diaporama_sidebar_title2 span').text(infos.tags.title);
			var list = '<ul>';
			for (t in infos.tags.items)
			{
				list += '<li class="icon icon_tag"><a href="'
					+ infos.tags.items[t].tag_link + '">'
					+ infos.tags.items[t].tag_name + '</a></li>' + "\n";
			}
			list += '</ul>';
			$('#diaporama_sidebar_tags .diaporama_sidebar_content').html(list);
		}

		// Informations : métadonnées.
		var metadata = ['exif', 'iptc', 'xmp'];
		for (meta in metadata)
		{
			if (infos[metadata[meta]].title === undefined)
			{
				$('#diaporama_sidebar_' + metadata[meta]).hide();
				$('#diaporama_sidebar_' + metadata[meta]
					+ ' .diaporama_sidebar_title2 span').empty();
				$('#diaporama_sidebar_' + metadata[meta]
					+ ' .diaporama_sidebar_content').empty();
			}
			else
			{
				$('#diaporama_sidebar_' + metadata[meta]).show();
				$('#diaporama_sidebar_' + metadata[meta]
					+ ' .diaporama_sidebar_title2 span').text(infos[metadata[meta]].title);
				var list = '<ul>';
				for (data in infos[metadata[meta]].items)
				{
					list += '<li><span>' + infos[metadata[meta]].items[data].name + '</span> : '
						+ infos[metadata[meta]].items[data].value + '</li>';
				}
				list += '</ul>';
				$('#diaporama_sidebar_' + metadata[meta]
					+ ' .diaporama_sidebar_content').html(list);
			}
		}

		// Édition.
		if ($('#diaporama_edit').is('div'))
		{
			// Nom d'URL.
			$('#diaporama_edit_urlname').val(current.image_url);

			// Titre et description pour chaque langue.
			$('#diaporama_edit_langs_select option').each(function()
			{
				// Titre.
				$('#diaporama').append('<textarea style="display:none" id="diaporama_tatemp">'
					+ current.locale.title[$(this).val()] + '</textarea>');
				$('#diaporama_edit_title_' + $(this).val())
					.val($('#diaporama_tatemp').val());
				$('#diaporama_tatemp').remove();

				// Description.
				if (current.locale.desc[$(this).val()] === null)
				{
					$('#diaporama_edit_description_' + $(this).val()).val('');
				}
				else
				{
					$('#diaporama').append('<textarea style="display:none" id="diaporama_tatemp">'
						+ current.locale.desc[$(this).val()] + '</textarea>');
					$('#diaporama_edit_description_' + $(this).val())
						.val($('#diaporama_tatemp').val());
					$('#diaporama_tatemp').remove();
				}
			});

			// Tags.
			if (infos.tags.title === undefined)
			{
				$('#diaporama_edit_tags').val('');
			}
			else
			{
				var tags = new Array;
				for (var i = 0; i < infos.tags.items.length; i++)
				{
					tags[i] = infos.tags.items[i].tag_name;
				}
				$('#diaporama').append('<textarea style="display:none" id="diaporama_tatemp">'
					+ tags.join(', ') + '</textarea>');
				$('#diaporama_edit_tags').val($('#diaporama_tatemp').val());
				$('#diaporama_tatemp').remove();
			}

			// Si l'utilisateur n'a pas la permission d'éditer l'image,
			// alors on désactive les éléments du formulaire d'édition.
			if (this._images[this._currentPosition].perm_edit == 1)
			{
				$('#diaporama_edit').removeClass('diaporama_disabled');
				$('#diaporama_edit input,#diaporama_edit textarea,#diaporama_edit select')
					.removeAttr('disabled');
			}
			else
			{
				$('#diaporama_edit').addClass('diaporama_disabled');
				$('#diaporama_edit input,#diaporama_edit textarea,#diaporama_edit select')
					.attr('disabled', 'disabled');
			}
		}

		// Dans les favoris ?
		if ($('#diaporama_icon_fav').is('a'))
		{
			var src = $('#diaporama_icon_fav img').attr('src').replace(/(-active)?.png$/, '');
			if (this._images[this._currentPosition].in_favorites)
			{
				src += '-active.png';
				$('#diaporama_icon_fav').attr('title', diaporama_fav_del);
			}
			else
			{
				src += '.png';
				$('#diaporama_icon_fav').attr('title', diaporama_fav_add);
			}
			$('#diaporama_icon_fav img').attr('src', src);
		}

		// Dans le panier ?
		if ($('#diaporama_icon_basket').is('a'))
		{
			var src = $('#diaporama_icon_basket img').attr('src').replace(/(-active)?.png$/, '');
			if (this._images[this._currentPosition].in_basket)
			{
				src += '-active.png';
				$('#diaporama_icon_basket').attr('title', diaporama_basket_del);
			}
			else
			{
				src += '.png';
				$('#diaporama_icon_basket').attr('title', diaporama_basket_add);
			}
			$('#diaporama_icon_basket img').attr('src', src);
		}

		// Carrousel.
		this._changeCarouselCurrent();
	};

	/**
	 * Change les dimensions et la position (coordonnées spatiales) de l'image
	 * en fonction de l'espace disponible dans la zone d'affichage.
	 *
	 * @param number position
	 *	Position de l'image dans la section courante (entre 1 et this._nb_images).
	 *	A ne pas confondre avec la position (top, left) de l'image
	 *	dans la zone d'affichage.
	 * @param boolean animate
	 *	Doit-on autoriser l'animation de l'image ?
	 * @param boolean visible
	 *	Doit-on afficher l'image ?
	 * @param boolean r
	 *	Doit-on retourner les règles CSS au lieu de les appliquer à l'image ?
	 * @param number width
	 *	Largeur à ajouter à la largeur de la zone d'affichage.
	 * @return object
	 *	Retourne les valeurs CSS, si demandées.
	 */
	this._changeImageSizePosition = function(position, animate, visible, r, width)
	{
		// Change la position de l'icône de chargement.
		this._changeLoadingPosition(width);

		if (this._images[position] === undefined
		|| !$('#diaporama_image_' + position).is('img'))
		{
			return;
		}

		// Récupération des dimensions du diaporama.
		var s = this._getDiaporamaSize();
		s.availableWidth += (width) ? width : 0;

		// Dimensions de l'image.
		var width_ratio = this._images[position].image_width / s.availableWidth;
		var height_ratio = this._images[position].image_height / s.availableHeight;
		var img_width = this._images[position].image_width;
		var img_height = this._images[position].image_height;
		var img_width_resize = img_width;
		var img_height_resize = img_height;
		if (!this._realsize)
		{
			if ((img_width > s.availableWidth) && (width_ratio >= height_ratio))
			{
				img_width_resize = s.availableWidth;
				img_height_resize = img_height / width_ratio;
			}
			if ((img_height > s.availableHeight) && (height_ratio >= width_ratio))
			{
				img_width_resize = img_width / height_ratio;
				img_height_resize = s.availableHeight;
			}
		}

		// Position de l'image.
		var img_offset = $('#diaporama_image_' + position).offset();
		var img_current_offset = $('#diaporama_image_' + position).offset();

		// Position de l'image, largeur : modification de la taille de l'image.
		if (img_width_resize <= s.availableWidth
		|| img_width_resize != $('#diaporama_image_' + position).width())
		{
			img_offset.left = (s.availableWidth - img_width_resize) / 2;
		}

		// Position de l'image, largeur : modification de la taille de la zone d'affichage.
		else if (img_width_resize > s.availableWidth
		&& s.availableWidth != this._prevDiaporamaSize.availableWidth)
		{
			img_offset.left += (s.availableWidth - this._prevDiaporamaSize.availableWidth) / 2;

			var right = img_width_resize - s.availableWidth + img_offset.left;
			if (right < 0)
			{
				img_offset.left -= right;
			}
			if (img_offset.left > 0)
			{
				img_offset.left = 0;
			}

			img_offset.left = img_offset.left;
		}

		// Position de l'image, hauteur : modification de la taille de l'image.
		if (img_height_resize <= s.availableHeight
		|| img_height_resize != $('#diaporama_image_' + position).height())
		{
			img_offset.top = ((s.availableHeight - img_height_resize) / 2) + s.barTopHeight;
		}

		// Position de l'image, hauteur : modification de la taille de la zone d'affichage.
		else if (img_height_resize > s.availableHeight
		&& s.availableHeight != this._prevDiaporamaSize.availableHeight)
		{
			img_offset.top += ((s.availableHeight - this._prevDiaporamaSize.availableHeight) / 2);
			var bottom = img_height_resize
				- (s.availableHeight + s.barBottomHeight + s.carouselHeight)
				+ img_offset.top;
			if (bottom < 0)
			{
				img_offset.top -= bottom;
			}
			if (img_offset.top > s.barTopHeight)
			{
				img_offset.top = s.barTopHeight;
			}

			img_offset.top = img_offset.top;
		}

		// Valeurs CSS.
		var css = {
			top: Math.round(img_offset.top) + 'px',
			left: Math.round(img_offset.left) + 'px',
			width: Math.round(img_width_resize) + 'px',
			height: Math.round(img_height_resize) + 'px'
		};

		// On applique les nouvelles propriétés à l'image.
		if (!r)
		{
			if (animate && $('#diaporama_image_' + position).is(':visible')
			&& this.imageResizeDuration > 0
			&& this.animate)
			{
				$('#diaporama_image_' + position).animate(
					css, this.imageResizeDuration);
			}
			else
			{
				$('#diaporama_image_' + position).css(css);
			}
		}

		// Doit-on afficher l'image ?
		if (visible)
		{
			$('#diaporama_image_' + position).show();
		}

		// Anti clic droit.
		if (no_right_click)
		{
			$('#diaporama_image_' + position).bind('contextmenu', function()
			{
				return false;
			});
		}

		// Doit-on retourner les valeurs CSS ?
		if (r)
		{
			return css;
		}

		// Change le bouton de switch.
		if (position == this._currentPosition)
		{
			this._changeSwitchButton();
		}
	};

	/**
	 * Change la position de l'icône de chargement.
	 *
	 * @param number width
	 *	Largeur à ajouter à la largeur de la zone d'affichage.
	 * @param number width
	 *	Démarrage du diaporama ?
	 * @return void
	 */
	this._changeLoadingPosition = function(width, start)
	{
		var s = this._getDiaporamaSize();
		s.availableWidth += (width) ? width : 0;

		$('#diaporama_loading').css(
		{
			top: (((s.availableHeight - $('#diaporama_loading').height()) / 2)
				+ s.barTopHeight) + 'px',
			left: ((s.availableWidth - $('#diaporama_loading').width()) / 2) + 'px'
		});

		// spin.js
		if (start)
		{
			var spinner = new Spinner({
				lines: 8,
				length: 0,
				width: 16,
				radius: 18,
				corners: 1,
				rotate: 0,
				color: '#fff',
				speed: 1,
				trail: 60,
				shadow: false
			}).spin(document.getElementById('diaporama_loading'));
		}
	};

	/**
	 * Change les boutons de navigation.
	 *
	 * @return void
	 */
	this._changeNavigationButtons = function()
	{
		var This = this;
		var buttons = {
			'first': 1,
			'prev': (this._currentPosition <= 2)
				? 1 : this._currentPosition - 1,
			'next': (this._currentPosition >= this._nbImages)
				? this._nbImages : this._currentPosition + 1,
			'last': this._nbImages
		};

		for (button in buttons)
		{
			var icon = '#diaporama_icon_' + button;
			var regexp = new RegExp(button + '(?:-hover|-grey)?\.png$');

			$(icon).unbind();

			if (this._images[buttons[button]] === undefined
			 || this._currentPosition == buttons[button])
			{
				$(icon + ' img').attr('src', $(icon + ' img').attr('src')
					.replace(regexp, button + '-grey.png'));
				$(icon)
					.removeAttr('href')
					.css({ cursor: 'default' });
			}
			else
			{
				if ($(icon + ' img').attr('src').match(/-grey.png$/))
				{
					$(icon + ' img').attr('src', $(icon + ' img').attr('src')
						.replace(regexp, button + '.png'));
				}
				$(icon)
					.css({ cursor: 'pointer' })
					.focus(function()
					{
						$(this).trigger('mouseover');
					})
					.blur(function()
					{
						$(this).trigger('mouseout');
					})
					.mouseover(function()
					{
						var button = $(this).attr('id').replace(/diaporama_icon_/, '');
						$(this).find('img').attr('src', $(this).find('img').attr('src')
							.replace(button + '.png', button + '-hover.png'));
					})
					.mouseout(function()
					{
						var button = $(this).attr('id').replace(/diaporama_icon_/, '');
						$(this).find('img').attr('src', $(this).find('img').attr('src')
							.replace(button + '-hover.png', button + '.png'));
					})
					.click(function()
					{
						This._changeImage(this, true);
					});
			}
		}
	};

	/**
	 * Change le bouton du switch taille réelle / taille redimensionnée de l'image.
	 *
	 * @return void
	 */
	this._changeSwitchButton = function()
	{
		var This = this;

		if (this._images[this._currentPosition] === undefined)
		{
			return;
		}

		// Récupération des dimensions du diaporama.
		var s = this._getDiaporamaSize();

		// Suppression de l'événement 'click' sur le bouton.
		$('#diaporama_icon_switch').unbind('click');

		// Si l'image est plus grande que la zone d'affichage, on met
		// en place le bouton adéquat.
		if (this._images[this._currentPosition].image_width > s.availableWidth
		 || this._images[this._currentPosition].image_height > s.availableHeight)
		{
			$('#diaporama_icon_switch').click(function()
			{
				This._realsize = !This._realsize;
				This._changeImageSizePosition(This._currentPosition, true);
			});
			var no = (this._realsize) ? 'no' : '';
			$('#diaporama_icon_switch img')
				.attr('src', $('#diaporama_icon_switch img').attr('src')
					.replace(/(no)?resize(-grey)?\.png$/, no + 'resize.png'))
				.css({ cursor: 'pointer' });
		}

		// Si l'image peut tenir dans la zone d'affichage, on désactive le bouton.
		else
		{
			$('#diaporama_icon_switch').removeAttr('href');
			$('#diaporama_icon_switch img')
				.attr('src', $('#diaporama_icon_switch img').attr('src')
					.replace(/(no)?resize(-grey)?\.png$/, 'resize-grey.png'))
				.css({ cursor: 'default' });
		}

		this._imageMouseDown();
	};

	/**
	 * Fermeture du diaporama.
	 *
	 * @return void
	 */
	this._closeDiaporama = function()
	{
		var This = this;

		$('#diaporama_icon_close').click(function()
		{
			// On désactive les événements clavier.
			This._keyboardActive = false;

			// On remet en place les barres de défilement.
			$('html').css('overflow', 'auto');
			$('body').css('overflow', 'auto');

			// On stoppe la lecture automatique.
			$('#diaporama_icon_stop').trigger('click');
			$('.diaporama_messages').stop().hide();
			$('#diaporama_edit .message').hide();

			// On cache le diaporama et on réaffiche la galerie.
			$('#igalerie_gallery').show();
			$('#diaporama').hide();
			$('#diaporama_image_' + This._currentPosition).hide();
		});
	};

	/**
	 * Création de l'élément '<img>' de l'image correspond à 'position'.
	 *
	 * @param string position
	 *	Position de l'image dans la section courante.
	 * @return void
	 */
	this._createImage = function(position)
	{
		if (this._images[position] === undefined
		|| $('#diaporama_image_' + position).is('img'))
		{
			return;
		}

		$('#diaporama').append(
			'<img class="diaporama_image" id="diaporama_image_' + position
			+ '" style="display:none;" src="' + this._images[position].image_src + '" alt="" />'
		);

		// Amélioration de l'affichage avec Firefox.
		if ($.browser.mozilla)
		{
			$('#diaporama_image_' + position).css('outline', '1px solid transparent');
		}

		// Icône de chargement.
		if (position == this._currentPosition)
		{
			if ($.browser.msie)
			{
				$('#diaporama_loading').css('visibility', 'hidden');
			}
			else
			{
				$('#diaporama_image_' + this._currentPosition)
					.one('load', function()
					{
						$('#diaporama_loading').css('visibility', 'hidden');
					})
					.each(function()
					{
						if (this.complete)
						{
							$(this).trigger('load');
						}
					});
			}
		}
	};

	/**
	 * Supprime les images inutiles pour ne pas encombrer et ralentir le diaporama.
	 *
	 * @param boolean all
	 *	Doit-on supprimer toutes les images ?
	 * @return void
	 */
	this._deleteImages = function(all)
	{
		var This = this;
		$('.diaporama_image').each(function()
		{
			var position = $(this).attr('id').replace(/diaporama_image_/, '');
			if (This._images[position] === undefined || all)
			{
				$(this).remove();
			}
		});
	};

	/**
	 * Fonction de drag basée sur le code posté par
	 * Lasse Reichstein Nielsen dans le groupe comp.lang.javascript
	 * en janvier 2004 et adapté pour iGalerie et jQuery.
	 *
	 * @param object img
	 * @param object evt
	 * @return boolean
	 */
	this._dragImage = function(img, evt)
	{
		// Récupération des dimensions du diaporama.
		var s = this._getDiaporamaSize();

		var x = img.offsetLeft;
		var y = img.offsetTop;
		var mx = evt.pageX;
		var my = evt.pageY;

		$(document).mousemove(function(evt)
		{
			var newmx = evt.pageX;
			var newmy = evt.pageY;
			x += newmx - mx;
			y += newmy - my;
			mx = newmx;
			my = newmy;

			if ($(img).width() > s.availableWidth)
			{
				if (x > 0)
				{
					x = 0;
				}
				else if (x < (s.availableWidth - $(img).width()))
				{
					x = s.availableWidth - $(img).width();
				}
			}
			else
			{
				x = $(img).css('left');
			}

			if ($(img).height() > s.availableHeight)
			{
				if (y > s.barTopHeight)
				{
					y = s.barTopHeight;
				}
				else if (y < $(window).height() - s.barBottomHeight
					- s.carouselHeight - $(img).height() - s.imageBorderHeight)
				{
					y = $(window).height() - s.barBottomHeight
						- s.carouselHeight - $(img).height() - s.imageBorderHeight;
				}
			}
			else
			{
				y = $(img).css('top');
			}

			$(img).css({
				left: x + 'px',
				top: y + 'px'
			});

			return false;
		});

		$(document).mouseup(function()
		{
			$(document).unbind('mousemove mouseup');

			return false;
		});

		return false;
	};

	/**
	 * Événements sur les éléments d'édition.
	 *
	 * @return void
	 */
	this._editSidebar = function()
	{
		if (!$('#diaporama_edit').is('div'))
		{
			return;
		}

		var This = this;
		var ajax_report_timeout;

		// Rapports.
		var ajax_message_error = function(msg)
		{
			$('#diaporama_edit .message').hide();
			$('#diaporama_edit .message_error span').text(msg);
			$('#diaporama_edit .message_error').show();
			ajax_report_timeout = setTimeout(
				function() { $('#diaporama_edit .message_error').hide(); },
				4000
			);
		};
		var ajax_message_success = function()
		{
			$('#diaporama_edit .message_success').show();
			ajax_report_timeout = setTimeout(
				function() { $('#diaporama_edit .message_success').hide(); },
				3000
			);
		};

		// Langues d'édition.
		$('#diaporama_edit_langs select').change(function()
		{
			var lang = $(this).find(':selected').val();
			$('#diaporama_edit label.icon_lang').parents('p').hide();
			$('#diaporama_edit label.icon_' + lang).parents('p').show();
		});

		// Envoi du formulaire.
		$('#diaporama_edit form').submit(function()
		{
			return false;
		});
		$('#diaporama_edit input.submit').click(function()
		{
			var data = $('#diaporama_edit form').serialize();
			var tags = $('#diaporama_edit_tags').val();
			var urlname = $('#diaporama_edit_urlname').val();

			// Désactive les éléments de formulaire, prépare le message de rapport
			// et affiche l'icône de chargement.
			clearTimeout(ajax_report_timeout);
			$('#diaporama_edit .message').hide();
			$('#diaporama_edit_loading').show();
			$('#diaporama_edit input').attr('disabled', 'disabled');
			$('#diaporama_edit textarea').attr('disabled', 'disabled');

			$.post(gallery_path + '/ajax.php', {
				section: 'edit-image',
				id: This._images[This._currentPosition].image_id,
				data: data,
				urlname: urlname,
				tags: tags,
				anticsrf: This._anticsrf,
				q: q,
				q_md5: q_md5
			},
			function(r)
			{
				// Réactive les éléments de formulaire et supprime l'icône de chargement.
				$('#diaporama_edit_loading').hide();
				$('#diaporama_edit input').removeAttr('disabled');
				$('#diaporama_edit textarea').removeAttr('disabled');

				switch (r.status)
				{
					// Aucun changement.
					case 'nochange' :
						$('#diaporama_edit .message').hide();
						break;

					// Modification réussie.
					case 'success' :
						ajax_message_success();
						This._getImages(false);
						break;

					// Avertissement.
					case 'warning' :
						ajax_message_error(r.msg);
						break;

					// Erreur.
					case 'error' :
						This._alertError(r.msg);
						break;
				}
			}, 'json');
		});
	};

	/**
	 * Téléchargement de l'image.
	 *
	 * @return void
	 */
	this._downloadImage = function()
	{
		var This = this;

		$('#diaporama_icon_download').click(function()
		{
			window.location = gallery_path + '/download.php?img='
				+ This._images[This._currentPosition].image_id;
		});
	};

	/**
	 * Événements pour l'ajout aux favoris et au panier.
	 *
	 * @return void
	 */
	this._favoritesBasketEvents = function()
	{
		var This = this;

		var o = ['fav', 'basket'];
		for (var i = 0; i < o.length; i++)
		{
			$('#diaporama_icon_' + o[i]).click(function()
			{
				if (This._images[This._currentPosition] === undefined)
				{
					return;
				}

				var id = $(this).attr('id');
				var type = id.match(/fav$/) ? 'fav' : 'basket';
				var action = $('#diaporama_icon_' + type + ' img').attr('src').match(/-active/)
					? '-remove' : '-add';

				$.post(gallery_path + '/ajax.php',
				{
					anticsrf: This._anticsrf,
					section: (id.match(/fav$/) ? 'favorites' : 'basket') + action,
					images_id: This._images[This._currentPosition].image_id,
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
							This._alertError(r.msg);
							break;

						case 'full' :
							alert(r.msg);
							break;

						case 'success' :
							// On recrée les images uniquement si l'on se situe
							// dans la section correspondante.
							var only_image_infos =
								  ((id.match(/basket$/) && This._q.match(/^basket/))
								|| (id.match(/fav$/) && This._q.match(/^user-favorites/)))
								? false
								: true;

							This._getImages(false, only_image_infos);
							break;
					}
				}, 'json');
			});
		}
	};

	/**
	 * Récupère les informations du caroussel.
	 *
	 * @param integer p
	 * @return void
	 */
	this._getCarousel = function(p)
	{
		if (!this.carousel)
		{
			return;
		}

		var This = this;

		var q = (p) ? this._q.replace(/\d+$/, p) : this._q;
		this._carouselCurrentPosition = (p) ? p : this._currentPosition;

		$.post(
			gallery_path + '/ajax.php?q=' + q,
			{
				section: 'carousel',
				size: this.carouselThumbsSize
			},
			function(r)
			{
				if (typeof r != 'object' || r === null)
				{
					return;
				}
				switch (r.status)
				{
					// Erreur.
					case 'error' :
						This._alertError(r.msg);
						break;

					// Aucun résultat.
					case 'no_result' :
						break;

					// Succès.
					default :
						This._carouselImages = r.images;

						// Si le nombre d'images de la section courante
						// n'est plus le même, on actualise le caroussel.
						if (r.nb_images != This.nbImages)
						{
							$('#diaporama_carousel_thumbs').empty();
						}

						// Si des images ont été supprimées, on recharge le diaporama.
						if (This._carouselImages[This._carouselCurrentPosition] === undefined
						&& r.nb_images > 0)
						{
							This._reload(r.nb_images);
							break;
						}

						This.nbImages = r.nb_images;

						This._changeCarouselImages();
						break;
				}
			},
			'json'
		);
	};

	/**
	 * Retourne la position à partir de laquelle on
	 * doit afficher les vignettes dans le carrousel.
	 *
	 * @return void
	 */
	this._getCarouselStart = function()
	{
		var i = Math.floor(this._carouselMaxThumbs) - 1;

		return ((Math.ceil(this._carouselCurrentPosition / i) - 1) * i) + 1;
	};

	/**
	 * Change l'indicateur de l'image courante du caroussel.
	 *
	 * @return void
	 */
	this._changeCarouselCurrent = function()
	{
		if (!this.carousel)
		{
			return;
		}

		$('#diaporama_carousel_thumbs dl').removeClass('current');
		$('#diaporama_carousel_image_' + this._currentPosition).addClass('current');
	};

	/**
	 * Change les images du caroussel.
	 *
	 * @param string nav
	 * @return void
	 */
	this._changeCarouselImages = function(nav)
	{
		if (this._carouselImages === undefined)
		{
			return;
		}

		if (this._currentPosition >= this._carouselMaxPosition)
		{
			var center, width, height, html = '', n = 0, This = this;
			var nb_thumbs = Math.floor(this._carouselMaxThumbs) - 1;
			var max_thumbs = Math.ceil(this._carouselMaxThumbs);

			// Position à partir de laquelle on affiche les vignettes.
			var start = this._getCarouselStart();
			if (start == this._nbImages)
			{
				start -= nb_thumbs;
				if (start < 1)
				{
					start = 1;
				}
			}

			// Si les animations sont désactivées, on regénère les vignettes
			// normalement lors de la navigation dans le carrousel.
			if (!this.animate)
			{
				nav = false;
			}

			// Dans le cas d'une navigation dans le carrousel,
			// on supprimera les vignettes après l'animation.
			if (nav)
			{
				this._carouselAnimation = true;
				this._carouselCurrentPosition = start;

				var s = this._getDiaporamaSize();
				var margin_left = nb_thumbs * (this.carouselThumbsSize
					+ (this.carouselThumbsBorder * 2) + this.carouselThumbsMargin);

				if (nav == 'prev')
				{
					$('#diaporama_carousel_thumbs dl:gt('
						+ (max_thumbs - nb_thumbs) + ')').addClass('old');
				}
				else
				{
					$('#diaporama_carousel_thumbs dl:lt('
						+ nb_thumbs + ')').addClass('old');
				}
			}

			// Sinon, on supprime tout de suite toutes les vignettes,
			// mais seulement si c'est nécessaire.
			else
			{
				var first_position = ($('#diaporama_carousel_thumbs dl').length)
					? parseInt($('#diaporama_carousel_thumbs dl:first').attr('id')
						.replace(/diaporama_carousel_image_(\d+)/, '$1'))
					: start;
				if (first_position != start)
				{
					$('#diaporama_carousel_thumbs').empty();
				}
			}

			// Création et insertion des nouvelles vignettes.
			for (p in this._carouselImages)
			{
				if (p < start || $('#diaporama_carousel_image_' + p).is('dl'))
				{
					continue;
				}
				if (nav == 'prev' && n == nb_thumbs)
				{
					break;
				}
				n++;

				width = this.carouselThumbsSize;
				height = this.carouselThumbsSize;
				center = this._carouselImages[p].thumb_center;
				html += '<dl id="diaporama_carousel_image_' + p
					+ '"><dt style="width:' + width + 'px"><a rel="'
					+ n + '" class="thumb_link" style="width:'
					+ width + 'px;height:' + height + 'px;"><img width="'
					+ this._carouselImages[p].thumb_width + '" height="'
					+ this._carouselImages[p].thumb_height + '" style="padding:'
					+ this._carouselImages[p].thumb_center + '" src="'
					+ this._carouselImages[p].thumb_src + '" /></a></dt></dl>' + "\n";

				this._carouselMaxPosition = p;

				if (n == max_thumbs)
				{
					break;
				}
			}
			if (nav == 'prev')
			{
				$('#diaporama_carousel_thumbs')
					.css({'margin-left': '-' + margin_left + 'px'})
					.prepend(html);
			}
			else if (html !== '')
			{
				$('#diaporama_carousel_thumbs').append(html);
			}

			// Suppression des anciennes vignettes lors
			// de la navigation dans le carrousel.
			if (nav)
			{
				var nav_css = (nav == 'prev')
					? {'margin-left': 0}
					: {'margin-left': '-' + margin_left + 'px'};

				$('#diaporama_carousel_thumbs').animate(
					nav_css,
					this.carouselNavDuration,
					'swing',
					function()
					{
						$('#diaporama_carousel_thumbs dl.old').remove();
						$('#diaporama_carousel_thumbs').css('margin-left', 0);

						This._getCarousel(This._carouselCurrentPosition);
						This._carouselAnimation = false;
					}
				);
			}

			// Gestion de l'événement "click" sur les vignettes du carrousel.
			$('#diaporama_carousel_thumbs dl').click(function()
			{
				var new_current_position = parseInt($(this).attr('id')
					.replace(/diaporama_carousel_image_(\d+)/, '$1'));
				var nb_images = This._nbImages;

				if (new_current_position == This._currentPosition)
				{
					return;
				}

				// On réinitialise le diaporama et on récupère les informations des images.
				This._reload(new_current_position);

				// Position de l'image courante dans le carrousel.
				This._changeCarouselCurrent();

				// Si la vignette cliquée est la dernière, inutile de changer le carrousel,
				// sauf si cette dernière vignette apparaît tronquée dans le carrousel.
				if (This._getCarouselStart() == nb_images)
				{
					return;
				}

				// On regénère le carrousel.
				if ($(this).find('a').attr('rel') >= Math.floor(This._carouselMaxThumbs))
				{
					This._carouselMaxPosition = 0;
					This._getCarousel();
				}
			});

			// Change les pages du carrousel.
			this._changeCarouselPages();
		}

		// Position de l'image courante dans le carrousel.
		if (!nav)
		{
			this._changeCarouselCurrent();
		}
	};

	/**
	 * Gère les pages du carrousel.
	 *
	 * @return void
	 */
	this._changeCarouselPages = function()
	{
		var This = this;

		// Détermine le nombre de pages ainsi que la page courante.
		var i = Math.floor(this._carouselMaxThumbs) - 1;
		this._carouselCurrentPage = Math.ceil(this._carouselCurrentPosition / i);
		this._carouselNbPages = Math.ceil((this._nbImages - 1) / i);

		// Bouton précédent.
		if (this._carouselCurrentPage > 1)
		{
			$('#diaporama_carousel_prev a')
				.addClass('active')
				.unbind()
				.click(function()
				{
					if (This._carouselAnimation)
					{
						return;
					}

					var id = $('#diaporama_carousel_thumbs dl:first').attr('id');
					if (!id)
					{
						return;
					}

					var p = parseInt(id.replace(/diaporama_carousel_image_/, '')) - 1;

					This._carouselMaxPosition = 0;
					This._carouselCurrentPosition = p;
					This._changeCarouselImages('prev');
				});
		}
		else
		{
			$('#diaporama_carousel_prev a').removeAttr('href').unbind().removeClass();
		}

		// Bouton suivant.
		if (this._carouselCurrentPage < this._carouselNbPages)
		{
			$('#diaporama_carousel_next a')
				.addClass('active')
				.unbind()
				.click(function()
				{
					if (This._carouselAnimation)
					{
						return;
					}

					var i = Math.floor(This._carouselMaxThumbs) - 1;
					var id = $('#diaporama_carousel_thumbs dl:eq(' + i + ')').attr('id');
					if (!id)
					{
						return;
					}

					var p = parseInt(id.replace(/diaporama_carousel_image_/, ''));

					This._carouselMaxPosition = 0;
					This._carouselCurrentPosition = p;
					This._changeCarouselImages('next');
				});
		}
		else
		{
			$('#diaporama_carousel_next a').removeAttr('href').unbind().removeClass();
		}
	};

	/**
	 * Change les dimensions et la position du caroussel.
	 *
	 * @param integer width
	 * @return void
	 */
	this._changeCarouselSizePosition = function(width)
	{
		var This = this;

		var s = this._getDiaporamaSize();
		s.availableWidth += (width) ? width : 0;

		var next_css = {
			'left': s.availableWidth - $('#diaporama_carousel_prev').outerWidth() + 'px'
		};

		var change = function()
		{
			// Détermine le nombre maximale de vignettes que peut contenir le carrousel.
			This._carouselMaxThumbs = (s.availableWidth
				- ($('#diaporama_carousel_prev').outerWidth() * 2)
				- This.carouselThumbsMargin)
				/ (This.carouselThumbsSize + (This.carouselThumbsBorder * 2)
				+ This.carouselThumbsMargin);

			// On regénère les images.
			This._carouselMaxPosition = 0;
			This._changeCarouselImages();
		};

		// Hauteur du carrousel.
		var height_add = (this.carouselThumbsMargin * 2) + (this.carouselThumbsBorder * 2)
			+ this.carouselBorderTop;
		$('#diaporama_carousel').css({'height': (this.carouselThumbsSize + height_add) + 'px'});

		// On crée une animation seulement lorsqu'une sidebar est affichée ou cachée.
		if (width && this.animate)
		{
			$('#diaporama_carousel_next').animate(
				next_css, this.sidebarShowDuration, function(){change();});
		}
		else
		{
			$('#diaporama_carousel_next').css(next_css);
			change();
		}
	};

	/**
	 * Retourne les dimensions des éléments du diaporama.
	 *
	 * @return object
	 */
	this._getDiaporamaSize = function()
	{
		var s = {
			'barBottomHeight': 0, 'barTopHeight': 0,
			'carouselHeight': 0, 'sidebarWidth': 0,
			'imageBorderHeight': 0, 'imageBorderWidth': 0
		};

		// Largeur de la sidebar.
		if (this.sidebarImageResize && $('.diaporama_sidebar').is(':visible'))
		{
			s.sidebarWidth = $('.diaporama_sidebar').outerWidth();
		}

		// Hauteur du carrousel.
		if (this.carousel && !this.hideControlBars)
		{
			s.carouselHeight = $('#diaporama_carousel').outerHeight();
		}

		// Hauteur des barres du haut et du bas.
		if (!this.hideControlBars)
		{
			s.barTopHeight = $('#diaporama_top').outerHeight();
			s.barBottomHeight = $('#diaporama_bottom').outerHeight();
		}

		// Bordure de l'image.
		s.imageBorderHeight
			= parseInt($('#diaporama_image_' + this._currentPosition).css('borderTopWidth'))
			+ parseInt($('#diaporama_image_' + this._currentPosition).css('borderBottomWidth'));
		s.imageBorderWidth
			= parseInt($('#diaporama_image_' + this._currentPosition).css('borderLeftWidth'))
			+ parseInt($('#diaporama_image_' + this._currentPosition).css('borderRightWidth'));
		if (isNaN(s.imageBorderHeight))
		{
			s.imageBorderHeight = 0;
		}
		if (isNaN(s.imageBorderWidth))
		{
			s.imageBorderWidth = 0;
		}

		// Dimensions de la zone d'affichage.
		s.availableHeight = $(window).height()
			- s.barTopHeight - s.barBottomHeight - s.carouselHeight - s.imageBorderHeight;
		s.availableWidth = $(window).width() - s.sidebarWidth - s.imageBorderWidth;

		return s;
	};

	/**
	 * Récupération des informations utiles depuis diaporama.php.
	 *
	 * @param boolean first
	 *	Utilise-t-on _getImages() pour la première fois ?
	 * @param boolean only_image_infos
	 *	Ne changer que les informations de l'image courante ?
	 * @return void
	 */
	this._getImages = function(first, only_image_infos)
	{
		var This = this;

		// Icône de chargement.
		if (first)
		{
			$('#diaporama_loading').css('visibility', 'visible');
		}

		$.post(
			gallery_path + '/ajax.php?q=' + this._q,
			{
				section: 'diaporama',
				first: first ? 1 : 0,
				preload: this.preload
			},
			function(r)
			{
				if (typeof r != 'object' || r === null)
				{
					return;
				}
				switch (r.status)
				{
					// Erreur.
					case 'error' :
						This._alertError(r.msg);
						break;

					// Aucun résultat.
					case 'no_result' :
						break;

					// Succès.
					default :

						var reload = false;

						// On ne change que les informations
						// sur l'image courante ?
						if (only_image_infos)
						{
							This._images = r.images;
							This._changeImageInfos();
							break;
						}

						// Si la section ne contient plus aucune image,
						// on vide le diaporama de toutes les informations
						// et on remet les icônes par défaut.
						if (r.nb_images == 0)
						{
							$('#diaporama_top_left,'
							+ '#diaporama_image_position,'
							+ '.diaporama_sidebar_inner').empty();

							$('#diaporama_icon_switch').removeAttr('href');
							$('#diaporama_icon_switch img')
								.attr('src', $('#diaporama_icon_switch img').attr('src')
									.replace(/(no)?resize(-grey)?\.png$/, 'resize-grey.png'))
								.css({ cursor: 'default' });
							$('#diaporama_icon_fav img')
								.attr('src', $('#diaporama_icon_fav img').attr('src')
									.replace(/fav(-active)?.png$/, 'fav.png'));
							$('#diaporama_icon_basket img')
								.attr('src', $('#diaporama_icon_basket img').attr('src')
									.replace(/basket(-active)?.png$/, 'basket.png'));

							// Carrousel.
							This._carouselImages = undefined;
							$('#diaporama_carousel_thumbs').empty();
							$('#diaporama_carousel_prev a,#diaporama_carousel_next a')
								.removeAttr('href').unbind().removeClass();
						}

						// Pour les informations des images enregistrées
						// dans This._images, on vérifie que les nouvelles
						// informations récupérées sont identiques.
						// Si ce n'est pas le cas, c'est que l'image
						// ou la section a été mise à jour. Dans ce cas,
						// on supprime toutes les images et on indique de
						// recréer l'image actuelle pour l'actualiser.
						for (p in r.images)
						{
							if (This._images[p] === undefined
							 || This._images[p].md5 == r.images[p].md5)
							{
								continue;
							}

							if (This._currentPosition > r.nb_images)
							{
								This._currentPosition = r.nb_images;
							}

							This._deleteImages(true);
							reload = true;
							break;
						}

						This._anticsrf = r.anticsrf;
						This._images = r.images;
						This._nbImages = r.nb_images;

						if (first && r.max_user_connections == 0 && This.createImageTempTime != 0)
						{
							This.createImageTempTime = 50;
						}

						// Création des images.
						var i = 1;
						var temp_autostart = 1;
						for (p in This._images)
						{
							var visible = (p == This._currentPosition);

							if ((!first && !reload) && visible)
							{
								continue;
							}

							// On ne crée pas toutes les images en même temps
							// pour éviter les limitations du paramètre
							// de base de données 'max_user_connections'.
							if (first || reload)
							{
								// On crée d'abord l'image correspondant
								// à la position courante, ceci afin d'éviter
								// de faire subir à l'utilisateur
								// le temps de création des images précédentes.
								if (visible)
								{
									This._changeImageInfos();
									This._createImage(p);
									This._changeImageSizePosition(p, false, visible);
									temp_autostart = i;
								}
								else
								{
									eval('var create_' + p + ' = function(){'
										+ 'This._createImage(' + p + ');'
										+ 'This._changeImageSizePosition(' + p
										+ ', false, ' + visible + ');};'
										+ 'setTimeout(create_' + p + ', '
										+ This.createImageTempTime + ' * i);');
									i++;
								}
							}
							else
							{
								This._createImage(p);
								This._changeImageSizePosition(p, false, visible);
							}
						}

						// On active les boutons de navigation.
						if (first || reload)
						{
							setTimeout(function()
								{
									This._changeNavigationButtons();
								},
								This.createImageTempTime * temp_autostart
							);
						}
						else
						{
							This._changeNavigationButtons();
						}

						// On indique de regénérer le carrousel.
						if (reload)
						{
							$('#diaporama_carousel_thumbs').empty();
						}

						This._deleteImages();

						// Démarrage de la lecture automatique au lancement ?
						if (first && This.autoStart)
						{
							setTimeout(function()
								{
									$('#diaporama_icon_start').trigger('click')
								},
								This.createImageTempTime * temp_autostart
							);
						}

						This._changeImageInfos();

						// Changement des images du carrousel.
						This._carouselMaxPosition = 0;
						This._getCarousel(This._currentPosition);
						break;
				}
			},
			'json'
		);
	};

	/**
	 * Récupère les préférences utilisateur.
	 *
	 * @return void
	 */
	this._getPrefs = function()
	{
		if (typeof diaporama_prefs != 'object')
		{
			return;
		}

		for (name in diaporama_prefs)
		{
			this[name] = diaporama_prefs[name];
		}
	};

	/**
	 * Ajoute ou supprime le gestionnaire d'événement 'mousedown'
	 * sur l'image courante pour déplacer l'image.
	 *
	 * @return void
	 */
	this._imageMouseDown = function()
	{
		var This = this;
		var img = $('#diaporama_image_' + this._currentPosition);

		img.unbind('mousedown');

		if ($('#diaporama_icon_switch img').attr('src').match(/noresize\.png$/))
		{
			img.css({ cursor: 'move' }).mousedown(function(event)
			{
				return This._dragImage(this, event);
			});
		}
		else
		{
			img.css({ cursor: 'default' });
		}
	};

	/**
	 * Une sidebar est-elle affichée ?
	 *
	 * @return boolean
	 */
	this._isSidebarVisible = function()
	{
		var visible = false;

		$('.diaporama_sidebar').each(function()
		{
			if ($(this).is(':visible'))
			{
				visible = true;
				return;
			}
		});

		return visible;
	};

	/**
	 * Attribue les touches du clavier aux fonctions correspondantes.
	 *
	 * @return void
	 */
	this._keyboard = function()
	{
		if (!this.keyboardNav)
		{
			return;
		}

		var This = this;

		// On désactive le contrôle au clavier lorsque
		// le focus est sur un élément de formulaire.
		$('#diaporama select, #diaporama input, #diaporama textarea')
			.focus(function()
			{
				This._keyboardActive = false;
			})
			.blur(function()
			{
				This._keyboardActive = true;
			});

		$(document).keyup(function(event)
		{
			if (!This._keyboardActive)
			{
				return;
			}

			switch (event.keyCode)
			{
				// Echap : quitte le diaporama.
				case 27 :
					$('#diaporama_icon_close').trigger('click');
					break;

				// Barre d'espacement : démarre ou arrête la lecture automatique.
				case 32 :
					if ($('#diaporama_icon_stop img').attr('src').match(/stop-active\.png$/))
					{
						$('#diaporama_icon_start').trigger('click');
					}
					else
					{
						$('#diaporama_icon_stop').trigger('click');
					}
					break;

				// Flèche gauche : image précédente.
				case 37 :
					$('#diaporama_icon_prev').trigger('click');
					break;

				// Flèche droite : image suivante.
				case 39 :
					$('#diaporama_icon_next').trigger('click');
					break;
			}
		});

		$(document).keydown(function(event)
		{
			if (!This._keyboardActive)
			{
				return;
			}

			switch (event.keyCode)
			{
				// Flèche haut : augmente la durée d'affichage de la lecture automatique.
				case 38 :
					This._autoDurationMouseDown = true;
					This._autoChangeDuration(This.autoDurationPrecision);
					This._autoDurationMouseDown = false;
					break;

				// Flèche bas : diminue la durée d'affichage de la lecture automatique.
				case 40 :
					This._autoDurationMouseDown = true;
					This._autoChangeDuration(-This.autoDurationPrecision);
					This._autoDurationMouseDown = false;
					break;
			}
		});
	};

	/**
	 * Gestion des options.
	 *
	 * @return void
	 */
	this._options = function()
	{
		var This = this;

		// Chargement des préférences de l'utilisateur.
		this._getPrefs();

		// Option "Afficher le carrousel".
		$('#diaporama_carousel_option').click(function()
		{
			This.carousel = !This.carousel;
			if (This.carousel)
			{
				This._getCarousel();
				$('#diaporama_carousel').show();
				This._changeCarouselSizePosition();
			}
			else
			{
				$('#diaporama_carousel').hide();
			}
			This._changeImageSizePosition(This._currentPosition);
			This._prevDiaporamaSize = This._getDiaporamaSize();
			This._savePrefs();
		});
		if (this.carousel)
		{
			$('#diaporama_carousel_option').attr('checked', 'checked');
			$('#diaporama_carousel').show();
		}
		else
		{
			$('#diaporama_carousel_option').removeAttr('checked');
			$('#diaporama_carousel').hide();
		}
		this.carouselThumbsSize = (this.carouselThumbsSize < 50) ? 50 : this.carouselThumbsSize;

		// Option "Effet de transition".
		$('#diaporama_transitions_effect').change(function()
		{
			This.transitionEffect = $('#diaporama_transitions_effect option:selected').val();
			This._savePrefs();
		});
		$('#diaporama_transitions_effect option[value=' + this.transitionEffect + ']')
			.attr('selected', 'selected');

		// Option "Durée de transition".
		$('#diaporama_transitions_duration').keyup(function()
		{
			This.transitionDuration = $('#diaporama_transitions_duration').val();
			This._savePrefs();
		});
		$('#diaporama_transitions_duration').val(this.transitionDuration);

		// Option "Lecture en boucle de la lecture automatique".
		$('#diaporama_autoloop').click(function()
		{
			This.autoLoop = !This.autoLoop;
			if (This._currentPosition == This._nbImages && !This.autoLoop)
			{
				$('#diaporama_icon_stop').trigger('click');
			}
			This._savePrefs();
		});
		if (this.autoLoop)
		{
			$('#diaporama_autoloop').attr('checked', 'checked');
		}
		else
		{
			$('#diaporama_autoloop').removeAttr('checked');
		}

		// Option "Cacher les barres de contrôle".
		$('#diaporama_hidebars').click(function()
		{
			This.hideControlBars = !This.hideControlBars;
			This._changeImageSizePosition(This._currentPosition);
			This._savePrefs();
		});
		if (this.hideControlBars)
		{
			$('#diaporama_hidebars').attr('checked', 'checked');
		}
		else
		{
			$('#diaporama_hidebars').removeAttr('checked');
		}

		// Option "Activer les animations ?".
		$('#diaporama_animate').click(function()
		{
			This.animate = !This.animate;
			This._savePrefs();
		});
		if (this.animate)
		{
			$('#diaporama_animate').attr('checked', 'checked');
		}
		else
		{
			$('#diaporama_animate').removeAttr('checked');
		}
	};

	/**
	 * Recharge tout le diaporama depuis la position p.
	 *
	 * @param integer p
	 * @return void
	 */
	this._reload = function(p)
	{
		this._q = this._q.replace(/position\/\d+$/, 'position/' + p);
		this._currentPosition = p;
		this._images = {};
		this._nbImages = 0;
		this._realsize = false;
		this._changeNavigationButtons();
		this._deleteImages(true);
		this._getImages(true, false);
	};

	/**
	 * Redimensionne le diaporama et l'image courante lors
	 * du redimensionnement de la fenêtre du navigateur.
	 *
	 * @return void
	 */
	this._resize = function()
	{
		// Dimensions du diaporama.
		this._changeDiaporamaSize();

		// Dimensions de l'image courante.
		if (this._images[this._currentPosition] !== undefined)
		{
			this._changeImageSizePosition(this._currentPosition);
		}

		this._prevDiaporamaSize = this._getDiaporamaSize();
	};

	/**
	 * Sauvegarde les préférences utilisateur.
	 *
	 * @return void
	 */
	this._savePrefs = function()
	{
		var value = this.animate + ',' + this.autoDuration + ',' + this.autoLoop + ',' +
					this.carousel + ',' + this.hideControlBars + ',' +
					this.transitionDuration + ',' + this.transitionEffect;
		$.post(
			gallery_path + '/ajax.php',
			{ section: 'prefs', cookie_param: 'diaporama', cookie_value: value }
		);
	};

	/**
	 * Gestionnaires d'événements pour les sidebars.
	 *
	 * @return void
	 */
	this._sidebarsEvents = function()
	{
		var This = this;
		var sidebar_icons = '#diaporama_top_right .diaporama_icon_sidebar';
		$(sidebar_icons).each(function()
		{
			var icon = '#' + $(this).attr('id');
			var sidebar = icon.replace(/_icon/, '');

			// Affichage de la sidebar.
			$(icon).click(function()
			{
				// Verrous.
				if (This._sidebarMoveLock || This._imageTransitionLock)
				{
					return;
				}
				This._sidebarMoveLock = true;
				
				var diaporama_sidebar_width = (This.sidebarImageResize)
					? $('.diaporama_sidebar').outerWidth() : 0;

				// On affiche la sidebar.
				if ($(sidebar).is(':hidden'))
				{
					var sidebar_visible = false;
					var show_sidebar = function()
					{
						var css = This._changeImageSizePosition(This._currentPosition,
							true, true, true, -diaporama_sidebar_width);
						var after_show = function()
						{
							This._prevDiaporamaSize = This._getDiaporamaSize();
							This._changeSwitchButton();
							This._sidebarMoveLock = false;
							$(sidebar).find('p:visible .diaporama_focus:not(:disabled)').focus();
						};
						This._changeCarouselSizePosition(-diaporama_sidebar_width);
						if (This.sidebarShowDuration && This.animate)
						{
							$('#diaporama_image_' + This._currentPosition).animate(
								css, This.sidebarShowDuration
							);
							$(sidebar).show('slide', { direction: 'right' },
								This.sidebarShowDuration, after_show);
						}
						else
						{
							$('#diaporama_image_' + This._currentPosition).css(css);
							$(sidebar).show();
							after_show();
						}
						$(icon + ' img').attr('src', $(icon + ' img')
							.attr('src').replace(/\.png$/, '-active.png'));
					}

					// Si une sidebar est déjà affichée,
					// on la cache avant d'afficher la sidebar courante.
					$(sidebar_icons).each(function()
					{
						var sidebar = '#' + $(this).attr('id').replace(/_icon/, '');
						if ($(sidebar).is(':visible'))
						{
							$(this).find('img').attr('src', $(this).find('img').attr('src')
								.replace(/-active\.png$/, '.png'));
							if (This.sidebarShowDuration && This.animate)
							{
								$(sidebar).hide('slide', { direction: 'right' },
									This.sidebarShowDuration, show_sidebar);
							}
							else
							{
								$(sidebar).hide();
								show_sidebar();
							}
							sidebar_visible = true;
						}
					});

					if (!sidebar_visible)
					{
						show_sidebar();
					}
				}

				// On cache la sidebar.
				else
				{
					var css = This._changeImageSizePosition(This._currentPosition,
						true, true, true, diaporama_sidebar_width);
					var after_hide = function()
					{
						This._prevDiaporamaSize = This._getDiaporamaSize();
						This._changeSwitchButton();
						This._sidebarMoveLock = false;
					};
					This._changeCarouselSizePosition(diaporama_sidebar_width);
					if (This.sidebarShowDuration && This.animate)
					{
						$('#diaporama_image_' + This._currentPosition).animate(
							css, This.sidebarShowDuration
						);
						$(sidebar).hide('slide', { direction: 'right' },
							This.sidebarShowDuration, after_hide);
					}
					else
					{
						$('#diaporama_image_' + This._currentPosition).css(css);
						$(sidebar).hide();
						after_hide();
					}
					$(icon + ' img').attr('src', $(icon + ' img')
						.attr('src').replace(/-active\.png$/, '.png'));
					$(icon).focus();
				}
			});
		});

		// Bouton de fermeture des sidebars.
		$('.diaporama_sidebar_close').click(function()
		{
			var icon = '#' + $(this).parents('div').attr('id')
				.replace(/diaporama/, 'diaporama_icon');
			$(icon).trigger('click');
		});

		// Informations.
		$('.diaporama_sidebar_title2 span').click(function()
		{
			var content = $(this).parents('li').find('.diaporama_sidebar_content');
			if (content.is(':hidden'))
			{
				content.slideDown('fast');
			}
			else
			{
				content.slideUp('fast');
			}
		});
	};

	/**
	 * Effectue la transition entre l'image courante et l'image demandée.
	 *
	 * @param integer old_position
	 * @param integer new_position
	 * @param string button
	 * @return void
	 */
	this._transition = function(old_position, new_position, button)
	{
		var This = this;
		var s = this._getDiaporamaSize();
		var img_old_offset = $('#diaporama_image_' + old_position).offset();
		var duration = parseInt(this.transitionDuration);
		var transition = this.transitionEffect;

		this._imageTransitionLock = true;

		// Effet aléatoire.
		if (this.transitionEffect == 'random')
		{
			var nb = $('#diaporama_transitions_effect option').length - 2;
			var rand = Math.floor(Math.random() * nb) + 1;
			transition = document.getElementById('diaporama_transitions_effect')
				.getElementsByTagName('option')[rand].value;
		}

		switch (transition)
		{
			case 'fade' :
				$('#diaporama_image_' + old_position).fadeOut(duration);
				$('#diaporama_image_' + new_position).fadeIn(duration);
				break;

			case 'slideX' :
			case 'slideY' :

				// Image actuelle.
				var old_css_animate = {};
				var old_css = { display : 'none' };
				if (transition == 'slideX')
				{
					old_css_animate.left = (button == 'next' || button == 'last')
						? (img_old_offset.left - s.availableWidth) + 'px'
						: (img_old_offset.left + s.availableWidth) + 'px';
					old_css.left = img_old_offset.left + 'px';
				}
				else
				{
					old_css_animate.top = (button == 'next' || button == 'last')
						? (img_old_offset.top + s.availableHeight) + 'px'
						: (img_old_offset.top - s.availableHeight) + 'px';
					old_css.top = img_old_offset.top + 'px';
				}
				$('#diaporama_image_' + old_position).animate(
					old_css_animate,
					duration,
					'swing',
					function()
					{
						$('#diaporama_image_' + old_position).css(old_css);
					}
				);

				// Nouvelle image.
				var new_css = { display : 'block' };
				var new_css_animate = {};
				if (transition == 'slideX')
				{
					var img_new_left = $('#diaporama_image_' + new_position).css('left');
					new_css_animate.left = img_new_left;
					new_css.left = (button == 'next' || button == 'last')
						? (parseFloat(img_new_left) + s.availableWidth) + 'px'
						: (parseFloat(img_new_left) - s.availableWidth) + 'px';
				}
				else
				{
					var img_new_top = $('#diaporama_image_' + new_position).css('top');
					new_css_animate.top = img_new_top;
					new_css.top = (button == 'next' || button == 'last')
						? (parseFloat(img_new_top) - s.availableHeight) + 'px'
						: (parseFloat(img_new_top) + s.availableHeight) + 'px';
				}
				$('#diaporama_image_' + new_position)
					.css(new_css)
					.animate(new_css_animate, duration, 'swing');
				break;

			case 'slideXLeft' :
			case 'slideYBottom' :

				// Image actuelle.
				var old_css_animate = {};
				var old_css = { display : 'none' };
				if (transition == 'slideXLeft')
				{
					old_css_animate.left = (img_old_offset.left - s.availableWidth) + 'px';
					old_css.left = img_old_offset.left + 'px';
				}
				else
				{
					old_css_animate.top = (img_old_offset.top + s.availableHeight) + 'px';
					old_css.top = img_old_offset.top + 'px';
				}
				$('#diaporama_image_' + old_position).animate(
					old_css_animate,
					duration / 2,
					'swing',
					function()
					{
						$('#diaporama_image_' + old_position).css(old_css);

						// Nouvelle image.
						var new_css = { display : 'block' };
						var new_css_animate = {};
						if (transition == 'slideXLeft')
						{
							var img_new_left = $('#diaporama_image_' + new_position).css('left');
							new_css_animate.left = img_new_left;
							new_css.left = -(parseFloat(img_new_left) + s.availableWidth) + 'px';
						}
						else
						{
							var img_new_top = $('#diaporama_image_' + new_position).css('top');
							new_css_animate.top = img_new_top;
							new_css.top = -(parseFloat(img_new_top) - s.availableHeight) + 'px';
						}
						$('#diaporama_image_' + new_position)
							.css(new_css)
							.animate(new_css_animate, duration / 2, 'swing');
					}
				);

				break;

			case 'zoom' :
			case 'curtainX' :
			case 'curtainY' :

				// Image actuelle.
				var old_css_animate = {};
				var old_css = { display : 'none' };
				if (transition != 'curtainX')
				{
					old_css_animate.height = 0;
					old_css_animate.top = s.availableHeight / 2;
					old_css.top = img_old_offset.top + 'px';
				}
				if (transition != 'curtainY')
				{
					old_css_animate.width = 0;
					old_css_animate.left = s.availableWidth / 2;
					old_css.left = img_old_offset.left + 'px';
				}
				$('#diaporama_image_' + old_position).animate(
					old_css_animate,
					duration / 2,
					'swing',
					function()
					{
						$('#diaporama_image_' + old_position).css(old_css);

						// Nouvelle image.
						var new_css = { display : 'block' };
						var new_css_animate = {};
						if (transition != 'curtainX')
						{
							new_css_animate.height =
								$('#diaporama_image_' + new_position).height();
							new_css_animate.top =
								$('#diaporama_image_' + new_position).css('top');
							new_css.top = s.availableHeight / 2;
							new_css.height = 0;
						}
						if (transition != 'curtainY')
						{
							new_css_animate.width =
								$('#diaporama_image_' + new_position).width();
							new_css_animate.left
								= $('#diaporama_image_' + new_position).css('left');
							new_css.left = s.availableWidth / 2;
							new_css.width = 0;
						}
						$('#diaporama_image_' + new_position)
							.css(new_css)
							.animate(new_css_animate, duration / 2, 'swing');
					}
				);
				break;

			case 'puff' :

				// Image actuelle.
				var old_css_animate = {};
				var old_css = { display : 'none', opacity : 1 };
				old_css_animate.height = $('#diaporama_image_' + old_position).height() * 1.5;
				old_css_animate.width = $('#diaporama_image_' + old_position).width() * 1.5;
				old_css_animate.left = (s.availableWidth - old_css_animate.width) / 2;
				old_css_animate.top = ((s.availableHeight - old_css_animate.height) / 2)
					+ s.barTopHeight;
				old_css_animate.opacity = 0;
				$('#diaporama_image_' + old_position).animate(
					old_css_animate,
					duration / 2,
					'swing',
					function()
					{
						$('#diaporama_image_' + old_position).css(old_css);

						// Nouvelle image.
						var new_css = { display : 'block' };
						new_css.height = $('#diaporama_image_' + new_position).height() * 1.5;
						new_css.width = $('#diaporama_image_' + new_position).width() * 1.5;
						new_css.left = (s.availableWidth - new_css.width) / 2;
						new_css.top = ((s.availableHeight - new_css.height) / 2)
							+ s.barTopHeight;
						new_css.opacity = 0;

						var new_css_animate = This._changeImageSizePosition(new_position,
							true, true, true);
						new_css_animate.opacity = 1;

						$('#diaporama_image_' + new_position)
							.css(new_css)
							.animate(new_css_animate, duration / 2, 'swing');
					}
				);
				break;

			// Aucun effet.
			default :
				$('#diaporama_image_' + old_position).hide();
				$('#diaporama_image_' + new_position).show();
				duration = 0;
				break;
		}

		setTimeout(
			function()
			{
				This._changeImageInfos();
				This._imageTransitionLock = false;
			},
			duration
		);
	};
};
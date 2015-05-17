<?php if ($tpl->disDeconnect()) : ?>
<?php include(dirname(__FILE__) . '/deconnect.tpl.php'); ?>

<?php endif; ?>

<?php if ($tpl->disThumbs() && ($tpl->disAdminLink() || $tpl->disGallery('diaporama') || $tpl->disGallery('download_zip_albums') || $tpl->disGallery('basket') || $tpl->disAuthUser() || $tpl->disTagsEdit() || $tpl->disEdit() || $tpl->disDeconnect())) : ?>
		<div id="obj_tools">
			<p class="obj_tool_menu_icon" id="obj_tools_link"><a href="javascript:;"><?php echo __('Outils'); ?></a></p>
			<div class="obj_tool_box" id="obj_tool_menu">
				<p class="obj_tool_title"><span><?php echo __('Outils'); ?></span></p>
				<ul id="obj_tool_menu_body" class="obj_tool_body">
<?php if ($tpl->disAdminLink()) : ?>
					<li id="tool_admin"><span class="icon icon_admin"><a class="normal_link" href="<?php echo $tpl->getAdminLink(); ?>"><?php echo __('Administrer'); ?></a></span></li>
<?php endif; ?>
<?php if ($tpl->disUpload()) : ?>
					<li id="tool_upload"><span class="icon icon_add_images"><a class="normal_link" href="<?php echo $tpl->getLink('upload/' . (int) $_GET['object_id']); ?>"><?php echo __('Ajouter des images'); ?></a></span></li>
<?php endif; ?>
<?php if ($tpl->disGallery('diaporama')) : ?>
					<li id="tool_diaporama"><span class="icon icon_diaporama"><a rel="<?php echo $tpl->getDiaporama('query'); ?>" class="js_link" href="javascript:;"><?php echo __('Lancer un diaporama'); ?></a></span></li>
<?php endif; ?>
<?php if ($tpl->disGallery('download_zip_albums')) : ?>
					<li id="tool_download"><span class="icon icon_download"><a class="js_link" rel="<?php echo $tpl->getGallery('gallery_path'); ?>/download.php?alb=<?php echo $_GET['object_id']; ?>"><?php echo __('Télécharger l\'album'); ?></a></span></li>
<?php endif; ?>
<?php if ($_GET['section'] == 'basket') : ?>
					<li id="tool_download"><span class="icon icon_download"><a class="js_link" rel="<?php echo $tpl->getGallery('gallery_path'); ?>/download.php?basket"><?php echo __('Télécharger le panier'); ?></a></span></li>
					<li id="tool_basket_empty"><span class="icon icon_basket_empty"><a class="js_link" ><?php echo __('Vider le panier'); ?></a></span></li>
<?php endif; ?>
<?php if ($tpl->disAuthUser() || $tpl->disGallery('basket')) : ?>
					<li id="tool_thumbs_tools"><span class="icon icon_thumbs_<?php if ($tpl->getCookiePref('thumb_icons') == 1) : ?>less<?php else : ?>more<?php endif; ?>"><a class="js_link" href="<?php echo $tpl->getAdminLink(); ?>"><?php echo __('Outils de vignettes'); ?></a></span></li>
<?php endif; ?>
<?php if ($tpl->disAuthUser() || $tpl->disGallery('basket') || $tpl->disTagsEdit()) : ?>
					<li id="tool_select_all"><span class="icon icon_select_all"><a class="js_link" href="javascript:;"><?php echo __('Tout sélectionner'); ?></a></span></li>
					<li class="obj_tool_box_link" id="tool_select"><span class="icon icon_select"><a class="js_link" href="javascript:;"><?php echo __('Action sur la sélection'); ?></a></span></li>
<?php endif; ?>
<?php if ($tpl->disEdit()) : ?>
					<li class="obj_tool_box_link" id="tool_edit"><span class="icon icon_edit"><a class="js_link" href="javascript:;"><?php echo __('Éditer'); ?></a></span></li>
<?php endif; ?>
<?php if ($tpl->disDeconnect()) : ?>
					<li id="deconnect_object_link"><span class="icon icon_deconnect"><a class="js_link" href="javascript:;"><?php echo __('Déconnexion'); ?></a></span></li>
<?php endif; ?>
				</ul>
			</div>
<?php if ($tpl->disEdit()) : ?>
<?php include(dirname(__FILE__) . '/edit.tpl.php'); ?>

<?php endif; ?>
<?php if ($tpl->disGallery('basket') || $tpl->disAuthUser() || $tpl->disTagsEdit()) : ?>
<?php include(dirname(__FILE__) . '/select.tpl.php'); ?>

<?php endif; ?>
		</div>
<?php endif; ?>

		<div id="position">
			<?php echo $tpl->getPosition(); ?>

<?php if ($tpl->disPositionAlbumsList()) : ?>
			<div class="albums_list">
				<?php echo __('Parcourir :'); ?>

				<select name="browse" onchange="window.location.href='<?php echo $tpl->getGallery('gallery_base_url'); ?>'+this.options[this.selectedIndex].value">
					<?php echo $tpl->getPositionAlbumsList(); ?>

				</select>
			</div>
<?php endif; ?>

<?php if ($tpl->disSearchResult('categories')) : ?>
			<span class="search_result"><?php echo $tpl->getSearchResult('nb_categories'); ?> :</span>
			<ul class="search_result_categories">
<?php while ($tpl->nextSearchResultCategory()) : ?>
				<li><?php echo $tpl->getSearchResultCategory('parents'); ?><a href="<?php echo $tpl->getSearchResultCategory('link'); ?>"><?php echo $tpl->getSearchResultCategory('title'); ?></a></li>
<?php endwhile; ?>
			</ul>
<?php endif; ?>

<?php if ($tpl->disSearchResult('albums')) : ?>
			<span class="search_result"><?php echo $tpl->getSearchResult('nb_albums'); ?> :</span>
			<ul class="search_result_categories">
<?php while ($tpl->nextSearchResultAlbum()) : ?>
				<li><?php echo $tpl->getSearchResultAlbum('parents'); ?><a href="<?php echo $tpl->getSearchResultAlbum('link'); ?>"><?php echo $tpl->getSearchResultAlbum('title'); ?></a></li>
<?php endwhile; ?>
			</ul>
<?php endif; ?>

<?php if ($tpl->disSearchResult('images')) : ?>
			<span class="search_result"><?php echo $tpl->getSearchResult('nb_images'); ?> :</span>
<?php endif; ?>

		</div>

<?php if ($tpl->disCategory('desc')) : ?>
		<div id="cat_description"><p><?php echo $tpl->getCategory('desc'); ?></p></div>
<?php endif; ?>

<?php if ($tpl->disNavigation('top')) : ?>
		<div class="nav" id="nav_top">
			<div class="nav_left"></div>
			<div class="nav_right"><?php printf(__('page %s|%s'), $_GET['page'], $tpl->getCategory('nb_pages')); ?></div>

<?php include(dirname(__FILE__) . '/nav.tpl.php'); ?>
			
		</div>
<?php endif; ?>

		<div class="thumbs thumbs_alb l<?php echo $tpl->getThumbLinesNumber(); ?>">

<?php while ($tpl->nextThumb()) : ?>
			<dl<?php if ($tpl->disThumb('recent')) : ?> class="new"<?php endif; ?>>
				<dt style="width:<?php echo $tpl->getThumb('width'); ?>px;">
					<a class="thumb_link" style="width:<?php echo $tpl->getThumb('width'); ?>px;height:<?php echo $tpl->getThumb('height'); ?>px;" title="<?php echo strip_tags($tpl->getThumb('desc')) ? strip_tags($tpl->getThumb('desc')) : $tpl->getThumb('title'); ?>" href="<?php echo $tpl->getThumb('link'); ?>">
<?php if ($tpl->disThumb('recent')) : ?>
						<span title="<?php echo $tpl->getThumb('recent_title'); ?>" class="new_message"><span><?php echo __('nouveau'); ?></span></span>
<?php endif; ?>
						<img <?php echo $tpl->getThumb('size'); ?> style="padding:<?php echo $tpl->getThumb('center'); ?>;" alt="<?php echo $tpl->getThumb('title'); ?>" src="<?php echo $tpl->getThumb('src'); ?>" />
					</a>
<?php if ($tpl->disAuthUser() || $tpl->disGallery('basket')) : ?>
					<span<?php if ($tpl->getCookiePref('thumb_icons') != 1) : ?> style="display:none"<?php endif; ?> class="thumb_icons">
<?php if ($tpl->disAuthUser()) : ?>
						<a rel="<?php echo $tpl->getThumb('id'); ?>" href="javascript:;" class="thumb_icon_fav" title="<?php echo ($tpl->disThumb('in_favorites')) ? __('Retirer des favoris') : __('Ajouter aux favoris'); ?>"><img src="<?php echo $tpl->getGallery('style_path'); ?>/icons/fav-thumb<?php if ($tpl->disThumb('in_favorites')) : ?>-active<?php endif; ?>.png" alt="" width="26" height="26" /></a>
<?php endif; ?>
<?php if ($tpl->disGallery('basket')) : ?>
						<a rel="<?php echo $tpl->getThumb('id'); ?>" href="javascript:;" class="thumb_icon_basket" title="<?php echo ($tpl->disThumb('in_basket')) ? __('Retirer du panier') : __('Ajouter au panier'); ?>"><img src="<?php echo $tpl->getGallery('style_path'); ?>/icons/basket-thumb<?php if ($tpl->disThumb('in_basket')) : ?>-active<?php endif; ?>.png" alt="" width="26" height="26" /></a>
<?php endif; ?>
						<a rel="<?php echo $tpl->getThumb('id'); ?>" href="javascript:;" class="thumb_icon_select" title="<?php echo __('Ajouter à la sélection'); ?>"><img src="<?php echo $tpl->getGallery('style_path'); ?>/icons/select-thumb.png" alt="" width="26" height="26" /></a>
					</span>
<?php endif; ?>
				</dt>
<?php if ($tpl->disThumb('infos')) : ?>
				<dd>
					<ul style="width:<?php echo $tpl->getThumb('width'); ?>px;">
<?php if ($tpl->disThumb('title')) : ?>
						<li title="<?php echo $tpl->getThumb('title'); ?>" class="title">
							<a href="<?php echo $tpl->getThumb('link'); ?>"><?php echo $tpl->getThumb('title'); ?></a>
						</li>
<?php endif; ?>
<?php if ($tpl->disThumb('filesize')) : ?>
						<li title="<?php echo $tpl->getThumb('filesize'); ?>" class="filesize"><?php echo $tpl->getThumb('filesize'); ?></li>
<?php endif; ?>
<?php if ($tpl->disThumb('image_size')) : ?>
						<li title="<?php echo $tpl->getThumb('image_size'); ?>" class="size"><?php echo $tpl->getThumb('image_size'); ?></li>
<?php endif; ?>
<?php if ($tpl->disThumb('added_date')) : ?>
						<li title="<?php echo $tpl->getThumb('added_date'); ?>" class="added_date<?php if ($tpl->disThumb('added_date_special')) : ?> special<?php endif; ?>"><?php echo $tpl->getThumb('added_date'); ?></li>
<?php endif; ?>
<?php if ($tpl->disThumb('hits')) : ?>
						<li title="<?php echo $tpl->getThumb('hits'); ?>" class="hits<?php if ($tpl->disThumb('hits_special')) : ?> special<?php endif; ?>"><?php echo $tpl->getThumb('hits'); ?></li>
<?php endif; ?>
<?php if ($tpl->disThumb('comments')) : ?>
						<li title="<?php echo $tpl->getThumb('comments'); ?>" class="comments<?php if ($tpl->disThumb('comments_special')) : ?> special<?php endif; ?>"><?php echo $tpl->getThumb('comments'); ?></li>
<?php endif; ?>
<?php if ($tpl->disThumb('rate')) : ?>
						<li title="<?php echo $tpl->getThumb('rate'); ?>" class="rate<?php if ($tpl->disThumb('rate_special')) : ?> special<?php endif; ?>"><?php echo $tpl->getThumb('rate_visual'); ?></li>
						<li title="<?php echo $tpl->getThumb('votes'); ?>" class="votes<?php if ($tpl->disThumb('rate_special')) : ?> special<?php endif; ?>"><?php echo $tpl->getThumb('votes'); ?></li>
<?php endif; ?>
					</ul>
				</dd>
<?php endif; ?>
			</dl>
<?php endwhile; ?>

			<hr class="sep" />
		</div>

<?php if ($tpl->disNavigation('bottom')) : ?>
		<div class="nav" id="nav_bottom">
			<div class="nav_left"></div>
			<div class="nav_right"><?php printf(__('page %s|%s'), $_GET['page'], $tpl->getCategory('nb_pages')); ?></div>

<?php include(dirname(__FILE__) . '/nav.tpl.php'); ?>
			
		</div>
<?php endif; ?>

		<script type="text/javascript">
		//<![CDATA[
		var cat_id = <?php echo $_GET['object_id']; ?>;
		var page = <?php echo $_GET['page']; ?>;
		var anticsrf = "<?php echo $tpl->getGallery('anticsrf'); ?>";
		var user_lang = "<?php echo $tpl->getGallery('lang_current_code'); ?>";
		var q = "<?php echo $tpl->getGallery('q'); ?>";
		var q_md5 = "<?php echo $tpl->getGallery('q_md5'); ?>";
		var no_right_click = <?php echo $tpl->disGallery('images_anti_copy') ? 'true' : 'false'; ?>;
<?php if ($tpl->disEdit()) : ?>
		var thumbs_cat_extended = <?php echo (int) $tpl->disGallery('thumbs_cat_extended'); ?>;
<?php endif; ?>
<?php if ($tpl->disGallery('basket')) : ?>
		var msg_basket_add = "<?php echo $tpl->getL10nJS(__('Ajouter au panier')); ?>";
		var msg_basket_del = "<?php echo $tpl->getL10nJS(__('Retirer du panier')); ?>";
		var confirm_basket_empty = "<?php echo $tpl->getL10nJS(__('Étes-vous sûr de vouloir supprimer toutes les images de votre panier ?')); ?>";
<?php endif; ?>
<?php if ($tpl->disAuthUser()) : ?>
		var msg_fav_add = "<?php echo $tpl->getL10nJS(__('Ajouter aux favoris')); ?>";
		var msg_fav_del = "<?php echo $tpl->getL10nJS(__('Retirer des favoris')); ?>";
<?php endif; ?>
		var msg_select_add = "<?php echo $tpl->getL10nJS(__('Ajouter à la sélection')); ?>";
		var msg_select_del = "<?php echo $tpl->getL10nJS(__('Retirer de la sélection')); ?>";
		var msg_select_all_add = "<?php echo $tpl->getL10nJS(__('Tout sélectionner')); ?>";
		var msg_select_all_del = "<?php echo $tpl->getL10nJS(__('Tout désélectionner')); ?>";
		var msg_select_nb_image = "<?php echo $tpl->getL10nJS(__('%s image sélectionnée')); ?>";
		var msg_select_nb_images = "<?php echo $tpl->getL10nJS(__('%s images sélectionnées')); ?>";
		//]]>
		</script>

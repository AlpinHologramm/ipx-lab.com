<!DOCTYPE html>
<html lang="fr">

<head>

<title><?php echo $tpl->getAdmin('title'); ?> - iGalerie</title>

<meta charset="utf-8" />

<script type="text/javascript" src="<?php echo $tpl->getAdmin('gallery_path'); ?>/js/jquery/jquery.js"></script>
<script type="text/javascript" src="<?php echo $tpl->getAdmin('gallery_path'); ?>/js/jquery/ui/core.js"></script>
<script type="text/javascript" src="<?php echo $tpl->getAdmin('gallery_path'); ?>/js/jquery/ui/draggable.js"></script>
<script type="text/javascript" src="<?php echo $tpl->getAdmin('gallery_path'); ?>/js/jquery/ui/sortable.js"></script>
<script type="text/javascript" src="<?php echo $tpl->getAdmin('gallery_path'); ?>/js/jquery/textarearesizer.js"></script>
<?php
if ($_GET['section'] == 'thumb-image' || $_GET['section'] == 'thumb-album' || $_GET['section'] == 'thumb-category' || $_GET['section'] == 'image'
|| ($_GET['section'] == 'new-thumb' && method_exists($tpl, 'getConf'))) :
?>
<script type="text/javascript" src="<?php echo $tpl->getAdmin('gallery_path'); ?>/js/jquery/jcrop.js"></script>
<script type="text/javascript" src="<?php echo $tpl->getAdmin('gallery_path'); ?>/js/jquery/rotate.js"></script>
<?php endif; ?>
<?php if (strstr($_GET['section'], 'watermark')) : ?>
<script type="text/javascript" src="<?php echo $tpl->getAdmin('gallery_path'); ?>/js/jquery/farbtastic/farbtastic.js"></script>
<?php endif; ?>
<?php if ($_GET['section'] == 'stats-users') : ?>
<script type="text/javascript" src="<?php echo $tpl->getAdmin('gallery_path'); ?>/js/jquery/tablesorter.js"></script>
<?php endif; ?>
<?php if (substr($_GET['section'], 0, 6) == 'geoloc' || ($_GET['section'] == 'page' && $_GET['page'] == 'worldmap')) : ?>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<?php endif; ?>
<script type="text/javascript" src="<?php echo $tpl->getAdmin('template_path'); ?>/js/admin.js"></script>
<script type="text/javascript">
//<![CDATA[
var gallery_path = "<?php echo $tpl->getAdmin('gallery_path'); ?>";
var style_path = "<?php echo $tpl->getAdmin('style_path'); ?>";
var help_html_title = "<?php echo __('Aide: Balises HTML'); ?>";
var help_html_content = "<?php echo $tpl->getAdmin('help_html_content'); ?>";
//]]>
</script>

<link rel="stylesheet" type="text/css" media="screen" title="style" href="<?php echo $tpl->getAdmin('style_file'); ?>" />

<style type="text/css">
<?php while ($tpl->nextLang()) : ?>
.icon_<?php echo $tpl->getLang('code'); ?> {
	background-image: url(<?php echo $tpl->getAdmin('gallery_path'); ?>/images/flags/<?php echo $tpl->getLang('code'); ?>.png);
}
<?php endwhile; ?>
<?php if (!$tpl->getAdmin('html_filter')) : ?>
.f_html {
	visibility: hidden;
}
<?php endif; ?>
</style>

<?php $tpl->inc('style_header'); ?>

</head>


<body id="section_<?php echo str_replace('-', '_', $_GET['section']); ?>">

<div id="top"></div>

<div id="global">

	<div id="menu">
		<h1><span>iGalerie</span></h1>
<?php if ($tpl->disPerm('ftp')) : ?>
		<p>FTP</p>
		<ul>
			<li><a href="<?php echo $tpl->getLink('ftp'); ?>"><?php echo __('Ajout d\'images'); ?></a></li>
		</ul>
<?php endif; ?>
<?php if ($tpl->disPerm('objects')) : ?>
		<p><?php echo __('Objets'); ?></p>
		<ul>
<?php if ($tpl->disPerm('albums')) : ?>
			<li><a href="<?php echo (!$tpl->disPerm('albums_edit') && !$tpl->disPerm('albums_modif')) ? $tpl->getLink('images-pending') : $tpl->getLink('category/1'); ?>"><?php echo __('Albums'); ?></a></li>
<?php endif; ?>
<?php if ($tpl->disPerm('comments')) : ?>
			<li><a href="<?php echo (!$tpl->disPerm('comments_edit')) ? $tpl->getLink('comments-options') : $tpl->getLink('comments-images'); ?>"><?php echo __('Commentaires'); ?></a></li>
<?php endif; ?>
<?php if ($tpl->disPerm('admin_votes')) : ?>
			<li><a href="<?php echo $tpl->getLink('votes'); ?>"><?php echo __('Votes'); ?></a></li>
<?php endif; ?>
<?php if ($tpl->disPerm('tags')) : ?>
			<li><a href="<?php echo $tpl->getLink('tags'); ?>"><?php echo __('Tags'); ?></a></li>
			<li><a href="<?php echo $tpl->getLink('camera-models'); ?>"><?php echo __('Appareils photos'); ?></a></li>
<?php endif; ?>
<?php if ($tpl->disPerm('users')) : ?>
			<li><a href="<?php echo (!$tpl->disPerm('users_members')) ? $tpl->getLink('users-options') : $tpl->getLink('users'); ?>"><?php echo __('Utilisateurs'); ?></a></li>
<?php endif; ?>
		</ul>
<?php endif; ?>
<?php if ($tpl->disPerm('settings')) : ?>
		<p><?php echo __('Réglages'); ?></p>
		<ul>
<?php if ($tpl->disPerm('settings_pages')) : ?>
			<li><a href="<?php echo $tpl->getLink('pages'); ?>"><?php echo __('Pages'); ?></a></li>
<?php endif; ?>
<?php if ($tpl->disPerm('settings_widgets')) : ?>
			<li><a href="<?php echo $tpl->getLink('widgets'); ?>"><?php echo __('Widgets'); ?></a></li>
<?php endif; ?>
<?php if ($tpl->disPerm('settings_functions')) : ?>
			<li><a href="<?php echo $tpl->getLink('functions'); ?>"><?php echo __('Fonctionnalités'); ?></a></li>
<?php endif; ?>
<?php if ($tpl->disPerm('settings_options')) : ?>
			<li><a href="<?php echo $tpl->getLink('options-gallery'); ?>"><?php echo __('Options'); ?></a></li>
<?php endif; ?>
<?php if ($tpl->disPerm('settings_themes')) : ?>
			<li><a href="<?php echo $tpl->getLink('themes'); ?>"><?php echo __('Thèmes'); ?></a></li>
<?php endif; ?>
<?php if ($tpl->disPerm('settings_maintenance')) : ?>
			<li><a href="<?php echo $tpl->getLink('maintenance'); ?>"><?php echo __('Maintenance'); ?></a></li>
<?php endif; ?>
		</ul>
<?php endif; ?>
<?php if ($tpl->disAdmin('superadmin') || $tpl->disPerm('infos_incidents')) : ?>
		<p><?php echo __('Informations'); ?></p>
		<ul>
<?php if ($tpl->disAdmin('superadmin')) : ?>
			<li><a href="<?php echo $tpl->getLink('stats-objects'); ?>"><?php echo __('Galerie'); ?></a></li>
			<li><a href="<?php echo $tpl->getLink('logs'); ?>"><?php echo __('Activité'); ?></a></li>
			<li><a href="<?php echo $tpl->getLink('system'); ?>"><?php echo __('Système'); ?></a></li>
<?php endif; ?>
<?php if ($tpl->disPerm('infos_incidents')) : ?>
			<li><a href="<?php echo $tpl->getLink('incidents'); ?>"><?php echo __('Incidents'); ?></a></li>
<?php endif; ?>
		</ul>
<?php endif; ?>
	</div>

	<div id="header">
		<a href="<?php echo $tpl->getLink('dashboard'); ?>"><img width="24" height="24" id="dashboard_link" title="<?php echo __('Tableau de bord'); ?>" alt="<?php echo __('Tableau de bord'); ?>" src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/24x24/home.png" /></a>
		<div id="links">
			<a style="display:none;" id="deconnect_link" href="javascript:;"><?php echo __('déconnexion'); ?></a>
			<form id="deconnect_form" action="" method="post">
				<p>
					<input name="anticsrf" type="hidden" value="<?php echo $tpl->getAdmin('anticsrf'); ?>" />
					<input name="deconnect" type="submit" value="<?php echo __('déconnexion'); ?>" />
				</p>
			</form>
			<script type="text/javascript">
			document.getElementById('deconnect_form').style.display = 'none';
			document.getElementById('deconnect_link').style.display = 'inline';
			</script>
			<span> | </span>
			<a href="<?php echo $tpl->getAdmin('gallery_path'); ?>/"><?php echo __('voir la galerie'); ?></a>
		</div>
		<div id="connexion">
			<?php printf(__('Connecté : %s'), '<a href="' . $tpl->getLink('user/' . $tpl->getAuthUser('id')) . '">' . $tpl->getAuthUser('login') . '</a>'); ?>

		</div>
	</div>
<?php if ($tpl->disAdmin('langs_edition')) : ?>
	<ul id="uhlinks">
		<li id="uhlink_langs">
			<img title="<?php echo __('Langues d\'édition'); ?>"
				alt="<?php echo $tpl->getAdmin('lang_default_code'); ?>" width="16" height="16"
				src="<?php echo $tpl->getAdmin('style_path'); ?>/icons/16x16/lang.png" />
		</li>
	</ul>
	<div id="lang_edition_list">
		<p><?php echo __('Langues'); ?></p>
		<ul>
<?php while ($tpl->nextLang()) : ?>
			<li>
				<input<?php if ($tpl->disLang('selected')) : ?> checked="checked" class="selected_lang"<?php endif; ?> type="checkbox" />
				<a class="icon icon_<?php echo $tpl->getLang('code'); ?>" rel="<?php echo $tpl->getLang('code'); ?>"><?php echo $tpl->getLang('name'); ?></a>
			</li>
<?php endwhile; ?>
		</ul>
	</div>
<?php endif; ?>

	<div id="content">

<?php $tpl->inc('page'); ?>
<?php $tpl->displayErrors(); ?>

<?php if ($tpl->disHelp()) : ?><?php include_once(dirname(__FILE__) . '/help_context.tpl.php'); ?><?php endif; ?>
	</div>

	<div class="clear">&nbsp;</div>

	<div id="footer">
		<p>
			<?php echo $tpl->getAdmin('powered_by'); ?>

<?php if ($tpl->disAdmin('exec_time')) : ?>
			-
			<?php echo $tpl->getAdmin('exec_time'); ?>
<?php endif; ?>

		</p>
	</div>

</div>

<?php echo $tpl->getDebugSQL(); ?>

</body>


</html>

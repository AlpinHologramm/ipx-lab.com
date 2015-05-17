<?php 
// Fichier exemple pour l'intégration d'iGalerie à un site,
// qu'il faudra renommer en 'index.php'.
// La ligne suivante correspond à l'inclusion du fichier 'index.php'
// d'iGalerie, renommé ici en 'index.inc'.

require_once(dirname(__FILE__) . '/index.inc');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr" dir="ltr">

<head>

<title><?php echo $tpl->getGallery('page_title'); ?></title>

<!-- <iGalerie:header> -->
<?php require_once(dirname(__FILE__) . '/template/'
	. $tpl->getGallery('template_name') . '/head.tpl.php'); ?>

<!-- </iGalerie:header> -->

<style type="text/css">
body {
	text-align: center;
}
#gallery {
	text-align: left;
	width: 750px;
	margin: 10px auto;
	border: 4px silver double;
}
</style>

</head>

<body>

<div id="gallery">

<!-- <iGalerie:content> -->
<?php require_once(dirname(__FILE__) . '/template/'
	. $tpl->getGallery('template_name') . '/index.tpl.php'); ?>

<!-- </iGalerie:content> -->

</div>

</body>

</html>

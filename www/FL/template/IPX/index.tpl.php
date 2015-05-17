




<?php if (!CONF_INTEGRATED) :

 /*
 
 * Internet eXchange Point (IPX)
 * www.ipx-lab.com  / [CC -BY -4.0]
 * 
 
 */

?>


<!-------------------------------------------- Déclaration du Document -------------------------> 



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo substr($tpl->getGallery('lang_current_code'), 0, 2); ?>" lang="<?php echo substr($tpl->getGallery('lang_current_code'), 0, 2); ?>" dir="ltr">





<!-----------------------------------------------------------------------------------------------> 
<!-------------------------------------------- HEAD ---------------------------------------------> 



<head>

<!-------------------------------------------- Titre de la page ---------------------------------------------> 

<title><?php if ($tpl->disGallery('page_title')) : ?><?php echo $tpl->getGallery('page_title'); ?> - <?php endif; ?><?php echo $tpl->getGallery('gallery_title'); ?></title>



<?php include_once(dirname(__FILE__) . '/head.tpl.php'); ?>

</head>











<!-------------------------------------------- Fin - Head  ---------------------------------------------> 







<!-------------------------------------------- BODY ---------------------------------------------> 


<body id="section_<?php echo str_replace('-', '_', $_GET['section']); ?>">
<?php endif; ?>

<div id="igalerie">
<div id="igalerie_gallery">


<!-------------------------------------------- Header ---------------------------------------------> 


	<div id="ig2_header">
		<h1><a accesskey="1" href="<?php echo $tpl->getGallery('gallery_path'); ?>/"><?php echo $tpl->getGallery('gallery_title_banner'); ?></a></h1>
	</div>


<!-------------------------------------------- Page ---------------------------------------------> 

<div id="ig2_content">

<?php $tpl->inc('page'); ?>

</div>
    
    
<!-------------------------------------------- Footer ---------------------------------------------> 

<div id="ig2_footer">
		  
		<p>
			<?php $tpl->inc('footer'); ?>
		  Test
      Footer</p>
          


</div>
    
    
    
    
<!-------------------------------------------- Erreur ---------------------------------------------> 
    
    
    
<?php $tpl->displayErrors(); ?>

</div>


<!-------------------------------------------- Debug ---------------------------------------------> 

</div>

<?php echo $tpl->getDebugSQL(); ?>

<?php if (!CONF_INTEGRATED) : ?>
</body>


</html>
<?php endif; ?>
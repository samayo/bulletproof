<?php

	require_once('seoWrapperClass.php');
	$_seoWrapper = new SeoWrapper();


	$StaticPage = $_seoWrapper->returnPageContent()->forUrl($_SERVER['REQUEST_URI'], parse_url($_SERVER['SCRIPT_NAME']));


	if($StaticPage !== false){

        $title = $_seoWrapper->fetchAllFromStaticPages()[1][$StaticPage];
        $content = $_seoWrapper->fetchAllFromStaticPages()[2][0];
        $keywords = $_seoWrapper->fetchAllFromStaticPages()[3][0];

	}else{

		$conn = new PDO('mysql:host=localhost; dbname=seoWrapper', 'root', '');
		$fetch = $_seoWrapper->getDynamicContents($conn, 'pages', "id");


        ($_seoWrapper->checkErrors()) ? die('Page not found') : '';
        // ^^ if page is not found or query failed, do your monkey business here.

        $title = $fetch['title'];
        $content = $fetch['content'];
        $keywords = $fetch['keywords'];





	}
?>
	
<!DOCTYPE html>
<html lang="en-US"> 
	<head> 
		<meta charset="utf-8" /> 
		<meta name="robots" content="index, follow" />
		<meta name="description" content="<?php echo $content; ?> " />
		<meta name="keywords" content="<?php echo $keywords; ?>" />
		<meta name="REVISIT-AFTER" content="15 DAYS" />
	<title><?php echo $title; ?>  </title>
</head> 




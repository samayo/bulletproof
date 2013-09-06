<?php

       $conn = new PDO('mysql:host=localhost; dbname=seoWrapper', 'root', ''); //don't mind me, i'm just an example
	require_once('seoWrapperClass.php');


	$SeoWrapper = new SeoWrapper();
	$currentPage = $SeoWrapper->isPageStaticOrDynamic($_SERVER['REQUEST_URI']);

    if($currentPage === 'dynamic'){
        $fetch = $SeoWrapper->getDynamicContents($conn, 'pages', "id"); //'pages' = table name. "id" = $_GET['*']
        ($SeoWrapper->checkErrors()) ? 'Your 404 Error Here' : list($title,$content,$keywords) = $fetch;

    }else{
        $title = $SeoWrapper->fetchAllFromStaticPages()['title'][$currentPage];
        $content = $SeoWrapper->fetchAllFromStaticPages()['keywords']['0'];
        $keywords = $SeoWrapper->fetchAllFromStaticPages()['content']['0'];
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




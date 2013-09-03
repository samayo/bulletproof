<?php

	require_once('seoWrapperClass.php');
	$_seoWrapper = new SeoWrapper();

	$StaticPage = $_seoWrapper->checkIfPageIsAllowed();

	if($StaticPage !== false){
		$fetch = $_seoWrapper->getPageProperties();
		$seo['title'] = $fetch[0];
		$seo['keywords'] = $fetch[1]['keywords'];
		$seo['content'] = $fetch[2]['content'];

	}else{

		$conn = new PDO('mysql:host=localhost; dbname=Test_SimpleSeo', 'root', '');
		$fetch = $_seoWrapper->getDynamicContents($conn, 'pages', "id");

                $checkError =  $_seoWrapper->checkErrors();
		$seo['title'] = $fetch['title'];
		$seo['content'] = $fetch['content'];
		$seo['keywords'] = $fetch['keyword'];
	}
?>
	
<!DOCTYPE html>
<html lang="en-US"> 
	<head> 
		<meta charset="utf-8" /> 
		<meta name="robots" content="index, follow" />
		<meta name="description" content="<?php echo $seo['content']; ?> " />
		<meta name="keywords" content="<?php echo $seo['keywords']; ?>" />
		<meta name="REVISIT-AFTER" content="15 DAYS" />
	<title><?php echo $seo['title'] ?>  </title>
</head> 




<?php

	require_once('seoWrapperClass.php');
	$_seoWrapper = new SeoWrapper();


	$StaticPage = $_seoWrapper->returnPageContent($_SERVER['REQUEST_URI'], parse_url($_SERVER['SCRIPT_NAME']));
    var_dump($StaticPage);

	if($StaticPage !== false){
		$title = $_seoWrapper->fetchAllFromStaticPages()[1][$StaticPage];
		$keyword = $_seoWrapper->fetchAllFromStaticPages()[2];
		$content = $_seoWrapper->fetchAllFromStaticPages()[3];

	var_dump($title);
	var_dump($keyword);
	var_dump($content);



	}else{

		$conn = new PDO('mysql:host=localhost; dbname=Test_SimpleSeo', 'root', '');
		$fetch = $_seoWrapper->getDynamicContents($conn, 'pages', "id");

        $errorChecking = $_seoWrapper->checkErrors();
        var_dump($errorChecking);
        ($errorChecking) ? die('Page not found') : '';
        // ^^ if page is not found or query failed, do your monkey business here.

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




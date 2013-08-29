<?php 
	require_once('seoWrapper.php');
	$_seoWrapper = new seoWrapper();

	/*
	*   Check if Current page is static / dynamic (dynamic == fetch data from db, and display title, cont, desc... from this data)
	*   if not, then treat it as dynamic page
	*/

	$StaticPage = $_seoWrapper->Static_Pages();
	
	if($StaticPage === null){ 
		//if current page is not found in the list of StaticPages, treat it as dynamic.
		
		$conn = new PDO('mysql:host=localhost; dbname=Test_SimpleSeo', 'root', '');
		$fetch = $_seoWrapper->Get_Dynamic_Contents($conn, 'pages', "id");
		$error = $_seoWrapper->checkErrors(); $error;
		
		$seo['title'] = $fetch['title'];
		$seo['content'] = $fetch['content'];
		$seo['keywords'] = $fetch['keyword'];

	}else{

		$G = $_seoWrapper->Get_Static_Contents();
		$seo['title'] = $G[0]; // title for current page 
		$seo['keywords'] = $G[1]['keywords']; //keywords
		$seo['content'] = $G[2]['content'];  //content
		
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

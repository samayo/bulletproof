		
	 
	$Obj_seoWrapper = new seoWrapper($conn);

	/*
	*   Check if Current page is static / dynamic (dynamic == fetch data from db, and display title, cont, desc... from this data)
	*   if not, then treat it as dynamic page
	*/

	$StaticPage = $Obj_seoWrapper->Static_Pages();

	if($StaticPage === null){ 
		// null means, that the current page is not found in the list of StaticPage, so treat it as dynamic.
		$M = $Obj_seoWrapper->Get_Dynamic_Contents('pages', "id");
		$error = $Obj_seoWrapper->checkErrors(); $error;
		
		$M['title'] = $fetch['title'];
		$M['content'] = $fetch['content'];
		$M['keywords'] = $fetch['keyword'];

	}else{

		$G = $Obj_seoWrapper->Get_Static_Contents();
		$M['title'] = $G[0]; // title for current page 
		$M['keywords'] = $G[1]['keywords']; //keywords
		$M['content'] = $G[2]['content'];  //content
		
	}
	?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US"> 
<head> 
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
	<meta name="robots" content="index, follow" />
	<meta name="description" content="<?php echo $M['content']; ?> " />
	<meta name="keywords" content="<?php echo $M['keywords']; ?>" />
	<meta name="REVISIT-AFTER" content="15 DAYS" />
	<link rel='stylesheet' href='<?php echo URI ?>/styles/style.css' type='text/css' media='screen' />
	<meta name="viewport" content="width=device-width initial-scale=1.0, user-scalable=yes" />
	<title><?php echo $M['title'] ?>  </title>
</head> 


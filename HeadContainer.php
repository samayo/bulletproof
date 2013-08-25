<?php 

    $Obj_tinySeo = new simpleSeoClass($conn);

    /*
     *   Check if Current page is static / dynamic (dynamic == fetch data from db, and display title, cont, desc... from this data)
     *   if not, then treat it as dynamic page
     */
    
	$StaticPage = $Obj_tinySeo->Static_Pages();
	
    if($StaticPage === null){ 
	// null means, that the current page is not found in the list of StaticPage, so treat it as dynamic.
        
       $Page_Id = (isset($_GET['id']) && !empty($_GET['id'])) ? htmlspecialchars((int)$_GET['id']) : 0 ;
        // check/get the id= value from the current page
		
       $fetch = $Obj_tinySeo->Get_Dynamic_Contents('clients', $Page_Id);
        // Make query to database. 
		
        $error = $Obj_tinySeo->checkErrors();
		// Check for errors, if query was not succesful 
		
        
		$error; // incase of errors. This will trigger a 404 page, else nothing happens.
		
		
		// $G is an array that holds returned rows from db. It is possible to shorten a multiple data with something like $M = $G[1].$G[2] 
		$M['title'] = $fetch['company'];
		$M['content'] = $fetch['tel'].' '.$fetch['email'].' '.$fetch['company'];
		$M['keywords'] = $fetch['company'].' '.$fetch['about'].' '.$fetch['address'].' '.$fetch['email'].' '.$fetch['tel'];
    
    }else{
	
     
        $G = $Obj_tinySeo->Get_Static_Contents();
		 // if page is static, just display the costumized declared data
		 
		   
		$M = []; 
		$M['title'] = $G[0]; // title for current page 
		$M['keywords'] = $G[1]['keywords']; //keywords
		$M['content'] = $G[2]['content'];  //content
	
    }
?>

<!DOCTYPE html>
<html lang="en-US"> 
<head> 
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
	<meta name="robots" content="index, follow" />
	<meta name="description" content="<?php echo $M['content']; ?> " />
	<meta name="keywords" content="<?php echo $M['keywords']; ?>" />
	<meta name="REVISIT-AFTER" content="15 DAYS" />
	<link rel='stylesheet' href='style.css' media='screen' />
	<meta name="viewport" content="width=device-width initial-scale=1.0, user-scalable=yes" />
	<title><?php echo $M['title'] ?>  </title>
</head> 

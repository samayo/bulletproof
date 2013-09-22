<?php

    $conn = new PDO('mysql:host=localhost; dbname=seowrapper', 'root', ''); //don't mind me, i'm just an example

    require_once('src/seoWrapper.php');


	$SeoWrapper = new SeoWrapper();


    if($SeoWrapper->currentPage($_SERVER['REQUEST_URI']) === 'dynamic'){
        $result = ($SeoWrapper->hasErrors()) ? die('page not found') :  $SeoWrapper->getContents($conn);
    }else{
		$result = $SeoWrapper->currentPage($_SERVER['REQUEST_URI']);
    }

	
?>





<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8" />
        <meta name="robots" content="index, follow" />
        <meta name="description" content="  <?php echo $result['title'] ; ?> " />
        <meta name="keywords" content="  <?php echo $result['keywords']; ?>  " />
        <meta name="REVISIT-AFTER" content="15 DAYS" />

        <title>  <?php echo $result['description']; ?>  </title>
    </head>

<!--
    Why limit yourself to fetching few keywords, you can fetch as many as you want and add it to
    your meta tags to make them richer, but you can also map the entire page down if you fetch everything from
    your database.


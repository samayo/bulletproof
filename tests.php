<?php

    $conn = new PDO('mysql:host=localhost; dbname=seowrapper', 'root', ''); //don't mind me, i'm just an example


    require_once('src/seoWrapper.php');
    require_once('src/StaticPages.php');

    $SeoWrapper = new SeoWrapper($myStaticPages, $myDefaultPageSettings);

    $currentPage = $SeoWrapper->isPageStaticOrDynamic($_SERVER['REQUEST_URI']);


    if($currentPage === 'dynamic'){
        $fetch = $SeoWrapper->getContents($conn, 'pages', "id", ['title', 'keywords', 'description']);
        ($SeoWrapper->hasErrors()) ? die('page not found') : list($title, $keywords, $description) = $fetch;
    }else{
        list($title, $keywords, $description) = $currentPage;
    }




?>





<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8" />
        <meta name="robots" content="index, follow" />
        <meta name="description" content="  <?php echo $description; ?> " />
        <meta name="keywords" content="  <?php echo $keywords; ?>  " />
        <meta name="REVISIT-AFTER" content="15 DAYS" />

        <title>  <?php echo $title; ?>  </title>
    </head>

/**
* Lets hit that second bird now.
* If you pulled more rows from your database, other than the specified ones, you could optionally use the variables to display more
* data here instead of your header tags, for ex:
*
*     echo $PageTitle <br/>
*     echo $PageContent ..
*
*/

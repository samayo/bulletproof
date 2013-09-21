<?php

    $conn = new PDO('mysql:host=localhost; dbname=seowrapper', 'root', ''); //don't mind me, i'm just an example


    require_once('src/seoWrapper.php');
    require_once('src/StaticPages.php');

    $SeoWrapper = new SeoWrapper($myStaticPages, $myDefaultPageSettings);
    
    if($SeoWrapper->currentPage($_SERVER['REQUEST_URI']) === 'dynamic'){
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

<!--
    Why limit yourself to fetching few keywords, you can fetch as many as you want and add it to
    your meta tags to make them richer, but you can also map the entire page down if you fetch everything from
    your database.


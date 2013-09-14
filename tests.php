<?php

    $conn = new PDO('mysql:host=localhost; dbname=seowrapper', 'root', ''); //don't mind me, i'm just an example


    /**
    *  require the main class and your personal config file
    */

    require_once('src/seoWrapper.php');
    require_once('src/MyConfig.php');


    /**
     * Injecting costume and default page declarations and properties
     */
    $SeoWrapper = new SeoWrapper($customPages, $defaultSettings);

    /**
     * We will take one server variable, and check the out settings for the current page
     */
    $currentPage = $SeoWrapper->isPageStaticOrDynamic($_SERVER['REQUEST_URI']);

    /**
     * If current page is static, we will take in configuration from the imported file
     * If page is dynamic we will check db, for table called  'pages' and page 'id'
     * matching the current page id. If data is not found, then we'll throw 404
     */

    if($currentPage === 'dynamic'){
        $fetch = $SeoWrapper->getDynamicContents($conn, 'pages', "id");
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
        <meta name="description" content="<?php echo $description; ?> " />
        <meta name="keywords" content="<?php echo $keywords; ?>" />
        <meta name="REVISIT-AFTER" content="15 DAYS" />

        <title><?php echo $title; ?>  </title>
    </head>


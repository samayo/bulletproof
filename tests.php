<?php

    $conn = new PDO('mysql:host=localhost; dbname=seowrapper', 'root', ''); //don't mind me, i'm just an example

    /**
     * require main class and a php file wich has our custome changes
     */
    require_once('src/seoWrapper.php');
    require_once('src/MyConfig.php');


        $SeoWrapper = new SeoWrapper($customPages, $defaultSettings);

    /**
     *  check if matching page is found in static page first, otherwise check dynamic
     */
    $currentPage = $SeoWrapper->isPageStaticOrDynamic($_SERVER['REQUEST_URI']);



    if($currentPage === 'dynamic'){
        $fetch = $SeoWrapper->getDynamicContents($conn, 'pages', "id")->bringMe('title', 'kewords', 'desc');

        /**
         * if out query throws an error, you can end the script or
         *  include a costume page to deal with 404 issues.
         */
        ($SeoWrapper->hasErrors()) ? die('Page is 404ed') : list($title, $keywords, $description) = $fetch;
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


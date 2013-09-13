<?php

    $conn = new PDO('mysql:host=localhost; dbname=seowrapper', 'root', ''); //don't mind me, i'm just an example

    require_once('seoWrapper.php');
    require_once('MyConfig.php');

    $SeoWrapper = new SeoWrapper($customPages, $defaultSettings);

	$currentPage = $SeoWrapper->isPageStaticOrDynamic($_SERVER['REQUEST_URI']);

    if($currentPage === 'dynamic'){
        $fetch = $SeoWrapper->getDynamicContents($conn, 'pages', "id");

        ($SeoWrapper->hasErrors()) ? die('Page is 404ed') : list($title, $keywords, $description) = $fetch;
    }else{
        list($title, $keywords, $description) = $currentPage;
    }




?>


    <meta charset="utf-8" />
    <meta name="robots" content="index, follow" />
    <meta name="description" content="<?php echo $description; ?> " />
    <meta name="keywords" content="<?php echo $keywords; ?>" />
    <meta name="REVISIT-AFTER" content="15 DAYS" />
    <title><?php echo $title; ?>  </title>


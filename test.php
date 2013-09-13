<?php

    $conn = new PDO('mysql:host=localhost; dbname=seoWrapper', 'root', ''); //don't mind me, i'm just an example

    require_once('seoWrapper.php');
    require_once('MyConfig.php');

    $SeoWrapper = new SeoWrapper($customPages, $defaultSettings);

	$currentPage = $SeoWrapper->isPageStaticOrDynamic($_SERVER['REQUEST_URI']);

    if($currentPage === 'dynamic'){
        $fetch = $SeoWrapper->getDynamicContents($conn, 'pages', "id"); 
        ($SeoWrapper->hasErrors()) ? 'Your 404 Error Here' : list($title, $content, $keywords) = $fetch;
    }else{
        list($title, $keywords, $description) = $currentPage;
    }




?>

<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8" />
    <meta name="robots" content="index, follow" />
    <meta name="description" content="<?php echo $content; ?> " />
    <meta name="keywords" content="<?php echo $keywords; ?>" />
    <meta name="REVISIT-AFTER" content="15 DAYS" />
    <title><?php echo $title; ?>  </title>
</head>

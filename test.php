<?php

   $conn = new PDO('mysql:host=localhost; dbname=seoWrapper', 'root', ''); //don't mind me, i'm just an example
	require_once('seoWrapperClass.php');



    $customPages = [

        'Pages'=>[
            '/seowrdapper/header.php'=>['costume title for static page called header',
                'optional keywords',
                'optional description'],

            '/seowrapper/header.php'=>['page title for about.php',
                'optional..',
                'optional..'],

            '/mywork.php'=>['page title for mywork.php',
                'optional',
                'optional..'],

            '/contact.php'=>['page title for contact.php',
                'otional keyword',
                'optional..']
        ]
     ];

    $defaultSettings = [
            'keywords'=>'describe, your, costume, static, page, keywords, here, separated, by, comas,',
            'description'=>'default website description for static pages goes here'
    ];


var_dump(array_keys($customPages['Pages'], '/seowrapper/header.php'));

    $SeoWrapper = new SeoWrapper($customPages, $defaultSettings);







	$currentPage = $SeoWrapper->isPageStaticOrDynamic($_SERVER['REQUEST_URI']);
var_dump($currentPage);

    if($currentPage === 'dynamic'){
        $fetch = $SeoWrapper->getDynamicContents($conn, 'pages', "id"); 
        ($SeoWrapper->hasErrors()) ? 'Your 404 Error Here' : list($title, $content, $keywords) = $fetch;
    }else{
        $title = $SeoWrapper->fetchAllFromStaticPages()['title'][$currentPage];
        $content = $SeoWrapper->fetchAllFromStaticPages()['keywords']['0'];
        $keywords = $SeoWrapper->fetchAllFromStaticPages()['content']['0'];
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




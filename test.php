<?php

$conn = new PDO('mysql:host=localhost; dbname=seoWrapper', 'root', ''); //don't mind me, i'm just an example
	require_once('seoWrapper.php');



    $customPages = [

        'Pages'=>[
            '/seowrapper/test.php'=>['costume title for static page called header',
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



    $SeoWrapper = new SeoWrapper($customPages, $defaultSettings);


   
	$currentPage = $SeoWrapper->isPageStaticOrDynamic($_SERVER['REQUEST_URI']);

var_dump($currentPage);

    if($currentPage === 'dynamic'){
        $fetch = $SeoWrapper->getDynamicContents($conn, 'pages', "id"); 
        ($SeoWrapper->hasErrors()) ? 'Your 404 Error Here' : list($title, $content, $keywords) = $fetch;
    }else{

    }


?>
	





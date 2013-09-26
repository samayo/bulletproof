<?php

/**
 * Class staticPages
 *
 * this is where all our static pages and a title for each page is stop
 *
 */

function definePages(){
    return [
        'staticPages'=>[

            '/seowrapper/demo.php'=> [
                 'title'=>'I am title for demo page',
                 'description'=>'this is demo pages description'],

            '/seowrapper/contact.php'=> [
                 'title'=>'I am title for contact page',
                 'decription'=>'I am optional description for test page'],
        ],


        'dynamicPages'=>[
                  'title'=>'I am title for dynamic page without any query',
                  'description'=>'I am obligatory description for test page'],
    ];
}





function myDefaultSettings(){
    return [
        'keywords'=>[
            'this, should, be, keywords, for, all, your, pages, seperated, by commas']
    ];
}



function databaseConfigs(){
     return [
        'tableName'=>'pages',
        'queryType'=>'id',
        'dataToFetch'=>['title','keywords','description']
    ];

}



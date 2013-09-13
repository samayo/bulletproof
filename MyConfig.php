<?php
/**
 * Fly you fools..
 *
 * this is where you declare all your static pages, and default configuration.
 *
 * Each static page should have a title and optional keyword and descriptions to be specific for that page.
 */


/**
 * Tell seoWrapper here how many static pages you have in your website, and titles for each on of them.
 */
$customPages = [

    'Pages'=>[
        '/seowrapper/test.php'=>  // declare the page here
            ['Hello you are now at test.php', // decalre a title for the above page
             'I am optional keyword for this specific page', // optional, keyword for this page goes here
             'Put your optional description here, otherwise the default array will be used'
            ],

        '/seowrapper/header.php'=>
            ['page title for about.php',
             'optional..',
             'optional..'
            ],

        '/mywork.php'=>
            ['page title for mywork.php',
             'optional',
             'optional..'
            ],

        '/contact.php'=>
            ['page title for contact.php',
             'otional keyword',
             'optional..'
            ]
    ]
];


/**
 * default settings such as keywords and description will be use when you have not specified anything in the previous array
 */

$defaultSettings = [
    'keywords'=>
        'describe, your, costume, static, page, keywords, here, separated, by, comas,',
    'description'=>
        'default website description for static pages goes here'
];

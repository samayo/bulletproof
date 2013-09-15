<?php

    //fly you fools


    /**
     *  this file only deals with static pages. If you have none, then you don't even need it.
     *
     *  If you have static pages in your website, like contact page or some pages that
     *  don't fetch contents from database, you can declare them here and asign titles for each,
     *
     *  Note: Keywords & Descriptions are optional, if you don't give your pages one of those, the
     *  the keywords and Descriptions in the defaultSettings array will be used.
     */

    $customPages = [

        'Pages'=>[
            '/tests.php'=> ['I am title for this page',
                             'I am optional keywords for test page',
                             'same here, optional description'
                            ],

            '/index.php'=> ['page title for about.php',
                             'optional',
                             'optional'
                             ],

            '/contact.php'=> ['page title for mywork.php',
                             'optional',
                             ],

        ]
    ];


    /**
     *  default settings go here, describe keywords and desc.. for all you *static* pages.
     */

    $defaultSettings = [
            'keywords'=>
                'describe, your, costume, static, page, keywords, here, separated, by, comas,',
            'description'=>
                'default website description for static pages goes here'
        ];

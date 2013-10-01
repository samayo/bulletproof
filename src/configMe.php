<?php

/**
 * This is the file, in which your entire configuration should take place
 */


/**
 * Name your static pages,
 * - title and description are obligatory for each new page you create.
 * - dynamic pages only need title, and description you don't have to name your dynamic pages
 *
 * @return array
 */
function definePages()
{
    return [
        'staticPages' => [

            '/demo.php' => [
                'title' => 'I am title for demo page',
                'description' => 'this is demo pages description'
            ],
            '/contact.php' => [
                'title' => 'I am title for contact page',
                'description' => 'I am optional description for test page'
            ],
        ],
        'dynamicPages' => [
            'title' => 'I am title for dynamic page without any query',
            'description' => 'I am obligatory description for test page'
        ],
    ];
}


/**
 * this is the keyword for all your static pages, configure it well.
 *
 * @return array
 */
function myDefaultSettings()
{
    return [
        'keywords' => [
            'this, should, be, keywords, for, all, your, pages, separated, by commas'
        ]
    ];
}


/**
 * this setting deals specifically with dynamic part of your page. It will help you query database for some rows
 * tableName = provide here, which table you want to pull data from, for your dynamic content
 * queryType = specify here, what your get parameter, is it $_GET['id'] or $_GET['article'] or $_GET['whatever']
 * by default, I am assuming your dynamic page uses $_GET['id']. So, the "id" is already mentioned
 * dataToFetch = for the purpose of this example, we are fetching only three rows from database and have integrated them inside
 * the header, but you can fetch 20 rows if you want and stuff them in the metadata, ... it's your choice now :)
 *
 * @return array
 */
function databaseConfigs()
{
    return [
        'tableName' => 'pages',
        'queryType' => 'id',
        'dataToFetch' => ['title', 'keywords', 'description']
    ];

}

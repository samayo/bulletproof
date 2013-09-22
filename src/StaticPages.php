<?php

/**
 * Class staticPages
 *
 * this is where all our static pages and a title for each page is stored. The description is option (see second page, it has none)
 * if you don't specify description for the pages, then the one from 'defaultPageSettings' or next trait will be used.
 *
 */
trait staticPages{
    public function myStaticPages(){
        return [
            'Pages'=>[
                    '/seowrapper/demo.php'=> [ 
							'title'=>'I am title for demo page', 
							'description'=>'I am optional description for demo page' ],
							
                    '/seowrapper/contact.php'=> [
							'title'=>'I am title for contact page',
                    ]
             ]
		];
    }

}


/**
 * Class defaultPageSettings
 *
 * By default, all your static pages will use the keyword array, the description however is optional. If user
 * omits/forgets to mention the description tag, then this one will be used.
 */

trait defaultPageSettings{
    public function myDefaultSettings(){
        return [
            'description'=>[
                'this is the default page description for all your static pages'],
            'keywords'=>[
                'this, should, be, keywords, for, all, your, pages, seperated, by commas']
        ];
    }
}


/**
 * Class databaseConfigs
 *
 * $TableName - Specify with table gets fetched for content, when your dynamic pages are fetching for a result.
 * $queryType - is 'id' or type of the query string
 * $dataToFetch - tells the what rows to fetch from $tableName
 */
trait databaseConfigs{
	public $tableName = 'pages';
	public $queryType = 'id';
	public $dataToFetch = ['title','keywords','description'];
}



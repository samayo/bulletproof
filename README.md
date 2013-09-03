SeoWrapper
===========================

Ever created a simple blog site, with few static/dynamic pages and wanted to build a function to output title, content, and keywords but felt lazy somehow? Well, here is SimpleSeo to the rescue. 


Extremely easy way, to explain what these files do is, to imagine: 

That you have a basic site, with static & one dynamic page. That means, you may have static pages like (`home.php, about.php, portofolio.php, contact.php`) pages. All are basic, or could be accessible through one page i.e. index.php?page=home, index.php?=page=about... BUT, you also have one dynamic page, let's call it `articles.php?id=...` 

OK! Now, if you include this class, then give it ONCE, a title, keywords, content for each of your static pages + provide table, row name for that daynamic page we talked, about. Then, you don't have to every worry about, what to put inside the </head></head> tags i.e. SEO-wise, speaking. 



Just, include the header file in your main page, and call in the class. 


## Installing  

Just `include()` the class file, at the start of your page, or use `spl_autoload_register('myAutoloader');`. Whatever works for you. 

## Configuring

You need to open the `MainClass.php` and add your details. Ex: You can start by putting your site name in

		public $SiteName = ' | Mysite.com';
	 
And the page, only you want your users to access. 

	
        $exsistingStaticPages = [
            '/index.php',
            '/about.php',
            '/password.php?task=forgot',
            '/password.php?task=sent'
        ];
		 


And title for the above page. 

	 $staticPageTitles = [
            'Welcome to my site, this is index page',
            'this is the about page',
            'So, you want to change your password ehh?',
            'Ok! your password has been sent'
        ];


And, keywords for all your static page, which are mentioned above. (NOTE! You can add more pages if you want)

	 $staticPageKeywords = [
            'keywords'=>'this, is, where, your, site, keywords, go, separated, by, commas,'
        ];

And, finally the contents of your each, which is going to be the same. i.e. `<meta name="description" content="" />`

	  $staticPageDescription = [
            'content'=>'This is where the (content) of your meta site goes'
        ];



And, that is it for the static page. You are good to go. You can also, have a dynamic page, just tell it your table name and which rows to fetch, and it will do so, and allocate those data to the relative parts inside your head doc.




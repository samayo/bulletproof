SimpleSeo
===========================

Ever created a simple blog site, with few static/dynamic pages and wanted to build a function to output title, content, and keywords but felt lazy somehow? Well, here is SimpleSeo to the rescue. 


Extremely easy way, to explain what these files do is, to imagine: 

That you have a basic site, with static & one dynamic page. That means, you may have static pages like (`home.php, about.php, portofolio.php, contact.php`) pages. All are basic, or could be accessible through one page i.e. index.php?page=home, index.php?=page=about... BUT, you also have one dynamic page, let's call it `articles.php?id=...` 

OK! Now, if you include this class, then give it ONCE, a title, keywords, content for each of your static pages + provide table, row name for that daynamic page we talked, about. Then, you don't have to every worry about, what to put inside the </head></head> tags i.e. SEO-wise, speaking. 



Just, include the header file in your main page, and call in the class. 


## Installing  

Just `include()` the class file, at the start of your page, or use `spl_autoload_register('myAutoloader');`. Whatever works for you. 

## Configuring

You need to open the `MainClass.php.php` and add your details. Ex: You can start by putting your site name in

		public $SiteName = ' | Mysite.com';
	 
And the page, only you want your users to access. 

		$Declared_Static_Pages = [
				'index.php', 
				'category.php', 
				'search.php', 
				'page.php?q=about', 
			];		 


And title for the above page. 

		$Static_Page_Titles = [
				'Put title for your index page here...',
				'Put title for your category page here page here...',
				'Put title for your search page here...',
				'Put title for your about page here...',
			];

And, keywords for all your static page, which are mentioned above. (NOTE! You can add more pages if you want)

		$Static_Page_Keywords = [
				'keywords'=>'this, is, where, your, keywords, go, seperated, by, comma'
			];

And, finally the contents of your each, which is going to be the same. i.e. `<meta name="description" content="" />`

		$Static_Page_Content = [
				'content'=>'This is where the decription of the page goes'
			];



And, that is it for the static page. You are good to go. You can also, have a dynamic page, just tell it your table name and which rows to fetch, and it will do so, and allocate those data to the relative parts inside your head doc.




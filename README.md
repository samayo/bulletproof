SeoWrapper
===========================

Ever created a simple blog site, with few static/dynamic pages and wanted to build a function to output title, content, and keywords but felt lazy somehow? Well, here is SimpleSeo to the rescue. 


Extremely easy way, to explain what these files do is, to imagine: 

That you have a basic site, with static & one dynamic page. That means, you may have static pages like
(`home.php, about.php, portofolio.php, contact.php`) pages. All are basic (i.e. NO dynamically changing content is in the page)

OK! Now, if you include this the `seoWrapperClass.php`  then give declare all static pages you have, and their titles, 
and you are done. The lib will detected the current page, pull titles, content and keywords for that page. 


## bootstrap  
(I don't know what boostrap means :) but, if it is an engine starter, then this is bootsrap)
Just `include()` `seoWrapperClass.php` class, at the start of your page, or use `spl_autoload_register('myAutoloader');`. Whatever works for you. 


## Configuring/installing

You need to open the `header.php` and add your details. 

Ex:  you can declare all your static pages here

    $existingStaticPages = [
            '/index.php',
            '/about.php.php',
            '/password.php?task=change',
            '/password.php?task=forgot'
        ];
		 


..and costume titles for each of the above mentioned pages(pages must relate to the title in pattern)

    $pageTitle = [
            'Welcome to my site, this is index page',
            'this is the about page',
            'So, you want to change your password ehh?',
            'Ok! your password has been sent'
        ];



Unlike pages, titles above keyword should just be one value. (don't include multiple arrays)

    $pageKeywords = [
           'this, is, where, your, site, keywords, go, separated, by, commas,'
        ];


Same goes for description of the meta `<meta name="description" content="" />` remember, only on array.

    $pageContent = [
          'testThis is where the (content) of your meta site goes'
        ];



And, that is it for the static page. You are good to go. You can also, have a dynamic page, just give  it your table name and which rows to fetch, and it will do so, and allocate those data to the relative parts inside the head of your page according to the pages. .




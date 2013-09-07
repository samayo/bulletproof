SeoWrapper
===========================

Ever created a simple blog site, with few static/dynamic pages and wanted to build a function to output:
`<meta name="description" content="`description`" />`

`<meta name="keywords" content="`keywords`" />`

`<title>`title`</title>`
	
But, felt lazy somehow, and needed a quick solution? Well, seoWrapper is a good solution. 


A another example is, to imagine, that you have a site with `4` pages. `3` are static pages, like 
        `home.php` // Static Page : just static stuff, same url & content always. 
        `about.php` // Static Page : just static stuff, same url & content always. 
        `blog.php` / /Dynamic Page : This page is dynamic, and url can change with `id?=` thus needs dynamic meta data
        `contact.php` // Static Page : just static stuff, same url & content always. 
        

## Configuring/installing

You need to open the `seoWrapperClass.php` and add your details. 

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



Unlike pages, titles arrays, the keyword should just contain one valued-array. (don't include multiple arrays)

    $pageKeywords = [
           'this, is, where, your, site, keywords, go, separated, by, commas,'
        ];


Same goes for description of the meta `<meta name="description" content="" />` remember, only on array.

    $pageContent = [
          'testThis is where the (content) of your meta site goes'
        ];



## bootstrap  
(I don't know what boostrap means :) but, if it is an engine starter, then this is bootsrap)
Just `include()` `seoWrapperClass.php` class, at the start of your page, or use `spl_autoload_register('myAutoloader');`. Whatever works for you. 




And, that is it for the static page. You are good to go. You can also, have a dynamic page, just give  it your table name and which rows to fetch, and it will do so, and allocate those data to the relative parts inside the head of your page according to the pages. .




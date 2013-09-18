`{NOTE!! This trivial piece of sh.. em code, is here to get me aquiented with github, so ... beat it }
SeoWrapper
===========================
define: Search Engine Optimization Flexibility:


Ever created a simple blog site, with few static/dynamic pages and wanted to build a function to output:
the title, keywords and description for each page inside the `<head>` tags?
	
But, felt lazy somehow, and needed a quick/dynamic solution? If so, then you are on the right place. 


Another example is, to just imagine, that you have a site with `4` pages.     
lets assume `3` of those pages are `static pages`, like    
        `home.php`  Static Page : just static stuff, same url & content always.    
        `about.php`  Static Page : just static stuff, same url & content always.     
        `contact.php`  Static Page : just static stuff, same url & content always.     
        
And the `4th` one is `dynamic`, and hence the all the infos must change according to the content being fetched.     
        `blog.php` Dynamic Page : This page is dynamic, and url can change with id?= thus needs dynamic meta data
        
Now, in simple terms, if you use `seoWrapper` class, then you don't have to worry about outputting `title,description,content
`for these pages ever. 
        

## Configuring
is all done here : https://github.com/Eritrea/seoWrapper/blob/master/src/StaticPages.php

## Dynamic pages
To fetch data from db, for your dynamic pages, just configure this line, from `tests.php`

 `$fetch = $SeoWrapper->getContents($conn, 'pages', "id", ['title', 'keywords', 'description']);`
 `$conn` should be established using `new PDO()` object    
 `id` is the current identifier of your dynamic page, it can be changed to anything     
 `['title', 'keywords', 'description']` signals, what you want to fetch from your database. 

It is done!! 

Finally, you have to include the `seoWrapperClass.php` to the page where your `<header></head>` tags are located, in-order to use it.	

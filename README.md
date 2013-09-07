SeoWrapper
===========================

Ever created a simple blog site, with few static/dynamic pages and wanted to build a function to output:
`<meta name="description" content="`description`" />`

`<meta name="keywords" content="`keywords`" />`

`<title>`title`</title>`
	
But, felt lazy somehow, and needed a quick solution? Well, seoWrapper is a good solution. 


Another example is, to imagine, that you have a site with `4` pages. `3` are static pages, like 
        `home.php // Static Page : just static stuff, same url & content always. `
       ` about.php // Static Page : just static stuff, same url & content always. `
       ` blog.php / /Dynamic Page : This page is dynamic, and url can change with id?= thus needs dynamic meta data`
        `contact.php // Static Page : just static stuff, same url & content always. `
        

## Configuring

You need to open the `seoWrapperClass.php` and declare all your static pages first. 

Ex:  you can declare all your static pages here

    $existingStaticPages = [
            '/index.php',
            '/about.php.php',
            '/password.php?task=change',
            '/password.php?task=forgot'
        ];
		 


..and costume titles for each of the above mentioned pages, titles must be relative to pages. 

    $pageTitle = [
            'Welcome to my site, this is index page',
            'this is the about page',
            'So, you want to change your password ehh?',
            'Ok! your password has been sent'
        ];



Since all your static pages share the same keyword, keep the array as-is: ONE! 

    $pageKeywords = [
           'this, is, where, your, site, keywords, go, separated, by, commas,'
        ];


Same goes for description of the meta, keep it one array, but length of character is unlimited.  

    $pageContent = [
          'testThis is where the (content) of your meta site goes'
        ];



## Dynamic pages
If you have a dynamic page, that needed to fetch data from db, then since the title, desc.. key.. must be relative 
to the pages fetched data, you need to just to give it table name & row of the data being fetched in `header.php` here

`$fetch = $SeoWrapper->getDynamicContents($conn, 'pages', "id"); //'pages' = table name. "id" = $_GET['*']`

It is done!! 

Finally, you have to include the `seoWrapperClass.php` to the page where your `<header></head>` tags are located, in-order to use it.	

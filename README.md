`{NOTE!! This trivial piece of sh... hmmm code, is here to get me aquiented with github, so ... beat it }`

###SeoWrapper
###### `define: Search Engine Optimization Flexibility:`

Do you want to create a simple site, with PHP but got stuck trying to create a very effective function to output 
title, keywords and description for each of your pages? In that case, then do no further. And just keep reading
        

### Configuring
is all done here : https://github.com/Eritrea/seoWrapper/blob/master/src/StaticPages.php

### Dynamic pages
To fetch data from db, for your dynamic pages, just configure this line, from `tests.php`

###### `$fetch = $SeoWrapper->getContents($conn, 'pages', "id", ['title', 'keywords', 'description']);`    
 `$conn` should be established using `new PDO()` object    
 `pages` is your table name from which the data for this page is being fetched from
 `id` is the row from your tables. 
 
 #### in short ...
 
 All that line does is send to `function getContents(..` a query as:      
 
 `$conn->prepare('SELECT title, keywords, description from pages where id = ?')`     
 
 
 
 
 `['title', 'keywords', 'description']` signals, what you want to fetch from your database. 

It is done!! 

Finally, you have to include the `seoWrapper.php` to the page where your `<header></head>` tags are located, in-order to use it.	

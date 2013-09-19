`{NOTE!! This trivial piece of sh... hmmm code, is here to get me aquiented with github, so ... beat it }`

###SeoWrapper
###### `define: Search Engine Optimization Flexibility:`

Do you want to create a simple site, with PHP but got stuck trying to create a very effective function to output 
title, keywords and description for each of your pages? In that case, then do no further. And just keep reading
        

### Configuring
If you have static pages, say it here: https://github.com/Eritrea/seoWrapper/blob/master/src/StaticPages.php

For you dynamic pages:
to fetch data from db, to display that data as meta tag, just configure this line, from `tests.php`

###### `$fetch = $SeoWrapper->getContents($conn, 'pages', "id", ['title', 'keywords', 'description']);`    
 All that line is saying is:    
 
###### `$conn->prepare('SELECT title, keywords, description from pages where id = ?')`
###### `execute->([$_GET['id']])`

It is done!! You will get what you asked for in here, either error or the data: 

`($SeoWrapper->hasErrors()) ? die('page not found') : list($title, $keywords, $description) = $fetch;`

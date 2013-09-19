###### This is just a way for me to learn about github. So, its not a real library :{

### WHAT?
###### `#seoWrapper{Simple, Dynamic, Seo, Flexibility, More ;}`

Let say, you wanted to create a website in PHP, and you needed to make a function to do the job of outputting 
title, keyword & description from database, or more in a very flexibly way, if so... its your lucky day. 
        

### HOW?
If you have static pages go here: https://github.com/Eritrea/seoWrapper/blob/master/src/StaticPages.php

If you have dynamic pages: 
fetch dynamic contents just with this line. 

###### `$fetch = $SeoWrapper->getContents($conn, 'pages', "id", ['title', 'keywords', 'description']);`    

All that line says is:    
 
###### `$conn->prepare('SELECT title, keywords, description from pages where id = ?')`
###### `execute->([$_GET['id']])`

It is done!! If there is data identified with the id of your page, then you will get content othewiser an error. 

`($SeoWrapper->hasErrors()) ? die('page not found') : list($title, $keywords, $description) = $fetch;`

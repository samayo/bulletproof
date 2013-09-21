###SeoWrapper
===========

The objective it to give you the ultimate flexibility on what to display inside your meta tags!!

First! If that "meta-tags" term is a bit fuzzy to you, then look down. (No!! Not your pants. the picture you stoopied :)

      <!DOCTYPE html>
      <html lang="en-US">
          <head>
              <meta charset="utf-8" />
              <meta name="robots" content="index, follow" />
              <meta name="description" content="______description_________" />
              <meta name="keywords" content="_________keywords__________" />
              
              <title>_________title___________</title>
          </head>




#####See those blank fileds, the purpose is to fill them!!
===========

###### But, why??

Well, if you are building a site from scratch, at some point you may need to write a function to take care of that for you, ...but if you were to include this class instead, you will have something hopefully more flexible & powerful than what you were aiming for.


#### Configuring ?

Assuming I just have answered your lousy question in StackOverflow, and the link on my profile lead you here, then First you MUST have the basic understanding of the difference between before going further.         

######" Static Vs Dynamic Pages "

In short, if you have static page like .. `contact.php` the meta-tags will/should remain the same always, if you have a dynamic page however, one with `id?=someInteger` every header content must be unique to that page.


###### Static Pages 
If you have static pages, only declare them here... [StaticPages.php]( https://github.com/Eritrea/seoWrapper/blob/master/src/StaticPages.php)


###### Dynamic Pages

For your dynamic pages, all you have to do configure is this below line:

`$fetch = $SeoWrapper->getContents($conn, 'pages', "id", ['title', 'keywords', 'description']);`     

 the `pages` is a table from which you are getting all those datas from.  It could be news, articles.. anything     
 `id` is the page identifier, or in short `$_GET['whatever-is-here']`     
 `['title', 'keywords', 'description']` are what you are fetching from row,    


the good news is that, you can fetch for more than three rows and add all those datas inside your meta tags however you want.

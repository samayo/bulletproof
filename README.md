

###SeoWrapper

The objective it to give you the ultimate flexibility on what to display inside your meta tags!!
If that meta-tags term if a bit fuzzy, then look down. (No!! Not your pants. the picture you stoopied :)




But, why? Well, if you are building a site from scratch  at some point you may need to write a function to output something to your
meta tags, so if you were to include this class instead, you will have something hopefully than you bargained for.


### Configuring ?

Just like the meta tags, you must have a clear understanding of the different between static & dynamic pages
In short, if you have static page like .. contact.php the meta-tags will remain the same, if you have a dynamic page
with id?=someNumer then starting from the title everything must be unique to that page.


So, if you have static pages, first declare them here.. https://github.com/Eritrea/seoWrapper/blob/master/src/StaticPages.php

For your dynamic pages, all you have to configure is this below line

`$fetch = $SeoWrapper->getContents($conn, 'pages', "id", ['title', 'keywords', 'description']);`

the `pages` is a table from which you are getting all those datas from.  It could be news, articles.. anything
`id` is the page identifier, or in short `$_GET['whatever-is-here']`
`['title', 'keywords', 'description']` are what you are fetching from row,


the good news is that, you can fetch for more than three rows and add all those datas inside your meta tags however you want.
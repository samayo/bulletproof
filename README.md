###SeoWrapper
===========

The objective it to give you the ultimate flexibility on what to display inside your meta tags!!

If the term "meta tags" is a bit fuzzy here, then look down.

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

###### But, why this class??

Sure, if you are building a site from scratch, at some point you may need to write a function to do that for you,
...but! if you were to include this class instead, with seconds of configuration time, you will have something more
flexible & powerful than what you might were aiming for. Which would give you much better SEO result.


#### Configuring ?

Assuming I have just answered your lousy question in StackOverflow, and the link on my profile lead you here, then:
      First thing you MUST know is the difference between:         

######" Static Vs Dynamic Pages "

In short, Static pages are like your `contact.php` the things we are talking about now, .. title, keyword, description
will always stay the same. However, in a dynamic page, one with `news.php?id=someInteger`
every one of those contents must change to be unique to that page id.


##### Which file should I touch? oO
The class has only two files, `confiMe.php` to apply your configuration, and `seowrapper.php` is the main class.
All configuration takes in `configMe.php` ( [configMe.php]( https://github.com/Eritrea/seoWrapper/blob/master/src/configMe.php) )


###### Declaring static pages

Static pages must be declared inside `definePages()` using this format:

        '/contact.php'=> [
                         'title'=>'this is where you put title for your contact page',
                         'description'=>'this is the description for this specific page'],

As you can see, each static pages needs `title`, `description` if you are wandering about `keywords` then check `myDefaultSettings()`:

        'keywords'=>[
                    'this, should, be, keywords, for, all, your, pages, seperated, by commas']

these are default keywords for all your static page, but not! for dynamic pages


###### Declaring Dynamic pages/things

Since we have said, dynamic pages with queries needs unique data for each query, then we have the table to get the data from,
what to query in a page, and which rows to pull from database.
        function databaseConfigs(){
             return [
                'tableName'=>'pages', //table name
                'queryType'=>'id', //this is query of $_GET array, or in short $_GET['whatever-is-inside-here']
                'dataToFetch'=>['title','keywords','description'] // which rows to fetch from tableName, so we can use this to map the metadata
            ];
        }



###### EXAMPLE: 

              <meta name='description' content=' <?= $title, $description  ?> ' />
              <meta name='keywords' content=' <?= $keywords, $description, $title ?> '/>
              <title> <?= $title ?> </title>

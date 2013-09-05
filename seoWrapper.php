<?php

class SeoWrapper{
    private $_errors = [];
    public $foo;

    function fetchAllFromStaticPages(){

        $existingStaticPages = [
            '/seowrapper/header.php',
            '/seowrapper/headder.php',
            '/seowrapper/password.php?task=change',
            '/seowrapper/password.php?task=forgot'
        ];


        $staticPageTitles = [
            'Welcome to my site, this is index page',
            'this is the about page',
            'So, you want to change your password ehh?',
            'Ok! your password has been sent'
        ];


        $staticPageKeywords = [
           'this, is, where, your, site, keywords, go, separated, by, commas,'
        ];


        $staticPageDescription = [
           'This is where the (content) of your meta site goes'
        ];

         return  [$existingStaticPages, $staticPageTitles, $staticPageKeywords, $staticPageDescription];
    }



    function returnPageContent(){

         function forUrl($requestUri, $scriptName){
            $existingStaticPages = $this->fetchAllFromStaticPages()[0];

            if(in_array($requestUri, $existingStaticPages)){
                $pageKey =  array_search($requestUri, $existingStaticPages);
               return $pageKey;

            }else if(in_array($scriptName, $existingStaticPages)){
                $pageKey =  array_search($scriptName, $existingStaticPages);
                    return $pageKey;

            }else{
                return $this->_errors = false;
            }
        }

    }







    function getDynamicContents($conn, $table, $identifier){
        if(!isset($_GET[$identifier]) || empty($_GET[$identifier])){
            return $this->_errors = 'Invalid URL is found';
        }else{
            try{
                $stmt = $conn->prepare("SELECT title,content,keywords FROM $table WHERE id = ? ");
                $stmt->execute([$_GET[$identifier]]);
            }catch(PDOException $e){
                return $this->_errors = 'Unknown error occured. Please try again'.$e->getMessage();
            }


            if($stmt->rowCount() == 0){
                return $this->_errors  = 'Page not found! Link may be invalid or expired';
            }

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result[0];
        }
    }



     function checkErrors(){
         return (!empty($this->_errors)) ? true : false;
     }
 }



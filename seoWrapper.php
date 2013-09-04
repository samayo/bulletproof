<?php

class SeoWrapper{
    private $_errors = [];
    private  $siteName = ' | YourSiteNamehere.tld '; // leave '' if not needed


    function fetchAllFromStaticPages(){

        $existingStaticPages = [
            '/seowrapper/headder.php',
            '/seowrapper/header.php',
            '/seowrapper/password.php?task=change',
            '/seowrapper/password.php?task=forgot'
        ];


        $staticPageTitles = [
            'title'=>'Welcome to my site, this is index page',
            'title'=>'this is the about page',
            'title'=>'So, you want to change your password ehh?',
            'title'=>'Ok! your password has been sent'
        ];


        $staticPageKeywords = [
            'keywords'=>'this, is, where, your, site, keywords, go, separated, by, commas,'
        ];


        $staticPageDescription = [
            'content'=>'This is where the (content) of your meta site goes'
        ];

         return  [$existingStaticPages, $staticPageTitles, $staticPageKeywords, $staticPageDescription];
    }



    function returnPageContent($requestUri, $scriptName){

        $existingStaticPages = $this->fetchAllFromStaticPages()[0];

        if(in_array($requestUri, $existingStaticPages)){
            $pageKey =  array_search($requestUri, $existingStaticPages);
           return $pageKey;
           // return $this->declaredPageProperties()[0][$pageKey];

        }else if(in_array($scriptName, $existingStaticPages)){
            $pageKey =  array_search($scriptName, $existingStaticPages);
                return $pageKey;
          //  return $this->declaredPageProperties()[0][$pageKey];

        }else{
            return $this->_errors = 'Current url is not found in allowed static pages';
        }
    }







    function getDynamicContents($conn, $table, $identifier){
        if(!isset($_GET[$identifier]) || empty($_GET[$identifier])){
            return $this->_errors = 'Invalid URL is found';
        }else{
            try{
                $stmt = $conn->prepare("SELECT * FROM '$table' WHERE id = ? ");
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



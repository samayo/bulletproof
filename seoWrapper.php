<?php

class SeoWrapper{
    private $_errors = [];
    function fetchAllFromStaticPages(){

        $existingStaticPages = [
            '/seowrapper/f.php',
            '/seowrapper/header.php',
            '/seowrapper/password.php?task=change',
            '/seowrapper/password.php?task=forgot'
        ];


        $pageTitle = [
            'Welcome to my site, this is index page',
            'this is the about page',
            'So, you want to change your password ehh?',
            'Ok! your password has been sent'
        ];


        $pageKeywords = [
           'this, is, where, your, site, keywords, go, separated, by, commas,'
        ];


        $pageContent = [
          'testThis is where the (content) of your meta site goes'
        ];

         return  [$existingStaticPages, 'title'=>$pageTitle, 'keywords'=>$pageKeywords, 'content'=>$pageContent];
    }





    function isPageStaticOrDynamic($requestUri){
            $inStatic = $this->fetchAllFromStaticPages()[0];
            return (in_array($requestUri, $inStatic)) ? array_search($requestUri, $inStatic) : 'dynamic';
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
            $result = $stmt->fetchAll(PDO::FETCH_NUM)[0];
            return $result;
        }
    }



     function checkErrors(){
         return (!empty($this->_errors)) ? true : false;
     }
 }



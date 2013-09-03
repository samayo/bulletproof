<?php

class SeoWrapper{
    private $_errors = [];
    private  $SiteName = ' | YourSiteNamehere.tld '; // leave '' if not needed


    function declarePageProperties(){

        $exsistingStaticPages = [
            '/index.php',
            '/about.php',
            '/password.php?task=forgot',
            '/password.php?task=sent'
        ];


        $staticPageTitles = [
            'Welcome to my site, this is index page',
            'this is the about page',
            'So, you want to change your password ehh?',
            'Ok! your password has been sent'
        ];


        $staticPageKeywords = [
            'keywords'=>'this, is, where, your, site, keywords, go, separated, by, commas,'
        ];


        $staticPageDescription = [
            'content'=>'This is where the (content) of your meta site goes'
        ];

         return  [$exsistingStaticPages, $staticPageTitles, $staticPageKeywords, $staticPageDescription];
    }



    function checkIfPageIsAllowed(){
            $page = $this->declarePageProperties()[0];
            $a = $_SERVER['REQUEST_URI'];
            $b = parse_url($_SERVER['SCRIPT_NAME']);
            return (in_array($b, $page)) ? array_search($b, $page) :  (in_array($a, $page)) ? array_search($a, $page) : false ;
    }




    function getPageProperties(){
            $pageKey = $this->checkIfPageIsAllowed();
            $pageTitle = $this->declarePageProperties()[1][$pageKey].$this->SiteName;
            return [$pageTitle, $this->declarePageProperties()[2], $this->declarePageProperties()[3]];

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



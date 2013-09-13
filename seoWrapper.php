<?php

class SeoWrapper{
    private $_errors = [];

    public function __construct($customPages, $defaultSettings){
        $this->customPages = $customPages;
        $this->defaultSettings = $defaultSettings;
    }

    public function isPageStaticOrDynamic($currentUrl){
        $pageName = array_keys($this->customPages['Pages']);
        $checkPage = (in_array($currentUrl, $pageName)) ? $this->customPages['Pages'][$currentUrl] : 'dynamic';
       return $checkPage;

        //return array_key_exists($requestUri, $this->customPages['Pages']) ?
           //array_search($requestUri, $this->customPages['Pages']) : 'dynamic';
      //  return (array_key_exists($requestUri, $checkPageExists)) ? array_search($requestUri, $checkPageExists) : 'dynamic';
   }

    public function getDynamicContents($conn, $table, $identifier){
        if(!isset($_GET[$identifier]) || empty($_GET[$identifier])){
            return $this->_errors = 'Invalid URL is found';
        }else{
            try{
                $stmt = $conn->prepare("SELECT title,content,keywords FROM $table WHERE id = ? ");
                $stmt->execute([$_GET[$identifier]]);
            }catch(PDOException $e){
                return $this->_errors = 'Unknown error occured. Please try again'.$e->getMessage();
            }
            
            return ( $stmt->rowCount() == 0) ? $this->_errors = 'Page Not Found' : $stmt->fetchAll(PDO::FETCH_NUM)[0];
           
        }
    }



     public function hasErrors(){
         return (!empty($this->_errors)) ? true : false;
     }
 }



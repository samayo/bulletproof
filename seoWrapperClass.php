<?php

class SeoWrapper{
    private $_errors = [];

    function construct($pageProperties){
        $this->pageProperties = $pageProperties;
    }








    public function isPageStaticOrDynamic($requestUri){
            $inStatic = $this->fetchAllFromStaticPages()[0];
            return (in_array($requestUri, $inStatic)) ? array_search($requestUri, $inStatic) : 'dynamic';
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



     private function hasErrors(){
         return (!empty($this->_errors)) ? true : false;
     }
 }



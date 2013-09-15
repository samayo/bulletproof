<?php


class SeoWrapper  {
    private $_errors = [];


    /**
     *  Bring custom settings so, we can send properties for each pages as defined in our MyConfig file
     *
     * @param $customPages
     * @param $defaultSettings
     *
     */

    public function __construct($customPages, $defaultSettings){
        $this->customPages = $customPages;
        $this->defaultSettings = $defaultSettings;
    }


    /**
     * check if current page is defined inside static pages
     *
     * @param $currentUrl this is feched from the server variable REQUEST_URI
     * @return string this will either return a current page name or the word dynamic, if page ins't static
     *
     */

    public function isPageStaticOrDynamic($currentUrl){
        $pageName = array_keys($this->customPages['Pages']);
        $checkPage = (in_array($currentUrl, $pageName)) ? $this->customPages['Pages'][$currentUrl] : 'dynamic';
       return $checkPage;
   }




    /**
     *  if page is dynamic, we need to fetch something to serve as title, key.. desc..
     *
     * @param $conn
     * @param $table specify the table from which we want to fetch datas
     * @param $identifier $identifier this checks if `something?=` is defined. could be id, q ...
     * @param array $values specified rows to fetch from db
     * @return string string if query is success, we will return fetched results, else we will send message to error method
     */

    public function getContents($conn, $table, $identifier, $values = []){
        if(!isset($_GET[$identifier]) || empty($_GET[$identifier])){
            return $this->_errors = 'Invalid URL / Broken Link ';
        }else{
            var_dump($values);
            $rows = implode(', ', $values);
            try{
                $stmt = $conn->prepare("SELECT  $rows FROM $table WHERE id = ? ");
                $stmt->execute([$_GET[$identifier]]);
            }catch(PDOException $e){
                return $this->_errors = 'Unknown error! Please try again later. '; //$e->getMessage();
            }
            
          return ( $stmt->rowCount() == 0) ? $this->_errors = 'Page Not Found' : $stmt->fetchAll(PDO::FETCH_NUM)[0];
           
        }
    }


    /**
     * @return bool
     *  Checks if query for fetching dynamic contents has failed.
     */
    public function hasErrors(){
         return (!empty($this->_errors)) ? true : false;
     }
 }



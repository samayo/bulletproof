<?php


class SeoWrapper  {
    private $_errors = [];


    /**
     *  The constructor will pull all your declared static pages and their costume meta tag settings
     *
     * @param $customPages | This variable contains list of all our pages defined as static
     * @param $defaultSettings | This variable imports default keyword, description for all the static pages
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
        $allStaticPages = array_keys($this->customPages['Pages']);

        if(in_array($currentUrl, $allStaticPages)){
            var_dump($this->customPages['Pages'][$currentUrl]);
        }else{
            return 'dynamic';
        }
//        $checkPage = (in_array($currentUrl, $allStaticPages)) ? $this->customPages['Pages'][$currentUrl] : 'dynamic';
  //     return $checkPage;
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



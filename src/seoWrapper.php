<?php

    include 'StaticPages.php';


class SeoWrapper  {
    private $_errors = [];


    use staticPages;
    use defaultPageSettings;
	use databaseConfigs;
    /**
     * check if current page is defined inside static pages
     *
     * @param $currentUrl this is feched from the server variable REQUEST_URI
     * @return string this will either return a current page name or the word dynamic, if page ins't static
     *
     */

    public function currentPage($currentUrl){
        $allStaticPages = array_keys($this->myStaticPages()['Pages']);

		if(in_array($currentUrl, $allStaticPages)){
            $myPages = $this->myStaticPages()['Pages'];
            $title = $this->myStaticPages()['Pages'][$currentUrl]['title'];
            $pageKeywords = $this->myDefaultSettings()['keywords'];

            if(array_key_exists('description', $myPages[$currentUrl])){
                $description = $myPages[$currentUrl]['description'];
            }else{
                $description = $this->myDefaultSettings()['description'];
            }

            return array_merge((array)$title, (array)$description, $pageKeywords);
		}else{
			return 'dynamic';
		}
        
		
		
        //return (in_array($currentUrl, $allStaticPages)) ? $description : 'dynamic';

   }


    /**
     * @param $conn
     * @return string
     */

    public function getContents($conn){
        $tableName = $this->tableName;
        $queryType = $this->queryType;
        $rows = implode(',', $this->dataToFetch);


        if(!isset($_GET[$queryType]) || empty($_GET[$queryType])){
            return $this->_errors = 'The URL, you have requested appears to be invalid. Please try again later.';
        }

        try{
            $stmt = $conn->prepare("SELECT  $rows FROM $tableName WHERE id = ? ");
            $stmt->execute([$_GET[$queryType]]);
        }catch(PDOException $e){
            return $this->_errors = 'Unknown error! Please try again later. '; //$e->getMessage();
        }
            
        return ($stmt->rowCount() == 0) ? $this->_errors = 'Page Not Found' : array_combine($this->dataToFetch, $stmt->fetchAll(PDO::FETCH_NUM)[0]);
           

    }


    /**
     * @return bool
     *  Checks if query for fetching dynamic contents has failed.
     */
    public function hasErrors(){
         return (!empty($this->_errors));
     }
 }



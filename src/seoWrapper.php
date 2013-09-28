<?php

    include 'configMe.php';


class SeoWrapper  {
    private $_errors = [];

    /**
     * method to validate existing URL and fetch metadata according to our settings
     * @param $currentUrl - REQUEST_URI to check if page has query
     * @param $PHP_SELF - to match query-less pages
     * @return array|string return title, keyword, description for static pages, otherwise return text: dynamic
     */
    public function currentPage($currentUrl, $PHP_SELF){
        $listOfStaticPages = array_keys(definePages()['staticPages']);

	if(in_array($PHP_SELF, $listOfStaticPages)){
            $title = definePages()['staticPages'][$PHP_SELF]['title'];
            $keywords = myDefaultSettings()['keywords'][0];
            $description = definePages()['staticPages'][$PHP_SELF]['description'];
            return ['title'=>$title, 'keywords'=>$keywords, 'description'=>$description];

	}else{

            if(isset($_GET) && !empty($_GET)){
                return 'dynamic';
            }else{
                $title = definePages()['dynamicPages']['title'];
                $description = definePages()['dynamicPages']['description'];
                $keywords = myDefaultSettings()['keywords'][0];
                return ['title'=>$title, 'keywords'=>$keywords, 'description'=>$description];
            }
	}

   }


    /**
     * @param $conn
     * @return string
     */

    public function getContents($conn){
        $tableName = databaseConfigs()['tableName'];
        $queryType = databaseConfigs()['queryType'];
        $rows = implode(',', databaseConfigs()['dataToFetch']);


        if(isset($_GET[$queryType]) || !empty($_GET[$queryType])){


        try{
            $stmt = $conn->prepare("SELECT  $rows FROM $tableName WHERE id = ? ");
            $stmt->execute([$_GET[$queryType]]);
        }catch(PDOException $e){
            return $this->_errors = 'Unknown error! Please try again later. '; //$e->getMessage();
        }

        return ($stmt->rowCount() == 0) ? $this->_errors = 'Page Not Found' :
            array_combine(databaseConfigs()['dataToFetch'], $stmt->fetchAll(PDO::FETCH_NUM)[0]);

        }else{
            
            $title = definePages()['staticPages'][$_SERVER['REQUEST_URI']]['title'];
            $keywords = myDefaultSettings()['keywords'][0];
            $description = myDefaultSettings()['description'][0];

            return ['title'=>$title, 'keywords'=>$keywords, 'description'=>$description];

        }
    }


    /**
     * @return bool
     *  Checks if query for fetching dynamic contents has failed.
     */
    public function hasErrors(){
         return (!empty($this->_errors));
     }
 }



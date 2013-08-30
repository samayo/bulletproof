<?php

/* 
INTRO:
	In a nutshell: Do not worry about what to output for title, keywords, description for your site inside the <head></head>

CONFIG: 
	1 - First declare all your satic pags inside $Declare_Static_Pages and thier titles, in the next array i.e. $Static_Page_Titles
	2 - Then add one content and decription to for all your static pages in $Static_Page_Keywords, and $Static_Page_Content. and THAT'S IT!!

	- If you have a dynamic page, that fetchs data from db, and uses the data to 
	to show the title, content and description... don't include it in this file. That should be mentioned in yourHeader.php file.

*/

	class SeoWrapper{
		private $_errors = [];
		protected $SiteName = ' | YourSiteNamehere.tld '; // leave '' if not needed
	 
/*
	Declare_Static_Content holds list of static pages, and title, keyword tags, and content for each of them
	Whenever using this class for new project, only these details need to be changed.
*/
		
		function Declare_Static_Contents(){  
// Declare pages that are static. Meaning, their content is accessible with same url, that doesn't need to change
## NOTE - If you add pages inside the $Declare_Static_Pages then you must add a title for 
## that page, inside the next array i.e. $Static_Page_Titles. the arrays must match in pattern
			$Declared_Static_Pages = [
				'/seoWrapper/header.php',
				'about.php',
				'/seoWrapper/header.php',	
				'password.php?task=change',			
				'password.php?task=forgot'
			];	
				
			// And their, costume title	
			$Static_Page_Titles = [
				'Welcome to my site, this is index page',
				'this is the about page',
				'So, you want to change your password ehh?',	
				'Ok! your password has been sent'		
			];
			
			// Enter the keywords for all the static pages. i.e. <meta name="keywords" content="..,..,...,">
			$Static_Page_Keywords = [
				'keywords'=>'this, is, where, your, site, keywords, go, seperated, by, commas,'
			];
				
			// Enter <meta content for all, <meta name="description" content="..,..,...,">	
			$Static_Page_Content = [
				'content'=>'This is where the (content) of your meta site goes'
			];
			
		return  [$Declared_Static_Pages, $Static_Page_Titles, $Static_Page_Keywords, $Static_Page_Content];
	 }
	 
			 
		 
		 
		 # ENJOY!! because The Below script should be left "As Is". No need for more configuration
	  ###############################################################################################
		   
		   

		// Will check if the page you are checking is declared as static in your array by doing PHP_SELF/REQUEST_URI, if yes it will return the array key of that page
		
		function Static_Pages(){
				$page = $this->Declare_Static_Contents()[0];
				$a = $_SERVER['REQUEST_URI'];
				$b = parse_url($_SERVER['SCRIPT_NAME']);
				return (in_array($b, $page)) ? array_search($b, $page) :  (in_array($a, $page)) ? array_search($a, $page) : false ;
		}

			
		
	
			
		// After getting page's array key from Static_Pages, this will fetch page's title, keyword, content
		
		function Get_Static_Contents(){
				$PageKey = $this->Static_Pages();
				$Page_Title = $this->Declare_Static_Contents()[1][$PageKey].$this->SiteName; 						
			return [$Page_Title, $this->Declare_Static_Contents()[2], $this->Declare_Static_Contents()[3]];
			
		}

	/*
		   This is seperate function to deal with 'A dynamic page', which has to fetch title, keywords and content from database. 
		   It gets data from db, based on the current ID
		   if data is not found, it will pass on an error
		 
		*/
		
		
		 function Get_Dynamic_Contents($conn, $table, $id){
		
		 
		 if(!isset($_GET[$id]) || empty($_GET[$id])){
			return $this->_errors = 'Content for this page are unavalable';
		 }else {
		 
			 
			try{ 	
				$stmt = $conn->prepare("SELECT * FROM $table WHERE id = ? ");
				$stmt->execute([$_GET[$id]]);
			}catch (PDOException $e){
				return $this->_errors = 'Unknown error occured, due to: '.$e->getMessage();
			}
			

			if($stmt->rowCount() == 0){
				return $this->_errors = 'This page can not be found. Link may be broken or expired.' ;
			}

			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
				return $result[0]; 
			}

		 }

		/*
		 * If errors are found during db, querying then this method will give ~404 error
		 */
		 function checkErrors(){
			 if(!empty($this->_errors)){
				 die('<pre><h1>  Not Found!! </h1> The requested address: <b>'.$_SERVER["REQUEST_URI"].'</b> was not found.</pre>');
			 }else{
				 return false;
			 }
		 }
	 }
	?>

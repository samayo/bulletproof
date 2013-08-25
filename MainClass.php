<?php

/* INTRO:
	If you wanted to create a small blog site, and you do not want to waste time creating a function that outputs 
	title, description and content inside the <head></head> tags, for all pages then tinySeo is your friend.
	Just spend 20 seconds mentioning your static/dynamic page + table and row name for the dynamic pages, then all is Ghuuud!

CONFIG: 
	1 - First declare all your satic pags inside $Declare_Static_Pages and thier titles, in the next array i.e. $Static_Page_Titles
	2 - Then add one content and decription to for all your static pages in $Static_Page_Keywords, and do the same for $Static_Page_Content. and THAT'S IT!!
	
	- If you have a dynamic page, that fetchs data from db, and uses the data to 
	to show the title, content and description... don't include it in this file. That should be mentioned in tinySeoObject file.

*/



class tinySeoClass{
		protected $conn;
		private $_errors = [];
		public $SiteName = ' | YourSiteNameHere.com ';

		
	function __construct($conn){
		$this->conn = $conn;
	}


 
 /*
  * Declare_Static_Content holds list of static pages, and title, keyword tags, and content for each of them
  * Whenever using this class for new project, only these details need to be changed.
  */
		  

		
  
function Declare_Static_Contents(){


// Declare pages that are static. Meaning, their content is accessible with same url, that doesn't need to change
## NOTE - If you add pages inside the $Declare_Static_Pages then you must add a title for 
## that page, inside the next array i.e. $Static_Page_Titles. the arrays must match in pattern
	
	$Declared_Static_Pages = [
		'index.php', 
		'about.php', 
		'portofolio.php', 
		'page.php?page=login', 
	];	
	
	
 // And their, costume title
 
	$Static_Page_Titles = [
		'Title for index.php goes here...',
		'Title for about.php goes here...',
		'Title for portofolio.php goes here...',
		'Title for page.php?page=login goes here...',
	];
	
	
// Enter the keywords for all the static pages. i.e. <meta name="keywords" content="..,..,...,">
	
	$Static_Page_Keywords = [
		'keywords'=>'this, is, where, your, site, keywords, go, seperated, by, commas,'];
		
		
// Enter <meta content for all, <meta name="description" content="..,..,...,">

	$Static_Page_Content = [
		'content'=>'This is where the "content" of your meta site goes'];
		
		
		return = [$Declare_Static_Pages, $Static_Page_Titles, $Static_Page_Keywords, $Static_Page_Content];
 }
 
		 
	 
	 
	 # ENJOY!! because The Below script should be left "As Is" unless, for extending the functionality of this script
	   ##########################################################################################
	   
	   

	// Will check if the page you are browsing is declared as static in your array by doing PHP_SELF/REQUEST_URI, if yes it will return the array key of that page
	
	
	function Static_Pages(){
			$Static_Pages = $this->Declare_Static_Contents()[0];
		for($i=0; $i < count($Static_Pages); $i++){
			if(strpos($_SERVER['REQUEST_URI'], $Static_Pages[$i]) || strpos($_SERVER['PHP_SELF'], $Static_Pages[$i])){
				return $i; break;
			}
		}
	}

		
	// After getting page's array key from Static_Pages, this will fetch page's title, keyword, content
	
	function Get_Static_Contents(){
	
			$PageKey = $this->Static_Pages();
			$Page_Title = $this->Declare_Static_Contents()[1][$PageKey].$this->SiteName; 		// get title + SiteName
			$Page_Keywords = $this->Declare_Static_Contents()[2]; 								// get keywords
			$Page_Content = $this->Declare_Static_Contents()[3];                               // get content
			
		return [$Page_Title, $Page_Keywords, $Page_Content];
		
	}



		
		
	/*
	   This is seperate function to deal with 'A dynamic page', which has to fetch title, keywords and content from database. 
	   It gets data from db, based on the current ID
	   if data is not found, it will pass on an error
	 
	*/
	
	 function Get_Dynamic_Contents($table, $id){
		 
		try{ 	
			$stmt = $this->conn->prepare("SELECT * FROM $table WHERE id = ? ");
			$stmt->execute(array($id));
		   }catch (PDOException $e){
			   return $this->_errors = 'Unknown error occured, due to: '.$e->getMessage();
		   }
		
		
		if($stmt->rowCount() === 0){
			 return $this->_errors = 'This page can not be found. Link may be broken or expired.' ;
		 }

		 $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		 
		 return $result; 
	 }

	/*
	 * If errors are found during db, querying then this method will give ~404 error
	 */
	 function ShowErrors(){
		 if(!empty($this->_errors)){
			 die('<h1>  Not Found!! </h1> The requested address: <b>'.$_SERVER["REQUEST_URI"].'</b> was not found. Error-Type: <i>'.$this->errors.'</i>');
		 }else{
			 return false;
		 }
	 }
 }
?>

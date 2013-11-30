<?php
/**
 * A small, secure & fast image uploader class written in all-static
 * class to give an extra boost in performance.
 * @author     Simon _eQ <https://github.com/simon-eQ>
 * @license    Public domain. Do anything you want with it.
 *
 */

    include_once 'BulletProof.php';

    BulletProof::options(array('png', 'jpeg', 'gif', 'jpg'),
						 array('max-width'=>150, 'max-height'=>150),
						 30000,
						'pictures/'
						);



    $renamePicture = "Passport_Picture";

    if($_FILES){
        $upload =  BulletProof::upload($_FILES['profile_pic'], $renamePicture);
      
        if($upload === true){
            echo "Success! Your image is uploaded";
        }
	}
	
	
	   echo "<form method='POST' enctype='multipart/form-data'>
			 <input type='hidden' name='MAX_FILE_SIZE' value='30000' />
			 <input type='file' name='profile_pic' />
	  		 <input type='submit' value='upload' />";

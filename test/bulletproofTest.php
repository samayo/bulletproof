<?php
// Trying to come up with ways how to test this thing. 
// if anyone can provide any idea, it would help a lot

class bulletproofTest extends \PHPUnit_Framework_TestCase 
{
    public function test_if_all_visible_class_methods_exist(){
    	$imageUploader = new ImageUploader\Bulletproof; 

    	$classMethod = get_class_methods($imageUploader);

    	$result = array_diff(
    		array(
    		'fileTypes',
    		'limitSize',
    		'limitDimension',   	
    		'uploadDir',    
    		'watermark',    		
    		'shrink',    		
    		'crop',    	
    		'change',
    		'deleteFile',
    		'upload',
    		), $classMethod);

    	$this->assertTrue(empty($result));
    }

    // define funny + trivial test :)
    public function test_if_files_exists(){
    	$bulletproofFileExists = file_exists(__DIR__.'/../src/bulletproof.php');
        /* more to follow (including examples, and dummy images )*/
    	$this->assertTrue($bulletproofFileExists);
    }	

}
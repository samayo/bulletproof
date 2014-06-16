<?php
// Trying to come up with ways how to test this thing. 
// if anyone can provide any idea, it would help a lot
class bulletproofTest extends \PHPUnit_Framework_TestCase 
{
    public function test_if_all_visible_class_methods_exist(){
    	$imageUploader = new ImageUploader\Bulletproof; 

    	$classMethod = get_class_methods($imageUploader);

    	$result = array_diff(
    		[
    		'fileTypes',
    		'limitSize',
    		'limitDimension',
    		'getMimeType',    	
    		'uploadDir',    
    		'watermark',    		
    		'shrink',    		
    		'crop',    	
    		'change',
    		'deleteFile',
    		'upload',
    		], $classMethod);

    	$this->assertTrue(empty($result));
    }

    // to application breakup, if folder, or file name changes. 
    public function test_if_file_exists(){
    	$bulletproofFileExists = file_exists(__DIR__.'/../src/bulletproof.php');
    	$exampleFileExists = file_exists(__DIR__.'/../examples.php');
    	$this->assertTrue($bulletproofFileExists);
    	$this->assertTrue($exampleFileExists);
    }	

}
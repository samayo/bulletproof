<?php

class overrideBulletProof extends ImageUploader\Bulletproof{


  


	public function upload($fileToUpload, $isNameProvided = null)
	{
	   /* check if 'php_exif' is enabled */       
       if(!function_exists('exif_imagetype')){
           return false; 
       }

  	   // First get the real file extension
       $this->getMimeType = $this->getMimeType($fileToUpload["name"]);

          // Check if this file type is allowed for upload
        if (!in_array($this->getMimeType, $this->imageType)) {
        	return false; 
        }

        $fileToUpload["size"] = filesize($fileToUpload["name"]);

        //Check if size (in bytes) of the image are above or below of defined in 'limitSize()' 
        if ($fileToUpload["size"] < $this->imageSize["min"] ||
             $fileToUpload["size"] > $this->imageSize["max"]
         ) {
            return false; 
         }
  
        //  check if image is valid pixel-wise.
        $pixel = $this->getPixels($fileToUpload["name"]);
        
        if($pixel["width"] < 4 || $pixel["height"] < 4){
            return false; 
        }

        if($pixel["height"] > $this->imageDimension["height"] || 
            $pixel["width"] > $this->imageDimension["width"])
        {
           return false; 
        }

        // Assign given name or generate a new one.
        $newFileName = $this->imageRename($isNameProvided);

        // create upload directory if it does not exist
        $this->uploadDir($this->uploadDir);

        $this->applyWatermark($fileToUpload);
        $this->applyShrink($fileToUpload, $fileToUpload);
        $this->applyCrop($fileToUpload, $fileToUpload);



        if(!$this->uploadDir || !$newFileName || ! $pixel || !$this->getMimeType){
        	return false; 
        }

        return true; 

        // watermark, shrink and crop 
        $this->applyWatermark($fileToUpload);
        $this->applyShrink($fileToUpload, $fileToUpload);
        $this->applyCrop($fileToUpload, $fileToUpload);

 
    }

}


class uploadTest extends \PHPUnit_Framework_TestCase 
{
	/*Test if the upload method uploads image with default settings */
	public $bulletproof;
	public $testingImage; 
	public $imageSize = [];

	function __construct(){
		$this->bulletproof = new overrideBulletProof;
		$this->testingImage = __DIR__.'/../test/monkey_pic.jpg'; 
	}


	function testSimpleUpload(){
		$image["name"] = $this->testingImage;	
		$upload = $this->bulletproof->upload($image); 
		$this->assertTrue($upload);		
	}

	/* test if it accepts image type rules as declared*/
	function testFileTypes(){
		$bulletproof = $this->bulletproof; 
		$image["name"] = $this->testingImage; 
		
		/* should not accept gif*/
		$bulletproof->fileTypes(array('gif')); 
		$upload = $bulletproof->upload($image); 
		$this->assertFalse($upload);

		/* should not accept png*/
		$bulletproof->fileTypes(array('png')); 
		$upload = $bulletproof->upload($image); 
		$this->assertFalse($upload);

		/* shouldn't accept this file */
		$bulletproof->fileTypes(array('exe')); 
		$upload = $bulletproof->upload($image); 
		$this->assertFalse($upload);

		/* example file is actually jpeg, not jpg */
		$bulletproof->fileTypes(array('png', 'jpeg')); 
		$upload = $bulletproof->upload($image); 
		$this->assertTrue($upload);
	}


	/* test if it accepts image size rules as declared */
	function testImageSize(){
		$bulletproof  = $this->bulletproof; 
		$bulletproof->limitSize(array("min"=>1, "max"=>33122));
		$image = array();
		$image["name"] = $this->testingImage; 
		$upload = $bulletproof->upload($image);
		$this->assertTrue($upload);

		/*give it invalid 'max' size*/
		$bulletproof  = $this->bulletproof; 
		$bulletproof->limitSize(array("min"=>1, "max"=>22));
		$image = array();
		$image["name"] = $this->testingImage; 
		$upload = $bulletproof->upload($image);
		$this->assertFalse($upload);	

	}


	//function testImageDimention(){}
	



}


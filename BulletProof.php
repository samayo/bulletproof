<?php

class BulletProof{
	static $allowedFileExtensions;
	static $allowedFileDimensions;
	static $allowedMaxFileSize;
	static $diretoryToUpload;
	static $fileMimeType; 
	static $newFileName; 
	
	static function set(array $allowedFileExtensions,
						array $allowedFileDimensions,
						array $allowedMaxFileSize, 
							  $diretoryToUpload){
							  
		self::$allowedFileExtensions = $allowedFileExtensions; 
		self::$allowedFileDimensions = $allowedFileDimensions;
		self::$allowedMaxFileSize = $allowedMaxFileSize;
		self::$diretoryToUpload = $diretoryToUpload;

	}
	
	static function commonFileUploadErrors(){
		return array(	
		UPLOAD_ERR_OK	=> "All is ok",
		UPLOAD_ERR_INI_SIZE 	=> "File is larger than the specifid amount set by the server",
		UPLOAD_ERR_FORM_SIZE	=> "Fils is larger than specified by browser",
		UPLOAD_ERR_PARTIAL 		=> "File not fully uploaded",
		UPLOAD_ERR_NO_FILE		=> "your file is nowhere to be found",
		UPLOAD_ERR_NO_TMP_DIR	=> "Can't write to disk, as per server configuration",
		UPLOAD_ERR_EXTENSION	=> "A PHP extention has stoped this file upload process"
		);	
	}
	
	
	static function debugEnviroment($newDir = null){

	$uploadDirectory = $newDir ? $newDir : ini_get("file_uploads");
	
	if(!is_dir($uploadDirectory)){
		return "Sorry, you don't have her majesty's permission to upload files in this server";
	}

	if(!$uploadDirectory){
		return "file directory not found. Please make sure file_uploads is on in you php.ini";
	}

	if(stripos('image', $_SERVER['HTTP_ACCEPT']) !== false){
		return 'This evil server does not seem to accept images.';
	}

	// if(!substr(sprintf('%o', fileperms($uploadDirectory)), -4) != 0777){
	// return 'this directory does not allow files to be written (uploaded)';
	// }


	}
	
	static function upload($fileToUpload, $renameFileTo = null){

	if($fileToUpload['error']){
		$commonFileUploadErrors = self::commonFileUploadErrors();
		return $commonFileUploadErrors[$fileToUpload['error']];
	}

	$fileType = substr($fileToUpload['type'], 6);
		if(!in_array($fileType, self::$allowedFileExtensions)){
			return 'this file is not supported';
	}

	self::$fileMimeType = $fileType;

	if($fileToUpload['size'] > self::$allowedMaxFileSize){
		return 'You file must be less than '.(self::$allowedMaxFileSize * 100) . ' kbytes';
	}

	list($width, $height, $type, $attr) = getimagesize($fileToUpload['tmp_name']);

	if($width > self::$allowedFileDimensions['max-width'] || $height > self::$allowedFileDimensions['max-height']){
		return "Your file must be less than". self::$allowedFileDimensions['max-width']." pixels wide and less than
				". self::$allowedFileDimensions['max-height']." pixels in height";
	}

	if($height <= 1 || $width <= 1){
		return  "This is invalid image file";	
	}


	if($renameFileTo){
		self::$newFileName = $renameFileTo; 
	}else{
		self::$newFileName = uniqid();
	}



	$newUploadDir = self::$diretoryToUpload; 
	$systemErrorCheck = self::debugEnviroment($newUploadDir); 


	if($systemErrorCheck){
		return $systemErrorCheck;
	}


	$upload = move_uploaded_file($fileToUpload['tmp_name'], $newUploadDir.'/'.self::$newFileName.'.'.self::$fileMimeType);
	return $upload ? $upload : 'File could not be uploaded. Please try again later.';
}
}

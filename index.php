<form method="POST" enctype="multipart/form-data">
	<input type="hidden" name="MAX_FILE_SIZE" value="600003333" />
	<input type="file" name="cat" />
	<input type="submit" value="upload" />
</form>


<pre>
<?php

//(new BulletProof\Image($_FILE))->upload(); 

require_once 'src/bulletproof.php';

$image = new BulletProof\Image($_FILES); 
// $image->setLocation("vacation"); 
// $image->setName("sky-diving");
$image->setSize(100, 1135000);
$image->setDimension(11110, 11220);
// $image->setType(["jpeg", "gif", "png", "jpg"]);


if($image['cat']){

	// $image->crop(100, 100); 
	//$image->resize(100, 100, true);
	$upload = $image->setLocation('ikea')->upload();
	//$upload = $image->crop(40, 40)->upload();
	//$upload = $image->watermark('map.png', 'center')->setName('RaT')->upload();
	//$upload = $image->resize(500, 500, true)->setName('ZZRT')->upload();

	if($upload){
		echo $image->name().PHP_EOL; // simon
		echo $image->size().PHP_EOL; // 56630
		echo $image->mimeType().PHP_EOL; // gif
		echo $image->width().PHP_EOL; // 400 
		echo $image->height().PHP_EOL; // 520 
		echo $image->location().PHP_EOL; // foo
		echo $image->fullPath().PHP_EOL;  // foo/simon.gif
		echo $image->serialize().PHP_EOL; //

	}else{
	
		var_dump($image->error());
	}
}



// $image->size();
// $image->name();
// $image->width();
// $image->height();
// $image->fullPath();
// $image->mimeType();
// $image->location();



// /* manipulation*/
// $image->crop();
// $image->upload();
// $image->remove();
// $image->change();
// $image->shrink();
// $image->watermark(); 



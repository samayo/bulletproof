<form method="POST" enctype="multipart/form-data">
	<input type="hidden" name="MAX_FILE_SIZE" value="600003333" />
	<input type="file" name="cat" />
	<input type="submit" value="upload" />
</form>


<pre>
<?php


require_once 'src/bulletproof.php';

$image = new BulletProof\Image($_FILES); 

$image->setName("sky-diving");
$image->setSize(100, 1132224);
$image->setDimension(11110, 11220);
$image->setMime(["png"]);


if($image['cat']){

	$upload = $image->upload();

	if($upload){
		echo $image->getName().PHP_EOL; // simon
		echo $image->getSize().PHP_EOL; // 56630
		echo $image->getMime().PHP_EOL; // gif
		echo $image->getWidth().PHP_EOL; // 400 
		echo $image->getHeight().PHP_EOL; // 520 
		echo $image->getLocation().PHP_EOL; // foo
		echo $image->getFullPath().PHP_EOL;  // foo/simon.gif
		echo $image->getJson().PHP_EOL; //

	}else{
	
		var_dump($image['error']);
	}
}

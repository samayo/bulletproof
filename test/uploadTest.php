<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use TestBootstrap\BulletproofTest; 


class uploadTest extends TestCase {
    public $bulletproof;

    /**
     *  Initialize an array to mimic the properties $_FILES global
     */
    public function setUp() : void {
      $files = array(
          'ikea' => array(
              'name' => __DIR__ . "/fixture/monkey.jpg",
              'type' => 'image/jpg',
              'tmp_name' =>  __DIR__ . "/fixture/monkey.jpg",
              'error' => 0,
              'size' => 17438,
          )
      );

      $this->bulletproof = new BulletproofTest($files);
      $this->bulletproof['ikea'];
      
    }

    // check if the name provide in the html input is read by bulletproof
    public function testImageNameIsSame () {
      $this->bulletproof->setName('samayo'); 
      $this->assertEquals($this->bulletproof->getName(), 'samayo');
    }


    // test image accepts certain mime types
    public function testMimeTypes () {
      $this->bulletproof->setMime(['jpeg']);
      $upload = $this->bulletproof->upload();
      $this->assertEquals($upload->getMime(), 'jpeg');
    }

    // check dimensions params
    public function testDimensions () {
      // give it out of range (minimum dimentions than the class requires)
      $this->bulletproof->setDimension(1, 'b');
      $upload = $this->bulletproof->upload();
      $this->assertFalse($upload);
    }


    // check size of the image is correct
    public function testSize () {
      $this->bulletproof->upload(); 
      $this->assertEquals($this->bulletproof->getSize(), 17438); // 17438 is size of the monkey.jpg
    }

    // get mime of the image
    public function testMimeType() {
      $this->bulletproof->upload();
      $this->assertEquals($this->bulletproof->getMime(), 'jpeg');
    }

    // check width and height of the image
    public function testImageSizes() {
      $this->bulletproof->upload(); 
      $isWidth = $this->bulletproof->getWidth() === 384; 
      $isHeight = $this->bulletproof->getHeight() === 345; 
      $this->assertEquals($this->bulletproof->getMime(), 'jpeg');

      $this->assertEquals($isHeight, $isWidth);
    }

    // check if setting image storage is correct
    public function testLocation () {
      $this->bulletproof->setStorage('uploads');
      $this->assertEquals($this->bulletproof->getStorage(), 'uploads');
    }


    // check full path of image uploaded

   public function testFullpath(){
        $this->bulletproof->setStorage('uploads');
        $this->bulletproof->setName('2012');
        $this->bulletproof->setMime(['jpeg']);
        $upload = $this->bulletproof->upload();
        $getMime = $this->bulletproof->getMime();
        $this->assertSame($upload->getPath(), 'uploads/2012.jpeg');
    }

 
    // check json return value of image
     public function testJsonOutput(){
        $upload = $this->bulletproof->setName('we_belive_in_json')->upload();
        $this->assertSame($upload->getJson(), 
            '{"name":"we_belive_in_json","mime":"jpeg","height":345,"width":384,"size":17438,"storage":"uploads","path":"uploads\/we_belive_in_json.jpeg"}');

    }

    // check invalid mimetype fails

    public function testMimeTypeFail () {
      $this->bulletproof->setMime(['gif']);
      $upload = $this->bulletproof->upload();
      $this->assertFalse($upload);
    }

    // check invalid mimetype fails with msg

    public function testMimeTypeFailWithMsg () {
      $this->bulletproof->setMime(['gif']);
      $upload = $this->bulletproof->upload();
      $this->assertEquals($this->bulletproof->getError(), 'Invalid File! Only (gif) image types are allowed');
    }

    // check invalid size with msg
    public function testImageSizeFailWithMsg () {
      $this->bulletproof->setSize(888, 9999);
      $upload = $this->bulletproof->upload();
       $this->assertEquals($this->bulletproof->getError(), 'Image size should be minimum 888 bytes (0 kb), upto maximum 9999 bytes (9 kb)');
    }

    // check invalid dimension fails with msg
public function testImageDimensionFailWithMsg () {
  $this->bulletproof->setDimension(42, 43);
  $this->bulletproof->upload();
    $this->assertEquals($this->bulletproof->getError(), 'Image should be smaller than 43px in height, and smaller than 42px in width');
}


}
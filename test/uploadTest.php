<?php

class uploadTest extends \PHPUnit_Framework_TestCase
{
    /*Test if the upload method uploads image with default settings */
    public $bulletproof;
    public $testingImage;
    public $imageSize = array();

    function __construct()
    {
        $this->bulletproof = new \ImageUploader\BulletProofOverride();
        $this->testingImage = __DIR__ . '/monkey_pic.jpg';
    }


    function testSimpleUpload()
    {
        $image = array('name' => $this->testingImage,
                'type' => 'image/jpg',
                'size' => 542,
                'tmp_name' => $this->testingImage,
                'error' => 0
            );
        $upload = $this->bulletproof
            ->uploadDir('/tmp')
            ->upload($image,'bulletproof_test_image');
        $this->assertEquals('/tmp/bulletproof_test_image.jpeg',$upload);
    }

    /* test if it accepts image type rules as declared*/
    function testFileTypes()
    {
        $bulletproof = $this->bulletproof->uploadDir('/tmp');
        $image = array('name' => $this->testingImage,
                       'type' => 'image/jpeg',
                       'size' => 542,
                       'tmp_name' => $this->testingImage,
                       'error' => 0
        );

        /* should not accept gif*/
        $bulletproof->fileTypes(array('gif'));
        $this->setExpectedException('ImageUploader\ImageUploaderException',' This is not allowed file type!
             Please only upload ( gif ) file types');
        $upload = $bulletproof->upload($image,'bulletproof_test_image');


        /* should not accept png*/
        $bulletproof->fileTypes(array('png'));
        $this->setExpectedException('ImageUploader\ImageUploaderException',' This is not allowed file type!
             Please only upload ( png ) file types');
        $upload = $bulletproof->upload($image,'bulletproof_test_image');

        /* shouldn't accept this file */
        $bulletproof->fileTypes(array('exe'));
        $this->setExpectedException('ImageUploader\ImageUploaderException',' This is not allowed file type!
             Please only upload ( exe ) file types');
        $upload = $bulletproof->upload($image,'bulletproof_test_image');

        /* example file is actually jpeg, not jpg */
        $bulletproof->fileTypes(array('png', 'jpeg'));
        $upload = $bulletproof->upload($image,'bulletproof_test_image');
        $this->assertEquals('uploads/bulletproof_test_image.jpeg',$upload);
    }


    /* test if it accepts image size rules as declared */
    function testImageSize()
    {
        $bulletproof = $this->bulletproof->uploadDir('/tmp');
        $bulletproof->limitSize(array("min" => 1, "max" => 33122));
        $image = array('name' => $this->testingImage,
                       'type' => 'image/jpeg',
                       'size' => 542,
                       'tmp_name' => $this->testingImage,
                       'error' => 0
        );
        $upload = $bulletproof->upload($image,'bulletproof_test_image');
        $this->assertEquals('/tmp/bulletproof_test_image.jpeg',$upload);

        /*give it invalid 'max' size*/
        $bulletproof = $this->bulletproof;
        $bulletproof->limitSize(array("min" => 1, "max" => 22));
        $image = array('name' => $this->testingImage,
                       'type' => 'image/jpeg',
                       'size' => 542,
                       'tmp_name' => $this->testingImage,
                       'error' => 0
        );
        $this->setExpectedException('ImageUploader\ImageUploaderException','File sizes must be between 1 to 22 bytes');
        $upload = $bulletproof->upload($image,'bulletproof_test_image');

    }
}
<?php

namespace Din\Upload\SimpleUpload\tests;

use Din\File\Folder;

class SimpleUploadTest extends \PHPUnit_Framework_TestCase
{

  protected $simpleUpload;

  public function __construct ()
  {

    $this->simpleUpload = new \Din\Upload\SimpleUpload\SimpleUpload;
    $test = $this->simpleUpload->getFile();
    $this->assertNull($test);

    $test = $this->simpleUpload->getMaxFileZize();
    $this->assertEquals(2097152, $test);

    $test = $this->simpleUpload->getFileType();
    $this->assertEquals('image', $test);

  }

  public function testFileNull ()
  {
    $test = $this->simpleUpload->setFile();
    $this->assertNull($test);

  }

  /**
   * @expectedException InvalidArgumentException
   */
  public function testSetFileNotArray ()
  {
    $this->simpleUpload->setFile('a');

  }

  /**
   * @expectedException InvalidArgumentException
   */
  public function testSetFileEmptyArray ()
  {
    $this->simpleUpload->setFile(array());

  }

  /**
   * @expectedException InvalidArgumentException
   */
  public function testSetFileInvalidArray ()
  {
    $this->simpleUpload->setFile(array('foo' => 'bar'));

  }

  /**
   * @expectedException InvalidArgumentException
   */
  public function testSetInvalidFileType ()
  {
    $this->simpleUpload->setFileType('exe');

  }

  public function testSetFileType ()
  {
    $this->simpleUpload->setFileType('image');
    $test = $this->simpleUpload->getFileType();
    $this->assertEquals('image', $test);

  }

  public function testSetFile ()
  {

    $_FILES = array(
        'test' => array(
            'name' => 'test.jpg',
            'type' => 'image/jpeg',
            'size' => 542,
            'tmp_name' => '',
            'error' => 0
        )
    );

    $this->simpleUpload->setFile($_FILES['test']);
    $test = $this->simpleUpload->getFile();
    $this->assertNotNull($test);

  }

  /**
   * @expectedException InvalidArgumentException
   */
  public function testSetBigFile ()
  {

    $_FILES = array(
        'test' => array(
            'name' => 'test.jpg',
            'type' => 'image/jpeg',
            'size' => 12312312312312,
            'tmp_name' => '//',
            'error' => 0
        )
    );

    $this->simpleUpload->setFile($_FILES['test']);

  }

  /**
   * @expectedException InvalidArgumentException
   */
  public function testMimeFile ()
  {

    $_FILES = array(
        'test' => array(
            'name' => 'test.jpg',
            'type' => 'pdf',
            'size' => 1000,
            'tmp_name' => '',
            'error' => 0
        )
    );

    $this->simpleUpload->setFile($_FILES['test']);

  }

  /**
   * @expectedException InvalidArgumentException
   */
  public function testExtensionFile ()
  {

    $_FILES = array(
        'test' => array(
            'name' => 'test.pdf',
            'type' => 'image/jpeg',
            'size' => 1000,
            'tmp_name' => '',
            'error' => 0
        )
    );

    $this->simpleUpload->setFile($_FILES['test']);

  }

  public function testSetMaxFileSize ()
  {
    $this->simpleUpload->setMaxFileZize(10);
    $test = $this->simpleUpload->getMaxFileZize();
    $this->assertEquals(14400000, $test);

  }

  /**
   * @expectedException InvalidArgumentException
   */
  public function testSetMaxFileSizeExceptior ()
  {
    $this->simpleUpload->setMaxFileZize('a');

  }

  public function testeSetPath ()
  {
    $this->simpleUpload->setPath(__DIR__ . '/files');
    $test = $this->simpleUpload->getPath();
    $this->assertEquals(__DIR__ . '/files', $test);
    Folder::delete(__DIR__ . '/files');

  }

  /**
   * @expectedException InvalidArgumentException
   */
  public function testeSetInvalidPath ()
  {
    $this->simpleUpload->setPath('/invalidpath');

  }

  /**
   * @depends testSetFile
   */
  public function testSetName ()
  {
    $this->simpleUpload->setName('arquivo');
    $test = $this->simpleUpload->getName();
    $this->assertNotEmpty($test);

  }

  /**
   * @depends testSetFile
   */
  public function testSetNullName ()
  {
    $this->simpleUpload->setName();
    $test = $this->simpleUpload->getName();
    $this->assertNotEmpty($test);

  }

  /* protected function createFile ()
    {
    Folder::make_writable(__DIR__ . '/uploads');
    $im = @imagecreatetruecolor(50, 100);
    imagejpeg($im, __DIR__ . '/uploads/test.jpg');

    }

    public function testMove ()
    {
    $_FILES = array(
    'test' => array(
    'name' => 'test.jpg',
    'type' => 'image/jpeg',
    'size' => 542,
    'tmp_name' => __DIR__ . '/uploads/test.jpg',
    'error' => 0
    )
    );

    $this->createFile();
    $this->simpleUpload->setFile($_FILES['test']);
    $this->simpleUpload->setPath(__DIR__ . '/files');

    } */
}

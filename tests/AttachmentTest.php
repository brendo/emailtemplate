<?php

use EmailTemplate\Attachment\Attachment;
use EmailTemplate\Interfaces as Interfaces;

class AttachmentTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->attachment = new Attachment;
    }

    public function testFilename()
    {
        $this->assertNull($this->attachment->filename());
        $this->attachment->filename('Sample Text File');
        $this->assertEquals('Sample Text File', $this->attachment->filename());
    }

    public function testContentType()
    {
        $this->assertNull($this->attachment->contentType());
        $this->attachment->contentType('text/plain');
        $this->assertEquals('text/plain', $this->attachment->contentType());
    }

    public function testDisposition()
    {
        $this->assertNull($this->attachment->disposition());
        $this->attachment->disposition('inline');
        $this->assertEquals('inline', $this->attachment->disposition());
    }

    public function testData()
    {
        $this->assertNull($this->attachment->data());
        $this->attachment->data('Hello World');
        $this->assertEquals('Hello World', $this->attachment->data());
        $this->assertFalse($this->attachment->isPath());
    }

    public function testPathData()
    {
        $this->assertNull($this->attachment->data());
        $this->attachment->data(__DIR__ . '/fixtures/hello.txt', true);
        $this->assertEquals(__DIR__ . '/fixtures/hello.txt', $this->attachment->data());
        $this->assertTrue($this->attachment->isPath());
    }
}

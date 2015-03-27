<?php

use EmailTemplate\Message as Components;
use EmailTemplate\Attachment\Attachment;
use EmailTemplate\Interfaces as Interfaces;

class EmailTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->email = new Components\Email;
    }

    public function testTo()
    {
        $this->assertNull($this->email->to());
        $this->email->to(['your-email@example.com']);
        $this->assertEquals(['your-email@example.com'], $this->email->to());
    }

    public function testCc()
    {
        $this->assertNull($this->email->cc());
        $this->email->cc(['your-email@example.com']);
        $this->assertEquals(['your-email@example.com'], $this->email->cc());
    }

    public function testBcc()
    {
        $this->assertNull($this->email->bcc());
        $this->email->bcc(['your-email@example.com']);
        $this->assertEquals(['your-email@example.com'], $this->email->bcc());
    }

    public function testFrom()
    {
        $this->assertNull($this->email->from());
        $this->email->from('system@example.com');
        $this->assertEquals(['system@example.com'], $this->email->from());
        $this->email->from('system@example.com', 'System');
        $this->assertEquals(['system@example.com' => 'System'], $this->email->from());
    }

    public function testSubject()
    {
        $this->assertNull($this->email->subject());
        $this->email->subject('Hello there');
        $this->assertEquals('Hello there', $this->email->subject());
    }

    public function testBody()
    {
        $email = new Components\Email;
        $this->assertNull($email->body());
        $email->body('Hello there mate');
        $this->assertEquals(['text/plain' => 'Hello there mate'], $email->body());
    }

    /**
     * @depends testBody
     */
    public function testRichBody()
    {
        $email = new Components\Email;
        $email->body('<strong>Hello there mate</strong>', 'text/html');
        $this->assertEquals(['text/html' => '<strong>Hello there mate</strong>'], $email->body());
    }

    /**
     * @depends testBody
     */
    public function testMulitipleBodies()
    {
        $email = new Components\Email;
        $email->body('<strong>Hello there mate</strong>', 'text/html');
        $email->body('Hello there mate');
        $this->assertEquals([
            'text/html' => '<strong>Hello there mate</strong>',
            'text/plain' => 'Hello there mate'
        ], $email->body());
    }

    public function testAttachment()
    {
        $attachment = new Attachment;
        $attachment->filename('helloworld.txt');
        $attachment->data(__DIR__ . '/fixtures/hello.txt', true);

        $this->assertEmpty($this->email->attachment());
        $this->email->attachment($attachment);
        $this->assertEquals(['helloworld.txt' => $attachment], $this->email->attachment());
    }
}

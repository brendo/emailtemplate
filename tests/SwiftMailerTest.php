<?php

use EmailTemplate\Message as Message;
use EmailTemplate\Mailer as Mailer;
use EmailTemplate\Attachment\Attachment;
use EmailTemplate\Interfaces as Interfaces;

class SwiftMailerTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        // Create a new email
        $email = new Message\Email;
        $email->to(['your-email@example.com' => 'Brendan Abbott']);
        $email->cc(['your-email@example.com']);
        $email->bcc(['your-email@example.com']);
        $email->from('system@example.com', 'The Webmaster');
        $email->subject('Test email');
        $email->body('This is the body');
        $email->body('<strong>This is the body</strong>', 'text/html');
        $this->email = $email;

        // Configuration
        $this->settings = [
            'host' => 'example.com',
            'port' => 465,
            'username' => 'example@example.com',
            'password' => 'password'
        ];

        // Mock the Swift_Mailer class's send() method to return 1
        $this->prophet = new \Prophecy\Prophet;
        $mailServer = $this->prophet->prophesize('Swift_Mailer');
        $mailServer->send(\Prophecy\Argument::cetera())->willReturn(1);
        $mailer = $mailServer->reveal();

        // Mock the SwiftMailer instance to return the mocked Swift_Mailer
        // class from the createSwiftTransport method.
        $sw = $this->getMockBuilder('EmailTemplate\Mailer\SwiftMailer')
            ->setMockClassName('SwiftMailer')
            ->setMethods(['createSwiftTransport'])
            ->getMock();

        $sw->method('createSwiftTransport')
            ->willReturn($mailer);

        $this->mailer = $sw;
    }

    protected function tearDown()
    {
        $this->prophet->checkPredictions();
    }

    public function testSetConfiguration()
    {
        $this->assertTrue($this->mailer->setConfiguration($this->settings));
    }

    public function testSend()
    {
        $this->mailer->setLogger(new \Psr\Log\NullLogger);
        $this->assertTrue($this->mailer->send($this->email));
    }

    public function testSendWithAttachmentPath()
    {
        $email = clone $this->email;

        $attachment = new Attachment;
        $attachment->filename('helloworld.txt');
        $attachment->data(__DIR__ . '/fixtures/hello.txt', true);

        $email->attachment($attachment);

        $this->assertTrue($this->mailer->send($email));
    }

    public function testSendWithAttachment()
    {
        $email = clone $this->email;

        $attachment = new Attachment;
        $attachment->filename('helloworld.txt');
        $attachment->data("Hello World");
        $attachment->contentType('text/plain');
        $attachment->disposition('attachment');

        $email->attachment($attachment);

        $this->assertTrue($this->mailer->send($email));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testBadSend()
    {
        $mailer = new Mailer\SwiftMailer;
        $this->assertTrue($mailer->send($this->email));
    }
}

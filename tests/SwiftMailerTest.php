<?php

use EmailTemplate\Message as Message;
use EmailTemplate\Mailer as Mailer;
use EmailTemplate\Interfaces as Interfaces;
use Monolog\Handler as Handler;
use Monolog\Logger;

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

        $this->email = $email;

        // Configuration
        $this->settings = [
            'host' => 'example.com',
            'port' => 465,
            'username' => 'example@example.com',
            'password' => 'password'
        ];

        // Logger
        $this->log = new Logger('email-template-test');
        $this->log->pushHandler(new Handler\TestHandler);
    }

    public function testSetConfiguration()
    {
        $mailer = new Mailer\SwiftMailer;
        $this->assertTrue($mailer->setConfiguration($this->settings));
    }

    public function testLogger()
    {
        $mailer = new Mailer\SwiftMailer;
        $this->assertFalse($mailer->canLog());
        $this->assertTrue($mailer->setLogger($this->log));
        $this->assertTrue($mailer->canLog());
        $this->assertInstanceOf('Monolog\Logger', $mailer->getLogger());
    }

    public function testSend()
    {
        $mailer = new Mailer\SwiftMailer;
        $mailer->setConfiguration($this->settings);

        $this->markTestIncomplete();
/*
        $this->assertTrue($mailer->send($this->email));
*/
    }

    /**
     * @depends testSend
     * @depends testLogger
     */
    public function testSendWithLogs()
    {
        $mailer = new Mailer\SwiftMailer;
        $mailer->setConfiguration($this->settings);
        $mailer->setLogger($this->log);

        $this->markTestIncomplete();
/*
        $this->assertTrue($mailer->send($this->email));

        $handler = $mailer->getLogger()->popHandler();
        $this->assertNotEmpty($handler->getRecords());
*/
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

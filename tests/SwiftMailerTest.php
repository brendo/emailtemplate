<?php

use EmailTemplate\Message as Message;
use EmailTemplate\Mailer as Mailer;
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

        $this->email = $email;

        // Configuration
        $this->settings = [
            'host' => 'example.com',
            'port' => 465,
            'username' => 'example@example.com',
            'password' => 'password'
        ];
    }

    public function testSetConfiguration()
    {
        $mailer = new Mailer\SwiftMailer;
        $this->assertTrue($mailer->setConfiguration($this->settings));
    }

    public function testSend()
    {
        $mailer = new Mailer\SwiftMailer;
        $mailer->setConfiguration($this->settings);
        $this->markTestIncomplete();
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

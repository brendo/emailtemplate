<?php

use EmailTemplate\Message as Message;
use EmailTemplate\Prepare as Prepare;
use EmailTemplate\Interfaces as Interfaces;

class EmailTemplateTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->path = __DIR__ . '/templates';
        $this->emailTemplate = new Message\EmailTemplate($this->path);
    }

    public function testConstructor()
    {
        $this->assertEquals($this->path, $this->emailTemplate->getTemplatePath());
    }

    public function testDefaultTemplatePath()
    {
        $emailTemplate = new Message\EmailTemplate();
        $this->assertNotNull($this->emailTemplate->getTemplatePath());
    }

    public function testSetTemplatePath()
    {
        $emailTemplate = new Message\EmailTemplate();
        $emailTemplate->setTemplatePath($this->path);
        $this->assertEquals($this->path, $this->emailTemplate->getTemplatePath());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testBadConstructor()
    {
        $emailTemplate = new Message\EmailTemplate();
        $emailTemplate->setTemplatePath();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testBadLoadNoHandle()
    {
        $this->emailTemplate->load();
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testBadLoadNoTemplate()
    {
        $this->emailTemplate->load('nonsense');
    }

    public function testLoad()
    {
        $this->assertTrue($this->emailTemplate->load('test'));
        $this->assertNotNull($this->emailTemplate->getTemplate());
    }

    public function testPrepareVSprintf()
    {
        $this->emailTemplate->load('test');
        $this->emailTemplate->prepare(new Prepare\VsprintfPrepare(), ['Developer']);
        $this->assertEquals('Hi Developer, how are you?', $this->emailTemplate->body());
        $this->assertEquals('Hi Developer, how are you?', $this->emailTemplate->getParsedTemplate());
    }

    public function testPrepareMustache()
    {
        $this->emailTemplate->load('test.mustache');
        $this->emailTemplate->prepare(new Prepare\MustachePrepare(), ['name' => 'Developer']);
        $this->assertEquals('<strong>Hi Developer</strong>', $this->emailTemplate->body());
        $this->assertEquals('<strong>Hi Developer</strong>', $this->emailTemplate->getParsedTemplate());
    }
}

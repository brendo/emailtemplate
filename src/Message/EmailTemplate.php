<?php

namespace EmailTemplate\Message;

use EmailTemplate\Interfaces as Interfaces;

class EmailTemplate extends Email implements Interfaces\TemplateInterface
{
    /**
     * Where do the templates live?
     * @var string
     */
    private $templatePath = __DIR__ . '/../../templates';

    /**
     * Holds the current email template, unparsed
     *
     * @var string
     */
    private $template;

    /**
     * Holds the parsed email template, ready for sending
     */
    private $parsedTemplate;

    /**
     * The Content Type of this email
     * @var string
     */
    protected $contentType = 'text/html';

    /**
     * Constructor optionally accepts `$templatePath`, which is the
     * directory where all templates are located.
     *
     * @param string $templatePath
     */
    public function __construct($templatePath = null)
    {
        if (!is_null($templatePath)) {
            $this->setTemplatePath($templatePath);
        }
    }

    /**
     * The constructor accepts a path to where all template files should be located.
     *
     * @throws \InvalidArgumentException if $templatePath is not provided
     * @param string $templatePath
     *  Defaults to the `templates` path of this component otherwise
     */
    public function setTemplatePath($templatePath = null)
    {
        if (is_null($templatePath)) {
            throw new \InvalidArgumentException('A template path must be set.');
        }

        $this->templatePath = realpath($templatePath);
    }

    /**
     * Returns the current template path for this email
     *
     * @return string
     */
    public function getTemplatePath()
    {
        return $this->templatePath;
    }

    /**
     * {@inheritdoc}
     */
    public function load($handle = null)
    {
        if (is_null($handle)) {
            throw new \InvalidArgumentException('A handle must be provided.');
        }

        $path = $this->templatePath . '/' . $handle . '.html';

        if (@file_exists($path) === false) {
            throw new \RuntimeException('The template ' . $handle . ' could not be loaded from ' . $path);
        }

        $this->template = file_get_contents($path);

        return true;
    }

    /**
     * Accessor the current, unparsed template.
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Allows the parsed template to be set, additionally this will
     * set the `body` of the underlying email.
     *
     * @param string $parsedTemplate
     * @return array
     */
    public function setParsedTemplate($parsedTemplate)
    {
        $this->parsedTemplate = $parsedTemplate;

        return $this->body($this->parsedTemplate, 'text/html');
    }

    /**
     * Returns the currently parsed template for this email
     *
     * @return string
     */
    public function getParsedTemplate()
    {
        return $this->parsedTemplate;
    }

    /**
     * {@inheritdoc}
     */
    public function prepare(Interfaces\PrepareInterface $render, array $data)
    {
        return $this->setParsedTemplate($render->render($this->template, $data));
    }
}

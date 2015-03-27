<?php

namespace EmailTemplate\Message;

use EmailTemplate\Interfaces as Interfaces;

class Email implements Interfaces\MessageInterface
{
    /**
     * The recipient
     *
     * @param array
     */
    private $to;

    /**
     * The cc
     *
     * @param array
     */
    private $cc;

    /**
     * The bcc
     *
     * @param array
     */
    private $bcc;

    /**
     * The sender
     *
     * @param array
     */
    private $from;

    /**
     * The subject
     *
     * @param string
     */
    private $subject;

    /**
     * The body of the email
     *
     * @param string
     */
    private $body;

    /**
     * The Content Type of this email
     * @var string
     */
    protected $contentType = 'text/plain';

    /**
     * Adds the ability to override the default content type
     * of `text/plain` with another, or to return the current
     * content type if passed with no parameters.
     *
     * @since 0.2.0
     * @param string $contentType
     * @return string
     */
    public function contentType($contentType = null)
    {
        if (isset($contentType)) {
            $this->contentType = $contentType;
        }

        return $this->contentType;
    }

    /**
     * {@inheritdoc}
     */
    public function to(array $recipients = null)
    {
        if (isset($recipients)) {
            $this->to = $recipients;
        }

        return $this->to;
    }

    /**
     * {@inheritdoc}
     */
    public function cc(array $recipients = null)
    {
        if (isset($recipients)) {
            $this->cc = $recipients;
        }

        return $this->cc;
    }

    /**
     * {@inheritdoc}
     */
    public function bcc(array $recipients = null)
    {
        if (isset($recipients)) {
            $this->bcc = $recipients;
        }

        return $this->bcc;
    }

    /**
     * {@inheritdoc}
     */
    public function from($email = null, $name = null)
    {
        if (isset($email, $name)) {
            $this->from = [$email => $name];
        } elseif (isset($email)) {
            $this->from = [$email];
        }

        return $this->from;
    }

    /**
     * {@inheritdoc}
     */
    public function subject($value = null)
    {
        if (isset($value)) {
            $this->subject = $value;
        }

        return $this->subject;
    }

    /**
     * {@inheritdoc}
     */
    public function body($value = null)
    {
        if (isset($value)) {
            $this->body = $value;
        }

        return $this->body;
    }
}

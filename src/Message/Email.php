<?php

namespace EmailTemplate\Message;

use EmailTemplate\Interfaces as Interfaces;

class Email implements Interfaces\MessageInterface
{
    /**
     * The recipient
     * @var array
     */
    private $to;

    /**
     * The cc
     * @var array
     */
    private $cc;

    /**
     * The bcc
     * @var array
     */
    private $bcc;

    /**
     * The sender
     * @var array
     */
    private $from;

    /**
     * The subject
     * @var string
     */
    private $subject;

    /**
     * The body of the message
     * @var string
     */
    private $body;

    /**
     * Any attachements for this message
     * @var array
     */
    private $attachments = [];

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
    public function body($value = null, $contentType = 'text/plain')
    {
        if (isset($value)) {
            $this->body[$contentType] = $value;
        }

        return $this->body;
    }

    /**
     * {@inheritDoc}
     */
    public function attachment(Interfaces\AttachmentInterface $attachment = null)
    {
        if (isset($attachment)) {
            $this->attachments[$attachment->filename()] = $attachment;
        }

        return $this->attachments;
    }
}

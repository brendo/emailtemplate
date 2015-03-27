<?php

namespace EmailTemplate\Attachment;

use EmailTemplate\Interfaces as Interfaces;

class Attachment implements Interfaces\AttachmentInterface
{
    /**
     * The filename of the attachment, how it should appear
     * in the mail client.
     * @var string
     */
    private $filename;

    /**
     * The content type of the attachment
     * @var string
     */
    private $contentType;

    /**
     * The disposition of the attachment
     * @var string
     */
    private $disposition;

    /**
     * The actual data of the attachment, or where the attachment lives.
     * @var mixed
     */
    private $data;

    /**
     * Is the data from a path?
     * @var boolean
     */
    private $isPath = false;

    /**
     * {@inheritdoc}
     */
    public function filename($filename = null)
    {
        if (isset($filename)) {
            $this->filename = $filename;
        }

        return $this->filename;
    }

    /**
     * {@inheritdoc}
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
    public function disposition($disposition = null)
    {
        if (isset($disposition)) {
            $this->disposition = $disposition;
        }

        return $this->disposition;
    }

    /**
     * {@inheritdoc}
     */
    public function data($data = null, $path = false)
    {
        if (isset($data, $path)) {
            $this->data = $data;
            $this->isPath = $path;
        }

        return $this->data;
    }

    /**
     * {@inheritDoc}
     */
    public function isPath()
    {
        return $this->isPath;
    }
}

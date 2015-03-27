<?php

namespace EmailTemplate\Interfaces;

interface AttachmentInterface
{
    /**
     * Sets the filename for the given attachment and
     * then returns it.
     *
     * @param string $filename
     * @return string
     */
    public function filename($filename = null);

    /**
     * Sets the content type for this attachment and then
     * returns it.
     *
     * @param string $contentType
     * @return string
     */
    public function contentType($contentType = null);

    /**
     * Sets the disposition for this attachment and then
     * returns it.
     *
     * @param string $disposition
     * @return string
     */
    public function disposition($disposition = null);

    /**
     * Sets the data for this attachment and then returns it.
     *
     * @param mixed $data
     * @param boolean $path
     *  If $path is true, `$data` is a path, otherwise, `$data`
     *  is the actual content
     * @return mixed
     */
    public function data($data = null, $path = false);

    /**
     * Returns if the specified data is a path or if it's the actual
     * data content.
     *
     * @return boolean
     */
    public function isPath();
}

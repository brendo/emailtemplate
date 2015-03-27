<?php

namespace EmailTemplate\Interfaces;

interface MessageInterface
{
    /**
     * Allow the 'recipient' to be set if `$value` is provided.
     * If `$value` does not exist, then returns the current recipient
     *
     * @param array $recipients
     * @return array
     */
    public function to(array $recipients = null);

    /**
     * Allow the 'cc' to be set if `$value` is provided.
     * If `$value` does not exist, then returns the current cc
     *
     * @param array $recipients
     * @return array
     */
    public function cc(array $recipients = null);

    /**
     * Allow the 'bcc' to be set if `$value` is provided.
     * If `$value` does not exist, then returns the current bcc
     *
     * @param array $recipients
     * @return array
     */
    public function bcc(array $recipients = null);

    /**
     * Allows the `From` email address and potentially `$name`
     * to be set if provided. Otherwise this will return
     * the from address.
     *
     * @param string $email
     * @param string $name
     * @return array
     */
    public function from($email = null, $name = null);

    /**
     * Sets the 'subject' if `$value` is provided, otherwise
     * returns the current 'subject'
     *
     * @param string $value
     * @return string
     */
    public function subject($value = null);

    /**
     * Sets the 'body' if `$value` is provided, otherwise
     * returns the current 'body'. Optionally accepts a
     * `$contentType` parameter that specifies the type of
     * body set. This will usually be 'text/plain' or 'text/html'
     *
     * @param string $value
     * @param string $contentType
     * @return array
     *  With the key being the content type the value the body string
     */
    public function body($value = null, $contentType = 'text/plain');

    /**
     * Adds an `$attachment` to this message if provided, otherwise
     * returns all attachments on this message.
     *
     * @param AttachmentInterface $attachment
     * @return array
     */
    public function attachment(AttachmentInterface $attachment = null);
}

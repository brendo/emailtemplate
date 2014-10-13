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
     * Sets the 'subject' if `$value` is provided, otherwise
     * returns the current 'subject'
     *
     * @param string $value
     * @return string
     */
    public function body($value = null);
}

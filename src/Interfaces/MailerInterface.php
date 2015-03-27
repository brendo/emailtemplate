<?php

namespace EmailTemplate\Interfaces;

use Psr\Log\LoggerAwareInterface;

interface MailerInterface extends LoggerAwareInterface
{
    /**
     * Accepts an array of configuration settings for the email client
     *
     * @param array $settings
     * @return boolean
     */
    public function setConfiguration(array $settings);

    /**
     * This function will actually send the email to the given recipients.
     * It expects an array that contains any of the following
     * keys: 'To', 'Cc', 'Bcc'.
     *
     * @param array $recipients
     * @return boolean
     */
    public function send(MessageInterface $message);
}

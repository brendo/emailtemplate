<?php

namespace EmailTemplate\Interfaces;

use Monolog\Logger as Logger;

interface MailerInterface
{
    /**
     * Accepts an array of configuration settings for the email client
     *
     * @param array $settings
     * @return boolean
     */
    public function setConfiguration(array $settings);

    /**
     * Enables logging of anything that occurs inside this class
     *
     * @param Monolog\Logger $logger
     * @return boolean
     */
    public function setLogger(Logger $logger);

    /**
     * Returns the current Log handler
     *
     * @return Monolog\Logger
     */
    public function getLogger();

    /**
     * Can this Mailer do logging?
     *
     * @return boolean
     */
    public function canLog();

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

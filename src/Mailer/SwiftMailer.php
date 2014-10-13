<?php

namespace EmailTemplate\Mailer;

use Monolog\Logger as Logger;
use EmailTemplate\Interfaces as Interfaces;

class SwiftMailer implements Interfaces\MailerInterface
{
    /**
     * The settings for the mail service
     *
     * @var array
     */
    private $settings;

    /**
     * Is there any logging to take place?
     * @var Logger
     */
    private $logger;


    /**
     * Holds the actual Swift mailer instance
     * @var Swift_Mailer
     */
    private static $mailer = null;

    /**
     * {@inheritdoc}
     */
    public function setConfiguration(array $settings)
    {
        $this->settings = $settings;

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function canLog()
    {
        return isset($this->logger);
    }

    /**
     * {@inheritdoc}
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * {@inheritdoc}
     */
    public function send(Interfaces\MessageInterface $message)
    {
        $swiftMessage = $this->createSwiftMessage($message);

        $mailer = $this->createSwiftTransport();

        $result = $mailer->send($swiftMessage, $failures);

        if ($this->canLog()) {
            if ($result === 0) {
                $this->logger->addError('Mail failed to send.', array(
                    'message' => $message,
                    'successfullySent' => $result,
                    'failedSent' => $failures
                ));
            } else {
                $this->logger->addInfo('Mail was sent.', array(
                    'message' => $message,
                    'successfullySent' => $result,
                    'failedSent' => $failures
                ));
            }
        }

        return (boolean) $result;
    }

    /**
     * Creates an SMTP transport with Swift Mailer using the
     * settings from setConfiguration.
     *
     * @throws \RuntimeException when no configuration items are provided
     * @return Swift_Mailer
     */
    public function createSwiftTransport()
    {
        if (empty($this->settings)) {
            throw new \RuntimeException('No configuration has been provided for SwiftMailer');
        }

        if (!isset(self::$mailer)) {
            $transport = \Swift_SmtpTransport::newInstance()
                ->setHost($this->settings['host'])
                ->setPort($this->settings['port'])
                ->setEncryption('ssl')
                ->setUsername($this->settings['username'])
                ->setPassword($this->settings['password']);

            self::$mailer = \Swift_Mailer::newInstance($transport);
        }

        return self::$mailer;
    }

    /**
     * Given a message, this function has the job of converting it to
     * a `Swift_Message` instance.
     *
     * @param Interfaces\MessageInterface $message
     * @return Swift_Message
     */
    private function createSwiftMessage($message)
    {
        $swiftMessage = \Swift_Message::newInstance()
            ->setSubject($message->subject())
            ->setFrom($message->from())
            ->setTo($message->to())
            ->setBody($message->body(), $message->contentType);

        if (!is_null($message->cc())) {
            $swiftMessage->setCc($message->cc());
        }

        if (!is_null($message->bcc())) {
            $swiftMessage->setBcc($message->bcc());
        }

        return $swiftMessage;
    }
}

<?php

namespace EmailTemplate\Mailer;

use EmailTemplate\Interfaces\MailerInterface;
use EmailTemplate\Interfaces\MessageInterface;
use Psr\Log\LoggerAwareTrait;
use Swift_SmtpTransport;
use Swift_Mailer;
use Swift_Message;

class SwiftMailer implements MailerInterface
{
    use LoggerAwareTrait;

    /**
     * The settings for the mail service
     *
     * @var array
     */
    private $settings;

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
    public function send(MessageInterface $message)
    {
        $swiftMessage = $this->createSwiftMessage($message);

        $mailer = $this->createSwiftTransport();

        $result = $mailer->send($swiftMessage, $failures);

        if ($this->logger) {
            if ($result === 0) {
                $this->logger->error('Mail failed to send.', [
                    'message' => $message,
                    'successfullySent' => $result,
                    'failedSent' => $failures
                ]);
            } else {
                $this->logger->info('Mail was sent.', [
                    'message' => $message,
                    'successfullySent' => $result,
                    'failedSent' => $failures
                ]);
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
            $transport = Swift_SmtpTransport::newInstance()
                ->setHost($this->settings['host'])
                ->setPort($this->settings['port'])
                ->setEncryption('ssl')
                ->setUsername($this->settings['username'])
                ->setPassword($this->settings['password']);

            self::$mailer = Swift_Mailer::newInstance($transport);
        }

        return self::$mailer;
    }

    /**
     * Given a message, this function has the job of converting it to
     * a `Swift_Message` instance.
     *
     * @param MessageInterface $message
     * @return Swift_Message
     */
    private function createSwiftMessage(MessageInterface $message)
    {
        $swiftMessage = Swift_Message::newInstance()
            ->setSubject($message->subject())
            ->setFrom($message->from())
            ->setTo($message->to());

        if (!is_null($message->cc())) {
            $swiftMessage->setCc($message->cc());
        }

        if (!is_null($message->bcc())) {
            $swiftMessage->setBcc($message->bcc());
        }

        // Handle multiple bodies so the mail client can pick the most appropriate
        $bodyCount = 0;
        $body = $message->body();
        foreach ($body as $contentType => $value) {
            // The first body is the 'main' body
            if ($bodyCount === 0) {
                $swiftMessage->setBody($value, $contentType);

            // Any further bodies are provided as alternatives.
            } else {
                $swiftMessage->addPart($value, $contentType);
            }
            $bodyCount++;
        }

        return $swiftMessage;
    }
}

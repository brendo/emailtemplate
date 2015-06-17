<?php

namespace EmailTemplate\Mailer;

use EmailTemplate\Interfaces\MailerInterface;
use EmailTemplate\Interfaces\MessageInterface;
use EmailTemplate\Interfaces\AttachmentInterface;
use Psr\Log\LoggerAwareTrait;
use Swift_SmtpTransport;
use Swift_Mailer;
use Swift_Message;
use Swift_Attachment;

class SwiftMailer implements MailerInterface
{
    use LoggerAwareTrait;

    /**
     * The settings for the mail service
     * @var array
     */
    private $settings;

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

        $transport = Swift_SmtpTransport::newInstance()
            ->setHost($this->settings['host'])
            ->setPort($this->settings['port'])
            ->setEncryption('ssl')
            ->setUsername($this->settings['username'])
            ->setPassword($this->settings['password']);

        return Swift_Mailer::newInstance($transport);
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

        // Handle attachments
        $attachments = $message->attachment();
        if (!empty($attachments)) {
            foreach ($attachments as $attachment) {
                $this->createSwiftAttachment($swiftMessage, $attachment);
            }
        }

        return $swiftMessage;
    }

    /**
     * Given an array of attachments, add them to the Swift_Message instance
     * so they will be included on the email.
     *
     * @param Swift_Message $swiftMessage
     * @param AttachmentInterface $attachment
     * @return Swift_Message
     */
    private function createSwiftAttachment(Swift_Message $swiftMessage, AttachmentInterface $attachment)
    {
        // Is the attachment an actual path or the raw content?
        if ($attachment->isPath()) {
            $swiftAttachment = Swift_Attachment::fromPath($attachment->data());

        // Handle raw content
        } else {
            $swiftAttachment = Swift_Attachment::newInstance();
            $swiftAttachment->setBody($attachment->data());
        }

        // Set the filename
        if (!is_null($attachment->filename())) {
            $swiftAttachment->setFilename($attachment->filename());
        }

        // Set the content type
        if (!is_null($attachment->contentType())) {
            $swiftAttachment->setContentType($attachment->contentType());
        }

        // Set the disposition
        if (!is_null($attachment->disposition())) {
            $swiftAttachment->setDisposition($attachment->disposition());
        }

        $swiftMessage->attach($swiftAttachment);
    }
}

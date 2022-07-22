<?php

namespace Newageerp\SfMail\Service;

use Doctrine\ORM\EntityManagerInterface;
use Newageerp\SfBaseEntity\Interface\IUser;
use Symfony\Component\Mime\MimeTypes;

abstract class MailSendService implements IMailSendService
{
    protected EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEm(): EntityManagerInterface
    {
        return $this->em;
    }

    /**
     * @param EntityManagerInterface $em
     */
    public function setEm(EntityManagerInterface $em): void
    {
        $this->em = $em;
    }

    public function prepareMail(
        string $fromName,
        string $fromEmail,
        string $subject,
        string $content,
        array  $recipients,
        ?array $files = [],
        ?IUser $creator = null,
        int    $parentId = 0,
        string $parentSchema = '',
        string $type = '',
    )
    {
        $mailClass = 'App\\Entity\\Mail';

        if ($_ENV['APP_ENV'] === 'dev') {
            $recipients = $this->emailForDevEnv();
        }

        $attachments = [];

        $mimeTypes = new MimeTypes();

        foreach ($files as $file) {
            if (!isset($file['name'])) {
                continue;
            }
            $tmpPath = sys_get_temp_dir() . '/' . str_replace('/', '', $file['name']);
            $data = file_get_contents($file['link']);
            file_put_contents($tmpPath, $data);

            $attachments[] = [
                'ContentType' => $mimeTypes->guessMimeType($tmpPath),
                'Filename' => $file['name'],
                'Base64Content' => base64_encode($data)
            ];
            unlink($tmpPath);
        }

        $mail = new $mailClass();

        $mail->setSubject($subject);
        $mail->setRecipient(implode(", ", $recipients));
        $mail->setHtml($content);

        $mail->setCreator($creator);
        $mail->setParentId($parentId);
        $mail->setParentSchema($parentSchema);
        $mail->setType($type);
        $this->getEm()->persist($mail);
        $this->getEm()->flush();

        $this->sendMail(
            $fromName,
            $fromEmail,
            $subject,
            $content,
            $recipients,
            $attachments,
            $mail->getCustomId(),
    );
    }

    public function emailForDevEnv(): array
    {
        return [
            $_ENV['SF_MAIL_DEV_TEST_EMAIL']
        ];
    }
}
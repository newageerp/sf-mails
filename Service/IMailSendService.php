<?php

namespace Newageerp\SfMail\Service;

use Newageerp\SfBaseEntity\Interface\IUser;

interface IMailSendService
{
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
    );

    public function sendMail(
        string $fromName,
        string $fromEmail,
        string $subject,
        string $content,
        array $recipients,
        ?array $attachments = [],
        string $customId = '',
    );

    public function emailForDevEnv(): array;
}
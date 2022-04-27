<?php

namespace Newageerp\SfMail\Event;

use Newageerp\SfBaseEntity\Interface\IUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;

class SfMailBeforeSendEvent extends Event
{
    public const NAME = 'sfpdf.beforesendmail';

    protected string $fromName = '';

    protected string $fromEmail = '';

    public function __construct(
        string $fromName,
        string $fromEmail,
    )
    {
        $this->fromName = $fromName;
        $this->fromEmail = $fromEmail;
    }

    /**
     * @return string
     */
    public function getFromName(): string
    {
        return $this->fromName;
    }

    /**
     * @param string $fromName
     */
    public function setFromName(string $fromName): void
    {
        $this->fromName = $fromName;
    }

    /**
     * @return string
     */
    public function getFromEmail(): string
    {
        return $this->fromEmail;
    }

    /**
     * @param string $fromEmail
     */
    public function setFromEmail(string $fromEmail): void
    {
        $this->fromEmail = $fromEmail;
    }


}
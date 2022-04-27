<?php

namespace Newageerp\SfMail\Event;

use Newageerp\SfBaseEntity\Interface\IUser;
use Newageerp\SfBaseEntity\Object\BaseUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;

class SfMailBeforeSendEvent extends Event
{
    public const NAME = 'sfpdf.beforesendmail';

    protected string $fromName = '';

    protected string $fromEmail = '';

    protected IUser $user;

    public function __construct(
        string $fromName,
        string $fromEmail,
        IUser $user,
    )
    {
        $this->fromName = $fromName;
        $this->fromEmail = $fromEmail;
        $this->user = $user;
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

    /**
     * @return BaseUser
     */
    public function getUser(): BaseUser
    {
        return $this->user;
    }

    /**
     * @param BaseUser $user
     */
    public function setUser(BaseUser $user): void
    {
        $this->user = $user;
    }


}
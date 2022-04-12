<?php

namespace Newageerp\SfMail\Event;

use Newageerp\SfBaseEntity\Interface\IUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;

class SfMailBeforeLoadEvent extends Event
{
    public const NAME = 'sfpdf.beforeloadmail';

    protected Request $request;

    protected IUser $user;

    protected object $entity;

    protected array $current;

    protected array $data;

    protected string $schema;

    public function __construct(
        Request $request,
        IUser   $user,
                $entity,
        array   $current,
        array   $data,
        string  $schema,
    )
    {
        $this->request = $request;
        $this->user = $user;
        $this->entity = $entity;
        $this->current = $current;
        $this->data = $data;
        $this->schema = $schema;
    }

    /**
     * @return string
     */
    public function getSchema(): string
    {
        return $this->schema;
    }

    /**
     * @param string $schema
     */
    public function setSchema(string $schema): void
    {
        $this->schema = $schema;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @param Request $request
     */
    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    /**
     * @return IUser
     */
    public function getUser(): IUser
    {
        return $this->user;
    }

    /**
     * @param IUser $user
     */
    public function setUser(IUser $user): void
    {
        $this->user = $user;
    }

    /**
     * @return object
     */
    public function getEntity(): object
    {
        return $this->entity;
    }

    /**
     * @param object $entity
     */
    public function setEntity(object $entity): void
    {
        $this->entity = $entity;
    }

    /**
     * @return array
     */
    public function getCurrent(): array
    {
        return $this->current;
    }

    /**
     * @param array $current
     */
    public function setCurrent(array $current): void
    {
        $this->current = $current;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }
}
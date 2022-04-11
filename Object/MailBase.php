<?php
namespace Newageerp\SfMail\Object;

use Doctrine\ORM\Mapping as ORM;
use Newageerp\SfBaseEntity\Object\BaseEntity;
use OpenApi\Annotations as OA;

class MailBase extends BaseEntity
{
    /**
     * @ORM\Column(type="integer")
     */
    protected int $parentId;

    /**
     * @ORM\Column(type="string")
     */
    protected string $parentSchema;

    /**
     * @ORM\Column(type="string")
     */
    protected string $type;

    /**
     * @ORM\Column(type="string")
     */
    protected string $recipient;

    /**
     * @ORM\Column(type="string")
     */
    protected string $subject;

    /**
     * @OA\Property(format="text")
     * @ORM\Column(type="text")
     */
    protected string $html;

    /**
     * @ORM\Column(type="string")
     */
    protected string $customId = '';

    /**
     * @return int
     */
    public function getParentId(): int
    {
        return $this->parentId;
    }

    /**
     * @param int $parentId
     */
    public function setParentId(int $parentId): void
    {
        $this->parentId = $parentId;
    }

    /**
     * @return string
     */
    public function getParentSchema(): string
    {
        return $this->parentSchema;
    }

    /**
     * @param string $parentSchema
     */
    public function setParentSchema(string $parentSchema): void
    {
        $this->parentSchema = $parentSchema;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getRecipient(): string
    {
        return $this->recipient;
    }

    /**
     * @param string $recipient
     */
    public function setRecipient(string $recipient): void
    {
        $this->recipient = $recipient;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getHtml(): string
    {
        return $this->html;
    }

    /**
     * @param string $html
     */
    public function setHtml(string $html): void
    {
        $this->html = $html;
    }

    /**
     * @return string
     */
    public function getCustomId(): string
    {
        return $this->customId;
    }

    /**
     * @param string $customId
     */
    public function setCustomId(string $customId): void
    {
        $this->customId = $customId;
    }

    /**
     * @return string
     */
    public function getLastStatus(): string
    {
        return $this->lastStatus;
    }

    /**
     * @param string $lastStatus
     */
    public function setLastStatus(string $lastStatus): void
    {
        $this->lastStatus = $lastStatus;
    }

    /**
     * @ORM\Column(type="string")
     */
    protected string $lastStatus = '';
}
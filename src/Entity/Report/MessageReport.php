<?php

namespace App\Entity\Report;

use App\Entity\User;
use App\Repository\Report\MessageReportRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageReportRepository::class)]
class MessageReport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $isGroupMessage = null;

    #[ORM\Column]
    private ?int $descolarId = null;

    #[ORM\Column(length: 2000)]
    private ?string $content = null;

    #[ORM\Column]
    private ?int $message_id = null;

    #[ORM\Column(length: 150)]
    private ?string $user_name = null;

    #[ORM\Column(type: Types::GUID)]
    private ?string $user_uuid = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?ReportCategory $reportCategory = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $comment = null;

    #[ORM\Column(options: ["default" => 0])]
    private ?bool $treating = false;

    #[ORM\ManyToOne]
    private ?User $admin_processing = null;

    #[ORM\Column(options: ["default" => 0])]
    private ?bool $ignored = false;

    #[ORM\Column(options: ["default" => 0])]
    private ?bool $deleted = false;

    #[ORM\Column(options: ["default" => 0])]
    private ?bool $user_ban = false;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $result_date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getIsGroupMessage(): ?bool
    {
        return $this->isGroupMessage;
    }

    public function setIsGroupMessage(?bool $isGroupMessage): void
    {
        $this->isGroupMessage = $isGroupMessage;
    }

    public function getDescolarId(): ?int
    {
        return $this->descolarId;
    }

    public function setDescolarId(?int $descolarId): void
    {
        $this->descolarId = $descolarId;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    public function getMessageId(): ?int
    {
        return $this->message_id;
    }

    public function setMessageId(?int $message_id): void
    {
        $this->message_id = $message_id;
    }

    public function getUserName(): ?string
    {
        return $this->user_name;
    }

    public function setUserName(?string $user_name): void
    {
        $this->user_name = $user_name;
    }

    public function getUserUuid(): ?string
    {
        return $this->user_uuid;
    }

    public function setUserUuid(?string $user_uuid): void
    {
        $this->user_uuid = $user_uuid;
    }

    public function getReportCategory(): ?ReportCategory
    {
        return $this->reportCategory;
    }

    public function setReportCategory(?ReportCategory $reportCategory): void
    {
        $this->reportCategory = $reportCategory;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): void
    {
        $this->date = $date;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    public function getTreating(): ?bool
    {
        return $this->treating;
    }

    public function setTreating(?bool $treating): void
    {
        $this->treating = $treating;
    }

    public function getAdminProcessing(): ?User
    {
        return $this->admin_processing;
    }

    public function setAdminProcessing(?User $admin_processing): void
    {
        $this->admin_processing = $admin_processing;
    }

    public function getIgnored(): ?bool
    {
        return $this->ignored;
    }

    public function setIgnored(?bool $ignored): void
    {
        $this->ignored = $ignored;
    }

    public function getDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(?bool $deleted): void
    {
        $this->deleted = $deleted;
    }

    public function getUserBan(): ?bool
    {
        return $this->user_ban;
    }

    public function setUserBan(?bool $user_ban): void
    {
        $this->user_ban = $user_ban;
    }

    public function getResultDate(): ?\DateTimeInterface
    {
        return $this->result_date;
    }

    public function setResultDate(?\DateTimeInterface $result_date): void
    {
        $this->result_date = $result_date;
    }
}

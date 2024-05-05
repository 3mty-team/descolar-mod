<?php

namespace App\Entity\Report;

use App\Entity\User;
use App\Repository\Report\UserReportRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserReportRepository::class)]
class UserReport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(unique: true)]
    private ?int $descolarId = null;

    #[ORM\Column(length: 150)]
    private ?string $user_name = null;

    #[ORM\Column(type: Types::GUID)]
    private ?string $user_uuid = null;

    #[ORM\ManyToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?ReportCategory $report_category = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $comment = null;

    #[ORM\Column(options: ["default" => 0])]
    private ?bool $treating = null;

    #[ORM\ManyToOne(cascade: ['persist', 'remove'])]
    private ?User $admin_processing = null;

    #[ORM\Column(options: ["default" => 0])]
    private ?bool $banned = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $result_date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getDescolarId(): ?int
    {
        return $this->descolarId;
    }

    public function setDescolarId(?int $descolarId): void
    {
        $this->descolarId = $descolarId;
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
        return $this->report_category;
    }

    public function setReportCategory(ReportCategory $report_category): static
    {
        $this->report_category = $report_category;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function isTreating(): ?bool
    {
        return $this->treating;
    }

    public function setTreating(bool $treating): static
    {
        $this->treating = $treating;

        return $this;
    }

    public function getAdminProcessing(): ?User
    {
        return $this->admin_processing;
    }

    public function setAdminProcessing(?User $admin_processing): static
    {
        $this->admin_processing = $admin_processing;

        return $this;
    }

    public function isBanned(): ?bool
    {
        return $this->banned;
    }

    public function setBanned(bool $banned): static
    {
        $this->banned = $banned;

        return $this;
    }

    public function getResultDate(): ?\DateTimeInterface
    {
        return $this->result_date;
    }

    public function setResultDate(?\DateTimeInterface $result_date): static
    {
        $this->result_date = $result_date;

        return $this;
    }

    public function toString(): string
    {
        return "Id : " . $this->getId()
            . ", User uuid : " . $this->getUserUuid()
            . ", User name : " . $this->getUserName()
            . ", Category : " . $this->getReportCategory()->getName()
            . ", Date : " . $this->getDate()->format('d/m/Y H:i:s')
            . ", Comment : " . (is_null($this->getComment()) ? "null" : $this->getComment())
            . ", Has the issue been treated yet ? : " . ($this->isTreating() ? "Yes" : "No")
            . ", Admin : " . (is_null($this->getAdminProcessing()) ? null : $this->getAdminProcessing()->getUsername())
            . ", Banned ? : " . ($this->isBanned() ? "Yes" : "No")
            . ", Result date : " . (is_null($this->getResultDate()) ? null : $this->getResultDate()->format('d/m/Y H:i:s'));
    }
}

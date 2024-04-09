<?php

namespace App\Entity\Report;

use App\Entity\User;
use App\Repository\Report\PostReportRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostReportRepository::class)]
class PostReport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $post_id = null;

    #[ORM\Column(type: Types::GUID)]
    private ?string $user_id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?ReportCategory $report_category = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $comment = null;

    #[ORM\Column(options: ["default" => 0])]
    private ?bool $treating = null;

    #[ORM\ManyToOne]
    private ?User $admin_processing = null;

    #[ORM\Column(options: ["default" => 0])]
    private ?bool $ignored = null;

    #[ORM\Column(options: ["default" => 0])]
    private ?bool $deleted = null;

    #[ORM\Column(options: ["default" => 0])]
    private ?bool $user_ban = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $result_date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPostId(): ?int
    {
        return $this->post_id;
    }

    public function setPostId(int $post_id): static
    {
        $this->post_id = $post_id;

        return $this;
    }

    public function getUserId(): ?string
    {
        return $this->user_id;
    }

    public function setUserId(string $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getReportCategory(): ?ReportCategory
    {
        return $this->report_category;
    }

    public function setReportCategory(?ReportCategory $report_category): static
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

    public function isIgnored(): ?bool
    {
        return $this->ignored;
    }

    public function setIgnored(bool $ignored): static
    {
        $this->ignored = $ignored;

        return $this;
    }

    public function isDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): static
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function isUserBan(): ?bool
    {
        return $this->user_ban;
    }

    public function setUserBan(bool $user_ban): static
    {
        $this->user_ban = $user_ban;

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
}

<?php

namespace App\Entity;

use App\Repository\UnbanRequestRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UnbanRequestRepository::class)]
class UnbanRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::GUID)]
    private ?string $user_id = null;

    #[ORM\OneToOne(inversedBy: 'unbanRequest', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?ReportCategory $report_category = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $comment = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?User $admin_processing = null;

    #[ORM\Column(options: ["default" => 0])]
    private ?bool $unban = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $result_date = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAdminProcessing(): ?User
    {
        return $this->admin_processing;
    }

    public function setAdminProcessing(?User $admin_processing): static
    {
        $this->admin_processing = $admin_processing;

        return $this;
    }

    public function isUnban(): ?bool
    {
        return $this->unban;
    }

    public function setUnban(bool $unban): static
    {
        $this->unban = $unban;

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

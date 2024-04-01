<?php

namespace App\Entity;

use App\Repository\ReportCategoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReportCategoryRepository::class)]
class ReportCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 60)]
    private ?string $name = null;

    #[ORM\Column(options: ["default" => 1])]
    private ?bool $is_active = null;

    #[ORM\OneToOne(mappedBy: 'report_category', cascade: ['persist', 'remove'])]
    private ?UnbanRequest $unbanRequest = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->is_active;
    }

    public function setIsActive(bool $is_active): static
    {
        $this->is_active = $is_active;

        return $this;
    }

    public function getUnbanRequest(): ?UnbanRequest
    {
        return $this->unbanRequest;
    }

    public function setUnbanRequest(UnbanRequest $unbanRequest): static
    {
        // set the owning side of the relation if necessary
        if ($unbanRequest->getReportCategory() !== $this) {
            $unbanRequest->setReportCategory($this);
        }

        $this->unbanRequest = $unbanRequest;

        return $this;
    }
}

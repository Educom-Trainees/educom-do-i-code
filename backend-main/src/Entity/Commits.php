<?php

namespace App\Entity;

use App\Repository\CommitsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommitsRepository::class)]
class Commits
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $issue_id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $start = null;

    #[ORM\Column(length: 255)]
    private ?string $message = null;

    #[ORM\ManyToOne(inversedBy: 'commits')]
    private ?Issues $has = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIssueId(): ?int
    {
        return $this->issue_id;
    }

    public function setIssueId(?int $issue_id): static
    {
        $this->issue_id = $issue_id;

        return $this;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): static
    {
        $this->start = $start;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getHas(): ?Issues
    {
        return $this->has;
    }

    public function setHas(?Issues $has): static
    {
        $this->has = $has;

        return $this;
    }
}

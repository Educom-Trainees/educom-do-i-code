<?php

namespace App\Entity;

use App\Repository\IssuesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IssuesRepository::class)]
class Issues
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'issues')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TraineeRepo $trainee_repo = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $start_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $end_date = null;

    #[ORM\Column(nullable: true)]
    private ?int $number_of_commits = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $issueNumber = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTraineeRepo(): ?TraineeRepo
    {
        return $this->trainee_repo;
    }

    public function setTraineeRepo(?TraineeRepo $trainee_repo): static
    {
        $this->trainee_repo = $trainee_repo;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTimeInterface $start_date): static
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(?\DateTimeInterface $end_date): static
    {
        $this->end_date = $end_date;

        return $this;
    }

    public function getNumberOfCommits(): ?int
    {
        return $this->number_of_commits;
    }

    public function setNumberOfCommits(?int $number_of_commits): static
    {
        $this->number_of_commits = $number_of_commits;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getIssueNumber(): ?int
    {
        return $this->issueNumber;
    }

    public function setIssueNumber(int $issueNumber): static
    {
        $this->issueNumber = $issueNumber;

        return $this;
    }
}

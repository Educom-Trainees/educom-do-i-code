<?php

namespace App\Entity;

use App\Repository\TraineeRepoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TraineeRepoRepository::class)]
class TraineeRepo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'TraineeRepo')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Trainee $trainee = null;

    #[ORM\ManyToOne(inversedBy: 'TraineeRepo')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Repo $repo = null;

    #[ORM\OneToMany(mappedBy: 'repo_trainee', targetEntity: Issues::class)]
    private Collection $issues;

    public function __construct()
    {
        $this->issues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTrainee(): ?Trainee
    {
        return $this->trainee;
    }

    public function setTrainee(Trainee $trainee): static
    {
        $this->trainee = $trainee;

        return $this;
    }

    public function getRepo(): ?Repo
    {
        return $this->repo;
    }

    public function setRepo(Repo $repo): static
    {
        $this->repo = $repo;

        return $this;
    }

    /**
     * @return Collection<int, Issues>
     */
    public function getIssues(): Collection
    {
        return $this->issues;
    }

    public function addIssue(Issues $issue): static
    {
        if (!$this->issues->contains($issue)) {
            $this->issues->add($issue);
            $issue->setTraineeRepo($this);
        }

        return $this;
    }

    public function removeIssue(Issues $issue): static
    {
        if ($this->issues->removeElement($issue)) {
            // set the owning side to null (unless already changed)
            if ($issue->getTraineeRepo() === $this) {
                $issue->setTraineeRepo(null);
            }
        }

        return $this;
    }
}

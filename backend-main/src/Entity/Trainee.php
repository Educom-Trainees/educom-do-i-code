<?php

namespace App\Entity;

use App\Repository\TraineeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TraineeRepository::class)]
class Trainee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column] 
    private ?int $traineeID = null;

    #[ORM\Column(length: 255)]
    private ?string $avatar_url = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getTraineeID(): ?int
    {
        return $this->traineeID;
    }

    public function getAvatarURL(): ?string
    {
        return $this->avatar_url;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function setTraineeID(int $traineeID): static
    {
        $this->traineeID = $traineeID;

        return $this;
    }

    public function setAvatarURL(string $avatar_url): static
    {
        $this->avatar_url = $avatar_url;

        return $this;
    }
}

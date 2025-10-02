<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;//jeb orm 

#[ORM\Entity(repositoryClass: UserRepository::class)]//entite 3taha repos
class User
{
    #[ORM\Id]//raho cle 1ere
    #[ORM\GeneratedValue]//id outo increm
    #[ORM\Column]//ajouter fonction 
    private ?int $id = null;

    #[ORM\Column]
    private ?int $age = null; // outomatique auto incrementa

    public function getId(): ?int// set /set attribe praivat
    {
        return $this->id;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): static
    {
        $this->age = $age;

        return $this;
    }
}

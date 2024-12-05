<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\TestingRepository;
use App\State\TestProvider;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TestingRepository::class)]
#[ApiResource(operations: [(new GetCollection(provider: TestProvider::class))])]
class Testing
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}

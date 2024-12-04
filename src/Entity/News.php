<?php

   namespace App\Entity;

   use ApiPlatform\Metadata\ApiResource;
   use ApiPlatform\Metadata\GetCollection;
   use ApiPlatform\Metadata\QueryParameter;
   use App\Repository\NewsRepository;
   use App\State\DataProvider;
   use Doctrine\ORM\Mapping as ORM;
   use ApiPlatform\OpenApi\Model\Parameter;


   #[ORM\Entity(repositoryClass: NewsRepository::class)]
   #[QueryParameter(
       key: 'max',
       schema: ['type' => 'interger'],
       openApi: new Parameter(name: 'max', in: 'query', allowEmptyValue: false, example: 3),
       required: true
   )] #[ApiResource(
       operations: [
           new GetCollection(
               provider: DataProvider::class,
           ),
       ]
   )]
   class News
   {
      #[ORM\Id]
      #[ORM\GeneratedValue]
      #[ORM\Column]
      private ?int $id = null;

      #[ORM\Column(length: 255, nullable: true)]
      private ?string $title = null;

      public function getId(): ?int
      {
         return $this->id;
      }

      public function getTitle(): ?string
      {
         return $this->title;
      }

      public function setTitle(?string $title): static
      {
         $this->title = $title;

         return $this;
      }
   }

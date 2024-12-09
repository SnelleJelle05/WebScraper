<?php

   namespace App\Entity;

   use ApiPlatform\Metadata\ApiResource;
   use ApiPlatform\Metadata\GetCollection;
   use ApiPlatform\Metadata\QueryParameter;
   use ApiPlatform\OpenApi\Model\Parameter;
   use App\Repository\NewsRepository;
   use App\State\Providers\News\DataProvider;
   use Doctrine\DBAL\Types\Types;
   use Doctrine\ORM\Mapping as ORM;
   use Symfony\Bridge\Doctrine\Types\UuidType;
   use Symfony\Component\Serializer\Attribute\Groups;
   use Symfony\Component\Uid\Uuid;

   #[ORM\Entity(repositoryClass: NewsRepository::class)]
   #[QueryParameter(
       key: 'max',
       schema: ['type' => 'interger'],
       openApi: new Parameter(name: 'max', in: 'query', allowEmptyValue: false, example: 25),
       required: true)]
   #[QueryParameter(
       key: 'Language/Location',
       schema: ['type' => 'string'],
       openApi: new Parameter(name: 'Language/Location', in: 'query', allowEmptyValue: false, example: 'unitedStates'),
       required: true
   )] #[ApiResource(
       operations: [
           new GetCollection(
               formats: ['json'],
               description: "Get news articles from the web",
               normalizationContext: ['groups' => ['user:read']],
               provider: DataProvider::class,
           ),
       ]
   )]
   class News
   {
      #[ORM\Id]
      #[ORM\Column(type: UuidType::NAME, unique: true)]
      #[ORM\GeneratedValue(strategy: 'CUSTOM')]
      #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
      #[Groups(['user:read'])]
      private ?Uuid $id = null;

      #[ORM\Column(length: 255, nullable: true)]
      #[Groups(['user:read'])]
      private ?string $title = null;

      #[ORM\Column(type: Types::TEXT, nullable: true)]
      #[Groups(['user:read'])]
      private ?string $Description = null;

      #[ORM\Column(length: 255, nullable: true)]
      #[Groups(['user:read'])]
      private ?string $Sentiment = null;

      #[ORM\Column(length: 255, nullable: true)]
      #[Groups(['user:read'])]
      private ?string $Source = null;

      #[ORM\Column(type: Types::TEXT, nullable: true)]
      #[Groups(['user:read'])]
      private ?string $ImageUrl = null;

      #[ORM\Column(length: 255, nullable: true)]
      #[Groups(['user:read'])]
      private ?string $Date = null;

      #[ORM\Column(type: Types::TEXT, nullable: true)]
      #[Groups(['user:read'])]
      private ?string $website_url = null;

      #[ORM\Column(length: 255)]
      private ?string $language = null;

      public function getId(): ?Uuid
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

      public function getDescription(): ?string
      {
         return $this->Description;
      }

      public function setDescription(?string $Description): static
      {
         $this->Description = $Description;

         return $this;
      }

      public function getSource(): ?string
      {
         return $this->Source;
      }

      public function setSource(?string $Source): static
      {
         $this->Source = $Source;

         return $this;
      }

      public function getImageUrl(): ?string
      {
         return $this->ImageUrl;
      }

      public function setImageUrl(?string $ImageUrl): static
      {
         $this->ImageUrl = $ImageUrl;

         return $this;
      }

      public function getDate(): ?string
      {
         return $this->Date;
      }

      public function setDate(?string $Date): static
      {
         $this->Date = $Date;

         return $this;
      }

      public function getSentiment(): ?string
      {
         return $this->Sentiment;
      }

      public function setSentiment(?string $Sentiment): static
      {
         $this->Sentiment = $Sentiment;
         return $this;
      }


      public function getWebsiteUrl(): ?string
      {
         return $this->website_url;
      }

      public function setWebsiteUrl(string $website_url): static
      {
         $this->website_url = $website_url;

         return $this;
      }

      public function getLanguage(): ?string
      {
         return $this->language;
      }

      public function setLanguage(string $language): static
      {
         $this->language = $language;

         return $this;
      }
   }

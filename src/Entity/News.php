<?php

   namespace App\Entity;

   use ApiPlatform\Metadata\ApiResource;
   use ApiPlatform\Metadata\GetCollection;
   use ApiPlatform\Metadata\QueryParameter;
   use ApiPlatform\OpenApi\Model\Parameter;
   use App\Repository\UserRepository;
   use App\State\Providers\News\DataProvider;
   use App\State\Providers\News\NewsDataBaseProvider;
   use Doctrine\DBAL\Types\Types;
   use Doctrine\ORM\Mapping as ORM;
   use Symfony\Bridge\Doctrine\Types\UuidType;
   use Symfony\Component\Serializer\Attribute\Groups;
   use Symfony\Component\Uid\Uuid;


   #[
       ORM\Entity(repositoryClass: UserRepository::class)]
   #[ORM\Table(name: '`news`')]
   #[ApiResource(
       operations: [
           new GetCollection(
               uriTemplate: '/v1/news',
               description: "Get news articles from the database",
               normalizationContext: ['groups' => ['user:read']],
               provider: NewsDataBaseProvider::class,
               parameters: [
                   new QueryParameter(
                       key: 'max',
                       schema: ['type' => 'interger'],
                       openApi: new Parameter(name: 'max', in: 'query', allowEmptyValue: false, example: 25),
                       required: false),
                   new QueryParameter(
                       key: 'apiKey',
                       schema: ['type' => 'string'],
                       openApi: new Parameter(name: 'apiKey', in: 'query', allowEmptyValue: false, example: 'xxxxx-xxxxx-xxxxx-xxxxx-xxxxx'),
                       required: true),
                   new QueryParameter(
                       key: 'language',
                       schema: ['type' => 'string'],
                       openApi: new Parameter(name: 'language', in: 'query', allowEmptyValue: false, example: 'English'),
                       required: false),
                   new QueryParameter(
                       key: 'sourceCountry',
                       schema: ['type' => 'string'],
                       openApi: new Parameter(name: 'sourceCountry', in: 'query', allowEmptyValue: false, example: 'United States'),
                       required: false),
               ]
           )
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
      private ?string $description = null;

      #[ORM\Column(length: 255, nullable: true)]
      #[Groups(['user:read'])]
      private ?string $sentiment = null;

      #[ORM\Column(length: 255, nullable: true)]
      #[Groups(['user:read'])]
      private ?string $source = null;

      #[ORM\Column(type: Types::TEXT, nullable: true)]
      #[Groups(['user:read'])]
      private ?string $imageUrl = null;

      #[ORM\Column(length: 255, nullable: true)]
      #[Groups(['user:read'])]
      private ?string $date = null;

      #[ORM\Column(type: Types::TEXT, nullable: true)]
      #[Groups(['user:read'])]
      private ?string $websiteUrl = null;

      #[ORM\Column(length: 255)]
      #[Groups(['user:read'])]
      private ?string $language = null;

      #[ORM\Column(length: 255, nullable: true)]
      private ?string $sourceCountry = null;

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
         return $this->description;
      }

      public function setDescription(?string $description): static
      {
         $this->description = $description;

         return $this;
      }

      public function getSource(): ?string
      {
         return $this->source;
      }

      public function setSource(?string $source): static
      {
         $this->source = $source;

         return $this;
      }

      public function getImageUrl(): ?string
      {
         return $this->imageUrl;
      }

      public function setImageUrl(?string $ImageUrl): static
      {
         $this->imageUrl = $ImageUrl;

         return $this;
      }

      public function getDate(): ?string
      {
         return $this->date;
      }

      public function setDate(?string $date): static
      {
         $this->date = $date;

         return $this;
      }

      public function getSentiment(): ?string
      {
         return $this->sentiment;
      }

      public function setSentiment(?string $Sentiment): static
      {
         $this->sentiment = $Sentiment;
         return $this;
      }

      public function getWebsiteUrl(): ?string
      {
         return $this->websiteUrl;
      }

      public function setWebsiteUrl(string $websiteUrl): static
      {
         $this->websiteUrl = $websiteUrl;

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

      public function getSourceCountry(): ?string
      {
         return $this->sourceCountry;
      }

      public function setSourceCountry(?string $sourceCountry): static
      {
         $this->sourceCountry = $sourceCountry;

         return $this;
      }
   }

<?php

   namespace App\Entity;

   use ApiPlatform\Metadata\ApiResource;
   use ApiPlatform\Metadata\Get;
   use ApiPlatform\Metadata\GetCollection;
   use ApiPlatform\Metadata\Post;
   use App\Repository\UserRepository;
   use App\State\Providers\GeneratePersonalAccessTokenProvider;
   use Doctrine\ORM\Mapping as ORM;
   use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
   use Symfony\Component\Security\Core\User\UserInterface;
   use Symfony\Component\Serializer\Attribute\Groups;
   use Symfony\Component\Serializer\Attribute\SerializedName;
   use Symfony\Component\Validator\Constraints\Email;
   use Symfony\Component\Validator\Constraints\NotBlank;

   #[ORM\Entity(repositoryClass: UserRepository::class)]
   #[ORM\Table(name: '`user`')]
   #[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
   #[ApiResource(
       operations: [
           new Get(
               uriTemplate: '/users/GeneratePersonalAccessToken',
               name: 'GeneratePersonalAccessToken!',
               provider: GeneratePersonalAccessTokenProvider::class
           ),
           new Post(),
       ],
       normalizationContext: ['groups' => ['user:read']],
       denormalizationContext: ['groups' => ['user:write']]
   )]
   class User implements UserInterface, PasswordAuthenticatedUserInterface
   {
      #[ORM\Id]
      #[ORM\GeneratedValue]
      #[ORM\Column]
      #[Groups(['user:read'])]
      private ?int $id = null;

      #[ORM\Column(length: 180)]
      #[NotBlank]
      #[Groups(['user:read', 'user:write'])]
      #[Email]
      private ?string $email = null;

      /**
       * @var list<string> The user roles
       */
      #[ORM\Column]
      #[Groups(['user:read'])]
      private array $roles = [];

      #[ORM\Column]
      #[Groups(['user:read'])]
      private ?string $password = null;

      #[NotBlank]
      #[SerializedName('password')]
      #[Groups(['user:write'])]
      private ?string $plainPassword = null;

      #[ORM\OneToOne(mappedBy: 'relatedUser', cascade: ['persist', 'remove'])]
      private ?PersonalAccessToken $personalAccessToken = null;

      public function getId(): ?int
      {
         return $this->id;
      }

      public function getEmail(): ?string
      {
         return $this->email;
      }

      public function setEmail(string $email): static
      {
         $this->email = $email;

         return $this;
      }

      /**
       * A visual identifier that represents this user.
       *
       * @see UserInterface
       */
      public function getUserIdentifier(): string
      {
         return (string)$this->email;
      }

      /**
       * @return list<string>
       * @see UserInterface
       *
       */
      public function getRoles(): array
      {
         $roles = $this->roles;
         // guarantee every user at least has ROLE_USER
         $roles[] = 'ROLE_USER';

         return array_unique($roles);
      }

      /**
       * @param list<string> $roles
       */
      public function setRoles(array $roles): static
      {
         $this->roles = $roles;

         return $this;
      }

      /**
       * @see PasswordAuthenticatedUserInterface
       */
      public function getPassword(): ?string
      {
         return $this->password;
      }

      public function setPassword(string $password): static
      {
         $this->password = $password;

         return $this;
      }

      public function getPlainPassword(): ?string
      {
         return $this->plainPassword;
      }

      public function setPlainPassword(?string $plainPassword): void
      {
         $this->plainPassword = $plainPassword;
      }


      /**
       * @see UserInterface
       */
      public function eraseCredentials(): void
      {
         $this->plainPassword = null;
      }

      public function getPersonalAccessToken(): ?PersonalAccessToken
      {
         return $this->personalAccessToken;
      }
   }

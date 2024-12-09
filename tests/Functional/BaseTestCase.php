<?php

   namespace App\Tests\Functional;

   use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
   use function Zenstruck\Foundry\faker;

   class BaseTestCase extends WebTestCase
   {


      protected \Symfony\Bundle\FrameworkBundle\KernelBrowser $client;
      public string $password = "password";

      protected function setUp(): void
      {
         parent::setUp();
         $this->client = static::createClient();
         $this->client->setServerParameter('CONTENT_TYPE', 'application/json');
         $this->client->setServerParameter('HTTP_ACCEPT', 'application/ld+json');
         $this->client->setServerParameter('HTTP_ORIGIN', 'http://phpunit');
      }

      public function jsonResponse(): array
      {
         $content = $this->client->getResponse()->getContent();
         $decoded = json_decode($content, true);
         if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Invalid JSON response: ' . json_last_error_msg());
         }
         return $decoded;
      }

      public function getToken(): string
      {
         $responseContent = $this->jsonResponse();
         return $responseContent['token'];
      }

      public function get(string $uri, array $content, string $token = null): void
      {
         $server = [];
         if (isset($token)) {
            $server['HTTP_AUTHORIZATION'] = 'Bearer ' . $token;
         }

         $this->client->jsonRequest('GET', $uri, $content, $server);
      }

      public function post(string $uri, array $content, string $token = null): void
      {
         $server = ['CONTENT_TYPE' => 'application/json'];
         if (isset($token)) {
            $server['HTTP_AUTHORIZATION'] = 'Bearer ' . $token;
         }

         $this->client->jsonRequest('POST', $uri, $content, $server);
      }

      public function GetNewUser(): array|null
      {
         $this->client->jsonRequest('POST', '/api/users', ['username' => faker()->userName(), 'plainPassword' => $this->password]);
         $responseContent = $this->client->getResponse()->getContent();
         return json_decode($responseContent, true);
      }

      public function patch(string $uri, array $content, ?string $token): void
      {
         $server = ['CONTENT_TYPE' => 'application/merge-patch+json'];

         if (isset($token)) {
            $server = ['CONTENT_TYPE' => 'application/merge-patch+json', "HTTP_AUTHORIZATION" => 'Bearer ' . $token];

         }
         $this->client->jsonRequest('PATCH', $uri, $content, $server);
      }

      public function delete(string $uri): void
      {
         $this->client->jsonRequest('DELETE', $uri);
      }
   }
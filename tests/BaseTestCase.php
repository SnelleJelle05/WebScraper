<?php

   namespace App\Tests;

   use Exception;
   use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
   use function Zenstruck\Foundry\faker;

   class BaseTestCase extends WebTestCase
   {

      protected \Symfony\Bundle\FrameworkBundle\KernelBrowser $client;
      public string $password = "password";

      protected function setUp(): void
      {
         // Initialize the client inside the setUp method
         $this->client = static::createClient();

      }

      public function jsonResponse(): array
      {
         return json_decode($this->client->getResponse()->getContent(), true);
      }

      public function getToken(): string
      {
         $responseContent = $this->client->getResponse()->getContent();
         $responseData = json_decode($responseContent, true);
         return $responseData['token'];
      }

      public function get(string $uri, array $queryParams = []): void
      {
         $this->client->request('GET', $uri, $queryParams);
      }

      public function post(string $uri, array $content, string $token = null): void
      {
         $server = ['CONTENT_TYPE' => 'application/json+ld'];
         if (isset($token)) {
            $server['HTTP_AUTHORIZATION'] = 'Bearer '.$token;
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

// Nope nog steed broken...
//      public function getBasicToken(): string
//      {
//         try {
//            // Create the user
//            $response = $this->client->jsonRequest('POST', '/api/users', [
//                'username' => 'user',
//                'plainPassword' => 'password'
//            ]);
//
//         } catch (Exception) {
//            error_log('Error in getBasicToken');
//         }
//
//         $this->client->jsonRequest('POST', '/auth', ['username' => 'user', 'password' => 'password']);
//         dump($this);
//         return $this->getToken();
//
//      }
   }
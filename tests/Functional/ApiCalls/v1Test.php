<?php

   namespace App\Tests\Functional\ApiCalls;

   use App\Factory\UserFactory;
   use App\Tests\Functional\BaseTestCase;
   use function PHPUnit\Framework\assertNotEmpty;
   use function Zenstruck\Foundry\faker;

   class v1Test extends BaseTestCase
   {
      public function testGetArticles()
      {
         $userData = ['email' => faker()->email(), 'password' => 'password'];
         UserFactory::new()->create($userData);
         $this->post('/auth', [
             'email' => $userData['email'],
             'password' => $userData['password'],
         ]);
         $json = $this->jsonResponse();
         self::assertNotEmpty($json['token']);
         self::assertResponseIsSuccessful();
         $json1 = $this->jsonResponse();
         $jwt = $json1['token'];
         $this->get('/api/users/GeneratePersonalAccessToken', [], $jwt);
         $json = $this->jsonResponse();
         $pat = $json['token'];
         self::assertResponseIsSuccessful();
         assertNotEmpty($pat);

         $parameters = [
             'apiKey' => 'valid_api_key',
         ];

         $url = '/api/newsV1?' . http_build_query($parameters);

         $this->get($url, []);  // Pass the JWT token in the headers
         $json = $this->jsonResponse();
         dump($json);
         self::assertResponseIsSuccessful();
      }

      public function testNewsv1()
      {
         $parameters = [
             'apiKey' => 'valid_api_key',  // Ensure this is a real API key or placeholder
             'max' => 3,
             'language' => 'English',
             'sourceCountry' => 'United States',
             'apikey' => 'your_api_key',
         ];
         $this->get('api/newsV1?' . http_build_query($parameters), []);
         $json = $this->jsonResponse();
         dump($json);
         self::assertResponseIsSuccessful();
         self::assertSame(200, $json['status']);
         self::assertSame(3, $json['total']);
      }

   }

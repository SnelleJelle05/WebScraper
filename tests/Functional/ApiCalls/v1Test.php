<?php

   namespace App\Tests\Functional\ApiCalls;

   use App\Factory\UserFactory;
   use App\Tests\Functional\BaseTestCase;
   use function PHPUnit\Framework\assertNotEmpty;
   use function PHPUnit\Framework\assertSame;
   use function Zenstruck\Foundry\faker;

   class v1Test extends BaseTestCase
   {
      public function testGetArticlesIUsSuc()
      {
         $userData = ['email' => faker()->email(), 'password' => 'password'];
         UserFactory::new()->create($userData);
         $this->post('/auth', [
             'email' => $userData['email'],
             'password' => $userData['password'],
         ]);
         //authenticate
         $json = $this->jsonResponse();
         self::assertNotEmpty($json['token']);
         self::assertResponseIsSuccessful();
         $jsonJWT = $this->jsonResponse();
         $jwt = $jsonJWT['token'];
         $this->get('/api/users/GeneratePersonalAccessToken', [], $jwt);
         $json = $this->jsonResponse();
         $pat = $json['token'];
         self::assertResponseIsSuccessful();

         assertNotEmpty($pat);
         $parameters = [
             'apiKey' => $pat['token'],  // Ensure this is a real API key or placeholder
             'max' => 3,
             'language' => 'English',
             'sourceCountry' => 'United States',
         ];

         $url = '/api/news/v1?' . http_build_query($parameters);

         $this->get($url, []);  // Pass the JWT token in the headers
         $json = $this->jsonResponse();
         assertSame(3, $json['total']);
         assertSame(200, $json['status']);
         assertSame('English', $json['data'][0]['language']);
         assertSame('United States', $json['data'][0]['sourceCountry']);
         self::assertResponseIsSuccessful();
      }

      public function testNewsV1InvalidPat0()
      {
         $parameters = [
             'apiKey' => "invalid",  // Ensure this is a real API key or placeholder
             'max' => 3,
             'language' => 'English',
             'sourceCountry' => 'United States',
             'apikey' => 'your_api_key',
         ];
         $this->get('api/news/v1?' . http_build_query($parameters), []);
         self::assertResponseStatusCodeSame(401);

      }

   }

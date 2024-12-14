<?php

   namespace App\Tests\Functional\ApiCalls;

   use App\Factory\UserFactory;
   use App\Tests\Functional\BaseTestCase;
   use function PHPUnit\Framework\assertNotEmpty;
   use function Zenstruck\Foundry\faker;

   class v1Test extends BaseTestCase
   {
      public function testGetArticlesSuc()
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
         dump($json);
         $pat = $json['token'];
         self::assertResponseIsSuccessful();

         assertNotEmpty($pat);
         $parameters = [
             'apiKey' => $pat['token'],  // Ensure this is a real API key or placeholder
             'max' => 3,
             'language' => 'English',
             'sourceCountry' => 'United States',
         ];

         $url = '/api/v1/news?' . http_build_query($parameters);

         $this->get($url, []);  // Pass the JWT token in the headers
         $json = $this->jsonResponse();
         dump($json);
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
         $this->get('api/v1/news?' . http_build_query($parameters), []);
         $json = $this->jsonResponse();
         dump($json);
         self::assertResponseStatusCodeSame(401);

      }

   }

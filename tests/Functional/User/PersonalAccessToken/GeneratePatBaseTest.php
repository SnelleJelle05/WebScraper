<?php
//
   namespace App\Tests\Functional\User\PersonalAccessToken;

   use App\Factory\UserFactory;
   use App\Tests\Functional\BaseTestCase;
   use function PHPUnit\Framework\assertNotEmpty;
   use function Zenstruck\Foundry\faker;

   class GeneratePatBaseTest extends BaseTestCase
   {
      public function testGetPAT0()
      {
         $userData = ['email' => faker()->email(), 'password' => 'password'];
         UserFactory::new()->create($userData);
         $this->post('/auth', [
             'email' => $userData['email'],
             'password' => $userData['password'],
         ]);

         $json = $this->jsonResponse();
         $token = $json['token'];
         self::assertNotEmpty($token);
         self::assertResponseIsSuccessful();

         $this->get('/api/users/GeneratePersonalAccessTokenProvider', [], $token);
         $json = $this->jsonResponse();
         self::assertResponseIsSuccessful();
         assertNotEmpty($json['token']);
      }

      public function testGetPATNoJWT0()
      {
         $this->get('/api/users/GeneratePersonalAccessTokenProvider', []);
         self::assertResponseStatusCodeSame(401);
      }

   }
<?php
//
   namespace App\Tests\Functional\User;

   use App\Factory\UserFactory;
   use App\Tests\Functional\BaseTestCase;
   use function PHPUnit\Framework\assertNotEmpty;
   use function PHPUnit\Framework\assertSame;
   use function Zenstruck\Foundry\faker;

   class GeneratePatTest extends BaseTestCase
   {
      public function testGetPATNoJWT1()
      {
         $this->get('/api/users/GeneratePersonalAccessToken', []);
         self::assertResponseStatusCodeSame(401);
      }

      public function testGetPATSuc()
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

         $this->get('/api/users/GeneratePersonalAccessToken', [], $token);
         $json = $this->jsonResponse();
         self::assertResponseIsSuccessful();
         assertNotEmpty($json['token']);
      }

      public function testPATChanceSuc()
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

         $this->get('/api/users/GeneratePersonalAccessToken', [], $token);
         $json = $this->jsonResponse();
         $pat1 = $json['token'];
         self::assertResponseIsSuccessful();
         assertNotEmpty($json['token']);

         $this->get('/api/users/GeneratePersonalAccessToken', [], $token);
         $json = $this->jsonResponse();
         $pat2 = $json['token'];
         self::assertResponseIsSuccessful();
         assertNotEmpty($json['token']);
         assertSame($pat2, $json['token']);
         self::assertNotSame($pat1, $pat2);
      }
   }
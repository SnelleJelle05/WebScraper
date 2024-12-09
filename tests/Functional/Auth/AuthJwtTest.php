<?php

   namespace App\Tests\Functional\Auth;

   use App\Factory\UserFactory;
   use App\Tests\Functional\BaseTestCase;
   use function Zenstruck\Foundry\faker;
   use function Zenstruck\Foundry\lazy;

   class AuthJwtTest extends BaseTestCase
   {
      public function testAuthUserWithJWT1(): void
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
      }
      public function testAuthUserWithJWT0(): void
      {
         $this->post('/auth', [
             'email' => 'emailNotInDB@gmail.nl',
             'password' => 'password',
         ]);
         self::assertResponseStatusCodeSame(401);
      }
   }

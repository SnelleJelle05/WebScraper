<?php

   namespace App\Tests\Functional\Auth;

   use App\Factory\UserFactory;
   use App\Tests\Functional\BaseTestCase;
   use function Zenstruck\Foundry\faker;

   class AuthJWT extends BaseTestCase
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
            dump($json);
            self::assertNotEmpty($json['token']);
            self::assertResponseIsSuccessful();
         }
   }

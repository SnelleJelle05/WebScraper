<?php

   namespace App\Tests\Functional\User;

   use App\Factory\UserFactory;
   use function Zenstruck\Foundry\faker;
   use App\Tests\Functional\BaseTestCase;


   class UserPatTokenTest extends BaseTestCase
   {
      // 0 = success 1 = failure
      public function testGetPATToken(): void
      {
         $userData = ['email' => faker()->email(), 'password' => 'password'];
         UserFactory::new()->create($userData);
         $this->post('/auth', [
             'email' => $userData['email'],
             'password' => $userData['password'],
         ]);


      }
   }

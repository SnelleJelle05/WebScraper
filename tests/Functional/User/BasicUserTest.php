<?php

   namespace App\Tests\Functional\User;

   use function Zenstruck\Foundry\faker;
   use App\Tests\Functional\BaseTestCase;


   class BasicUserTest extends BaseTestCase
   {
      // 0 = success 1 = failure
      public function testPostUserSuc(): void
      {
         $this->post('/api/users', [
             'email' => faker()->email,
             'password' => $this->password,
         ]);

         $this->assertResponseStatusCodeSame(201);
         $json = $this->jsonResponse();
         self::assertNotEmpty($json['roles']);
         self::assertNotSame($json['password'], $this->password);
      }

      public function testPostUserEmailErr(): void
      {
         $this->post('/api/users', [
             'email' => faker()->email(),
         ]);

         $this->assertResponseStatusCodeSame(422);
         $json = $this->jsonResponse();
         self::assertSame('This value should not be blank.', $json['violations'][0]['message']);
      }

      public function testPostUserPasswordErr(): void
      {
         $this->post('/api/users', [
             'password' => $this->password,
         ]);
         $this->assertResponseStatusCodeSame(422);
         $json = $this->jsonResponse();
         self::assertSame('This value should not be blank.', $json['violations'][0]['message']);
      }
   }

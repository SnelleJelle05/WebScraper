<?php

   namespace App\Tests\Functional\User;

   use App\Tests\BaseTestCase;
   use BasicUserTest;
   use function Zenstruck\Foundry\faker;

   class BasicUserTests extends BaseTestCase
   {
      // 0 = success 1 = failure
      public function testPostUser0(): void
      {
         $this->post('/api/users', [
             'email' => faker()->userName,
             'password' => $this->password,
         ]);

         $this->assertResponseStatusCodeSame(201);
         $json = $this->jsonResponse();
         self::assertNotEmpty($json['roles']);
         self::assertNotSame($json['password'], $this->password);
      }

      public function testPostUserEmail1(): void
      {
         $this->post('/api/users', [
             'email' => faker()->userName,
         ]);

         $this->assertResponseStatusCodeSame(422);
         $json = $this->jsonResponse();
         self::assertSame('This value should not be blank.', $json['violations'][0]['message']);
      }

      public function testPostUserPassword1(): void
      {
         $this->post('/api/users', [
             'password' => $this->password,
         ]);
         $this->assertResponseStatusCodeSame(422);
         $json = $this->jsonResponse();
         self::assertSame('This value should not be blank.', $json['violations'][0]['message']);
      }


   }

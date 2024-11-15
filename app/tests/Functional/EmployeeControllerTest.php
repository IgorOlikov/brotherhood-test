<?php

namespace App\Tests\Functional;


use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;


class EmployeeControllerTest extends ApiTestCase
{


    public function testSomething(): void
    {




        $response = static::createClient()->request('GET', '/api/v1/employee/{slug}');


        $this->assertResponseIsUnprocessable();
        //$this->assertResponseIsSuccessful();
        //$this->assertJsonContains(['slug' => '{slug}']);
    }
}

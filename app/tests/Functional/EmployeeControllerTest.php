<?php

namespace App\Tests\Functional;


use App\DataFixtures\AppFixtures;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class EmployeeControllerTest extends WebTestCase
{

    /** @var AbstractDatabaseTool */
    protected AbstractDatabaseTool $databaseTool;

    public function setUp(): void
    {
        //self::bootKernel();

        parent::setUp();

        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function testSomething(): void
    {

        $clinet = static::createClient();

        $this->databaseTool->loadFixteures([AppFixtures::class]);


        $response = $clinet->request('GET', '/api/v1/employee/{slug}');


        $this->assertResponseIsUnprocessable();
        //$this->assertResponseIsSuccessful();
        //$this->assertJsonContains(['slug' => '{slug}']);
        //->jso
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }
}

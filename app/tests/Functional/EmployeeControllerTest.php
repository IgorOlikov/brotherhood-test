<?php

namespace App\Tests\Functional;


use App\DataFixtures\AppFixtures;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class EmployeeControllerTest extends WebTestCase
{

    /** @var AbstractDatabaseTool */
    private AbstractDatabaseTool $databaseTool;

    /** @var KernelBrowser */
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();

        $this->databaseTool = self::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function testEmployeeIndex(): void
    {
        $executor = $this->databaseTool->loadFixtures([AppFixtures::class]);

        ///** @var Project $project */
        //$project = $executor->getReferenceRepository()->getReference('project', Project::class);


        $this->client->request('GET', '/api/v1/project');

        $this->assertResponseIsSuccessful();
    }

    public function testEmployeeShow()
    {

    }

    public function testEmployeeStore()
    {

    }

    public function testEmployeeUpdate()
    {

    }

    public function testEmployeePatch()
    {

    }
    public function testEmployeeDelete()
    {

    }

    public function testEmployeeAddProject()
    {

    }





    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }
}

<?php

namespace App\Tests\Functional;


use App\DataFixtures\EmployeeFixtures;
use App\Entity\Employee;
use Doctrine\Common\DataFixtures\Executor\AbstractExecutor;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\SerializerInterface;


class EmployeeControllerTest extends WebTestCase
{

    /** @var AbstractDatabaseTool */
    private AbstractDatabaseTool $databaseTool;

    /** @var KernelBrowser */
    private KernelBrowser $client;
    private AbstractExecutor $executor;

    /** @var SerializerInterface $serializer  */
    private SerializerInterface $serializer;

    private array $serializerContext = [
        DateTimeNormalizer::FORMAT_KEY => 'Y-m-d',
        AbstractNormalizer::GROUPS => ['public'],
        AbstractObjectNormalizer::SKIP_UNINITIALIZED_VALUES => false,
        AbstractObjectNormalizer::SKIP_NULL_VALUES => false
    ];


    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();

        $this->databaseTool = self::getContainer()->get(DatabaseToolCollection::class)->get();

        $this->executor = $this->databaseTool->loadFixtures([EmployeeFixtures::class]);

        $this->serializer = self::getContainer()->get(SerializerInterface::class);
    }

    public function testEmployeeIndex(): void
    {
        $this->client->request('GET', '/api/v1/employee');

        $this->assertResponseIsSuccessful();
    }


    public function testEmployeeShow()
    {
        /** @var Employee $employee */
        $employee = $this->executor->getReferenceRepository()->getReference('employee', Employee::class);

        $this->client->request('GET', "/api/v1/employee/{$employee->getSlug()}");

        $this->assertResponseIsSuccessful();

        $jsonResponseContent = $this->client->getResponse()->getContent();

        $this->assertJson($jsonResponseContent);

        $this->assertJsonStringEqualsJsonString($this->serializer->serialize(
            data: $employee,
            format: 'json',
            context: $this->serializerContext
        ),
            $jsonResponseContent);

    }
    /**
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
    */




    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }
}

<?php

namespace App\Tests\Functional;


use App\DataFixtures\FunctionalEmployeeControllerTestFixtures;
use App\DataFixtures\ProjectFixtures;
use App\Entity\Employee;
use App\Entity\Project;
use Doctrine\Common\DataFixtures\Executor\AbstractExecutor;
use Doctrine\ORM\EntityManagerInterface;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;


class EmployeeControllerTest extends WebTestCase
{

    /** @var AbstractDatabaseTool */
    private AbstractDatabaseTool $databaseTool;

    /** @var KernelBrowser */
    private KernelBrowser $client;
    private AbstractExecutor $executor;

    /** @var SerializerInterface */
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

        $this->executor = $this->databaseTool->loadFixtures([FunctionalEmployeeControllerTestFixtures::class]);

        $this->serializer = self::getContainer()->get(SerializerInterface::class);
    }

    public function testEmployeeIndex(): void
    {
        $this->client->request('GET', '/api/v1/employee', server: ['CONTENT_TYPE' => 'application/json']);

        $this->assertResponseIsSuccessful();
    }

    public function testEmployeeShow()
    {
        /** @var Employee $employee */
        $employee = $this->executor->getReferenceRepository()->getReference('employee', Employee::class);

        $this->client->request(
            'GET',
            "/api/v1/employee/{$employee->getSlug()}",
            server: ['CONTENT_TYPE' => 'application/json']
        );

        $this->assertResponseIsSuccessful();

        $jsonResponseContent = $this->client->getResponse()->getContent();

        $this->assertJson($jsonResponseContent);

        $this->assertJsonStringEqualsJsonString($this->serializer->serialize(
            data: $employee,
            format: 'json',
            context: $this->serializerContext
        ),
            $jsonResponseContent
        );
    }

    public function testEmployeeStore()
    {
        $requestBody = [
            'fullName' => 'Random User Full Name',
            'position' => 'programmer',
            'email' => 'random.user@example.com',
            'phoneNumber' => '79899991',
            'dateOfBrith' => '1970-10-10'
            ];

        $this->client->request(
            'POST', '/api/v1/employee',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode($requestBody));

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(201);

        $jsonResponseContent = $this->client->getResponse()->getContent();

        $this->assertJson($jsonResponseContent);

        $responseDecoded = json_decode($jsonResponseContent, true);

        $this->assertEquals($requestBody['fullName'], $responseDecoded['fullName']);
    }

    public function testEmployeeUpdate()
    {
        /** @var Employee $employee */
        $employee = $this->executor->getReferenceRepository()->getReference('employee', Employee::class);

        $requestBody = [
            'status' => 'dismissal',
            'fullName' => 'Updated' . ' ' . $employee->getFullName(),
            'position' => 'devops',
            'email' => 'updated.user@mail.ru',
            'phoneNumber' => '9333231133',
            'dateOfBrith' => '1980-12-24'
        ];

        $this->client->request(
            'PUT', "/api/v1/employee/{$employee->getSlug()}",
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode($requestBody));

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        $jsonResponseContent = $this->client->getResponse()->getContent();

        $this->assertJson($jsonResponseContent);

        $responseDecoded = json_decode($jsonResponseContent, true);

        $this->assertEquals($employee->getId(), $responseDecoded['id']);
        $this->assertEquals($requestBody['status'], $responseDecoded['status']);
        $this->assertEquals($requestBody['fullName'], $responseDecoded['fullName']);
        $this->assertEquals($requestBody['position'], $responseDecoded['position']);
        $this->assertEquals($requestBody['email'], $responseDecoded['email']);
        $this->assertEquals($requestBody['phoneNumber'], $responseDecoded['phoneNumber']);
        $this->assertEquals($requestBody['dateOfBrith'], $responseDecoded['dateOfBrith']);
    }

    public function testEmployeePatch()
    {
        /** @var Employee $employee */
        $employee = $this->executor->getReferenceRepository()->getReference('employee', Employee::class);

        if ($employee->getStatus() === 'dismissal') {
            $newEmployeeStatus = 'working';
        } else {
            $newEmployeeStatus = 'dismissal';
        }

        $requestBody = [
            'status' => $newEmployeeStatus,
            'fullName' => 'Patched' . ' ' . $employee->getFullName(),
            'position' => 'administrator'
        ];

        $this->client->request(
            'PATCH', "/api/v1/employee/{$employee->getSlug()}",
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode($requestBody));

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        $jsonResponseContent = $this->client->getResponse()->getContent();

        $this->assertJson($jsonResponseContent);

        $responseDecoded = json_decode($jsonResponseContent, true);

        $this->assertEquals($employee->getId(), $responseDecoded['id']);
        $this->assertEquals($requestBody['status'], $responseDecoded['status']);
        $this->assertEquals($requestBody['fullName'], $responseDecoded['fullName']);
        $this->assertEquals($requestBody['position'], $responseDecoded['position']);

        $this->assertEquals($employee->getEmail(), $responseDecoded['email']);
        $this->assertEquals($employee->getPhoneNumber(), $responseDecoded['phoneNumber']);
        $this->assertEquals($employee->getDateOfBrith()->format('Y-m-d'), $responseDecoded['dateOfBrith']);
    }

    public function testEmployeeAddProject()
    {
        /** @var Employee $employee */
        $employee = $this->executor->getReferenceRepository()->getReference('employee', Employee::class);

        /** @var Project $project */
        $project = $this->executor->getReferenceRepository()->getReference('projectWithoutEmployees', Project::class);

        $this->client->request(
            'POST', "/api/v1/employee/{$employee->getSlug()}/project/{$project->getSlug()}",
            server: ['CONTENT_TYPE' => 'application/json']
            );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        $jsonResponseContent = $this->client->getResponse()->getContent();

        $this->assertJson($jsonResponseContent);

        $responseDecoded = json_decode($jsonResponseContent, true);

        $this->assertEquals(
            ['status' => 'success', 'message' => 'Project successfully added to employee'],
            $responseDecoded
        );
    }

    public function testRemoveProjectFromEmployee()
    {
        /** @var Employee $employee */
        $employee = $this->executor->getReferenceRepository()->getReference('employee', Employee::class);

        /** @var Project $project */
        $project = $this->executor->getReferenceRepository()->getReference('projectWithEmployee', Project::class);

        $this->client->request(
            'DELETE', "/api/v1/employee/{$employee->getSlug()}/project/{$project->getSlug()}",
            server: ['CONTENT_TYPE' => 'application/json']
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        $jsonResponseContent = $this->client->getResponse()->getContent();

        $this->assertJson($jsonResponseContent);

        $responseDecoded = json_decode($jsonResponseContent, true);

        $this->assertEquals(
            ['status' => 'success', 'message' => 'Project successfully removed from employee'],
            $responseDecoded
        );
    }

    public function testEmployeeDelete()
    {
        /** @var Employee $employee */
        $employee = $this->executor->getReferenceRepository()->getReference('employee', Employee::class);

        $this->client->request(
            'DELETE',
            "/api/v1/employee/{$employee->getSlug()}",
            server: ['CONTENT_TYPE' => 'application/json']
        );

        $this->assertResponseIsSuccessful();

        $jsonResponseContent = $this->client->getResponse()->getContent();

        $this->assertJson($jsonResponseContent);

        $responseDecoded = json_decode($jsonResponseContent, true);

        $this->assertEquals(
            ['status' => 'success', 'message' => 'Employee successfully created'],
            $responseDecoded
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }
}

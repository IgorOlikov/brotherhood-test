<?php

namespace App\Tests\Functional;


use App\DataFixtures\AppFixtures;
use App\Entity\Project;
use Doctrine\DBAL\Schema\DefaultSchemaManagerFactory;
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

        // Теперь можно безопасно получить контейнер и DatabaseTool
        $this->databaseTool = self::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function testSomething(): void
    {
        // Загружаем фикстуры
        $executor = $this->databaseTool->loadFixtures([AppFixtures::class]);

        /** @var Project $project */
        $project = $executor->getReferenceRepository()->getReference('project', Project::class);


        //$client = static::createClient();

        // Отправляем запрос на тестируемый маршрут
        $this->client->request('GET', '/api/v1/project');

        // Проверка ответа
        $this->assertResponseIsSuccessful();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }
}

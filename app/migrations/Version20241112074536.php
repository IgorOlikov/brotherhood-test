<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241112074536 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE employee (id SERIAL NOT NULL, status VARCHAR(255) DEFAULT \'working\' NOT NULL, full_name VARCHAR(200) NOT NULL, position VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone_number VARCHAR(12) NOT NULL, date_of_brith TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, date_of_employment TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, date_of_dismissal TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5D9F75A1E7927C74 ON employee (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5D9F75A16B01BC5B ON employee (phone_number)');
        $this->addSql('CREATE INDEX employee_slug_idx ON employee (slug)');
        $this->addSql('COMMENT ON COLUMN employee.date_of_brith IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN employee.date_of_employment IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN employee.date_of_dismissal IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE employees_projects (employee_id INT NOT NULL, project_id INT NOT NULL, PRIMARY KEY(employee_id, project_id))');
        $this->addSql('CREATE INDEX IDX_22A27DD88C03F15C ON employees_projects (employee_id)');
        $this->addSql('CREATE INDEX IDX_22A27DD8166D1F9C ON employees_projects (project_id)');
        $this->addSql('CREATE TABLE project (id SERIAL NOT NULL, name VARCHAR(200) NOT NULL, client VARCHAR(255) NOT NULL, status VARCHAR(20) DEFAULT \'opened\' NOT NULL, slug VARCHAR(255) NOT NULL, opened_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, closed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2FB3D0EE5E237E06 ON project (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2FB3D0EE989D9B62 ON project (slug)');
        $this->addSql('CREATE INDEX project_slug_idx ON project (slug)');
        $this->addSql('COMMENT ON COLUMN project.opened_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN project.closed_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE refresh_tokens (id SERIAL NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9BACE7E1C74F2195 ON refresh_tokens (refresh_token)');
        $this->addSql('CREATE TABLE "user" (id SERIAL NOT NULL, name VARCHAR(200) NOT NULL, slug VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, roles TEXT NOT NULL, email_verified_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6495E237E06 ON "user" (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649989D9B62 ON "user" (slug)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('COMMENT ON COLUMN "user".roles IS \'(DC2Type:simple_array)\'');
        $this->addSql('COMMENT ON COLUMN "user".email_verified_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE employees_projects ADD CONSTRAINT FK_22A27DD88C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE employees_projects ADD CONSTRAINT FK_22A27DD8166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE employees_projects DROP CONSTRAINT FK_22A27DD88C03F15C');
        $this->addSql('ALTER TABLE employees_projects DROP CONSTRAINT FK_22A27DD8166D1F9C');
        $this->addSql('DROP TABLE employee');
        $this->addSql('DROP TABLE employees_projects');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE refresh_tokens');
        $this->addSql('DROP TABLE "user"');
    }
}

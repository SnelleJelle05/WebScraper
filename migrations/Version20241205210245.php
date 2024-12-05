<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241205210245 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE news (id UUID NOT NULL, title VARCHAR(255) DEFAULT NULL, description TEXT DEFAULT NULL, category VARCHAR(255) DEFAULT NULL, source VARCHAR(255) DEFAULT NULL, image_url VARCHAR(255) DEFAULT NULL, date VARCHAR(255) DEFAULT NULL, website_url VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN news.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE testing (id SERIAL NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE news');
        $this->addSql('DROP TABLE testing');
    }
}

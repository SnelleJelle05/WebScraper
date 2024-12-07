<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241207174510 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE news ALTER image_url TYPE TEXT');
        $this->addSql('ALTER TABLE news ALTER website_url TYPE TEXT');
        $this->addSql('ALTER TABLE news ALTER website_url DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE news ALTER image_url TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE news ALTER website_url TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE news ALTER website_url SET NOT NULL');
    }
}

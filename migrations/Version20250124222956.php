<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250124222956 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX slug ON product');
        $this->addSql('ALTER TABLE product DROP slug, CHANGE rubric_id rubric_id INT NOT NULL');
        $this->addSql('ALTER TABLE rubric DROP INDEX IDX_60C4016C727ACA70, ADD UNIQUE INDEX parent (parent_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product ADD slug VARCHAR(100) NOT NULL, CHANGE rubric_id rubric_id INT DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX slug ON product (slug)');
        $this->addSql('ALTER TABLE rubric DROP INDEX parent, ADD INDEX IDX_60C4016C727ACA70 (parent_id)');
    }
}

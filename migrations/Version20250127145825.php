<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250127145825 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        //$this->addSql('CREATE UNIQUE INDEX slug ON product (slug)');
        $this->addSql('CREATE UNIQUE INDEX reference ON product (reference)');
        $this->addSql('DROP INDEX slug ON rubric');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        //$this->addSql('DROP INDEX slug ON product');
        $this->addSql('DROP INDEX reference ON product');
        $this->addSql('CREATE UNIQUE INDEX slug ON rubric (slug)');
    }
}

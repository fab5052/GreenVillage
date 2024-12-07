<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241204164413 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE `admin`');
        $this->addSql('ALTER TABLE orders DROP status');
        $this->addSql('DROP INDEX ref ON products');
        $this->addSql('DROP INDEX slug ON products');
        $this->addSql('ALTER TABLE products ADD image VARCHAR(255) NOT NULL, ADD reference VARCHAR(255) NOT NULL, DROP stock, DROP slug, DROP ref, DROP updated_at, CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX reference ON products (reference)');
        $this->addSql('ALTER TABLE rubrics DROP slug, CHANGE parent parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE rubrics ADD CONSTRAINT FK_5F6A2679727ACA70 FOREIGN KEY (parent_id) REFERENCES rubrics (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_5F6A2679727ACA70 ON rubrics (parent_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, commercial VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE `admin` (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE rubrics DROP FOREIGN KEY FK_5F6A2679727ACA70');
        $this->addSql('DROP INDEX IDX_5F6A2679727ACA70 ON rubrics');
        $this->addSql('ALTER TABLE rubrics ADD slug VARCHAR(100) NOT NULL, CHANGE parent_id parent INT DEFAULT NULL');
        $this->addSql('DROP INDEX reference ON products');
        $this->addSql('ALTER TABLE products ADD stock INT NOT NULL, ADD slug VARCHAR(100) NOT NULL, ADD ref VARCHAR(100) NOT NULL, ADD updated_at DATETIME NOT NULL, DROP image, DROP reference, CHANGE created_at created_at DATETIME NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX ref ON products (ref)');
        $this->addSql('CREATE UNIQUE INDEX slug ON products (slug)');
        $this->addSql('ALTER TABLE orders ADD status VARCHAR(255) NOT NULL');
    }
}

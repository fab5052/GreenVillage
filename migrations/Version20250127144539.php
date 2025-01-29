<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250127144539 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
         $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADA29EC0FC');
         $this->addSql('DROP INDEX IDX_D34A04ADA29EC0FC ON product');
         $this->addSql('ALTER TABLE product CHANGE rubric_id subrubric_id INT NOT NULL');
         $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD36F16BC5 FOREIGN KEY (subrubric_id) REFERENCES rubric (id)');
         $this->addSql('CREATE INDEX IDX_D34A04AD36F16BC5 ON product (subrubric_id)');
    }

    public function down(Schema $schema): void
    {
        // // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD36F16BC5');
        $this->addSql('DROP INDEX IDX_D34A04AD36F16BC5 ON product');
        $this->addSql('ALTER TABLE product CHANGE subrubric_id rubric_id INT NOT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADA29EC0FC FOREIGN KEY (rubric_id) REFERENCES rubric (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_D34A04ADA29EC0FC ON product (rubric_id)');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250112182345 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE delivery (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL, note VARCHAR(255) NOT NULL, ord_id INT DEFAULT NULL, INDEX IDX_3781EC10E636D3F5 (ord_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE delivery_details (shipped_qty INT NOT NULL, product_id INT NOT NULL, delivery_id INT NOT NULL, INDEX IDX_7838B4544584665A (product_id), INDEX IDX_7838B45412136921 (delivery_id), PRIMARY KEY(product_id, delivery_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, img VARCHAR(255) NOT NULL, product_id INT DEFAULT NULL, INDEX IDX_C53D045F4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE info_suppliers (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(20) NOT NULL, status VARCHAR(20) NOT NULL, reference VARCHAR(50) NOT NULL, user_id INT DEFAULT NULL, INDEX IDX_1580461A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, reference VARCHAR(50) NOT NULL, status VARCHAR(100) NOT NULL, payment_date DATETIME NOT NULL, payment_method VARCHAR(100) NOT NULL, total NUMERIC(10, 2) NOT NULL, document VARCHAR(255) DEFAULT NULL, type VARCHAR(255) NOT NULL, date DATETIME NOT NULL, payment_status VARCHAR(50) NOT NULL, user_id INT NOT NULL, INDEX IDX_F5299398A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE order_details (id INT AUTO_INCREMENT NOT NULL, quantity INT NOT NULL, price INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, price NUMERIC(10, 2) NOT NULL, reference VARCHAR(50) NOT NULL, slug VARCHAR(100) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, weight NUMERIC(10, 2) NOT NULL, stock INT NOT NULL, is_available TINYINT(1) NOT NULL, suppliers_id INT NOT NULL, rubric_id INT DEFAULT NULL, tva_id INT NOT NULL, INDEX IDX_D34A04AD355AF43 (suppliers_id), INDEX IDX_D34A04ADA29EC0FC (rubric_id), INDEX IDX_D34A04AD4D79775F (tva_id), UNIQUE INDEX slug (slug), UNIQUE INDEX reference (reference), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE rubric (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(50) NOT NULL, slug VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, image VARCHAR(255) NOT NULL, parent_id INT DEFAULT NULL, INDEX IDX_60C4016C727ACA70 (parent_id), UNIQUE INDEX slug (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE tva (id INT AUTO_INCREMENT NOT NULL, rate NUMERIC(10, 2) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, lastname VARCHAR(100) NOT NULL, firstname VARCHAR(100) NOT NULL, address VARCHAR(255) NOT NULL, zipcode VARCHAR(5) NOT NULL, city VARCHAR(150) NOT NULL, plain_password VARCHAR(255) DEFAULT NULL, is_verified TINYINT(1) NOT NULL, reset_token VARCHAR(100) DEFAULT NULL, created_at DATETIME NOT NULL, siret VARCHAR(20) DEFAULT NULL, last_connect DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE delivery ADD CONSTRAINT FK_3781EC10E636D3F5 FOREIGN KEY (ord_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE delivery_details ADD CONSTRAINT FK_7838B4544584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE delivery_details ADD CONSTRAINT FK_7838B45412136921 FOREIGN KEY (delivery_id) REFERENCES delivery (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE info_suppliers ADD CONSTRAINT FK_1580461A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD355AF43 FOREIGN KEY (suppliers_id) REFERENCES info_suppliers (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADA29EC0FC FOREIGN KEY (rubric_id) REFERENCES rubric (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD4D79775F FOREIGN KEY (tva_id) REFERENCES tva (id)');
        $this->addSql('ALTER TABLE rubric ADD CONSTRAINT FK_60C4016C727ACA70 FOREIGN KEY (parent_id) REFERENCES rubric (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE delivery DROP FOREIGN KEY FK_3781EC10E636D3F5');
        $this->addSql('ALTER TABLE delivery_details DROP FOREIGN KEY FK_7838B4544584665A');
        $this->addSql('ALTER TABLE delivery_details DROP FOREIGN KEY FK_7838B45412136921');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F4584665A');
        $this->addSql('ALTER TABLE info_suppliers DROP FOREIGN KEY FK_1580461A76ED395');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398A76ED395');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD355AF43');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADA29EC0FC');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD4D79775F');
        $this->addSql('ALTER TABLE rubric DROP FOREIGN KEY FK_60C4016C727ACA70');
        $this->addSql('DROP TABLE delivery');
        $this->addSql('DROP TABLE delivery_details');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE info_suppliers');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_details');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE rubric');
        $this->addSql('DROP TABLE tva');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

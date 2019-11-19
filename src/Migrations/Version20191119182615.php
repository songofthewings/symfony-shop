<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration
 */
final class Version20191119182615 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cart_products DROP FOREIGN KEY FK_2D2515311AD5CDBF');
        $this->addSql('ALTER TABLE cart_products DROP FOREIGN KEY FK_2D2515314584665A');
        $this->addSql('ALTER TABLE cart_products DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE cart_products ADD id INT AUTO_INCREMENT NOT NULL, ADD quantity INT NOT NULL, CHANGE product_id product_id INT DEFAULT NULL, CHANGE cart_id cart_id INT DEFAULT NULL, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE cart_products ADD CONSTRAINT FK_2D2515311AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)');
        $this->addSql('ALTER TABLE cart_products ADD CONSTRAINT FK_2D2515314584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE UNIQUE INDEX product_unique ON cart_products (cart_id, product_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cart_products MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE cart_products DROP FOREIGN KEY FK_2D2515311AD5CDBF');
        $this->addSql('ALTER TABLE cart_products DROP FOREIGN KEY FK_2D2515314584665A');
        $this->addSql('DROP INDEX product_unique ON cart_products');
        $this->addSql('ALTER TABLE cart_products DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE cart_products DROP id, DROP quantity, CHANGE cart_id cart_id INT NOT NULL, CHANGE product_id product_id INT NOT NULL');
        $this->addSql('ALTER TABLE cart_products ADD CONSTRAINT FK_2D2515311AD5CDBF FOREIGN KEY (cart_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE cart_products ADD CONSTRAINT FK_2D2515314584665A FOREIGN KEY (product_id) REFERENCES cart (id)');
        $this->addSql('ALTER TABLE cart_products ADD PRIMARY KEY (product_id, cart_id)');
    }
}

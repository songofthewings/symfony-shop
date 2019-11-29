<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration: changes for not null
 */
final class Version20191129135957 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Alter not null columns.';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cart_products CHANGE product_id product_id INT NOT NULL, CHANGE cart_id cart_id INT NOT NULL');
        $this->addSql('ALTER TABLE promotion_option CHANGE promotion_id promotion_id INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cart_products CHANGE cart_id cart_id INT DEFAULT NULL, CHANGE product_id product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE promotion_option CHANGE promotion_id promotion_id INT DEFAULT NULL');
    }
}

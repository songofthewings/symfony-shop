<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration: PromotionOption user_id.
 */
final class Version20191129134026 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add promotion_option.user_id .';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE promotion_option ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE promotion_option ADD CONSTRAINT FK_4F3C2B8DA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)');
        $this->addSql('CREATE INDEX IDX_4F3C2B8DA76ED395 ON promotion_option (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE promotion_option DROP FOREIGN KEY FK_4F3C2B8DA76ED395');
        $this->addSql('DROP INDEX IDX_4F3C2B8DA76ED395 ON promotion_option');
        $this->addSql('ALTER TABLE promotion_option DROP user_id');
    }
}

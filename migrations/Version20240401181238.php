<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240401181238 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_report DROP INDEX UNIQ_A17D6CB9A35E36EA, ADD INDEX IDX_A17D6CB9A35E36EA (report_category_id)');
        $this->addSql('ALTER TABLE user_report DROP INDEX UNIQ_A17D6CB9856CFB8F, ADD INDEX IDX_A17D6CB9856CFB8F (admin_processing_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_report DROP INDEX IDX_A17D6CB9A35E36EA, ADD UNIQUE INDEX UNIQ_A17D6CB9A35E36EA (report_category_id)');
        $this->addSql('ALTER TABLE user_report DROP INDEX IDX_A17D6CB9856CFB8F, ADD UNIQUE INDEX UNIQ_A17D6CB9856CFB8F (admin_processing_id)');
    }
}

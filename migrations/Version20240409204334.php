<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240409204334 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE post_report (id INT AUTO_INCREMENT NOT NULL,
        post_id INT NOT NULL,
        user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\',
        report_category_id INT NOT NULL,
        date DATETIME NOT NULL,
        comment VARCHAR(150) DEFAULT NULL,
        treating TINYINT(1) DEFAULT 0 NOT NULL,
        admin_processing_id INT DEFAULT NULL,
        ignored TINYINT(1) DEFAULT 0 NOT NULL,
        deleted TINYINT(1) DEFAULT 0 NOT NULL,
        user_timeout TINYINT(1) DEFAULT 0 NOT NULL,
        user_ban TINYINT(1) DEFAULT 0 NOT NULL,
        result_date DATETIME DEFAULT NULL, INDEX IDX_F40D93E1A35E36EA (report_category_id), INDEX IDX_F40D93E1856CFB8F (admin_processing_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post_report ADD CONSTRAINT FK_F40D93E1A35E36EA FOREIGN KEY (report_category_id) REFERENCES report_category (id)');
        $this->addSql('ALTER TABLE post_report ADD CONSTRAINT FK_F40D93E1856CFB8F FOREIGN KEY (admin_processing_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post_report DROP FOREIGN KEY FK_F40D93E1A35E36EA');
        $this->addSql('ALTER TABLE post_report DROP FOREIGN KEY FK_F40D93E1856CFB8F');
        $this->addSql('DROP TABLE post_report');
    }
}

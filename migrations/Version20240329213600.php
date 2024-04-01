<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240329213600 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE report_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(60) NOT NULL, is_active TINYINT(1) DEFAULT 1 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE unban_request (id INT AUTO_INCREMENT NOT NULL,
        user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\',
        report_category_id INT NOT NULL,
        date DATETIME NOT NULL,
        comment VARCHAR(150) DEFAULT NULL,
        admin_processing_id INT DEFAULT NULL,
        unban TINYINT(1) DEFAULT 0 NOT NULL,
        result_date DATETIME DEFAULT NULL,
        UNIQUE INDEX UNIQ_E10D9D09A35E36EA (report_category_id),
        UNIQUE INDEX UNIQ_E10D9D09856CFB8F (admin_processing_id),
        PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE unban_request ADD CONSTRAINT FK_E10D9D09A35E36EA FOREIGN KEY (report_category_id) REFERENCES report_category (id)');
        $this->addSql('ALTER TABLE unban_request ADD CONSTRAINT FK_E10D9D09856CFB8F FOREIGN KEY (admin_processing_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE login CHANGE password password VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE username username VARCHAR(15) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE unban_request DROP FOREIGN KEY FK_E10D9D09A35E36EA');
        $this->addSql('ALTER TABLE unban_request DROP FOREIGN KEY FK_E10D9D09856CFB8F');
        $this->addSql('DROP TABLE report_category');
        $this->addSql('DROP TABLE unban_request');
        $this->addSql('ALTER TABLE user CHANGE username username VARCHAR(25) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX username_unique ON user (username)');
        $this->addSql('ALTER TABLE login CHANGE password password VARCHAR(255) NOT NULL');
    }
}

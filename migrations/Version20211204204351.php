<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211204204351 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs ALTER TABLE table_name RENAME COLUMN old_column_name TO new_column_name;
        $this->addSql('ALTER TABLE user ADD username VARCHAR(70) NOT NULL, CHANGE last_login_date last_login_date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user_login_date ADD CONSTRAINT FK_CB8B7AD379F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP username, CHANGE last_login_date last_login_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user_login_date DROP FOREIGN KEY FK_CB8B7AD379F37AE5');
    }
}

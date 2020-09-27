<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200927204631 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Update relation between command and products table';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE command_products (command_id INT NOT NULL, products_id INT NOT NULL, INDEX IDX_8A5683CB33E1689A (command_id), INDEX IDX_8A5683CB6C8A81A9 (products_id), PRIMARY KEY(command_id, products_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE command_products ADD CONSTRAINT FK_8A5683CB33E1689A FOREIGN KEY (command_id) REFERENCES command (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE command_products ADD CONSTRAINT FK_8A5683CB6C8A81A9 FOREIGN KEY (products_id) REFERENCES products (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE command_command');
        $this->addSql('ALTER TABLE article CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE image_name image_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE command_command (command_source INT NOT NULL, command_target INT NOT NULL, INDEX IDX_61A0B937F92CE6BF (command_target), INDEX IDX_61A0B937E0C9B630 (command_source), PRIMARY KEY(command_source, command_target)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE command_command ADD CONSTRAINT FK_61A0B937E0C9B630 FOREIGN KEY (command_source) REFERENCES command (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE command_command ADD CONSTRAINT FK_61A0B937F92CE6BF FOREIGN KEY (command_target) REFERENCES command (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE command_products');
        $this->addSql('ALTER TABLE article CHANGE image_name image_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE created_at created_at DATETIME DEFAULT \'current_timestamp()\' NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT \'current_timestamp()\' NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE created_at created_at DATETIME DEFAULT \'current_timestamp()\' NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT \'current_timestamp()\' NOT NULL');
    }
}

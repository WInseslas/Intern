<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220426000706 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE certificates (id INT AUTO_INCREMENT NOT NULL, templates_id INT DEFAULT NULL, created_at DATE NOT NULL, coded VARCHAR(255) NOT NULL, INDEX IDX_8D26FB5F22CE5C94 (templates_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE internet_users (id INT AUTO_INCREMENT NOT NULL, last_name VARCHAR(255) NOT NULL, first_name VARCHAR(255) DEFAULT NULL, login VARCHAR(255) NOT NULL, password LONGTEXT NOT NULL, date_of_birth DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE other_informations (id INT AUTO_INCREMENT NOT NULL, post VARCHAR(255) NOT NULL, topic VARCHAR(255) DEFAULT NULL, start_date DATE DEFAULT NULL, end_date DATE DEFAULT NULL, result LONGTEXT DEFAULT NULL, school VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE templates (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, wording VARCHAR(255) NOT NULL, created_at DATE NOT NULL, INDEX IDX_6F287D8EF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, other_informations_id INT DEFAULT NULL, last_name VARCHAR(255) NOT NULL, first_name VARCHAR(255) DEFAULT NULL, date_of_birth DATE NOT NULL, login VARCHAR(255) NOT NULL, password LONGTEXT NOT NULL, INDEX IDX_1483A5E93FBB336C (other_informations_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE certificates ADD CONSTRAINT FK_8D26FB5F22CE5C94 FOREIGN KEY (templates_id) REFERENCES templates (id)');
        $this->addSql('ALTER TABLE templates ADD CONSTRAINT FK_6F287D8EF675F31B FOREIGN KEY (author_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E93FBB336C FOREIGN KEY (other_informations_id) REFERENCES other_informations (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E93FBB336C');
        $this->addSql('ALTER TABLE certificates DROP FOREIGN KEY FK_8D26FB5F22CE5C94');
        $this->addSql('ALTER TABLE templates DROP FOREIGN KEY FK_6F287D8EF675F31B');
        $this->addSql('DROP TABLE certificates');
        $this->addSql('DROP TABLE internet_users');
        $this->addSql('DROP TABLE other_informations');
        $this->addSql('DROP TABLE templates');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

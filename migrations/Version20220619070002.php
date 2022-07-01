<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220619070002 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE certificate (id INT AUTO_INCREMENT NOT NULL, template_id INT NOT NULL, people_id INT NOT NULL, user_id INT NOT NULL, coded VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_219CDA4A667E448A (coded), INDEX IDX_219CDA4A5DA0FB8 (template_id), INDEX IDX_219CDA4A3147C936 (people_id), INDEX IDX_219CDA4AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE people (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) DEFAULT NULL, dateofbirth DATE NOT NULL, sex TINYINT(1) NOT NULL, email VARCHAR(255) NOT NULL, post VARCHAR(255) NOT NULL, topic LONGTEXT DEFAULT NULL, startdate DATE NOT NULL, enddate DATE NOT NULL, result LONGTEXT DEFAULT NULL, school VARCHAR(150) NOT NULL, level INT NOT NULL, domain VARCHAR(100) NOT NULL, internshipletter VARCHAR(255) DEFAULT NULL, report VARCHAR(255) DEFAULT NULL, otherfile VARCHAR(255) DEFAULT NULL, INDEX IDX_28166A26A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE template (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, wording VARCHAR(100) NOT NULL, file VARCHAR(255) NOT NULL, coordinates VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_97601F83F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, fullname VARCHAR(255) NOT NULL, reset_token VARCHAR(255) DEFAULT NULL, avatar VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE userhasverified (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, certificate_id INT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_D8D0402BA76ED395 (user_id), INDEX IDX_D8D0402B99223FFD (certificate_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        // $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE certificate ADD CONSTRAINT FK_219CDA4A5DA0FB8 FOREIGN KEY (template_id) REFERENCES template (id)');
        $this->addSql('ALTER TABLE certificate ADD CONSTRAINT FK_219CDA4A3147C936 FOREIGN KEY (people_id) REFERENCES people (id)');
        $this->addSql('ALTER TABLE certificate ADD CONSTRAINT FK_219CDA4AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE people ADD CONSTRAINT FK_28166A26A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE template ADD CONSTRAINT FK_97601F83F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE userhasverified ADD CONSTRAINT FK_D8D0402BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE userhasverified ADD CONSTRAINT FK_D8D0402B99223FFD FOREIGN KEY (certificate_id) REFERENCES certificate (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE userhasverified DROP FOREIGN KEY FK_D8D0402B99223FFD');
        $this->addSql('ALTER TABLE certificate DROP FOREIGN KEY FK_219CDA4A3147C936');
        $this->addSql('ALTER TABLE certificate DROP FOREIGN KEY FK_219CDA4A5DA0FB8');
        $this->addSql('ALTER TABLE certificate DROP FOREIGN KEY FK_219CDA4AA76ED395');
        $this->addSql('ALTER TABLE people DROP FOREIGN KEY FK_28166A26A76ED395');
        $this->addSql('ALTER TABLE template DROP FOREIGN KEY FK_97601F83F675F31B');
        $this->addSql('ALTER TABLE userhasverified DROP FOREIGN KEY FK_D8D0402BA76ED395');
        $this->addSql('DROP TABLE certificate');
        $this->addSql('DROP TABLE people');
        $this->addSql('DROP TABLE template');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE userhasverified');
        // $this->addSql('DROP TABLE messenger_messages');
    }
}

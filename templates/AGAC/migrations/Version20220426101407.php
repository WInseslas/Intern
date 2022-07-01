<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220426101407 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE internet_users_verifie_certificates (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE internet_users_verifie_certificates_internet_users (internet_users_verifie_certificates_id INT NOT NULL, internet_users_id INT NOT NULL, INDEX IDX_E9DC6AE1F79023E4 (internet_users_verifie_certificates_id), INDEX IDX_E9DC6AE12CB18B7B (internet_users_id), PRIMARY KEY(internet_users_verifie_certificates_id, internet_users_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE internet_users_verifie_certificates_certificates (internet_users_verifie_certificates_id INT NOT NULL, certificates_id INT NOT NULL, INDEX IDX_4BA345AEF79023E4 (internet_users_verifie_certificates_id), INDEX IDX_4BA345AE24E411BB (certificates_id), PRIMARY KEY(internet_users_verifie_certificates_id, certificates_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_has_certificates (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_has_certificates_users (users_has_certificates_id INT NOT NULL, users_id INT NOT NULL, INDEX IDX_D1E39BB05088CA08 (users_has_certificates_id), INDEX IDX_D1E39BB067B3B43D (users_id), PRIMARY KEY(users_has_certificates_id, users_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_has_certificates_certificates (users_has_certificates_id INT NOT NULL, certificates_id INT NOT NULL, INDEX IDX_DDBA589F5088CA08 (users_has_certificates_id), INDEX IDX_DDBA589F24E411BB (certificates_id), PRIMARY KEY(users_has_certificates_id, certificates_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE internet_users_verifie_certificates_internet_users ADD CONSTRAINT FK_E9DC6AE1F79023E4 FOREIGN KEY (internet_users_verifie_certificates_id) REFERENCES internet_users_verifie_certificates (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE internet_users_verifie_certificates_internet_users ADD CONSTRAINT FK_E9DC6AE12CB18B7B FOREIGN KEY (internet_users_id) REFERENCES internet_users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE internet_users_verifie_certificates_certificates ADD CONSTRAINT FK_4BA345AEF79023E4 FOREIGN KEY (internet_users_verifie_certificates_id) REFERENCES internet_users_verifie_certificates (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE internet_users_verifie_certificates_certificates ADD CONSTRAINT FK_4BA345AE24E411BB FOREIGN KEY (certificates_id) REFERENCES certificates (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_has_certificates_users ADD CONSTRAINT FK_D1E39BB05088CA08 FOREIGN KEY (users_has_certificates_id) REFERENCES users_has_certificates (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_has_certificates_users ADD CONSTRAINT FK_D1E39BB067B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_has_certificates_certificates ADD CONSTRAINT FK_DDBA589F5088CA08 FOREIGN KEY (users_has_certificates_id) REFERENCES users_has_certificates (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_has_certificates_certificates ADD CONSTRAINT FK_DDBA589F24E411BB FOREIGN KEY (certificates_id) REFERENCES certificates (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE internet_users_verifie_certificates_internet_users DROP FOREIGN KEY FK_E9DC6AE1F79023E4');
        $this->addSql('ALTER TABLE internet_users_verifie_certificates_certificates DROP FOREIGN KEY FK_4BA345AEF79023E4');
        $this->addSql('ALTER TABLE users_has_certificates_users DROP FOREIGN KEY FK_D1E39BB05088CA08');
        $this->addSql('ALTER TABLE users_has_certificates_certificates DROP FOREIGN KEY FK_DDBA589F5088CA08');
        $this->addSql('DROP TABLE internet_users_verifie_certificates');
        $this->addSql('DROP TABLE internet_users_verifie_certificates_internet_users');
        $this->addSql('DROP TABLE internet_users_verifie_certificates_certificates');
        $this->addSql('DROP TABLE users_has_certificates');
        $this->addSql('DROP TABLE users_has_certificates_users');
        $this->addSql('DROP TABLE users_has_certificates_certificates');
    }
}

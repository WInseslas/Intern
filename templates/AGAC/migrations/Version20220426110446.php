<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220426110446 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE users_has_certificates (id INT AUTO_INCREMENT NOT NULL, users_id INT DEFAULT NULL, certificates_id INT DEFAULT NULL, INDEX IDX_34A81D967B3B43D (users_id), INDEX IDX_34A81D924E411BB (certificates_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE users_has_certificates ADD CONSTRAINT FK_34A81D967B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE users_has_certificates ADD CONSTRAINT FK_34A81D924E411BB FOREIGN KEY (certificates_id) REFERENCES certificates (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE users_has_certificates');
    }
}

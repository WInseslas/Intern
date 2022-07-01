<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220427235958 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE other_informations DROP FOREIGN KEY FK_6B409423A76ED395');
        $this->addSql('DROP INDEX IDX_6B409423A76ED395 ON other_informations');
        $this->addSql('ALTER TABLE other_informations DROP user_id');
        $this->addSql('ALTER TABLE users ADD other_informations_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E93FBB336C FOREIGN KEY (other_informations_id) REFERENCES other_informations (id)');
        $this->addSql('CREATE INDEX IDX_1483A5E93FBB336C ON users (other_informations_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE other_informations ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE other_informations ADD CONSTRAINT FK_6B409423A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_6B409423A76ED395 ON other_informations (user_id)');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E93FBB336C');
        $this->addSql('DROP INDEX IDX_1483A5E93FBB336C ON users');
        $this->addSql('ALTER TABLE users DROP other_informations_id');
    }
}

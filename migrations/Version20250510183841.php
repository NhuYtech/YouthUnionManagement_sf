<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250510183841 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE instruction_document ADD created_by_id INT NOT NULL');
        $this->addSql('ALTER TABLE instruction_document ADD CONSTRAINT FK_5D7B3FF9B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_5D7B3FF9B03A8386 ON instruction_document (created_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE instruction_document DROP FOREIGN KEY FK_5D7B3FF9B03A8386');
        $this->addSql('DROP INDEX IDX_5D7B3FF9B03A8386 ON instruction_document');
        $this->addSql('ALTER TABLE instruction_document DROP created_by_id');
    }
}

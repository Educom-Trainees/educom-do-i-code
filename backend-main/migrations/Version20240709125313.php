<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240709125313 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commits ADD has_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commits ADD CONSTRAINT FK_B327C47011BD6139 FOREIGN KEY (has_id) REFERENCES issues (id)');
        $this->addSql('CREATE INDEX IDX_B327C47011BD6139 ON commits (has_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commits DROP FOREIGN KEY FK_B327C47011BD6139');
        $this->addSql('DROP INDEX IDX_B327C47011BD6139 ON commits');
        $this->addSql('ALTER TABLE commits DROP has_id');
    }
}

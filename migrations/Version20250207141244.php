<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250207141244 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE level DROP FOREIGN KEY FK_9AEACC136E283409');
        $this->addSql('DROP INDEX IDX_9AEACC136E283409 ON level');
        $this->addSql('ALTER TABLE level CHANGE filed_id field_id INT NOT NULL');
        $this->addSql('ALTER TABLE level ADD CONSTRAINT FK_9AEACC13443707B0 FOREIGN KEY (field_id) REFERENCES field (id)');
        $this->addSql('CREATE INDEX IDX_9AEACC13443707B0 ON level (field_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE level DROP FOREIGN KEY FK_9AEACC13443707B0');
        $this->addSql('DROP INDEX IDX_9AEACC13443707B0 ON level');
        $this->addSql('ALTER TABLE level CHANGE field_id filed_id INT NOT NULL');
        $this->addSql('ALTER TABLE level ADD CONSTRAINT FK_9AEACC136E283409 FOREIGN KEY (filed_id) REFERENCES field (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_9AEACC136E283409 ON level (filed_id)');
    }
}

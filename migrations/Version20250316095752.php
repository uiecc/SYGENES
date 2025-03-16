<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250316095752 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE student_ue (id INT AUTO_INCREMENT NOT NULL, student_id INT NOT NULL, ue_id INT NOT NULL, registered_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', academic_year VARCHAR(20) NOT NULL, status VARCHAR(20) DEFAULT NULL, grade DOUBLE PRECISION DEFAULT NULL, is_validated TINYINT(1) DEFAULT NULL, INDEX IDX_5AE422D1CB944F1A (student_id), INDEX IDX_5AE422D162E883B1 (ue_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE student_ue ADD CONSTRAINT FK_5AE422D1CB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
        $this->addSql('ALTER TABLE student_ue ADD CONSTRAINT FK_5AE422D162E883B1 FOREIGN KEY (ue_id) REFERENCES ue (id)');
        $this->addSql('ALTER TABLE ue ADD is_compulsory TINYINT(1) DEFAULT 1 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE student_ue DROP FOREIGN KEY FK_5AE422D1CB944F1A');
        $this->addSql('ALTER TABLE student_ue DROP FOREIGN KEY FK_5AE422D162E883B1');
        $this->addSql('DROP TABLE student_ue');
        $this->addSql('ALTER TABLE ue DROP is_compulsory');
    }
}

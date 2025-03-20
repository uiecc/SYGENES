<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250320113421 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE academic_year (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, atart_date DATETIME NOT NULL, end_date DATETIME DEFAULT NULL, is_current TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE anonymous_code (id INT AUTO_INCREMENT NOT NULL, student_id INT DEFAULT NULL, exam_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, create_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_1D57C2FACB944F1A (student_id), INDEX IDX_1D57C2FA578D5E91 (exam_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exam (id INT AUTO_INCREMENT NOT NULL, ec_id INT DEFAULT NULL, academic_year_id INT DEFAULT NULL, original_exam_id INT DEFAULT NULL, exam_date DATETIME NOT NULL, weight DOUBLE PRECISION DEFAULT NULL, type VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_38BBA6C627634BEF (ec_id), INDEX IDX_38BBA6C6C54F3401 (academic_year_id), INDEX IDX_38BBA6C6D10DF066 (original_exam_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exam_grade (id INT AUTO_INCREMENT NOT NULL, anonymous_code_id INT DEFAULT NULL, grade DOUBLE PRECISION DEFAULT NULL, comment VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_BBFA884D2D142B0 (anonymous_code_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE anonymous_code ADD CONSTRAINT FK_1D57C2FACB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
        $this->addSql('ALTER TABLE anonymous_code ADD CONSTRAINT FK_1D57C2FA578D5E91 FOREIGN KEY (exam_id) REFERENCES exam (id)');
        $this->addSql('ALTER TABLE exam ADD CONSTRAINT FK_38BBA6C627634BEF FOREIGN KEY (ec_id) REFERENCES ec (id)');
        $this->addSql('ALTER TABLE exam ADD CONSTRAINT FK_38BBA6C6C54F3401 FOREIGN KEY (academic_year_id) REFERENCES academic_year (id)');
        $this->addSql('ALTER TABLE exam ADD CONSTRAINT FK_38BBA6C6D10DF066 FOREIGN KEY (original_exam_id) REFERENCES exam (id)');
        $this->addSql('ALTER TABLE exam_grade ADD CONSTRAINT FK_BBFA884D2D142B0 FOREIGN KEY (anonymous_code_id) REFERENCES anonymous_code (id)');
        $this->addSql('ALTER TABLE note CHANGE student_id student_id INT NOT NULL, CHANGE ec_id ec_id INT NOT NULL, CHANGE academic_year academic_year VARCHAR(255) NOT NULL, CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE anonymous_code DROP FOREIGN KEY FK_1D57C2FACB944F1A');
        $this->addSql('ALTER TABLE anonymous_code DROP FOREIGN KEY FK_1D57C2FA578D5E91');
        $this->addSql('ALTER TABLE exam DROP FOREIGN KEY FK_38BBA6C627634BEF');
        $this->addSql('ALTER TABLE exam DROP FOREIGN KEY FK_38BBA6C6C54F3401');
        $this->addSql('ALTER TABLE exam DROP FOREIGN KEY FK_38BBA6C6D10DF066');
        $this->addSql('ALTER TABLE exam_grade DROP FOREIGN KEY FK_BBFA884D2D142B0');
        $this->addSql('DROP TABLE academic_year');
        $this->addSql('DROP TABLE anonymous_code');
        $this->addSql('DROP TABLE exam');
        $this->addSql('DROP TABLE exam_grade');
        $this->addSql('ALTER TABLE note CHANGE student_id student_id INT DEFAULT NULL, CHANGE ec_id ec_id INT DEFAULT NULL, CHANGE academic_year academic_year VARCHAR(255) DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }
}

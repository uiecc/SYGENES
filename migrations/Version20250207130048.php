<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250207130048 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ec (id INT AUTO_INCREMENT NOT NULL, ue_id INT NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, credit INT NOT NULL, INDEX IDX_8DE8BDFF62E883B1 (ue_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE field (id INT AUTO_INCREMENT NOT NULL, school_id INT NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, INDEX IDX_5BF54558C32A47EE (school_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE level (id INT AUTO_INCREMENT NOT NULL, filed_id INT NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, INDEX IDX_9AEACC136E283409 (filed_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE school (id INT AUTO_INCREMENT NOT NULL, university_id INT NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, INDEX IDX_F99EDABB309D1878 (university_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE semester (id INT AUTO_INCREMENT NOT NULL, level_id INT NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, INDEX IDX_F7388EED5FB14BA7 (level_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ue (id INT AUTO_INCREMENT NOT NULL, semester_id INT NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, credit INT NOT NULL, INDEX IDX_2E490A9B4A798B6F (semester_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE university (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, address VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ec ADD CONSTRAINT FK_8DE8BDFF62E883B1 FOREIGN KEY (ue_id) REFERENCES ue (id)');
        $this->addSql('ALTER TABLE field ADD CONSTRAINT FK_5BF54558C32A47EE FOREIGN KEY (school_id) REFERENCES school (id)');
        $this->addSql('ALTER TABLE level ADD CONSTRAINT FK_9AEACC136E283409 FOREIGN KEY (filed_id) REFERENCES field (id)');
        $this->addSql('ALTER TABLE school ADD CONSTRAINT FK_F99EDABB309D1878 FOREIGN KEY (university_id) REFERENCES university (id)');
        $this->addSql('ALTER TABLE semester ADD CONSTRAINT FK_F7388EED5FB14BA7 FOREIGN KEY (level_id) REFERENCES level (id)');
        $this->addSql('ALTER TABLE ue ADD CONSTRAINT FK_2E490A9B4A798B6F FOREIGN KEY (semester_id) REFERENCES semester (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ec DROP FOREIGN KEY FK_8DE8BDFF62E883B1');
        $this->addSql('ALTER TABLE field DROP FOREIGN KEY FK_5BF54558C32A47EE');
        $this->addSql('ALTER TABLE level DROP FOREIGN KEY FK_9AEACC136E283409');
        $this->addSql('ALTER TABLE school DROP FOREIGN KEY FK_F99EDABB309D1878');
        $this->addSql('ALTER TABLE semester DROP FOREIGN KEY FK_F7388EED5FB14BA7');
        $this->addSql('ALTER TABLE ue DROP FOREIGN KEY FK_2E490A9B4A798B6F');
        $this->addSql('DROP TABLE ec');
        $this->addSql('DROP TABLE field');
        $this->addSql('DROP TABLE level');
        $this->addSql('DROP TABLE school');
        $this->addSql('DROP TABLE semester');
        $this->addSql('DROP TABLE ue');
        $this->addSql('DROP TABLE university');
    }
}

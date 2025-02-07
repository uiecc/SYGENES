<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250207151926 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE administrator (id INT NOT NULL, university_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_58DF0651309D1878 (university_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE field_manager (id INT NOT NULL, field_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_14000019443707B0 (field_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE level_manager (id INT NOT NULL, level_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_5B8C00A65FB14BA7 (level_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE school_manager (id INT NOT NULL, school_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_680B1721C32A47EE (school_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE teacher (id INT NOT NULL, ec_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_B0F6A6D527634BEF (ec_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE uemanager (id INT NOT NULL, ue_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_A37EC62C62E883B1 (ue_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE administrator ADD CONSTRAINT FK_58DF0651309D1878 FOREIGN KEY (university_id) REFERENCES university (id)');
        $this->addSql('ALTER TABLE administrator ADD CONSTRAINT FK_58DF0651BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE field_manager ADD CONSTRAINT FK_14000019443707B0 FOREIGN KEY (field_id) REFERENCES field (id)');
        $this->addSql('ALTER TABLE field_manager ADD CONSTRAINT FK_14000019BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE level_manager ADD CONSTRAINT FK_5B8C00A65FB14BA7 FOREIGN KEY (level_id) REFERENCES level (id)');
        $this->addSql('ALTER TABLE level_manager ADD CONSTRAINT FK_5B8C00A6BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE school_manager ADD CONSTRAINT FK_680B1721C32A47EE FOREIGN KEY (school_id) REFERENCES school (id)');
        $this->addSql('ALTER TABLE school_manager ADD CONSTRAINT FK_680B1721BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE teacher ADD CONSTRAINT FK_B0F6A6D527634BEF FOREIGN KEY (ec_id) REFERENCES ec (id)');
        $this->addSql('ALTER TABLE teacher ADD CONSTRAINT FK_B0F6A6D5BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE uemanager ADD CONSTRAINT FK_A37EC62C62E883B1 FOREIGN KEY (ue_id) REFERENCES ue (id)');
        $this->addSql('ALTER TABLE uemanager ADD CONSTRAINT FK_A37EC62CBF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE responsable ADD `function` VARCHAR(255) DEFAULT NULL, ADD department VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE administrator DROP FOREIGN KEY FK_58DF0651309D1878');
        $this->addSql('ALTER TABLE administrator DROP FOREIGN KEY FK_58DF0651BF396750');
        $this->addSql('ALTER TABLE field_manager DROP FOREIGN KEY FK_14000019443707B0');
        $this->addSql('ALTER TABLE field_manager DROP FOREIGN KEY FK_14000019BF396750');
        $this->addSql('ALTER TABLE level_manager DROP FOREIGN KEY FK_5B8C00A65FB14BA7');
        $this->addSql('ALTER TABLE level_manager DROP FOREIGN KEY FK_5B8C00A6BF396750');
        $this->addSql('ALTER TABLE school_manager DROP FOREIGN KEY FK_680B1721C32A47EE');
        $this->addSql('ALTER TABLE school_manager DROP FOREIGN KEY FK_680B1721BF396750');
        $this->addSql('ALTER TABLE teacher DROP FOREIGN KEY FK_B0F6A6D527634BEF');
        $this->addSql('ALTER TABLE teacher DROP FOREIGN KEY FK_B0F6A6D5BF396750');
        $this->addSql('ALTER TABLE uemanager DROP FOREIGN KEY FK_A37EC62C62E883B1');
        $this->addSql('ALTER TABLE uemanager DROP FOREIGN KEY FK_A37EC62CBF396750');
        $this->addSql('DROP TABLE administrator');
        $this->addSql('DROP TABLE field_manager');
        $this->addSql('DROP TABLE level_manager');
        $this->addSql('DROP TABLE school_manager');
        $this->addSql('DROP TABLE teacher');
        $this->addSql('DROP TABLE uemanager');
        $this->addSql('ALTER TABLE responsable DROP `function`, DROP department');
    }
}

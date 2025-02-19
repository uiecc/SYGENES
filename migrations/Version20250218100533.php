<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250218100533 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE action (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(20) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', metadata JSON DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE administrator (id INT NOT NULL, university_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_58DF0651309D1878 (university_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ec (id INT AUTO_INCREMENT NOT NULL, ue_id INT NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, credit INT NOT NULL, INDEX IDX_8DE8BDFF62E883B1 (ue_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE field (id INT AUTO_INCREMENT NOT NULL, school_id INT NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, INDEX IDX_5BF54558C32A47EE (school_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE field_manager (id INT NOT NULL, field_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_14000019443707B0 (field_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE level (id INT AUTO_INCREMENT NOT NULL, field_id INT NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, INDEX IDX_9AEACC13443707B0 (field_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE level_manager (id INT NOT NULL, level_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_5B8C00A65FB14BA7 (level_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE responsable (id INT NOT NULL, code_resp VARCHAR(20) DEFAULT NULL, fonction VARCHAR(255) DEFAULT NULL, department VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, permissions JSON DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE school (id INT AUTO_INCREMENT NOT NULL, university_id INT NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, logo VARCHAR(255) DEFAULT NULL, INDEX IDX_F99EDABB309D1878 (university_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE school_manager (id INT NOT NULL, school_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_680B1721C32A47EE (school_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE semester (id INT AUTO_INCREMENT NOT NULL, level_id INT NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, INDEX IDX_F7388EED5FB14BA7 (level_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student (id INT NOT NULL, level_id INT NOT NULL, sex VARCHAR(1) DEFAULT NULL, date_of_birth DATE DEFAULT NULL, place_of_birth VARCHAR(255) DEFAULT NULL, matricule VARCHAR(20) DEFAULT NULL, nationality VARCHAR(255) DEFAULT NULL, parent_contact VARCHAR(255) DEFAULT NULL, address LONGTEXT DEFAULT NULL, INDEX IDX_B723AF335FB14BA7 (level_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE teacher (id INT NOT NULL, ec_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_B0F6A6D527634BEF (ec_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ue (id INT AUTO_INCREMENT NOT NULL, semester_id INT NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, credit INT NOT NULL, INDEX IDX_2E490A9B4A798B6F (semester_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE uemanager (id INT NOT NULL, ue_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_A37EC62C62E883B1 (ue_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE university (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, address VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, logo VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, phone_number VARCHAR(255) NOT NULL, profile_photo VARCHAR(255) NOT NULL, cni VARCHAR(255) NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, is_active TINYINT(1) DEFAULT 0 NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_role (user_id INT NOT NULL, role_id INT NOT NULL, INDEX IDX_2DE8C6A3A76ED395 (user_id), INDEX IDX_2DE8C6A3D60322AC (role_id), PRIMARY KEY(user_id, role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE verification_code (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, code VARCHAR(255) DEFAULT NULL, expires_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_used TINYINT(1) DEFAULT NULL, INDEX IDX_E821C39FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE administrator ADD CONSTRAINT FK_58DF0651309D1878 FOREIGN KEY (university_id) REFERENCES university (id)');
        $this->addSql('ALTER TABLE administrator ADD CONSTRAINT FK_58DF0651BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ec ADD CONSTRAINT FK_8DE8BDFF62E883B1 FOREIGN KEY (ue_id) REFERENCES ue (id)');
        $this->addSql('ALTER TABLE field ADD CONSTRAINT FK_5BF54558C32A47EE FOREIGN KEY (school_id) REFERENCES school (id)');
        $this->addSql('ALTER TABLE field_manager ADD CONSTRAINT FK_14000019443707B0 FOREIGN KEY (field_id) REFERENCES field (id)');
        $this->addSql('ALTER TABLE field_manager ADD CONSTRAINT FK_14000019BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE level ADD CONSTRAINT FK_9AEACC13443707B0 FOREIGN KEY (field_id) REFERENCES field (id)');
        $this->addSql('ALTER TABLE level_manager ADD CONSTRAINT FK_5B8C00A65FB14BA7 FOREIGN KEY (level_id) REFERENCES level (id)');
        $this->addSql('ALTER TABLE level_manager ADD CONSTRAINT FK_5B8C00A6BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE responsable ADD CONSTRAINT FK_52520D07BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE school ADD CONSTRAINT FK_F99EDABB309D1878 FOREIGN KEY (university_id) REFERENCES university (id)');
        $this->addSql('ALTER TABLE school_manager ADD CONSTRAINT FK_680B1721C32A47EE FOREIGN KEY (school_id) REFERENCES school (id)');
        $this->addSql('ALTER TABLE school_manager ADD CONSTRAINT FK_680B1721BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE semester ADD CONSTRAINT FK_F7388EED5FB14BA7 FOREIGN KEY (level_id) REFERENCES level (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF335FB14BA7 FOREIGN KEY (level_id) REFERENCES level (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE teacher ADD CONSTRAINT FK_B0F6A6D527634BEF FOREIGN KEY (ec_id) REFERENCES ec (id)');
        $this->addSql('ALTER TABLE teacher ADD CONSTRAINT FK_B0F6A6D5BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ue ADD CONSTRAINT FK_2E490A9B4A798B6F FOREIGN KEY (semester_id) REFERENCES semester (id)');
        $this->addSql('ALTER TABLE uemanager ADD CONSTRAINT FK_A37EC62C62E883B1 FOREIGN KEY (ue_id) REFERENCES ue (id)');
        $this->addSql('ALTER TABLE uemanager ADD CONSTRAINT FK_A37EC62CBF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_role ADD CONSTRAINT FK_2DE8C6A3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_role ADD CONSTRAINT FK_2DE8C6A3D60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE verification_code ADD CONSTRAINT FK_E821C39FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE administrator DROP FOREIGN KEY FK_58DF0651309D1878');
        $this->addSql('ALTER TABLE administrator DROP FOREIGN KEY FK_58DF0651BF396750');
        $this->addSql('ALTER TABLE ec DROP FOREIGN KEY FK_8DE8BDFF62E883B1');
        $this->addSql('ALTER TABLE field DROP FOREIGN KEY FK_5BF54558C32A47EE');
        $this->addSql('ALTER TABLE field_manager DROP FOREIGN KEY FK_14000019443707B0');
        $this->addSql('ALTER TABLE field_manager DROP FOREIGN KEY FK_14000019BF396750');
        $this->addSql('ALTER TABLE level DROP FOREIGN KEY FK_9AEACC13443707B0');
        $this->addSql('ALTER TABLE level_manager DROP FOREIGN KEY FK_5B8C00A65FB14BA7');
        $this->addSql('ALTER TABLE level_manager DROP FOREIGN KEY FK_5B8C00A6BF396750');
        $this->addSql('ALTER TABLE responsable DROP FOREIGN KEY FK_52520D07BF396750');
        $this->addSql('ALTER TABLE school DROP FOREIGN KEY FK_F99EDABB309D1878');
        $this->addSql('ALTER TABLE school_manager DROP FOREIGN KEY FK_680B1721C32A47EE');
        $this->addSql('ALTER TABLE school_manager DROP FOREIGN KEY FK_680B1721BF396750');
        $this->addSql('ALTER TABLE semester DROP FOREIGN KEY FK_F7388EED5FB14BA7');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF335FB14BA7');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF33BF396750');
        $this->addSql('ALTER TABLE teacher DROP FOREIGN KEY FK_B0F6A6D527634BEF');
        $this->addSql('ALTER TABLE teacher DROP FOREIGN KEY FK_B0F6A6D5BF396750');
        $this->addSql('ALTER TABLE ue DROP FOREIGN KEY FK_2E490A9B4A798B6F');
        $this->addSql('ALTER TABLE uemanager DROP FOREIGN KEY FK_A37EC62C62E883B1');
        $this->addSql('ALTER TABLE uemanager DROP FOREIGN KEY FK_A37EC62CBF396750');
        $this->addSql('ALTER TABLE user_role DROP FOREIGN KEY FK_2DE8C6A3A76ED395');
        $this->addSql('ALTER TABLE user_role DROP FOREIGN KEY FK_2DE8C6A3D60322AC');
        $this->addSql('ALTER TABLE verification_code DROP FOREIGN KEY FK_E821C39FA76ED395');
        $this->addSql('DROP TABLE action');
        $this->addSql('DROP TABLE administrator');
        $this->addSql('DROP TABLE ec');
        $this->addSql('DROP TABLE field');
        $this->addSql('DROP TABLE field_manager');
        $this->addSql('DROP TABLE level');
        $this->addSql('DROP TABLE level_manager');
        $this->addSql('DROP TABLE responsable');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE school');
        $this->addSql('DROP TABLE school_manager');
        $this->addSql('DROP TABLE semester');
        $this->addSql('DROP TABLE student');
        $this->addSql('DROP TABLE teacher');
        $this->addSql('DROP TABLE ue');
        $this->addSql('DROP TABLE uemanager');
        $this->addSql('DROP TABLE university');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_role');
        $this->addSql('DROP TABLE verification_code');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

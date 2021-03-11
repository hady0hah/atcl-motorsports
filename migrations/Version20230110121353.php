<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230110121353 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
//        $this->addSql('ALTER TABLE championship ADD published TINYINT(1) NOT NULL');
//        $this->addSql('ALTER TABLE document ADD published TINYINT(1) NOT NULL');
//        $this->addSql('ALTER TABLE document_category ADD published TINYINT(1) NOT NULL');
//        $this->addSql('ALTER TABLE driver ADD published TINYINT(1) NOT NULL, CHANGE license_number license_number LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\'');
//        $this->addSql('ALTER TABLE event ADD published TINYINT(1) NOT NULL');
//        $this->addSql('ALTER TABLE license ADD published TINYINT(1) NOT NULL');
//        $this->addSql('ALTER TABLE license_grade ADD published TINYINT(1) NOT NULL');
//        $this->addSql('ALTER TABLE license_grade_price ADD published TINYINT(1) NOT NULL');
//        $this->addSql('ALTER TABLE participant ADD published TINYINT(1) NOT NULL');
//        $this->addSql('ALTER TABLE result ADD published TINYINT(1) NOT NULL');
//        $this->addSql('ALTER TABLE section ADD published TINYINT(1) NOT NULL, CHANGE end_date end_date DATETIME NOT NULL');
//        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
//        $this->addSql('ALTER TABLE z_type ADD published TINYINT(1) NOT NULL, CHANGE config config LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
//        $this->addSql('ALTER TABLE championship DROP published');
//        $this->addSql('ALTER TABLE document DROP published');
//        $this->addSql('ALTER TABLE document_category DROP published');
//        $this->addSql('ALTER TABLE driver DROP published, CHANGE license_number license_number LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_bin`');
//        $this->addSql('ALTER TABLE event DROP published');
//        $this->addSql('ALTER TABLE license DROP published');
//        $this->addSql('ALTER TABLE license_grade DROP published');
//        $this->addSql('ALTER TABLE license_grade_price DROP published');
//        $this->addSql('ALTER TABLE participant DROP published');
//        $this->addSql('ALTER TABLE result DROP published');
//        $this->addSql('ALTER TABLE section DROP published, CHANGE end_date end_date DATETIME DEFAULT NULL');
//        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
//        $this->addSql('ALTER TABLE z_type DROP published, CHANGE config config LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_bin`');
    }
}

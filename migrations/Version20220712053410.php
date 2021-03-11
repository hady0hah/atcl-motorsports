<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220712053410 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE event_participant');
        $this->addSql('ALTER TABLE participant ADD gap DOUBLE PRECISION DEFAULT NULL, ADD ordering INT DEFAULT NULL');
        $this->addSql('ALTER TABLE result ADD penalty VARCHAR(255) DEFAULT NULL, ADD expected_start_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE section ADD section_type VARCHAR(255) DEFAULT NULL, ADD expected_start_date DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event_participant (event_id INT NOT NULL, participant_id INT NOT NULL, INDEX IDX_7C16B89171F7E88B (event_id), INDEX IDX_7C16B8919D1C3019 (participant_id), PRIMARY KEY(event_id, participant_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE event_participant ADD CONSTRAINT FK_7C16B89171F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_participant ADD CONSTRAINT FK_7C16B8919D1C3019 FOREIGN KEY (participant_id) REFERENCES participant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE participant DROP gap, DROP ordering');
        $this->addSql('ALTER TABLE result DROP penalty, DROP expected_start_date');
        $this->addSql('ALTER TABLE section DROP section_type, DROP expected_start_date');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220723201336 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Adding Parent/Children Results + Change Penalty to float';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE result ADD parent_result_id INT DEFAULT NULL, CHANGE penalty penalty DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC113F6EC99AD FOREIGN KEY (parent_result_id) REFERENCES result (id)');
        $this->addSql('CREATE INDEX IDX_136AC113F6EC99AD ON result (parent_result_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC113F6EC99AD');
        $this->addSql('DROP INDEX IDX_136AC113F6EC99AD ON result');
        $this->addSql('ALTER TABLE result DROP parent_result_id, CHANGE penalty penalty VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}

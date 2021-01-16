<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210108055617 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE criteres_referentiel (id INT AUTO_INCREMENT NOT NULL, reference_referentiel_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, libelle VARCHAR(255) NOT NULL, archive TINYINT(1) NOT NULL, INDEX IDX_FBF02686D9CD741B (reference_referentiel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE criteres_referentiel ADD CONSTRAINT FK_FBF02686D9CD741B FOREIGN KEY (reference_referentiel_id) REFERENCES referentiel (id)');
        $this->addSql('ALTER TABLE referentiel DROP criteres, CHANGE programme programme LONGBLOB DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE criteres_referentiel');
        $this->addSql('ALTER TABLE referentiel ADD criteres VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE programme programme VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}

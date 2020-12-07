<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201204093728 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE groupe_promo_formateur (groupe_promo_id INT NOT NULL, formateur_id INT NOT NULL, INDEX IDX_4C08732B4162718F (groupe_promo_id), INDEX IDX_4C08732B155D8F51 (formateur_id), PRIMARY KEY(groupe_promo_id, formateur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE groupe_promo_formateur ADD CONSTRAINT FK_4C08732B4162718F FOREIGN KEY (groupe_promo_id) REFERENCES groupe_promo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_promo_formateur ADD CONSTRAINT FK_4C08732B155D8F51 FOREIGN KEY (formateur_id) REFERENCES formateur (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE groupe_promo_formateur');
    }
}

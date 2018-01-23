<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180121113449 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE optie DROP optie_prijs, CHANGE optie_datum_uit optie_datum_uit DATETIME DEFAULT NULL, CHANGE optie_datum_terug optie_datum_terug DATETIME DEFAULT NULL, CHANGE optie_dagen_verhuurd optie_dagen_verhuurd CHAR(255) DEFAULT NULL COMMENT \'(DC2Type:dateinterval)\'');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE optie ADD optie_prijs NUMERIC(10, 0) NOT NULL, CHANGE optie_datum_uit optie_datum_uit DATETIME NOT NULL, CHANGE optie_datum_terug optie_datum_terug DATETIME NOT NULL, CHANGE optie_dagen_verhuurd optie_dagen_verhuurd CHAR(255) NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:dateinterval)\'');
    }
}

<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170806183641 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE area (id INT AUTO_INCREMENT NOT NULL, location_id INT DEFAULT NULL, imagePath VARCHAR(255) DEFAULT NULL, isActive TINYINT(1) NOT NULL, created DATETIME NOT NULL, INDEX IDX_D7943D6864D218E (location_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE AreaTranslation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, areaName VARCHAR(255) NOT NULL, locale VARCHAR(10) NOT NULL, INDEX IDX_568BE6E42C2AC5D3 (translatable_id), UNIQUE INDEX UNIQ_568BE6E42C2AC5D34180C698 (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE area ADD CONSTRAINT FK_D7943D6864D218E FOREIGN KEY (location_id) REFERENCES locations (id)');
        $this->addSql('ALTER TABLE AreaTranslation ADD CONSTRAINT FK_568BE6E42C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES area (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE AreaTranslation DROP FOREIGN KEY FK_568BE6E42C2AC5D3');
        $this->addSql('DROP TABLE area');
        $this->addSql('DROP TABLE AreaTranslation');
    }
}

<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170806150331 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE locations (id INT AUTO_INCREMENT NOT NULL, isActive TINYINT(1) NOT NULL, created DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE LocationTranslation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, locationHeading VARCHAR(255) DEFAULT NULL, locale VARCHAR(10) NOT NULL, INDEX IDX_CE124D0B2C2AC5D3 (translatable_id), UNIQUE INDEX UNIQ_CE124D0B2C2AC5D34180C698 (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE LocationTranslation ADD CONSTRAINT FK_CE124D0B2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES locations (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE LocationTranslation DROP FOREIGN KEY FK_CE124D0B2C2AC5D3');
        $this->addSql('DROP TABLE locations');
        $this->addSql('DROP TABLE LocationTranslation');
    }
}

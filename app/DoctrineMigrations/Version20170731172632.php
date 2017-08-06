<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170731172632 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE UserTranslation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, firstName VARCHAR(255) DEFAULT NULL, lastName VARCHAR(255) DEFAULT NULL, mobileNumber BIGINT DEFAULT NULL, locale VARCHAR(10) NOT NULL, INDEX IDX_7E1CC2B22C2AC5D3 (translatable_id), UNIQUE INDEX UNIQ_7E1CC2B22C2AC5D34180C698 (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE UserTranslation ADD CONSTRAINT FK_7E1CC2B22C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES fos_user (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE fos_user_translation');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE fos_user_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, mobileNumber BIGINT DEFAULT NULL, locale VARCHAR(10) NOT NULL COLLATE utf8_unicode_ci, firstName VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, lastName VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, UNIQUE INDEX UNIQ_D253BB6E2C2AC5D34180C698 (translatable_id, locale), INDEX IDX_D253BB6E2C2AC5D3 (translatable_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fos_user_translation ADD CONSTRAINT FK_D253BB6E2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES fos_user (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE UserTranslation');
    }
}

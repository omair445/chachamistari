<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170730115530 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fos_user DROP FOREIGN KEY FK_957A6479F72FF47C');
        $this->addSql('DROP INDEX IDX_957A6479F72FF47C ON fos_user');
        $this->addSql('ALTER TABLE fos_user DROP user_translation_id');
        $this->addSql('ALTER TABLE fos_user_translation ADD user_id INT DEFAULT NULL, DROP user');
        $this->addSql('ALTER TABLE fos_user_translation ADD CONSTRAINT FK_D253BB6EA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)');
        $this->addSql('CREATE INDEX IDX_D253BB6EA76ED395 ON fos_user_translation (user_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fos_user ADD user_translation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fos_user ADD CONSTRAINT FK_957A6479F72FF47C FOREIGN KEY (user_translation_id) REFERENCES fos_user_translation (id)');
        $this->addSql('CREATE INDEX IDX_957A6479F72FF47C ON fos_user (user_translation_id)');
        $this->addSql('ALTER TABLE fos_user_translation DROP FOREIGN KEY FK_D253BB6EA76ED395');
        $this->addSql('DROP INDEX IDX_D253BB6EA76ED395 ON fos_user_translation');
        $this->addSql('ALTER TABLE fos_user_translation ADD user VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, DROP user_id');
    }
}

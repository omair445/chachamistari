<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170731185929 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ServiceResponsesTranslation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, successMsg VARCHAR(255) NOT NULL, failureMsg VARCHAR(255) NOT NULL, locale VARCHAR(10) NOT NULL, INDEX IDX_94E9A7852C2AC5D3 (translatable_id), UNIQUE INDEX UNIQ_94E9A7852C2AC5D34180C698 (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ServiceResponsesTranslation ADD CONSTRAINT FK_94E9A7852C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES service_responses (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE service_responses_translations');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE service_responses_translations (id INT AUTO_INCREMENT NOT NULL, serviceResponseTranslations_id INT DEFAULT NULL, locale VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, successMsg VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, failureMsg VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, INDEX IDX_F1924D3C3C5C70E6 (serviceResponseTranslations_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE service_responses_translations ADD CONSTRAINT FK_F1924D3C3C5C70E6 FOREIGN KEY (serviceResponseTranslations_id) REFERENCES service_responses (id)');
        $this->addSql('DROP TABLE ServiceResponsesTranslation');
    }
}

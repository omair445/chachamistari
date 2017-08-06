<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170731100630 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE service_responses CHANGE service_type serviceType VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE service_responses_translations DROP FOREIGN KEY FK_F1924D3CA9D770C2');
        $this->addSql('DROP INDEX IDX_F1924D3CA9D770C2 ON service_responses_translations');
        $this->addSql('ALTER TABLE service_responses_translations ADD successMsg VARCHAR(255) NOT NULL, ADD failureMsg VARCHAR(255) NOT NULL, DROP success_msg, DROP failure_msg, CHANGE service_response_translations_id serviceResponseTranslations_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE service_responses_translations ADD CONSTRAINT FK_F1924D3C3C5C70E6 FOREIGN KEY (serviceResponseTranslations_id) REFERENCES service_responses (id)');
        $this->addSql('CREATE INDEX IDX_F1924D3C3C5C70E6 ON service_responses_translations (serviceResponseTranslations_id)');
        $this->addSql('ALTER TABLE fos_user_translation ADD translatable_id INT DEFAULT NULL, ADD firstName VARCHAR(255) DEFAULT NULL, ADD lastName VARCHAR(255) DEFAULT NULL, DROP first_name, DROP last_name, CHANGE mobile_number mobileNumber BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE fos_user_translation ADD CONSTRAINT FK_D253BB6E2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES fos_user (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_D253BB6E2C2AC5D3 ON fos_user_translation (translatable_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D253BB6E2C2AC5D34180C698 ON fos_user_translation (translatable_id, locale)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fos_user_translation DROP FOREIGN KEY FK_D253BB6E2C2AC5D3');
        $this->addSql('DROP INDEX IDX_D253BB6E2C2AC5D3 ON fos_user_translation');
        $this->addSql('DROP INDEX UNIQ_D253BB6E2C2AC5D34180C698 ON fos_user_translation');
        $this->addSql('ALTER TABLE fos_user_translation ADD first_name VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD last_name VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, DROP translatable_id, DROP firstName, DROP lastName, CHANGE mobilenumber mobile_number BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE service_responses CHANGE servicetype service_type VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE service_responses_translations DROP FOREIGN KEY FK_F1924D3C3C5C70E6');
        $this->addSql('DROP INDEX IDX_F1924D3C3C5C70E6 ON service_responses_translations');
        $this->addSql('ALTER TABLE service_responses_translations ADD success_msg VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ADD failure_msg VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, DROP successMsg, DROP failureMsg, CHANGE serviceresponsetranslations_id service_response_translations_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE service_responses_translations ADD CONSTRAINT FK_F1924D3CA9D770C2 FOREIGN KEY (service_response_translations_id) REFERENCES service_responses (id)');
        $this->addSql('CREATE INDEX IDX_F1924D3CA9D770C2 ON service_responses_translations (service_response_translations_id)');
    }
}

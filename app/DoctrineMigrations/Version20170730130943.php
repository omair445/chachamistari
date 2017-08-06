<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170730130943 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE service_responses ADD service_type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE service_responses_translations ADD success_msg VARCHAR(255) NOT NULL, ADD failure_response VARCHAR(255) NOT NULL, DROP user_sign_up_response, DROP user_login_response, DROP service_translations, DROP locale');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE service_responses DROP service_type');
        $this->addSql('ALTER TABLE service_responses_translations ADD user_sign_up_response VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ADD user_login_response VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ADD service_translations VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ADD locale VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, DROP success_msg, DROP failure_response');
    }
}

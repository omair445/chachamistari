<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170730125516 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE service_responses (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service_responses_translations (id INT AUTO_INCREMENT NOT NULL, service_response_translations_id INT DEFAULT NULL, user_sign_up_response VARCHAR(255) NOT NULL, user_login_response VARCHAR(255) NOT NULL, service_translations VARCHAR(255) NOT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_F1924D3CA9D770C2 (service_response_translations_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE service_responses_translations ADD CONSTRAINT FK_F1924D3CA9D770C2 FOREIGN KEY (service_response_translations_id) REFERENCES service_responses (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE service_responses_translations DROP FOREIGN KEY FK_F1924D3CA9D770C2');
        $this->addSql('DROP TABLE service_responses');
        $this->addSql('DROP TABLE service_responses_translations');
    }
}

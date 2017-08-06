<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170806022945 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE app_user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, status TINYINT(1) NOT NULL, userRole VARCHAR(255) DEFAULT NULL, created DATETIME DEFAULT NULL, verification_code INT DEFAULT NULL, mobileNumber BIGINT DEFAULT NULL, UNIQUE INDEX UNIQ_88BDF3E955E247B2 (mobileNumber), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE AppUserTranslation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, firstName VARCHAR(255) DEFAULT NULL, lastName VARCHAR(255) DEFAULT NULL, locale VARCHAR(10) NOT NULL, INDEX IDX_20D9B5F32C2AC5D3 (translatable_id), UNIQUE INDEX UNIQ_20D9B5F32C2AC5D34180C698 (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE login_tokens (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, token_key VARCHAR(255) NOT NULL, is_enabled TINYINT(1) NOT NULL, created DATETIME NOT NULL, INDEX user_id (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service_responses (id INT AUTO_INCREMENT NOT NULL, serviceType VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ServiceResponsesTranslation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, successMsg VARCHAR(255) NOT NULL, failureMsg VARCHAR(255) NOT NULL, failureMsg1 VARCHAR(255) DEFAULT NULL, locale VARCHAR(10) NOT NULL, INDEX IDX_94E9A7852C2AC5D3 (translatable_id), UNIQUE INDEX UNIQ_94E9A7852C2AC5D34180C698 (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fos_user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_957A647992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_957A6479A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_957A6479C05FB297 (confirmation_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE UserTranslation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, firstName VARCHAR(255) DEFAULT NULL, lastName VARCHAR(255) DEFAULT NULL, mobileNumber BIGINT DEFAULT NULL, locale VARCHAR(10) NOT NULL, INDEX IDX_7E1CC2B22C2AC5D3 (translatable_id), UNIQUE INDEX UNIQ_7E1CC2B22C2AC5D34180C698 (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE AppUserTranslation ADD CONSTRAINT FK_20D9B5F32C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES app_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE login_tokens ADD CONSTRAINT FK_5D3E3614A76ED395 FOREIGN KEY (user_id) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE ServiceResponsesTranslation ADD CONSTRAINT FK_94E9A7852C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES service_responses (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE UserTranslation ADD CONSTRAINT FK_7E1CC2B22C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES fos_user (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE AppUserTranslation DROP FOREIGN KEY FK_20D9B5F32C2AC5D3');
        $this->addSql('ALTER TABLE login_tokens DROP FOREIGN KEY FK_5D3E3614A76ED395');
        $this->addSql('ALTER TABLE ServiceResponsesTranslation DROP FOREIGN KEY FK_94E9A7852C2AC5D3');
        $this->addSql('ALTER TABLE UserTranslation DROP FOREIGN KEY FK_7E1CC2B22C2AC5D3');
        $this->addSql('DROP TABLE app_user');
        $this->addSql('DROP TABLE AppUserTranslation');
        $this->addSql('DROP TABLE login_tokens');
        $this->addSql('DROP TABLE service_responses');
        $this->addSql('DROP TABLE ServiceResponsesTranslation');
        $this->addSql('DROP TABLE fos_user');
        $this->addSql('DROP TABLE UserTranslation');
    }
}

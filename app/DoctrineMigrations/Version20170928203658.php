<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170928203658 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE company_images (id INT AUTO_INCREMENT NOT NULL, imageUrl VARCHAR(255) DEFAULT NULL, company VARCHAR(255) NOT NULL, created DATETIME DEFAULT NULL, isPrivate_id INT DEFAULT NULL, INDEX IDX_DD80DA1FD4B1167D (isPrivate_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE company_owner (id INT AUTO_INCREMENT NOT NULL, created DATETIME NOT NULL, status TINYINT(1) NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, cnic VARCHAR(255) DEFAULT NULL, phone INT DEFAULT NULL, age INT DEFAULT NULL, homeTown VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE company_images ADD CONSTRAINT FK_DD80DA1FD4B1167D FOREIGN KEY (isPrivate_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE company ADD owner_id INT DEFAULT NULL, ADD description LONGTEXT DEFAULT NULL, ADD startTime TIME DEFAULT NULL, ADD endTime TIME DEFAULT NULL, ADD vistingCardImageUrl VARCHAR(255) DEFAULT NULL, ADD shopage INT DEFAULT NULL');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094F7E3C61F9 FOREIGN KEY (owner_id) REFERENCES company_owner (id)');
        $this->addSql('CREATE INDEX IDX_4FBF094F7E3C61F9 ON company (owner_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094F7E3C61F9');
        $this->addSql('DROP TABLE company_images');
        $this->addSql('DROP TABLE company_owner');
        $this->addSql('DROP INDEX IDX_4FBF094F7E3C61F9 ON company');
        $this->addSql('ALTER TABLE company DROP owner_id, DROP description, DROP startTime, DROP endTime, DROP vistingCardImageUrl, DROP shopage');
    }
}

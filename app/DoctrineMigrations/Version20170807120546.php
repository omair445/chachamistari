<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170807120546 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE company CHANGE lat lat DOUBLE PRECISION DEFAULT NULL, CHANGE `long` `long` DOUBLE PRECISION DEFAULT NULL, CHANGE imageUrl imageUrl VARCHAR(255) DEFAULT NULL, CHANGE phone phone INT DEFAULT NULL, CHANGE instagram instagram VARCHAR(255) DEFAULT NULL, CHANGE facebook facebook VARCHAR(255) DEFAULT NULL, CHANGE website website VARCHAR(255) DEFAULT NULL, CHANGE email email VARCHAR(255) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE company CHANGE lat lat DOUBLE PRECISION NOT NULL, CHANGE `long` `long` DOUBLE PRECISION NOT NULL, CHANGE imageUrl imageUrl VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE phone phone INT NOT NULL, CHANGE instagram instagram VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE facebook facebook VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE website website VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE email email VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
    }
}

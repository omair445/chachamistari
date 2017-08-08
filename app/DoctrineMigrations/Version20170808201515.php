<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170808201515 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE companyfavourite DROP FOREIGN KEY FK_D60418B7A76ED395');
        $this->addSql('ALTER TABLE companyfavourite ADD CONSTRAINT FK_D60418B7A76ED395 FOREIGN KEY (user_id) REFERENCES app_user (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE companyFavourite DROP FOREIGN KEY FK_D60418B7A76ED395');
        $this->addSql('ALTER TABLE companyFavourite ADD CONSTRAINT FK_D60418B7A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)');
    }
}

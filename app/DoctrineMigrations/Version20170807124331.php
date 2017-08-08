<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170807124331 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE app_user DROP FOREIGN KEY FK_88BDF3E964D218E');
        $this->addSql('ALTER TABLE app_user DROP FOREIGN KEY FK_88BDF3E9BD0F409C');
        $this->addSql('ALTER TABLE app_user DROP FOREIGN KEY FK_88BDF3E9ED5CA9E6');
        $this->addSql('DROP INDEX IDX_88BDF3E964D218E ON app_user');
        $this->addSql('DROP INDEX IDX_88BDF3E9BD0F409C ON app_user');
        $this->addSql('DROP INDEX IDX_88BDF3E9ED5CA9E6 ON app_user');
        $this->addSql('ALTER TABLE app_user DROP location_id, DROP area_id, DROP service_id');
        $this->addSql('ALTER TABLE company ADD location_id INT DEFAULT NULL, ADD area_id INT DEFAULT NULL, ADD service_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094F64D218E FOREIGN KEY (location_id) REFERENCES locations (id)');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094FBD0F409C FOREIGN KEY (area_id) REFERENCES area (id)');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094FED5CA9E6 FOREIGN KEY (service_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_4FBF094F64D218E ON company (location_id)');
        $this->addSql('CREATE INDEX IDX_4FBF094FBD0F409C ON company (area_id)');
        $this->addSql('CREATE INDEX IDX_4FBF094FED5CA9E6 ON company (service_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE app_user ADD location_id INT DEFAULT NULL, ADD area_id INT DEFAULT NULL, ADD service_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE app_user ADD CONSTRAINT FK_88BDF3E964D218E FOREIGN KEY (location_id) REFERENCES locations (id)');
        $this->addSql('ALTER TABLE app_user ADD CONSTRAINT FK_88BDF3E9BD0F409C FOREIGN KEY (area_id) REFERENCES area (id)');
        $this->addSql('ALTER TABLE app_user ADD CONSTRAINT FK_88BDF3E9ED5CA9E6 FOREIGN KEY (service_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_88BDF3E964D218E ON app_user (location_id)');
        $this->addSql('CREATE INDEX IDX_88BDF3E9BD0F409C ON app_user (area_id)');
        $this->addSql('CREATE INDEX IDX_88BDF3E9ED5CA9E6 ON app_user (service_id)');
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094F64D218E');
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094FBD0F409C');
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094FED5CA9E6');
        $this->addSql('DROP INDEX IDX_4FBF094F64D218E ON company');
        $this->addSql('DROP INDEX IDX_4FBF094FBD0F409C ON company');
        $this->addSql('DROP INDEX IDX_4FBF094FED5CA9E6 ON company');
        $this->addSql('ALTER TABLE company DROP location_id, DROP area_id, DROP service_id');
    }
}

<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170928203930 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE company_images DROP FOREIGN KEY FK_DD80DA1FD4B1167D');
        $this->addSql('DROP INDEX IDX_DD80DA1FD4B1167D ON company_images');
        $this->addSql('ALTER TABLE company_images ADD isPrivate TINYINT(1) NOT NULL, DROP company, CHANGE isprivate_id company_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE company_images ADD CONSTRAINT FK_DD80DA1F979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('CREATE INDEX IDX_DD80DA1F979B1AD6 ON company_images (company_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE company_images DROP FOREIGN KEY FK_DD80DA1F979B1AD6');
        $this->addSql('DROP INDEX IDX_DD80DA1F979B1AD6 ON company_images');
        $this->addSql('ALTER TABLE company_images ADD company VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, DROP isPrivate, CHANGE company_id isPrivate_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE company_images ADD CONSTRAINT FK_DD80DA1FD4B1167D FOREIGN KEY (isPrivate_id) REFERENCES company (id)');
        $this->addSql('CREATE INDEX IDX_DD80DA1FD4B1167D ON company_images (isPrivate_id)');
    }
}

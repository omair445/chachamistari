<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170808121528 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C699218D81');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6A0CDDBA6');
        $this->addSql('DROP INDEX IDX_794381C699218D81 ON review');
        $this->addSql('DROP INDEX IDX_794381C6A0CDDBA6 ON review');
        $this->addSql('ALTER TABLE review ADD company_id INT DEFAULT NULL, ADD totalRating DOUBLE PRECISION NOT NULL, ADD obtRating DOUBLE PRECISION NOT NULL, ADD message VARCHAR(255) DEFAULT NULL, DROP reviewMessage, DROP ratingPoints, DROP userId_id, DROP companyId_id');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('CREATE INDEX IDX_794381C6979B1AD6 ON review (company_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6979B1AD6');
        $this->addSql('DROP INDEX IDX_794381C6979B1AD6 ON review');
        $this->addSql('ALTER TABLE review ADD reviewMessage VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ADD ratingPoints INT NOT NULL, ADD companyId_id INT DEFAULT NULL, DROP totalRating, DROP obtRating, DROP message, CHANGE company_id userId_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C699218D81 FOREIGN KEY (userId_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6A0CDDBA6 FOREIGN KEY (companyId_id) REFERENCES company (id)');
        $this->addSql('CREATE INDEX IDX_794381C699218D81 ON review (userId_id)');
        $this->addSql('CREATE INDEX IDX_794381C6A0CDDBA6 ON review (companyId_id)');
    }
}

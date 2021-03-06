<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190619174535 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE special_proposition (id INT AUTO_INCREMENT NOT NULL, special_price DOUBLE PRECISION DEFAULT NULL, percent INT DEFAULT NULL, global_price INT DEFAULT NULL, quantity DOUBLE PRECISION DEFAULT NULL, status TINYINT(1) DEFAULT NULL, available_at DATETIME DEFAULT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE receipt ADD special_receipt_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE receipt ADD CONSTRAINT FK_5399B645CF3C8C26 FOREIGN KEY (special_receipt_id) REFERENCES special_proposition (id)');
        $this->addSql('CREATE INDEX IDX_5399B645CF3C8C26 ON receipt (special_receipt_id)');
        $this->addSql('ALTER TABLE product ADD special_proposition_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADED1582FE FOREIGN KEY (special_proposition_id) REFERENCES special_proposition (id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADED1582FE ON product (special_proposition_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE receipt DROP FOREIGN KEY FK_5399B645CF3C8C26');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADED1582FE');
        $this->addSql('DROP TABLE special_proposition');
        $this->addSql('DROP INDEX IDX_D34A04ADED1582FE ON product');
        $this->addSql('ALTER TABLE product DROP special_proposition_id');
        $this->addSql('DROP INDEX IDX_5399B645CF3C8C26 ON receipt');
        $this->addSql('ALTER TABLE receipt DROP special_receipt_id');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200501155944 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE dish DROP FOREIGN KEY FK_957D8CB8D7C83568');
        $this->addSql('CREATE TABLE menu (id INT AUTO_INCREMENT NOT NULL, date DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE lunch');
        $this->addSql('DROP INDEX IDX_957D8CB8D7C83568 ON dish');
        $this->addSql('ALTER TABLE dish CHANGE lunch_id menu_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE dish ADD CONSTRAINT FK_957D8CB8CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id)');
        $this->addSql('CREATE INDEX IDX_957D8CB8CCD7E912 ON dish (menu_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE dish DROP FOREIGN KEY FK_957D8CB8CCD7E912');
        $this->addSql('CREATE TABLE lunch (id INT AUTO_INCREMENT NOT NULL, date DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP INDEX IDX_957D8CB8CCD7E912 ON dish');
        $this->addSql('ALTER TABLE dish CHANGE menu_id lunch_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE dish ADD CONSTRAINT FK_957D8CB8D7C83568 FOREIGN KEY (lunch_id) REFERENCES lunch (id)');
        $this->addSql('CREATE INDEX IDX_957D8CB8D7C83568 ON dish (lunch_id)');
    }
}

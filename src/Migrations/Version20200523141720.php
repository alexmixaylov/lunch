<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200523141720 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE company ADD owner_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094F7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4FBF094F7E3C61F9 ON company (owner_id)');
        $this->addSql('ALTER TABLE user ADD company_persons_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649B266FB3B FOREIGN KEY (company_persons_id) REFERENCES company (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649B266FB3B ON user (company_persons_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094F7E3C61F9');
        $this->addSql('DROP INDEX UNIQ_4FBF094F7E3C61F9 ON company');
        $this->addSql('ALTER TABLE company DROP owner_id');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649B266FB3B');
        $this->addSql('DROP INDEX IDX_8D93D649B266FB3B ON user');
        $this->addSql('ALTER TABLE user DROP company_persons_id');
    }
}

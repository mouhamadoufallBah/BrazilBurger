<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220108040119 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE burger DROP FOREIGN KEY FK_EFE35A0DD1322E03');
        $this->addSql('DROP INDEX IDX_EFE35A0DD1322E03 ON burger');
        $this->addSql('ALTER TABLE burger DROP complements_id');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A931769E031');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A93D1322E03');
        $this->addSql('DROP INDEX IDX_7D053A93D1322E03 ON menu');
        $this->addSql('DROP INDEX IDX_7D053A931769E031 ON menu');
        $this->addSql('ALTER TABLE menu DROP burgers_id, DROP complements_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE burger ADD complements_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE burger ADD CONSTRAINT FK_EFE35A0DD1322E03 FOREIGN KEY (complements_id) REFERENCES complement (id)');
        $this->addSql('CREATE INDEX IDX_EFE35A0DD1322E03 ON burger (complements_id)');
        $this->addSql('ALTER TABLE menu ADD burgers_id INT NOT NULL, ADD complements_id INT NOT NULL');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A931769E031 FOREIGN KEY (burgers_id) REFERENCES burger (id)');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A93D1322E03 FOREIGN KEY (complements_id) REFERENCES complement (id)');
        $this->addSql('CREATE INDEX IDX_7D053A93D1322E03 ON menu (complements_id)');
        $this->addSql('CREATE INDEX IDX_7D053A931769E031 ON menu (burgers_id)');
    }
}

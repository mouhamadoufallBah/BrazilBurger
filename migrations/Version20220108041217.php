<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220108041217 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE burger ADD menu_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE burger ADD CONSTRAINT FK_EFE35A0DCCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id)');
        $this->addSql('CREATE INDEX IDX_EFE35A0DCCD7E912 ON burger (menu_id)');
        $this->addSql('ALTER TABLE complement ADD menu_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE complement ADD CONSTRAINT FK_F8A41E34CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id)');
        $this->addSql('CREATE INDEX IDX_F8A41E34CCD7E912 ON complement (menu_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE burger DROP FOREIGN KEY FK_EFE35A0DCCD7E912');
        $this->addSql('DROP INDEX IDX_EFE35A0DCCD7E912 ON burger');
        $this->addSql('ALTER TABLE burger DROP menu_id');
        $this->addSql('ALTER TABLE complement DROP FOREIGN KEY FK_F8A41E34CCD7E912');
        $this->addSql('DROP INDEX IDX_F8A41E34CCD7E912 ON complement');
        $this->addSql('ALTER TABLE complement DROP menu_id');
    }
}

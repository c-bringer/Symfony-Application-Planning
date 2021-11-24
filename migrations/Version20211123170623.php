<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211123170623 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cours ADD fk_intervenant_id INT DEFAULT NULL, ADD fk_groupe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9CDE94FEFF FOREIGN KEY (fk_intervenant_id) REFERENCES intervenant (id)');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9C1B1CE21C FOREIGN KEY (fk_groupe_id) REFERENCES groupe (id)');
        $this->addSql('CREATE INDEX IDX_FDCA8C9CDE94FEFF ON cours (fk_intervenant_id)');
        $this->addSql('CREATE INDEX IDX_FDCA8C9C1B1CE21C ON cours (fk_groupe_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9CDE94FEFF');
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9C1B1CE21C');
        $this->addSql('DROP INDEX IDX_FDCA8C9CDE94FEFF ON cours');
        $this->addSql('DROP INDEX IDX_FDCA8C9C1B1CE21C ON cours');
        $this->addSql('ALTER TABLE cours DROP fk_intervenant_id, DROP fk_groupe_id');
    }
}

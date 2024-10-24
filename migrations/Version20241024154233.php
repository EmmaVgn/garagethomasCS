<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241024154233 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE critair (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, image_name VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE energy (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product ADD energy_id INT DEFAULT NULL, ADD critair_id INT DEFAULT NULL, DROP energy, DROP critair, CHANGE circulation_at circulation_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADEDDF52D FOREIGN KEY (energy_id) REFERENCES energy (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADDF30CE08 FOREIGN KEY (critair_id) REFERENCES critair (id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADEDDF52D ON product (energy_id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADDF30CE08 ON product (critair_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADDF30CE08');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADEDDF52D');
        $this->addSql('DROP TABLE critair');
        $this->addSql('DROP TABLE energy');
        $this->addSql('DROP INDEX IDX_D34A04ADEDDF52D ON product');
        $this->addSql('DROP INDEX IDX_D34A04ADDF30CE08 ON product');
        $this->addSql('ALTER TABLE product ADD energy VARCHAR(255) NOT NULL, ADD critair VARCHAR(255) NOT NULL, DROP energy_id, DROP critair_id, CHANGE circulation_at circulation_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }
}

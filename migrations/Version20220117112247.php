<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220117112247 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ad_spend_account (id INT AUTO_INCREMENT NOT NULL, ad_spend_source_id INT NOT NULL, shop_id INT NOT NULL, data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', INDEX IDX_62452FB7DD0A8B18 (ad_spend_source_id), INDEX IDX_62452FB74D16C4DD (shop_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ad_spend_source (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(32) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ad_spend_account ADD CONSTRAINT FK_62452FB7DD0A8B18 FOREIGN KEY (ad_spend_source_id) REFERENCES ad_spend_source (id)');
        $this->addSql('ALTER TABLE ad_spend_account ADD CONSTRAINT FK_62452FB74D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id)');
        $this->addSql('ALTER TABLE product ADD status VARCHAR(16) NOT NULL');
        $this->addSql('ALTER TABLE shop CHANGE name url VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ad_spend_account DROP FOREIGN KEY FK_62452FB7DD0A8B18');
        $this->addSql('DROP TABLE ad_spend_account');
        $this->addSql('DROP TABLE ad_spend_source');
        $this->addSql('ALTER TABLE product DROP status');
        $this->addSql('ALTER TABLE shop CHANGE url name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}

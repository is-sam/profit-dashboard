<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220131021744 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE shipping_profile (id INT AUTO_INCREMENT NOT NULL, shop_id INT NOT NULL, name VARCHAR(32) NOT NULL, countries LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', cost NUMERIC(7, 2) NOT NULL, is_variant_profile TINYINT(1) NOT NULL, INDEX IDX_671FACCB4D16C4DD (shop_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shipping_profile_variant (shipping_profile_id INT NOT NULL, variant_id INT NOT NULL, INDEX IDX_FB5EA7B8740142E6 (shipping_profile_id), INDEX IDX_FB5EA7B83B69A9AF (variant_id), PRIMARY KEY(shipping_profile_id, variant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shipping_profile ADD CONSTRAINT FK_671FACCB4D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id)');
        $this->addSql('ALTER TABLE shipping_profile_variant ADD CONSTRAINT FK_FB5EA7B8740142E6 FOREIGN KEY (shipping_profile_id) REFERENCES shipping_profile (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shipping_profile_variant ADD CONSTRAINT FK_FB5EA7B83B69A9AF FOREIGN KEY (variant_id) REFERENCES variant (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shipping_profile_variant DROP FOREIGN KEY FK_FB5EA7B8740142E6');
        $this->addSql('DROP TABLE shipping_profile');
        $this->addSql('DROP TABLE shipping_profile_variant');
    }
}

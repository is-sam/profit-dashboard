<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220124203358 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ad_spend_account DROP FOREIGN KEY FK_62452FB7DD0A8B18');
        $this->addSql('CREATE TABLE marketing_account (id INT AUTO_INCREMENT NOT NULL, marketing_source_id INT NOT NULL, shop_id INT NOT NULL, data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', INDEX IDX_D6DB0D2BD59EBB74 (marketing_source_id), INDEX IDX_D6DB0D2B4D16C4DD (shop_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE marketing_source (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(32) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE marketing_account ADD CONSTRAINT FK_D6DB0D2BD59EBB74 FOREIGN KEY (marketing_source_id) REFERENCES marketing_source (id)');
        $this->addSql('ALTER TABLE marketing_account ADD CONSTRAINT FK_D6DB0D2B4D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id)');
        $this->addSql('DROP TABLE ad_spend_account');
        $this->addSql('DROP TABLE ad_spend_source');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE marketing_account DROP FOREIGN KEY FK_D6DB0D2BD59EBB74');
        $this->addSql('CREATE TABLE ad_spend_account (id INT AUTO_INCREMENT NOT NULL, ad_spend_source_id INT NOT NULL, shop_id INT NOT NULL, data LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', INDEX IDX_62452FB74D16C4DD (shop_id), INDEX IDX_62452FB7DD0A8B18 (ad_spend_source_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE ad_spend_source (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(32) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE ad_spend_account ADD CONSTRAINT FK_62452FB74D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id)');
        $this->addSql('ALTER TABLE ad_spend_account ADD CONSTRAINT FK_62452FB7DD0A8B18 FOREIGN KEY (ad_spend_source_id) REFERENCES ad_spend_source (id)');
        $this->addSql('DROP TABLE marketing_account');
        $this->addSql('DROP TABLE marketing_source');
    }
}

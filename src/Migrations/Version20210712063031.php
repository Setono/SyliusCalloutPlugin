<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210712063031 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE setono_sylius_callout__callout (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(64) NOT NULL, name VARCHAR(255) NOT NULL, starts_at DATETIME DEFAULT NULL, ends_at DATETIME DEFAULT NULL, priority INT NOT NULL, position VARCHAR(255) DEFAULT NULL, enabled TINYINT(1) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT NULL, rules_assigned_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_FCD13FF077153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE setono_sylius_callout__callout_channels (callout_id INT NOT NULL, channel_id INT NOT NULL, INDEX IDX_782BF40EECDD285E (callout_id), INDEX IDX_782BF40E72F5A1AA (channel_id), PRIMARY KEY(callout_id, channel_id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE setono_sylius_callout__callout_rule (id INT AUTO_INCREMENT NOT NULL, callout_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, configuration LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_CFC395CBECDD285E (callout_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE setono_sylius_callout__callout_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, text VARCHAR(255) DEFAULT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_D5DA72A62C2AC5D3 (translatable_id), UNIQUE INDEX setono_sylius_callout__callout_translation_uniq_trans (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE setono_sylius_callout__callout_channels ADD CONSTRAINT FK_782BF40EECDD285E FOREIGN KEY (callout_id) REFERENCES setono_sylius_callout__callout (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE setono_sylius_callout__callout_channels ADD CONSTRAINT FK_782BF40E72F5A1AA FOREIGN KEY (channel_id) REFERENCES sylius_channel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE setono_sylius_callout__callout_rule ADD CONSTRAINT FK_CFC395CBECDD285E FOREIGN KEY (callout_id) REFERENCES setono_sylius_callout__callout (id)');
        $this->addSql('ALTER TABLE setono_sylius_callout__callout_translation ADD CONSTRAINT FK_D5DA72A62C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES setono_sylius_callout__callout (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE setono_sylius_callout__callout_channels DROP FOREIGN KEY FK_782BF40EECDD285E');
        $this->addSql('ALTER TABLE setono_sylius_callout__callout_rule DROP FOREIGN KEY FK_CFC395CBECDD285E');
        $this->addSql('ALTER TABLE setono_sylius_callout__callout_translation DROP FOREIGN KEY FK_D5DA72A62C2AC5D3');
        $this->addSql('DROP TABLE setono_sylius_callout__callout');
        $this->addSql('DROP TABLE setono_sylius_callout__callout_channels');
        $this->addSql('DROP TABLE setono_sylius_callout__callout_rule');
        $this->addSql('DROP TABLE setono_sylius_callout__callout_translation');
    }
}

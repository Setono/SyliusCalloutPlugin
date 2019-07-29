<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20190728170647 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Timestampable fields for Callout';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE setono_sylius_callout__callout ADD created_at DATETIME NOT NULL DEFAULT NOW(), ADD updated_at DATETIME DEFAULT NULL, ADD rules_assigned_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE setono_sylius_callout__callout DROP created_at, DROP updated_at, DROP rules_assigned_at');
    }
}

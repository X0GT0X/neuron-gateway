<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230411091921 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add Payment entity';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE payments (id UUID NOT NULL, currency VARCHAR(255) NOT NULL, amount INT NOT NULL, type VARCHAR(255) NOT NULL, unique_reference VARCHAR(255) NOT NULL, payer_id UUID NOT NULL, status VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, bank_id UUID NOT NULL, bank_is_read_only BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN payments.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN payments.updated_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE payments');
    }
}

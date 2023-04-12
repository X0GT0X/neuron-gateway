<?php

declare(strict_types=1);

namespace App\Infrastructure\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230412121337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Drop NOT NULL on Payment::bankId';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE payments ALTER bank_id DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE payments ALTER bank_id SET NOT NULL');
    }
}

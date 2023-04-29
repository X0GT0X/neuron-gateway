<?php

declare(strict_types=1);

namespace App\Infrastructure\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230423142235 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add outbox';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE outbox_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_24F454FEFB7336F0 ON outbox_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_24F454FEE3BD61CE ON outbox_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_24F454FE16BA31DB ON outbox_messages (delivered_at)');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_outbox_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'outbox_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON outbox_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON outbox_messages FOR EACH ROW EXECUTE PROCEDURE notify_outbox_messages();');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE outbox_messages');
    }
}

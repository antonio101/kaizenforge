<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260306121859 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE kf_access_tokens (id VARCHAR(36) NOT NULL, user_id VARCHAR(36) NOT NULL, token_hash VARCHAR(64) NOT NULL, created_at DATETIME NOT NULL, expires_at DATETIME NOT NULL, revoked_at DATETIME DEFAULT NULL, INDEX idx_kf_access_tokens_user_id (user_id), INDEX idx_kf_access_tokens_expires_at (expires_at), UNIQUE INDEX uniq_kf_access_tokens_token_hash (token_hash), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE kf_users (id VARCHAR(36) NOT NULL, email VARCHAR(180) NOT NULL, password_hash VARCHAR(255) NOT NULL, roles JSON NOT NULL, is_active TINYINT NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX uniq_kf_users_email (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE kf_access_tokens');
        $this->addSql('DROP TABLE kf_users');
    }
}

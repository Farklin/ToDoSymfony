<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220218083105 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE job ADD user_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE job ADD CONSTRAINT FK_FBD8E0F89D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_FBD8E0F89D86650F ON job (user_id_id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64948704627 FOREIGN KEY (jobs_id) REFERENCES job (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64948704627 ON user (jobs_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE job DROP FOREIGN KEY FK_FBD8E0F89D86650F');
        $this->addSql('DROP INDEX IDX_FBD8E0F89D86650F ON job');
        $this->addSql('ALTER TABLE job DROP user_id_id');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64948704627');
        $this->addSql('DROP INDEX IDX_8D93D64948704627 ON user');
    }
}

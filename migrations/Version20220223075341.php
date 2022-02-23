<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220223075341 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments_files DROP FOREIGN KEY FK_4E03A73F93CB796C');
        $this->addSql('ALTER TABLE comments_files ADD CONSTRAINT FK_4E03A73F93CB796C FOREIGN KEY (file_id) REFERENCES simple_file (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments_files DROP FOREIGN KEY FK_4E03A73F93CB796C');
        $this->addSql('ALTER TABLE comments_files ADD CONSTRAINT FK_4E03A73F93CB796C FOREIGN KEY (file_id) REFERENCES file (id)');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210708091454 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comments_files (comment_id INT NOT NULL, file_id INT NOT NULL, INDEX IDX_4E03A73FF8697D13 (comment_id), UNIQUE INDEX UNIQ_4E03A73F93CB796C (file_id), PRIMARY KEY(comment_id, file_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comments_files ADD CONSTRAINT FK_4E03A73FF8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id)');
        $this->addSql('ALTER TABLE comments_files ADD CONSTRAINT FK_4E03A73F93CB796C FOREIGN KEY (file_id) REFERENCES file (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE comments_files');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240731144630 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create `users` table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, login VARCHAR(8) NOT NULL, phone VARCHAR(8) NOT NULL, pass VARCHAR(8) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_LOGIN_PASS (login, pass), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE users');
    }
}
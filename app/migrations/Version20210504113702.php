<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210504113702 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__highscore AS SELECT id, score, date FROM highscore');
        $this->addSql('DROP TABLE highscore');
        $this->addSql('CREATE TABLE highscore (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, score INTEGER NOT NULL, date VARCHAR(8) NOT NULL, username VARCHAR(10) NOT NULL)');
        $this->addSql('INSERT INTO highscore (id, score, date) SELECT id, score, date FROM __temp__highscore');
        $this->addSql('DROP TABLE __temp__highscore');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__highscore AS SELECT id, score, date FROM highscore');
        $this->addSql('DROP TABLE highscore');
        $this->addSql('CREATE TABLE highscore (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, score INTEGER NOT NULL, date DATE NOT NULL)');
        $this->addSql('INSERT INTO highscore (id, score, date) SELECT id, score, date FROM __temp__highscore');
        $this->addSql('DROP TABLE __temp__highscore');
    }
}

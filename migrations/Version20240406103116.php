<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240406103116 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE inspector (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job (id INT AUTO_INCREMENT NOT NULL, description LONGTEXT NOT NULL, status VARCHAR(255) DEFAULT NULL, inspector INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE schedule (id INT AUTO_INCREMENT NOT NULL, inspector_id INT NOT NULL, job_id INT NOT NULL, assigned_at DATETIME NOT NULL, completed_at DATETIME DEFAULT NULL, INDEX IDX_5A3811FBD0E3F35F (inspector_id), INDEX IDX_5A3811FBBE04EA9 (job_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FBD0E3F35F FOREIGN KEY (inspector_id) REFERENCES inspector (id)');
        $this->addSql('ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FBBE04EA9 FOREIGN KEY (job_id) REFERENCES job (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE schedule DROP FOREIGN KEY FK_5A3811FBD0E3F35F');
        $this->addSql('ALTER TABLE schedule DROP FOREIGN KEY FK_5A3811FBBE04EA9');
        $this->addSql('DROP TABLE inspector');
        $this->addSql('DROP TABLE job');
        $this->addSql('DROP TABLE schedule');
    }
}

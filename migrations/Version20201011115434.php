<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201011115434 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE st_comment (id INT NOT NULL, trick_id INT DEFAULT NULL, user_id INT DEFAULT NULL, content LONGTEXT NOT NULL, date DATETIME NOT NULL, UNIQUE INDEX UNIQ_D6A5A84CB281BE2E (trick_id), UNIQUE INDEX UNIQ_D6A5A84CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE st_media (id INT NOT NULL, trick_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, file VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_E0C5111B281BE2E (trick_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE st_trick (id INT NOT NULL, tricks_group_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_BCD05903DE4E02E0 (tricks_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE st_tricks_group (id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE st_user (id INT NOT NULL, avatar_id INT DEFAULT NULL, contributions_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, email LONGTEXT NOT NULL, password VARCHAR(255) NOT NULL, connected TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_C6CB0B5086383B10 (avatar_id), INDEX IDX_C6CB0B50525F2C4B (contributions_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE st_comment ADD CONSTRAINT FK_D6A5A84CB281BE2E FOREIGN KEY (trick_id) REFERENCES st_trick (id)');
        $this->addSql('ALTER TABLE st_comment ADD CONSTRAINT FK_D6A5A84CA76ED395 FOREIGN KEY (user_id) REFERENCES st_user (id)');
        $this->addSql('ALTER TABLE st_media ADD CONSTRAINT FK_E0C5111B281BE2E FOREIGN KEY (trick_id) REFERENCES st_trick (id)');
        $this->addSql('ALTER TABLE st_trick ADD CONSTRAINT FK_BCD05903DE4E02E0 FOREIGN KEY (tricks_group_id) REFERENCES st_tricks_group (id)');
        $this->addSql('ALTER TABLE st_user ADD CONSTRAINT FK_C6CB0B5086383B10 FOREIGN KEY (avatar_id) REFERENCES st_media (id)');
        $this->addSql('ALTER TABLE st_user ADD CONSTRAINT FK_C6CB0B50525F2C4B FOREIGN KEY (contributions_id) REFERENCES st_trick (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE st_user DROP FOREIGN KEY FK_C6CB0B5086383B10');
        $this->addSql('ALTER TABLE st_comment DROP FOREIGN KEY FK_D6A5A84CB281BE2E');
        $this->addSql('ALTER TABLE st_media DROP FOREIGN KEY FK_E0C5111B281BE2E');
        $this->addSql('ALTER TABLE st_user DROP FOREIGN KEY FK_C6CB0B50525F2C4B');
        $this->addSql('ALTER TABLE st_trick DROP FOREIGN KEY FK_BCD05903DE4E02E0');
        $this->addSql('ALTER TABLE st_comment DROP FOREIGN KEY FK_D6A5A84CA76ED395');
        $this->addSql('DROP TABLE st_comment');
        $this->addSql('DROP TABLE st_media');
        $this->addSql('DROP TABLE st_trick');
        $this->addSql('DROP TABLE st_tricks_group');
        $this->addSql('DROP TABLE st_user');
    }
}

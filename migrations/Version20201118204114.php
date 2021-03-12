<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201118204114 extends AbstractMigration {
	public function getDescription(): string {
		return '';
	}

	public function up( Schema $schema ): void {
		// this up() migration is auto-generated, please modify it to your needs
		$this->addSql( 'CREATE TABLE st_comment (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', trick_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', user_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', content LONGTEXT NOT NULL, createdAt DATETIME NOT NULL, INDEX IDX_D6A5A84CB281BE2E (trick_id), INDEX IDX_D6A5A84CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB' );
		$this->addSql( 'CREATE TABLE st_media (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, file VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, createdAt DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB' );
		$this->addSql( 'CREATE TABLE st_token_history (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', createdAt DATETIME NOT NULL, value VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_216BA3509D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB' );
		$this->addSql( 'CREATE TABLE st_trick (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', tricks_group_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, createdAt DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_BCD05903DE4E02E0 (tricks_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB' );
		$this->addSql( 'CREATE TABLE trick_has_media (trick_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', media_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_CA1ECC2BB281BE2E (trick_id), INDEX IDX_CA1ECC2BEA9FDD75 (media_id), PRIMARY KEY(trick_id, media_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB' );
		$this->addSql( 'CREATE TABLE st_trick_group (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, createdAt DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB' );
		$this->addSql( 'CREATE TABLE st_user (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', media_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, email LONGTEXT NOT NULL, password VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', createdAt DATETIME NOT NULL, INDEX IDX_C6CB0B50EA9FDD75 (media_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB' );
		$this->addSql( 'CREATE TABLE user_has_tricks (user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', trick_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_6A9CD3B6A76ED395 (user_id), INDEX IDX_6A9CD3B6B281BE2E (trick_id), PRIMARY KEY(user_id, trick_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB' );
		$this->addSql( 'ALTER TABLE st_comment ADD CONSTRAINT FK_D6A5A84CB281BE2E FOREIGN KEY (trick_id) REFERENCES st_trick (id)' );
		$this->addSql( 'ALTER TABLE st_comment ADD CONSTRAINT FK_D6A5A84CA76ED395 FOREIGN KEY (user_id) REFERENCES st_user (id)' );
		$this->addSql( 'ALTER TABLE st_token_history ADD CONSTRAINT FK_216BA3509D86650F FOREIGN KEY (user_id_id) REFERENCES st_user (id)' );
		$this->addSql( 'ALTER TABLE st_trick ADD CONSTRAINT FK_BCD05903DE4E02E0 FOREIGN KEY (tricks_group_id) REFERENCES st_trick_group (id)' );
		$this->addSql( 'ALTER TABLE trick_has_media ADD CONSTRAINT FK_CA1ECC2BB281BE2E FOREIGN KEY (trick_id) REFERENCES st_trick (id)' );
		$this->addSql( 'ALTER TABLE trick_has_media ADD CONSTRAINT FK_CA1ECC2BEA9FDD75 FOREIGN KEY (media_id) REFERENCES st_media (id)' );
		$this->addSql( 'ALTER TABLE st_user ADD CONSTRAINT FK_C6CB0B50EA9FDD75 FOREIGN KEY (media_id) REFERENCES st_media (id)' );
		$this->addSql( 'ALTER TABLE user_has_tricks ADD CONSTRAINT FK_6A9CD3B6A76ED395 FOREIGN KEY (user_id) REFERENCES st_user (id)' );
		$this->addSql( 'ALTER TABLE user_has_tricks ADD CONSTRAINT FK_6A9CD3B6B281BE2E FOREIGN KEY (trick_id) REFERENCES st_trick (id)' );
	}

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trick_has_media DROP FOREIGN KEY FK_CA1ECC2BEA9FDD75');
        $this->addSql('ALTER TABLE st_user DROP FOREIGN KEY FK_C6CB0B50EA9FDD75');
        $this->addSql('ALTER TABLE st_comment DROP FOREIGN KEY FK_D6A5A84CB281BE2E');
        $this->addSql('ALTER TABLE trick_has_media DROP FOREIGN KEY FK_CA1ECC2BB281BE2E');
        $this->addSql('ALTER TABLE user_has_tricks DROP FOREIGN KEY FK_6A9CD3B6B281BE2E');
        $this->addSql('ALTER TABLE st_trick DROP FOREIGN KEY FK_BCD05903DE4E02E0');
        $this->addSql('ALTER TABLE st_comment DROP FOREIGN KEY FK_D6A5A84CA76ED395');
        $this->addSql('ALTER TABLE st_token_history DROP FOREIGN KEY FK_216BA3509D86650F');
        $this->addSql('ALTER TABLE user_has_tricks DROP FOREIGN KEY FK_6A9CD3B6A76ED395');
        $this->addSql('DROP TABLE st_comment');
        $this->addSql('DROP TABLE st_media');
        $this->addSql('DROP TABLE st_token_history');
        $this->addSql('DROP TABLE st_trick');
        $this->addSql('DROP TABLE trick_has_media');
        $this->addSql('DROP TABLE st_trick_group');
        $this->addSql('DROP TABLE st_user');
        $this->addSql('DROP TABLE user_has_tricks');
    }
}

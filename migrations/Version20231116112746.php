<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231116112746 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE band ADD email VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE organizer ADD email VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE organizer DROP email');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT DEFAULT \'[\'\'ROLE_USER\'\']\' NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE band DROP email');
    }
}

<?php

declare(strict_types=1);

namespace App\Migrations\Doctrine;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211001004014 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE usuarios ADD ativo BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE usuarios ALTER email TYPE VARCHAR(45)');
        $this->addSql('ALTER TABLE usuarios ALTER email DROP DEFAULT');
        $this->addSql('ALTER TABLE usuarios ALTER email TYPE VARCHAR(45)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE usuarios DROP ativo');
        $this->addSql('ALTER TABLE usuarios ALTER email TYPE VARCHAR(45)');
        $this->addSql('ALTER TABLE usuarios ALTER email DROP DEFAULT');
    }
}

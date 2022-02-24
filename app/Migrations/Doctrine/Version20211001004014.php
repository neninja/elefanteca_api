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
        return 'Add campo ativo em usuarios';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE usuarios ADD ativo BOOLEAN NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE usuarios DROP ativo');
    }
}

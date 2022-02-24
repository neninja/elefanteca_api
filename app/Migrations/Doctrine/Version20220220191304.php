<?php

declare(strict_types=1);

namespace App\Migrations\Doctrine;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220220191304 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add papel em usuários';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE usuarios ADD papel VARCHAR(15) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE usuarios DROP papel');
    }
}

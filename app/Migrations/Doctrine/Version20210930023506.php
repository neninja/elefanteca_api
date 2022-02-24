<?php

declare(strict_types=1);

namespace App\Migrations\Doctrine;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210930023506 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Cria usuários';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE usuarios (id SERIAL NOT NULL, nome VARCHAR(45) NOT NULL, cpf VARCHAR(11) NOT NULL, senha VARCHAR(255) NOT NULL, email VARCHAR(45) NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE usuarios');
    }
}

<?php

declare(strict_types=1);

namespace App\Migrations\Doctrine;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220216031415 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE autores (id SERIAL NOT NULL, nome VARCHAR(45) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE livros (id SERIAL NOT NULL, id_autor INT DEFAULT NULL, titulo VARCHAR(45) NOT NULL, quantidade INT NOT NULL, ativo BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX UNIQ_689E6F2EDF821F8A ON livros (id_autor)');
        $this->addSql('ALTER TABLE livros ADD CONSTRAINT FK_689E6F2EDF821F8A FOREIGN KEY (id_autor) REFERENCES autores (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE livros DROP CONSTRAINT FK_689E6F2EDF821F8A');
        $this->addSql('DROP TABLE autores');
        $this->addSql('DROP TABLE livros');
    }
}

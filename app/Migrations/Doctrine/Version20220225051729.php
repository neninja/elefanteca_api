<?php

declare(strict_types=1);

namespace App\Migrations\Doctrine;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220225051729 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE autores ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE autores ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('COMMENT ON COLUMN autores.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE livros DROP CONSTRAINT FK_689E6F2EDF821F8A');
        $this->addSql('ALTER TABLE livros ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE livros ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('COMMENT ON COLUMN livros.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE livros ADD CONSTRAINT FK_689E6F2EDF821F8A FOREIGN KEY (id_autor) REFERENCES autores (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER INDEX uniq_689e6f2edf821f8a RENAME TO IDX_689E6F2EDF821F8A');
        $this->addSql('ALTER TABLE usuarios ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE usuarios ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('COMMENT ON COLUMN usuarios.created_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE autores DROP created_at');
        $this->addSql('ALTER TABLE autores DROP updated_at');
        $this->addSql('ALTER TABLE livros DROP CONSTRAINT fk_689e6f2edf821f8a');
        $this->addSql('ALTER TABLE livros DROP created_at');
        $this->addSql('ALTER TABLE livros DROP updated_at');
        $this->addSql('ALTER TABLE livros ADD CONSTRAINT fk_689e6f2edf821f8a FOREIGN KEY (id_autor) REFERENCES autores (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER INDEX idx_689e6f2edf821f8a RENAME TO uniq_689e6f2edf821f8a');
        $this->addSql('ALTER TABLE usuarios DROP created_at');
        $this->addSql('ALTER TABLE usuarios DROP updated_at');
    }
}

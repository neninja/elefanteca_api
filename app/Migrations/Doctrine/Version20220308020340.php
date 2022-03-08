<?php

declare(strict_types=1);

namespace App\Migrations\Doctrine;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220308020340 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE emprestimos (id SERIAL NOT NULL, id_livro INT DEFAULT NULL, id_usuario_membro INT DEFAULT NULL, id_usuario_colaborador INT DEFAULT NULL, data_emprestimo TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, data_entrega_prevista TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, data_entrega_realizada TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, ativo BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5E9BC58A233E7B7 ON emprestimos (id_livro)');
        $this->addSql('CREATE INDEX IDX_5E9BC58D1154C38 ON emprestimos (id_usuario_membro)');
        $this->addSql('CREATE INDEX IDX_5E9BC58AC3965F6 ON emprestimos (id_usuario_colaborador)');
        $this->addSql('COMMENT ON COLUMN emprestimos.data_emprestimo IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN emprestimos.data_entrega_prevista IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN emprestimos.data_entrega_realizada IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN emprestimos.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE emprestimos ADD CONSTRAINT FK_5E9BC58A233E7B7 FOREIGN KEY (id_livro) REFERENCES livros (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE emprestimos ADD CONSTRAINT FK_5E9BC58D1154C38 FOREIGN KEY (id_usuario_membro) REFERENCES usuarios (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE emprestimos ADD CONSTRAINT FK_5E9BC58AC3965F6 FOREIGN KEY (id_usuario_colaborador) REFERENCES usuarios (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE emprestimos');
    }
}

Auth API
 [x] Cria token jwt
 [x] Falha ao criar token com senha errada jwt
 [x] Falha ao criar token com email errado jwt

Authors API
 [x] Falha sem autenticacao ao criar
 [x] Falha como membro ao criar
 [x] Cria como colaborador
 [x] Cria como admin
 [x] Falha sem autenticacao ao listar
 [x] Lista 20 com 2 paginas
 [x] Lista por nome parcial
 [x] Falha sem autenticacao ao listar por id
 [x] Lista por id
 [x] Falha se nao existe ao listar por id
 [x] Falha sem autenticacao ao editar
 [x] Falha como membro ao editar
 [x] Edita como colaborador
 [x] Falha se nao existe ao editar por id
 [x] Falha sem autenticacao ao deletar
 [x] Deleta como colaborador
 [x] Falha se nao existe ao deletar

Books API
 [x] Falha sem autenticacao ao criar
 [x] Falha como membro ao criar
 [x] Cria como colaborador
 [x] Cria como admin
 [x] Falha sem autenticacao ao listar
 [x] Lista 20 com 2 paginas
 [x] Lista por titulo parcial
 [x] Falha sem autenticacao ao listar por id
 [x] Lista por id
 [x] Falha se nao existe ao listar por id
 [x] Falha sem autenticacao ao editar
 [x] Falha como membro ao editar
 [x] Edita como colaborador
 [x] Falha se nao existe ao editar por id
 [x] Falha sem autenticacao ao deletar
 [x] Deleta como colaborador
 [x] Falha se nao existe ao deletar
 [x] Falha sem autenticacao ao reativar
 [x] Reativa como colaborador
 [x] Falha se nao existe ao reativar

Loans API
 [x] Falha sem autenticacao ao criar
 [x] Falha como membro ao criar
 [x] Cria como colaborador
 [x] Cria como admin
 [x] Falha sem autenticacao ao listar
 [x] Falha como membro ao listar
 [x] Lista 20 com 2 paginas
 [x] Lista por livro
 [x] Lista por membro
 [x] Falha sem autenticacao ao listar por id
 [x] Falha como membro ao listar por id
 [x] Lista por id
 [x] Falha se nao existe ao listar por id
 [x] Falha sem autenticacao ao devolver
 [x] Falha como membro ao devolver
 [x] Devolve como colaborador
 [x] Falha se nao existe ao devolver

Users API
 [x] Falha sem todos campos
 [x] Cria usuario
 [x] Cria usuario como colaborador
 [x] Cria usuario como membro tentando criar colaborador
 [x] Falha sem autenticacao ao listar
 [x] Falha como membro ao listar
 [x] Lista como colaborador admin
 [x] Lista 20 com 2 paginas
 [x] Lista por name parcial
 [x] Lista por email parcial
 [x] Falha sem autenticacao ao listar por id
 [x] Falha como membro ao listar por id
 [x] Lista por id
 [x] Falha se nao existe ao listar por id
 [x] Falha sem autenticacao ao editar
 [x] Falha como membro ao editar
 [x] Edita como colaborador
 [x] Falha se nao existe ao editar por id
 [x] Falha sem autenticacao ao deletar
 [x] Falha como membro ao deletar
 [x] Deleta como colaborador
 [x] Falha se nao existe ao deletar por id
 [x] Falha sem autenticacao ao reativar
 [x] Falha como membro ao reativar
 [x] Reativa como colaborador
 [x] Falha se nao existe ao reativar

Cadastro Autor Service
 [x] Cria com valores obrigatorios
 [x] Edita com valores obrigatorios

Cadastro Livro Service
 [x] Persiste com valores obrigatorios

Cadastro Usuario Service
 [x] Persiste com nome cpf email senha corretos
 [x] Criptografa senha
 [x] Cria como ativo
 [x] Falha ao criar com email existente
 [x] Falha ao criar com cpfl existente

Devolucao Service
 [x] Cria com valores obrigatorios
 [x] Falha ao devolver em duplicidade
 [x] Falha ao devolver emprestimo inexistente

Edicao Usuario Service
 [x] Persiste com nome cpf email corretos
 [x] Falha ao editar com email novo ja utilizado
 [x] Falha ao editar com cpf novo ja utilizado

Emprestimo Service
 [x] Cria com valores obrigatorios
 [x] Falha ao criar sem livro no estoque

CPF
 [x] Formata 81026092140 para 810.260.921-40
 [x] Formata 84820226959 para 848.202.269-59
 [x] Formata 74442704309 para 744.427.043-09
 [x] Falha como "CPF inválido" 00000000000
 [x] Falha como "CPF inválido" 11111111111
 [x] Falha como "CPF inválido" 22222222222
 [x] Falha como "CPF inválido" 33333333333
 [x] Falha como "CPF inválido" 44444444444
 [x] Falha como "CPF inválido" 55555555555
 [x] Falha como "CPF inválido" 66666666666
 [x] Falha como "CPF inválido" 77777777777
 [x] Falha como "CPF inválido" 88888888888
 [x] Falha como "CPF inválido" 99999999999
 [x] Falha como "CPF inválido" 81026092141
 [x] Falha como "CPF inválido" 881026092141
 [x] Falha como "CPF inválido" 8102609214

Email
 [x] Falha como "Email inválido" exemplo
 [x] Falha como "Email inválido" exemplo@
 [x] Falha como "Email inválido" exemplo.com
 [x] Falha como "Email inválido" exemplo@.com
 [x] Falha como "Email inválido" @com
 [x] Falha como "Email inválido" @.com
 [x] Falha como "Email inválido" exemplo@com

Emprestimo
 [x] Reserva para duas semanas


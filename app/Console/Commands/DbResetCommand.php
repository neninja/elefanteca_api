<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

use Symfony\Component\Process\Process;

use Core\Models\Papel;

class DbResetCommand extends Command
{
    protected $name = 'db:reset';
    protected $description = "Reset database with seed for development enviroment";

    public function encrypt($v)
    {
        $crypt = app()->make(\Core\Providers\ICriptografiaProvider::class);
        return $crypt->encrypt($v);
    }

    public function __invoke()
    {
        try {

            $process = new Process(['composer', 'doctrine:migrations', 'migrate', 'first']);
            $process->mustRun();

            $process = new Process(['composer', 'doctrine:migrations', 'migrate', 'latest']);
            $process->mustRun();


            $qb = $this->qb();

            $qb->insert('usuarios')
               ->values([
                   'nome'  => ':nome',
                   'ativo' => ':ativo',
                   'cpf'   => ':cpf',
                   'senha' => ':senha',
                   'email' => ':email',
                   'papel' => ':papel',
               ])
               ->setParameter('nome', 'Admin')
               ->setParameter('ativo', 1)
               ->setParameter('cpf', '75261018021')
               ->setParameter('senha', $this->encrypt('asdf'))
               ->setParameter('email', 'admin@desativemeemprod.com')
               ->setParameter('papel', Papel::$ADMIN)
               ->execute();

            if ($this->option('development')) {
                $this->insertForDevelopment($qb);
                $this->comment("Additional data added for --development.");
            }

            $this->info("Command completed successfully.");
        } catch (ProcessFailedException $e) {
            $this->error($e->getMessage());
        } catch (\Throwable $e) {
            $this->error($e->getMessage());
        }
    }


    protected function qb()
    {
        $em = app()->make(\Doctrine\ORM\EntityManagerInterface::class);
        return $em
            ->getConnection()
            ->createQueryBuilder();
    }

    protected function tablesForDevelopment(): array
    {
        return [
            [
                'table'  => 'usuarios',
                'params' => [
                    [
                        'nome'  => 'Colaborador',
                        'ativo' => 1,
                        'cpf'   => '82464881040',
                        'senha' => $this->encrypt('asdf'),
                        'email' => 'colaborador@desativemeemprod.com',
                        'papel' => Papel::$COLABORADOR,
                    ],
                    [
                        'nome'  => 'Membro',
                        'ativo' => 1,
                        'cpf'   => '55090833010',
                        'senha' => $this->encrypt('asdf'),
                        'email' => 'membro@desativemeemprod.com',
                        'papel' => Papel::$MEMBRO,
                    ],
                ]
            ],
            [
                'table'  => 'autores',
                'params' => [
                    [
                        'nome'  => 'Arthur Conan Doyle',
                    ],
                    [
                        'nome'  => 'Agatha Christie',
                    ],
                ]
            ],
            [
                'table'  => 'livros',
                'params' => [
                    [
                        'titulo'     => 'Um estudo em vermelho',
                        'quantidade' => 2,
                        'id_autor'   => 1,
                        'ativo'      => 1,
                    ],
                    [
                        'titulo'     => 'As memórias de Sherlock Holmes',
                        'quantidade' => 0,
                        'id_autor'   => 1,
                        'ativo'      => 1,
                    ],
                    [
                        'titulo'     => 'O cão dos Baskerville',
                        'quantidade' => 1,
                        'id_autor'   => 1,
                        'ativo'      => 0,
                    ],
                    [
                        'titulo'     => 'E não sobrou nenhum',
                        'quantidade' => 4,
                        'id_autor'   => 2,
                        'ativo'      => 1,
                    ],
                    [
                        'titulo'     => 'Assassinato no Expresso do Oriente',
                        'quantidade' => 2,
                        'id_autor'   => 2,
                        'ativo'      => 1,
                    ],
                    [
                        'titulo'     => 'O assassinato de Roger Ackroyd',
                        'quantidade' => 1,
                        'id_autor'   => 2,
                        'ativo'      => 1,
                    ],
                    [
                        'titulo'     => 'Morte no Nilo ',
                        'quantidade' => 1,
                        'id_autor'   => 2,
                        'ativo'      => 0,
                    ],
                    [
                        'titulo'     => 'Os crimes ABC',
                        'quantidade' => 5,
                        'id_autor'   => 2,
                        'ativo'      => 1,
                    ],
                    [
                        'titulo'     => 'Convite para um homicídio',
                        'quantidade' => 1,
                        'id_autor'   => 2,
                        'ativo'      => 1,
                    ],
                ]
            ],
        ];
    }

    protected function insertForDevelopment()
    {
        $tables = $this->tablesForDevelopment();

        foreach($tables as $t) {
            foreach($t['params'] as $d) {
                $qb = $this->qb();
                $qb->insert($t['table']);

                $columns = array_keys($d);

                foreach($columns as $column) {
                    $qb->setValue($column, ":".$column);
                }

                foreach($columns as $column) {
                    $qb->setParameter($column, $d[$column]);
                }

                $qb->execute();
            }
        }
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('development', null, InputOption::VALUE_NONE, 'With seed for development')
        );
    }
}


<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use League\Csv\Reader;
use League\Csv\Statement;
use Illuminate\Support\Facades\Log;

class importCsvJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected $filePath)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        // definir o caminho completo do arquivo csv armazenado no diretório 'storage/app/private'
        $filePath = storage_path('app/private/' . $this->filePath);

        // verificar se o arquivo realmente existe, se não, finalizar o job sem fazer nada
        if (!file_exists($filePath)) {
            Log::error("Arquivo CSV não encontrado: $filePath");
            return;
        }

        // criar uma instância do leitor de csv a partir do caminho 
        $csv = Reader::createFromPath($filePath, 'r');

        // definir o limitador de campos
        $csv->setDelimiter(';');

        // indica que a primeira linha do csv será usada como cabeçalho (nome das colunas)
        $csv->setHeaderOffset(0);

        // processar o arquivo e retornar os registros como uma coleção iterável
        $records = (new Statement())->process($csv);

        // iniciar um array para armazenar os dados que serão inseridos em lote
        $batchInsert = [];

        // itera sobre cada linha do arquivo csv
        foreach($records as $record) {
            // obtém o valor da coluna name, ou null se não existir
            $name = $record['name'] ?? null;

            // obtém o valor da coluna email, ou null se não existir
            $email = $record['email'] ?? null;

            // valida se o email existe e se está no formato correto
            if(!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // pular para o próxim caso o email for inválido
                continue;
            }

            // verifica s já existeum usuário com esse email no banco
            if(User::where('email', $email)->exists()) {
                // pular para o próxim caso o email já estiver cadastrado
                continue;
            }

            // adiciona os dados no array de inserção em lote
            $batchInsert[] = [
                'name' => $name,
                'email' => $email,
                'password' => Hash::make(Str::random(7), ['rounds' => 12]) // gera uma senha aleatória
            ];

            // se já existe 50 registros prontos, faz a inserção no banco de dados
            if(count($batchInsert) >= 50) {
                // insere os usuários no banco
                User::insert($batchInsert);

                Log::info('Inseridos '.count($batchInsert).' usuários dentro foreach');

                // limpa o array para os próximos 50
                $batchInsert = [];
            }
        } 

        // após o loop, insere os regustros restantes que ficaram abaixo de 50
        if (!empty($batchInsert)) {
            // insere os usuários no banco
            User::insert($batchInsert);

            Log::info('Inseridos '.count($batchInsert).' usuários fora foreach');

            // limpa o array para os próximos 50
            $batchInsert = [];
        }
    }
}

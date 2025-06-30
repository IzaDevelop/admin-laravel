<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ImportCSVController extends Controller
{
    // importar dados do excel
    public function importCsvUsers(Request $request) 
    {
        // dd("importar");

        // validar o arquivo
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:2048',
        ], [
            'file.required' => 'O campo arquivo é obrigatório.',
            'file.mimes' => 'Arquivo inválido, necessário ser do tipo CSV.',
            'file.max' => 'Tamanho do arquivo excede :max Mb.'
        ]);
    
        try {
            // criar o array com as colunas/cabeçalho no bd
            $header = ['name', 'email', 'password'];

            // receber o arqivo, ler os dados e converter a string em array
            // $fileData = array_map('str_getcsv', file($request->file('file')));
            $filePath = $request->file('file')->getRealPath();
            $fileData = @array_map('str_getcsv', file($filePath));

            if (!$fileData) {
                return back()->with('error', 'Erro ao ler o arquivo CSV.');
            }

            // definir o separados dos valores no csv
            $separator = ';';

            // criar array para armazenar os valores que seão inseridos no bd
            $arrayValues = [];

            // criar array para armazenar linhas com dados vazios
            $invalidRows = [];

            // criar array para armazenar e-mails duplicados encontrados
            $duplicatedEmails = [];

            // criar a variável para contar o número de regstros que serão cadastrados
            $numberRegisteredRecords = 0;

            // percorrer cada linha do arquivo csv
            foreach ($fileData as $row) {
                // separar os valores da linha utilizando o separador
                $values = explode($separator, $row[0]);

                // verificar se a quantidade de valores corresponde ao numero de colunas esperado
                if (count($values) !== count($header)) {
                   continue; //ignora linhas inválidas
                }

                // combinar os valores com os nomes das colunas/cabeçalho
                $userData = array_combine($header, $values);

                // consulta apenas o e-mail atual para verificar se existe no bd
                $emailExists = User::where('email', $userData['email'])->exists();

                // se alguma liha estiver vazia, adiconar na lista de vazios e pular para a próxima linha
                if (in_array(null, $userData, true) || in_array('', $userData, true)) {
                    $invalidRows[] = $values;
                    continue;
                }

                // se o email existir, adicionar na lista de duplicados e pular para a próxima linha
                if ($emailExists) {
                    $duplicatedEmails[] = $userData['email'];
                    continue;
                }

                // adicionar o usuário ao array de valores a serem inseridos no bd
                $arrayValues[] = [
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => Hash::make(Str::random(7), ['rounds' => 12]),
                    'created_at' => now(),
                    'updated_at' => now()
                ];

                // incrementar o contador de registros que serão cadastrados
                $numberRegisteredRecords++;
            }

            // verificar se algum campo está vazio e por isso o erro ao importar
            if (!empty($invalidRows)) {
                return back()->withInput()->with('error', 'Uma ou mais linhas possuem campos vazios. Por favor, corrija o arquivo.');
            }

            // verificar se existe e-mail já cadastrado, retorna erro e não cadastra no bd
            if (!empty($duplicatedEmails)) {
                return back()->withInput()->with('error', 'Dados não importados. <br/> E-mail já cadastrado anteriormente:<br>' . implode(', ', $duplicatedEmails));
            }

            // cadastrar registros no bd
            User::insert($arrayValues);

            return redirect()->route('user.list')->with('success', $numberRegisteredRecords . ' cadastrado realizado!');

        } catch (Exception $e) {
            return back()->with('error', 'Erro ao importar o arquivo CSV.');
        }
    }
}

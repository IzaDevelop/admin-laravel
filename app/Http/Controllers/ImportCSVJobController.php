<?php

namespace App\Http\Controllers;

use App\Jobs\ImportCsvJob;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ImportCSVJobController extends Controller
{
    // importar dados do excel
    public function importCsvJobUsers(Request $request) 
    {
        // dd("importar");

        // validar o arquivo
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:8192', // 8MB
        ], [
            'file.required' => 'O campo arquivo é obrigatório.',
            'file.mimes' => 'Arquivo inválido, necessário ser do tipo CSV.',
            'file.max' => 'Tamanho do arquivo excede :max Mb.'
        ]);
    
        try {
            // gerar um nome de arquivo baseado na data e hora local
            $fileName = 'import-' . now()->format('Y-m-d-H-i-s') . '.csv';

            // recber o arquivo e movê-lo para o servidor
            $path = $request->file('file')->storeAs('uploads', $fileName);

            // despachar o job para processar o csv
            ImportCsvJob::dispatch($path);

            // redirecionar o usuário, enviar mensagem de sucesso
            return back()->with('success', 'Dados estão sendo importados!.');

        } catch (Exception $e) {
            return back()->with('error', 'Dados não importados.');
        }
    }
}

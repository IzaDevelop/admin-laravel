<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Mail\UserPDF;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // $users = User::orderByDesc('id')->paginate(10);
        $search = $request->input('search');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        // VERSÂO 0 : apenas campo de busca por nome/email
        // $users = User::when(
        //     $search,
        //     fn($query) =>
        //     $query->where(function ($q) use ($search) {
        //         $q->where('name', 'like', '%' . $search . '%')
        //           ->orWhere('email', 'like', '%' . $search . '%');
        //     })
        // )

        // VERSÂO 1 : assim só funciona se todos os campos estiverem preenchidos
        // $users = User::when($search, function ($query) use ($search, $startDate, $endDate) {
        //     $query->where(function ($q) use ($search) {
        //         $q->where('name', 'like', '%' . $search . '%')
        //         ->orWhere('email', 'like', '%' . $search . '%');
        //     });

        //     if ($startDate) {
        //         $query->where('created_at', '>=', Carbon::parse($startDate)->startOfDay());
        //     }

        //     if ($endDate) {
        //         $query->where('created_at', '<=', Carbon::parse($endDate)->endOfDay());
        //     }
        // })

        // VERSÂO 2 : assim cada campo funciona independente do outro
        $users = User::query()
        ->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
            });
        })
        ->when($startDate, function ($query) use ($startDate) {
            // para usar o horário como filtro, remova o startOfDay()
            $query->where('created_at', '>=', Carbon::parse($startDate)->startOfDay());
        })
        ->when($endDate, function ($query) use ($endDate) {
            // para usar o horário como filtro, remova o endOfDay()
            $query->where('created_at', '<=', Carbon::parse($endDate)->endOfDay());
        })
    
        ->orderByDesc('id')
        ->paginate(10)
        ->withQueryString();
        
        return view('users.list', [
            'users' => $users, 
            'search' => $search,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(UserRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'description' => $request->description,
                'password' => $request->password
            ]);

            return redirect()->route('user.view', ['user' => $user->id])->with('success', 'Usuário cadastrado com sucesso!');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Falha ao cadastrar o usuário');
        }
    }

    public function edit(User $user)
    {
        return view('users.edit', ['user' => $user]);
    }

    public function update(UserRequest $request, User $user)
    {
        try {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'description' => $request->description,
            ]);

            return redirect()->route('user.view', ['user' => $user->id])->with('success', 'Usuário editado com sucesso!');
        } catch (Exception $e) {
            // dd($e->getMessage());
            return back()->withInput()->with('error', 'Falha ao editar o usuário');
        }
    }

    public function show(User $user)
    {
        return view('users.view', ['user' => $user]);
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();
            return redirect()->route('user.list')->with('success', 'Usuário deletado com sucesso!');
        } catch (Exception $e) {
            return back()->with('error', 'Erro ao deletar o usuário.');
        }
    }

    public function generatePdf(User $user) 
    {
        // dd($user);

        try {
            $dompdf = Pdf::loadView('users.generate-pdf', ['user' => $user])->setPaper('a4', 'portrait');
        
            $filename = 'usuario-' . strtolower(str_replace(' ', '-', $user->name)) . '.pdf';
            
            return $dompdf->download($filename);
        } catch (Exception $e) {
            return back()->with('error', 'Erro ao gerar o PDF.');
        }
    }

    public function sendPdf(User $user) 
    {
        try {
            $dompdf = Pdf::loadView('users.generate-pdf', ['user' => $user])->setPaper('a4', 'portrait');

            $filename = 'usuario-' . strtolower(str_replace(' ', '-', $user->name)) . '.pdf';
            
            $pdfPath = storage_path($filename);

            $dompdf->save($pdfPath);
              
            Mail::to($user->email)->send(new UserPDF($pdfPath, $user));

            if(file_exists($pdfPath)) {
                unlink($pdfPath);
                
                return back()->with('success', 'PDF enviado com sucesso!');   
            }    
        } catch (Exception $e) {
            return back()->with('error', 'Erro ao enviar o PDF.');
        }
    }

    public function generatePdfUsers(Request $request) 
    {
        try {
            $search = $request->input('search');
            $startDate = $request->input('startDate');
            $endDate = $request->input('endDate');

            $users = User::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
                });
            })
            ->when($startDate, function ($query) use ($startDate) {
                $query->where('created_at', '>=', Carbon::parse($startDate)->startOfDay());
            })
            ->when($endDate, function ($query) use ($endDate) {
                $query->where('created_at', '<=', Carbon::parse($endDate)->endOfDay());
            })
            ->orderByDesc('id')
            ->get();

            $totalRecords = $users->count('id');
            $numberRecordsAllowed = 500;
            if ( $totalRecords > $numberRecordsAllowed ) {
                return redirect()->route('user.list', [
                    // retorna para a rota caso de erro e mantém os filtros
                    'search' => $request->search,
                    'startDate' => $request->startDate,
                    'endDate' => $request->endDate,
                ])->with('error', "Limite de registros ultrapassados para gerar o PDF. O limite é de $numberRecordsAllowed registros");
            }

            $pdf = Pdf::loadView('users.generate-pdf-users', [
                'users' => $users
            ])->setPaper('a4', 'portrait');

            return $pdf->download('lista-de-usuarios.pdf');
        } catch (Exception $e) {
            return back()->with('error', 'Erro ao gerar o PDF.');
        }
    }

    public function generateXlsfUsers(Request $request) 
    {
        try {
            $search = $request->input('search');
            $startDate = $request->input('startDate');
            $endDate = $request->input('endDate');

            $users = User::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
                });
            })
            ->when($startDate, function ($query) use ($startDate) {
                $query->where('created_at', '>=', Carbon::parse($startDate)->startOfDay());
            })
            ->when($endDate, function ($query) use ($endDate) {
                $query->where('created_at', '<=', Carbon::parse($endDate)->endOfDay());
            })
            ->orderByDesc('name')
            ->get();

            $totalRecords = $users->count('id');
            $numberRecordsAllowed = 500;
            if ( $totalRecords > $numberRecordsAllowed ) {
                return redirect()->route('user.list', [
                    // retorna para a rota caso de erro e mantém os filtros
                    'search' => $request->search,
                    'startDate' => $request->startDate,
                    'endDate' => $request->endDate,
                ])->with('error', "Limite de registros ultrapassados para gerar o Xls. O limite é de $numberRecordsAllowed registros");
            }

            // arquivo temporário
            $xlsFileName = tempnam(sys_get_temp_dir(), 'csv_' . Str::ulid());

            // abrir o arquivo
            $openFile = fopen($xlsFileName, 'w');

            // criar o cabeçalho
            $header = ['id', 'Nome', 'E-mail', 'Data de Cadastro'];

            // escrever o cabeçalho no arquivo
            fputcsv($openFile, $header, ';');

            // ler os registros recuperados do bd
            foreach( $users as $user ) {
                // criar array com os dados
                $userArray = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'created_at' => \Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:i:s')
                ];

                fputcsv($openFile, $userArray, ';');
            }

            // fechar o arquivo
            fclose($openFile);

            // realizar o download do arquivo
            return response()->download($xlsFileName, 'Lista-de-usuários_'. Str::ulid() . '.csv');
        } catch (Exception $e) {
            return back()->with('error', 'Erro ao gerar o Xls.');
        }
    }
}

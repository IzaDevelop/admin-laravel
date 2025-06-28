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
                'email' => $request->email
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
}

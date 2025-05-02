@extends('layouts.admin')

@section('content')
    <article class="content">
        <section class="content-title">
            <h1 class="page-title">Listar Usuário</h1>
            <a href="{{ route('user.create') }}" class="btn-primary">Cadastrar</a>
        </section>

        <x-alert/>

        <section class="table-container">
            <table class="table">
                <thead>
                    <tr class="table-header">
                        <th class="table-header">ID</th>
                        <th class="table-header">Nome</th>
                        <th class="table-header">E-mail</th>
                        <th class="table-header center">Ações</th>
                    </tr>
                </thead>
                <tbody class="table-body">
                    @forelse ($users as $user)
                        <tr class="table-row">
                            <td class="table-cell">{{ $user->id }}</td>
                            <td class="table-cell">{{ $user->name }}</td>
                            <td class="table-cell">{{ $user->email }}</td>
                            <td class="table-actions">
                                <a href="{{ route('user.view', ['user' => $user->id]) }}" class="btn-view">Visualizar</a>
                                <a href="{{ route('user.edit', ['user' => $user->id]) }}" class="btn-edit">Editar</a>
                                <form id="delete-form-{{ $user->id }}" action="{{ route('user.destroy', ['user' => $user->id]) }}" method="POST">
                                    @csrf 
                                    @method('DELETE')

                                    <button type="button" class="btn-delete" onclick="confirmDelete({{ $user->id }})">Deletar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <p class="alert-error">Nenhum usuário encontrado</p>
                    @endforelse
                </tbody>
            </table>
        </section>

        <div class="paginate">
            {{ $users->links() }}
        </div>
    </article>
@endsection

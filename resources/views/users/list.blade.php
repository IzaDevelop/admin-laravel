@extends('layouts.admin')

@section('content')
    <article class="content">
        <section class="content-title flex flex-wrap md:flex-nowrap">
            <h1 class="page-title">Listar Usuário</h1>

            <div class="flex gap-3">
                <a href="{{ url('generate-pdf-users') . (request()->getQueryString() ? '?' . request()->getQueryString() : '') }}" class="btn-outline btn-download">
                    <img src="{{ asset('images/file-pdf.svg') }}" alt="Download PDF">
                </a>
                <a href="{{ url('generate-xls-users') . (request()->getQueryString() ? '?' . request()->getQueryString() : '') }}" class="btn-outline btn-download">
                    <img src="{{ asset('images/file-xls.svg') }}" alt="Download CSV">
                </a>
            </div>
        </section>

        <x-alert />

        <form class="flex flex-wrap md:flex-nowrap gap-5 pb-5" action="{{ route('user.import-csv-users') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <label class="form-search">
                <input type="file" name="file" id="file" accept=".csv">
            </label>

            <button type="submit" class="btn-outline btn-upload"><img src="images/upload-file.svg" alt=""></button>
        </form>

        <form class="flex flex-wrap md:flex-nowrap gap-5 pb-5" method="GET" action="{{ route('user.list') }}">
            <input type="text" name="search" class="form-search" value="{{ request('search') }}"
                placeholder="Buscar nome ou e-mail">

            {{-- para data e hora utilize datetime-local no type --}}
            <input type="date" name="startDate" class="form-search" value="{{ request('startDate') }}"
                placeholder="Buscar nome ou e-mail">

            {{-- para data e hora utilize datetime-local no type --}}
            <input type="date" name="endDate" class="form-search" value="{{ request('endDate') }}"
                placeholder="Buscar nome ou e-mail">

            <div class="flex flex-1 justify-between gap-3">
                <button type="submit" class="btn-outline btn-search">
                    <img src="{{ asset('images/search.svg') }}" alt="Pesquisar">
                </button>
                <a href="{{ route('user.list') }}" class="btn-outline btn-clean">
                    <img src="{{ asset('images/broom.svg') }}" alt="Limpar">
                </a>
            </div>
        </form>

        @if ($users->count())
            <section class="table-container overflow-x-scroll md:overflow-x-hidden">
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
                                    <a href="{{ route('user.view', ['user' => $user->id]) }}"
                                        class="btn-view">Visualizar</a>
                                    <a href="{{ route('user.edit', ['user' => $user->id]) }}" class="btn-edit">Editar</a>
                                    <form id="delete-form-{{ $user->id }}"
                                        action="{{ route('user.destroy', ['user' => $user->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button type="button" class="btn-delete"
                                            onclick="confirmDelete({{ $user->id }})">Deletar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            {{-- <p class="alert-error">Nenhum usuário encontrado</p> --}}
                        @endforelse
                    </tbody>
                </table>
            </section>
        @else
            <p class="alert-error">Nenhum usuário encontrado</p>
        @endif

        <div class="paginate">
            {{ $users->links() }}
        </div>
    </article>
@endsection

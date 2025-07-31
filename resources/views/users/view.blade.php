@extends('layouts.admin')

@section('content')
    <article class="content">
        <section class="content-title">
            <h1 class="page-title">Detalhes do Usuário</h1>
            <span>
                <a href="{{ route('user.generate-pdf', ['user' => $user->id]) }}" class="btn-download">Gerar PDF</a>
                <a href="{{ route('user.list') }}" class="btn-list">Listar</a>
                <a href="{{ route('user.edit', ['user' => $user->id]) }}" class="btn-edit">Editar</a>
                <form id="delete-form-{{ $user->id }}" action="{{ route('user.destroy', ['user' => $user->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="button" class="btn-delete" onclick="confirmDelete({{ $user->id }})">Deletar</button>
                </form>
            </span>
        </section>

        <x-alert/>

        <section class="info-container">
            <h2 class="info-title">Informações do Usuário</h2>

            <div class="info-row">
                <div>
                    <span class="info-text bold">ID:</span>
                    <span class="info-text">{{ $user->id }}</span>
                </div>
            </div>

            <div class="info-row">
                <div>
                    <span class="info-text bold">Nome:</span>
                    <span class="info-text">{{ $user->name }}</span>
                </div>
            </div>

            <div class="info-row">
                <div>
                    <span class="info-text bold">E-mail:</span>
                    <span class="info-text">{{ $user->email }}</span>
                </div>
            </div>

             <div class="info-row">
                <div>
                    <span class="info-text bold">Descrição:</span>
                    <span class="info-text">{!! $user->description !!}</span>
                </div>
            </div>

            <div class="info-row">
                <div>
                    <span class="info-text bold">Criado em:</span>
                    <span class="info-text">{{ $user->created_at->format('d/m/Y H:i:s') }}</span>
                </div>
            </div>

            <div class="info-row">
                <div>
                    <span class="info-text bold">Última edição:</span>
                    <span class="info-text">{{ $user->updated_at->format('d/m/Y H:i:s') }}</span>
                </div>
            </div>
        </section>
    </article>
@endsection

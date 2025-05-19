@extends('layouts.admin')

@section('content')
    <article class="content">
        <section class="content-title">
            <h1 class="page-title">Editar Usuário</h1>
            <span>
                <a href="{{ route('user.list') }}" class="btn-list">Listar</a>
                <a href="{{ route('user.view', ['user' => $user->id]) }}" class="btn-view">Visualizar</a>
            </span>
        </section>

        <x-alert/>

        <form action="{{ route('user.update', ['user' => $user->id]) }}" method="POST" class="form-container">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="name" class="form-label">Nome:</label>
                <input type="text" name="name" id="name" placeholder="Nome completo" 
                value="{{ old('name', $user->name) }}" class="form-input" required />
            </div>

            <div class="mb-4">
                <label for="email" class="form-label">E-mail:</label>
                <input type="email" name="email" id="email" placeholder="Exemplo@gmail.com"
                value="{{ old('email', $user->email) }}" class="form-input" required />
            </div>

            <div>
                <button type="submit" class="btn-geral">Salvar</button>
            </div>
        </form>
    </article>
@endsection

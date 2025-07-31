@extends('layouts.admin')

@section('content')
    <article class="content">
        <section class="content-title">
            <h1 class="page-title">Cadastrar Usuário</h1>
            <a href="{{ route('user.list') }}" class="btn-list">Listar</a>
        </section>

        <x-alert/>

        <form action="{{ route('user.store') }}" method="POST" class="form-container">
            @csrf

            <div class="mb-4">
                <label for="name" class="form-label">Nome:</label>
                <input type="text" name="name" id="name" placeholder="Nome completo" 
                value="{{ old('name') }}" class="form-input" required />
            </div>

            <div class="mb-4">
                <label for="email" class="form-label">E-mail:</label>
                <input type="email" name="email" id="email" placeholder="Exemplo@gmail.com"
                value="{{ old('email') }}" class="form-input" required />
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">Senha:</label>
                <input type="password" name="password" id="password" placeholder="Senha" 
                value="{{ old('password') }}" class="form-input" required />
            </div>

            <div class="mb-4">
                <label for="description" class="form-label">Decrição:</label>
                <textarea type="text" name="description" id="summernote" class="form-input" minlength="1">
                    {{ old('description') }}
                </textarea>
            </div>

            <div>
                <button type="submit" class="btn-create">Cadastrar</button>
            </div>
        </form>
    </article>
@endsection

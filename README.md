# 📦 Requisitos

- **PHP** 8.2 ou superior  
- **Composer**  
- **Node.js** 22.15.0 ou superior  
- **NPM**

# ⚙️ Instalação do Projeto

1. Clone o repositório:
```bash
   git clone <seu-repositório>
   cd <nome-do-projeto>
```

2. Instale as dependências do PHP:
```bash
   composer install
```

3. Instale as dependências do Node:
```bash
   npm install
```

4. Copie o arquivo .env.example para .env:
```bash
   cp .env.example .env
```

5. Gere a chave da aplicação:
```bash
   php artisan key:generate
```

6. Configure seu arquivo .env com as credenciais de banco de dados e serviços de e-mail (como Mailtrap ou Iagente).

7. Execute as migrações:
```bash
   php artisan migrate
```

# ▶️ Rodando o Projeto
Back-end (Laravel):
```bash
   php artisan serve
```

Front-end (Vite/React):
```bash
   npm run dev
```

# 🛠️ Comandos Úteis
| Descrição                                       | Comando                                              |
| ----------------------------------------------- | ---------------------------------------------------- |
| Criar novo projeto Laravel                      | `composer create-project laravel/laravel .`          |
| Rodar servidor local                            | `php artisan serve`                                  |
| Criar controller                                | `php artisan make:controller NomeDoController`       |
| Criar componente (Blade)                        | `php artisan make:component NomeDoComponente --view` |
| Criar form request                              | `php artisan make:request NomeDoRequest`             |
| Criar e-mail                                    | `php artisan make:mail NomeDaClasse`                 |
| Criar view (comando Laravel)                    | `php artisan make:view pasta/nomeDaView`             |
| Criar view (manualmente)                        | `resources/views/pasta/nomeDaView.blade.php`         |
| Rodar as migrações                              | `php artisan migrate`                                |

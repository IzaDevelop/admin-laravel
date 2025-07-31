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

8. Criar o job:
```bash
   php artisan make:job ImportCsvJob
```

9. Instalar a biblioteca para processar o arquivo gradativamente:
```bash
   composer require league/csv
```

10. Executar o job:
```bash
   php artisan queue:work
```

11. Istalando o react-summernote com o jquery:
```bash
   npm install jquery summernote
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
| Rodar um refresh nas migrações                  | `php artisan migrate:fresh`                          |
| Apaga os jobs falhos                            | `php artisan queue:flush`                            |
| Rodar o job manualmente                         | `php artisan queue:retry all`                        |

# 🧪 Usando o Tinker para criar usuários
1. php artisan tinker
```bash
   php artisan tinker
```

2. Agora sim, dentro do prompt do Tinker, digite:
```bash
   use App\Models\User;
```

3. Digite as informações:
```bash
   User::create([
    'name' => 'Teste',
    'email' => 'teste@email.com',
    'password' => Hash::make('senha123')
   ]);
```

Obs: Se o usuário for criado com sucesso, ele aparecerá no banco — se não, o erro aparecerá direto.
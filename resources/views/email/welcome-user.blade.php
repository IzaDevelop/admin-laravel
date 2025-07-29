<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lorem</title>
</head>
<body>
    <p>Olá, {{$user->name}}</p>
    <p>Seja bem-vindo ao nosso sistema</p>

    <p>Seu Login: <strong>{{ $user->email }}</strong></p>
    <p>Sua Senha: <strong>{{ $password }}</strong></p>
    
    <p>Por favor, altere sua senha após o login.</p>
</body>
</html>
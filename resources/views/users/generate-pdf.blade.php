<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <title>Lorem</title>

    <style>
        *, ::after, ::before {
            font-family: 'Roboto', sans-serif;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <h2>Informações do Usuário</h2>

    <div class="page-break">
        @php
            // $data = [
            //     'Id' => $user->id,
            //     'Name' => $user->name,
            //     'Email' => $user->email
            // ];

            // foreach ($data as $key => $value) {
            //     echo "$key: $value<br>";
            // }

            foreach ($user->only(['id', 'name', 'email']) as $key => $value) {
                echo ucfirst($key) . ': ' . $value . '<br>';
            }
        @endphp
    </div>
</body>
</html>
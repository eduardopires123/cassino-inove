<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 1px solid #eee;
        }
        .header img {
            max-width: 200px;
            height: auto;
        }
        .content {
            padding: 20px 0;
        }
        .code {
            text-align: center;
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 8px;
            margin: 30px 0;
            color: #333;
            background-color: #f7f7f7;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            color: #777;
            font-size: 12px;
            border-top: 1px solid #eee;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $site_name ?? config('app.name') }}</h1>
        </div>
        <div class="content">
            <h2>Olá, {{ $name ?? 'Usuário' }}!</h2>
            <p>Você solicitou um código de verificação para sua conta.</p>
            <p>Use o código abaixo para completar a verificação:</p>
            
            <div class="code">{{ $code }}</div>
            
            <p>Este código é válido por 10 minutos. Se você não solicitou este código, por favor ignore este email.</p>
            
            <p>Atenciosamente,<br>Equipe {{ $site_name ?? config('app.name') }}</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ $site_name ?? config('app.name') }}. Todos os direitos reservados.</p>
            <p>Este é um email automático, por favor não responda.</p>
        </div>
    </div>
</body>
</html> 
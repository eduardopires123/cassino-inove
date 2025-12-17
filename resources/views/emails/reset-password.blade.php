<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperação de Senha - BETBR</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #10b981;
            padding: 20px;
            text-align: center;
        }
        .header img {
            max-width: 150px;
        }
        .content {
            padding: 30px;
        }
        .footer {
            background-color: #f0f0f0;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
        h1 {
            color: #222;
            margin-top: 0;
        }
        .btn {
            display: inline-block;
            background-color: #10b981;
            color: white;
            padding: 12px 24px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            margin: 20px 0;
        }
        .warning {
            margin-top: 30px;
            padding: 15px;
            background-color: #fff8e6;
            border-left: 4px solid #fbbf24;
            color: #92400e;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('images/logo-white.png') }}" alt="BETBR" />
        </div>
        
        <div class="content">
            <h1>Recuperação de Senha</h1>
            
            <p>Olá,</p>
            
            <p>Recebemos uma solicitação para redefinir a senha da sua conta BETBR. Se você não solicitou uma redefinição de senha, por favor ignore este e-mail.</p>
            
            <p>Para redefinir sua senha, clique no botão abaixo:</p>
            
            <div style="text-align: center;">
                <a href="{{ route('password.reset', ['token' => $token, 'email' => $email]) }}" class="btn">Redefinir Senha</a>
            </div>
            
            <p>Ou copie e cole o link a seguir no seu navegador:</p>
            <p style="word-break: break-all; font-size: 14px;">{{ route('password.reset', ['token' => $token, 'email' => $email]) }}</p>
            
            <div class="warning">
                <p>Este link expirará em 60 minutos por motivos de segurança.</p>
                <p>Se você não solicitou uma redefinição de senha, nenhuma ação adicional é necessária.</p>
            </div>
            
            <p>Atenciosamente,<br>Equipe BETBR</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} BETBR. Todos os direitos reservados.</p>
            <p>Por favor, não responda a este e-mail. Esta é uma mensagem automática.</p>
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <title>Autenticação Twitch</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        :root {
            --twitch-purple: #9147FF;
            --twitch-dark: #0E0E10;
            --twitch-light: #EFEFF1;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: var(--twitch-dark);
            color: var(--twitch-light);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            text-align: center;
            padding: 2rem;
            max-width: 400px;
            width: 90%;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .twitch-logo {
            width: auto;
            height: 120px;
            margin-bottom: 1.5rem;
        }

        h3 {
            color: var(--twitch-purple);
            font-size: 1.5rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        p {
            margin: 0.5rem 0;
            line-height: 1.5;
            opacity: 0.9;
        }

        .status-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .success {
            color: #00ff00;
        }

        .error {
            color: #ff4444;
        }

        .loading {
            width: 40px;
            height: 40px;
            border: 3px solid var(--twitch-purple);
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s linear infinite;
            margin: 1rem auto;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="https://www.vectorlogo.zone/logos/twitch/twitch-ar21.svg" alt="Twitch Logo" class="twitch-logo">
        <div class="status-icon {{ $success ? 'success' : 'error' }}">
            {{ $success ? '✓' : '✕' }}
        </div>
        <h3>{{ $success ? 'Autenticação bem-sucedida!' : 'Falha na autenticação' }}</h3>
        <p>{{ $message }}</p>
        <div class="loading"></div>
        <p>Essa janela será fechada automaticamente...</p>
    </div>

    <script>
        // Enviar mensagem para a janela principal
        window.opener.postMessage({
            type: 'twitch-auth',
            success: {{ $success ? 'true' : 'false' }},
            message: "{{ $message }}"
        }, "{{ url('/') }}");

        // Fechar a janela após um pequeno delay
        setTimeout(function() {
            window.close();
        }, 1500);
    </script>
</body>
</html> 
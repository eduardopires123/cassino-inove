<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #4361ee;
            padding-bottom: 20px;
        }
        
        .header h1 {
            margin: 0;
            color: #4361ee;
            font-size: 24px;
            font-weight: bold;
        }
        
        .header .subtitle {
            color: #666;
            font-size: 14px;
            margin-top: 5px;
        }
        
        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }
        
        .info-item {
            text-align: center;
        }
        
        .info-item .label {
            font-weight: bold;
            color: #666;
            font-size: 11px;
            text-transform: uppercase;
        }
        
        .info-item .value {
            font-size: 14px;
            color: #333;
            margin-top: 2px;
        }
        
        .table-container {
            margin-bottom: 30px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        
        table th {
            background-color: #4361ee;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #ddd;
        }
        
        table td {
            padding: 8px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        
        table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        table tbody tr:hover {
            background-color: #e3f2fd;
        }
        
        .status-badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-align: center;
            color: white;
        }
        
        .status-concluido {
            background-color: #28a745;
        }
        
        .status-pendente {
            background-color: #ffc107;
            color: #212529;
        }
        
        .status-cancelado {
            background-color: #dc3545;
        }
        
        .total-section {
            margin-top: 30px;
            background-color: #e8f5e8;
            padding: 20px;
            border-radius: 5px;
            border-left: 5px solid #28a745;
        }
        
        .total-section h2 {
            margin: 0 0 10px 0;
            color: #28a745;
            font-size: 18px;
        }
        
        .total-value {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>@yield('title')</h1>
        <div class="subtitle">Relatório de Pagamentos - Sistema Inovei Gaming</div>
    </div>

    <div class="info-section">
        <div class="info-item">
            <div class="label">Período</div>
            <div class="value">{{ $periodo['inicio'] }} a {{ $periodo['fim'] }}</div>
        </div>
        <div class="info-item">
            <div class="label">@yield('filtro-label')</div>
            <div class="value">{{ $filtro_usuario ?: 'Todos' }}</div>
        </div>
        <div class="info-item">
            <div class="label">Total de Registros</div>
            <div class="value">@yield('total-registros')</div>
        </div>
        <div class="info-item">
            <div class="label">Gerado em</div>
            <div class="value">{{ $data_geracao }}</div>
        </div>
    </div>

    <div class="table-container">
        @yield('content')
    </div>

    <div class="total-section">
        <h2>@yield('total-title')</h2>
        <div class="total-value">R$ {{ number_format($total, 2, ',', '.') }}</div>
        <small>* Valor considera apenas transações concluídas</small>
    </div>

    <div class="footer">
        <p>Este relatório foi gerado automaticamente pelo sistema em {{ $data_geracao }}</p>
        <p>Inovei Gaming - Sistema de Gestão de Pagamentos</p>
    </div>
</body>
</html> 
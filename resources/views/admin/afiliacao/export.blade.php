<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Exportação de Afiliados de Gerentes</title>
</head>
<body>
<h1>Exportação de Dados</h1>
<p>Afiliados do Gerente <strong>{{ $username ?? "" }}</strong></p>

<table border="1" cellpadding="6" cellspacing="0" style="width:100%; border-collapse: collapse;">
    <thead>
    <tr>
        <th>Nome</th>
        <th>CPF</th>
        <th>E-mail</th>
        <th>Telefone</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($afiliados as $afiliado)
        <tr>
            <td>{{ $afiliado->name ?? "" }}</td>
            <td>{{ $afiliado->cpf ?? "" }}</td>
            <td>{{ $afiliado->email ?? "" }}</td>
            <td>{{ $afiliado->phone ?? "" }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<br>

<hr>
<p style="font-size: 12px;">
    Gerado em: {{ $emitido_em }}
</p>

</body>
</html>

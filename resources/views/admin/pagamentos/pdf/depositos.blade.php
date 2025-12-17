@extends('admin.pagamentos.pdf.base')

@section('title', 'Relatório de Depósitos')

@section('filtro-label', 'Filtro de Usuário')

@section('total-registros', count($depositos))

@section('total-title', 'Total de Depósitos Concluídos')

@section('content')
<table>
    <thead>
        <tr>
            <th style="width: 5%;">ID</th>
            <th style="width: 25%;">Usuário</th>
            <th style="width: 15%;">CPF</th>
            <th style="width: 15%;">Valor</th>
            <th style="width: 15%;">Gateway</th>
            <th style="width: 10%;">Status</th>
            <th style="width: 15%;">Data</th>
        </tr>
    </thead>
    <tbody>
        @forelse($depositos as $deposito)
        <tr>
            <td>{{ $deposito->id }}</td>
            <td>{{ $deposito->usuario_nome }}</td>
            <td>{{ $deposito->cpf }}</td>
            <td>R$ {{ number_format($deposito->amount, 2, ',', '.') }}</td>
            <td>{{ $deposito->gateway }}</td>
            <td>
                @if($deposito->status == 0)
                    <span class="status-badge status-pendente">Pendente</span>
                @elseif($deposito->status == 1)
                    <span class="status-badge status-concluido">Concluído</span>
                @elseif($deposito->status == 2)
                    <span class="status-badge status-cancelado">Cancelado</span>
                @else
                    <span class="status-badge">Desconhecido</span>
                @endif
            </td>
            <td>{{ \Carbon\Carbon::parse($deposito->updated_at)->format('d/m/Y H:i:s') }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="7" style="text-align: center; padding: 20px; color: #666;">
                Nenhum depósito encontrado para os filtros aplicados.
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

@if(count($depositos) > 0)
<div style="margin-top: 20px; font-size: 11px; color: #666;">
    <h4 style="margin-bottom: 10px;">Resumo do Relatório:</h4>
    <table style="width: 100%; margin-top: 10px;">
        <tr>
            <td style="background-color: #f8f9fa; padding: 8px; border: 1px solid #ddd; font-weight: bold;">Total de Registros:</td>
            <td style="padding: 8px; border: 1px solid #ddd;">{{ count($depositos) }}</td>
        </tr>
        <tr>
            <td style="background-color: #f8f9fa; padding: 8px; border: 1px solid #ddd; font-weight: bold;">Depósitos Concluídos:</td>
            <td style="padding: 8px; border: 1px solid #ddd;">{{ $depositos->where('status', 1)->count() }}</td>
        </tr>
        <tr>
            <td style="background-color: #f8f9fa; padding: 8px; border: 1px solid #ddd; font-weight: bold;">Depósitos Pendentes:</td>
            <td style="padding: 8px; border: 1px solid #ddd;">{{ $depositos->where('status', 0)->count() }}</td>
        </tr>
        <tr>
            <td style="background-color: #f8f9fa; padding: 8px; border: 1px solid #ddd; font-weight: bold;">Depósitos Cancelados:</td>
            <td style="padding: 8px; border: 1px solid #ddd;">{{ $depositos->where('status', 2)->count() }}</td>
        </tr>
        <tr>
            <td style="background-color: #f8f9fa; padding: 8px; border: 1px solid #ddd; font-weight: bold;">Valor Total (Todos):</td>
            <td style="padding: 8px; border: 1px solid #ddd;">R$ {{ number_format($depositos->sum('amount'), 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td style="background-color: #e8f5e8; padding: 8px; border: 1px solid #ddd; font-weight: bold; color: #28a745;">Valor Total (Concluídos):</td>
            <td style="padding: 8px; border: 1px solid #ddd; color: #28a745; font-weight: bold;">R$ {{ number_format($total, 2, ',', '.') }}</td>
        </tr>
    </table>
</div>
@endif

@endsection 
<?php

namespace App\Services;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\GamesApi;

class DataTableService
{
    /**
     * Cria e retorna um DataTable configurado com opções padrão
     *
     * @param Builder|Model $query Query Builder ou Model para criar o DataTable
     * @param callable|null $columns Função para configurar colunas e formatações
     * @param array $options Opções adicionais para configurar o DataTable
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public static function criar($query, callable $columns = null, array $options = [])
    {
        $datatable = DataTables::of($query);

        // Configuração das colunas fornecidas pelo callback
        if ($columns !== null) {
            $columns($datatable);
        }

        // Aplicar opções adicionais
        if (isset($options['rawColumns']) && is_array($options['rawColumns'])) {
            $datatable->rawColumns($options['rawColumns']);
        }

        // Não aplicamos mais ordenação aqui, deixamos isso para o frontend

        return $datatable;
    }


}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Settings;
use App\Models\Gateways;
use App\Models\Admin\CustomCSS;
use App\Models\Admin\Permissions;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ConfigController extends Controller
{
    /**
     * Lista de tipos MIME permitidos para imagens
     */
    private $allowedMimes = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/ico',
        'image/webp',
        'image/svg+xml',
        'image/x-icon',  // Para favicon
        'image/vnd.microsoft.icon' // Outro tipo para favicon
    ];

    /**
     * Tamanho máximo permitido para arquivos em bytes (5MB)
     */
    private $maxFileSize = 5 * 1024 * 1024;

    /**
     * Dimensões máximas para imagens
     */
    private $maxWidth = 2024;
    private $maxHeight = 2024;

    public function configuracoes()
    {
        $Settings = Settings::first();
        return view('admin.config.configuracoes_gerais', compact('Settings'));
    }

    public function salvarConfiguracoes(Request $request)
    {
        try {
            $settings = Settings::first();
            $updated = false;

            // Check if it's a FilePond file upload request from iOS
            $isFilepondRequest = $request->hasHeader('X-Requested-With') &&
                $request->header('X-Requested-With') == 'XMLHttpRequest' &&
                ($request->hasFile('filepond') || $request->hasFile('filepond_favicon'));

            // Mapear campos permitidos e seus tipos para validação
            $allowedFields = [
                'name' => 'string',
                'subtitle' => 'string',
                'min_saque_n' => 'numeric',
                'max_saque_n' => 'numeric',
                'max_saque_aut' => 'numeric',
                'max_saque_diario' => 'numeric',
                'max_quantidade_saques_diario' => 'integer',
                'max_quantidade_saques_automaticos_diario' => 'integer',
                'rollover_saque' => 'integer',
                'min_dep' => 'numeric',
                'max_dep' => 'numeric',
                'bonus_min_dep' => 'numeric',
                'bonus_max_dep' => 'numeric',
                'bonus_mult' => 'integer',
                'bonus_rollover' => 'integer',
                'bonus_expire_days' => 'integer',
                'tawkto_src' => 'string',
                'tawkto_active' => 'integer',
                'jivochat_src' => 'string',
                'jivochat_active' => 'integer',
                'enable_cassino_bonus' => 'integer',
                'bonus_all_deposits' => 'integer',
                'default_home_page' => 'string'
            ];

            // Processar apenas os campos enviados no request
            foreach ($allowedFields as $field => $type) {
                // Verificar se o campo existe no request (mesmo que vazio)
                if ($request->has($field) || $request->filled($field)) {
                    $value = $request->input($field);

                    // Tratar valores vazios ou null
                    if ($value === null || $value === '' || $value === 'null') {
                        // Para campos integer, valor vazio significa 0
                        if ($type === 'integer') {
                            $value = 0;
                        } else {
                            // Para outros tipos, pular se vazio
                            continue;
                        }
                    }

                    // Validar e converter o valor conforme o tipo esperado
                    if ($type === 'numeric' && is_string($value)) {
                        // Garantir formato decimal correto
                        $value = str_replace(',', '.', $value);

                        // Verificar se o valor é um número válido
                        if (!is_numeric($value) && $value !== '') {
                            continue; // Pula este campo se o valor não for numérico
                        }
                        $value = (float)$value;
                    } elseif ($type === 'integer') {
                        // Converter para inteiro se possível
                        if (is_string($value)) {
                            // Remover espaços e caracteres não numéricos (exceto sinal negativo)
                            $value = trim($value);
                            // Permitir valores vazios, 0, e números positivos
                            if ($value === '' || $value === null) {
                                $value = 0;
                            } elseif (!is_numeric($value)) {
                                continue; // Pula este campo se o valor não for numérico
                            }
                        }
                        $value = (int)$value;
                    }

                    // Tratar caso especial: campo 'subtitle' deve ser salvo na coluna 'subname'
                    if ($field === 'subtitle') {
                        if ($settings->subname != $value) {
                            $settings->subname = $value;
                            $updated = true;
                        }
                    } else {
                        // Atualizar o campo apenas se for diferente
                        // Usar comparação estrita para evitar problemas com tipos
                        $currentValue = $settings->$field ?? null;
                        if ($currentValue != $value) {
                            $settings->$field = $value;
                            $updated = true;
                        }
                    }
                }
            }

            // Verificar se o diretório existe, se não, criá-lo
            $uploadPath = public_path('img/logo');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Validar e processar upload de logo, se houver
            if ($request->hasFile('filepond')) {
                $file = $request->file('filepond');
                $fileType = $request->input('fileType', 'logo');

                try {
                    // Validar MIME type
                    $this->validateImage($file);

                    // Se for especificado como favicon, salvar como favicon
                    if ($fileType === 'favicon') {
                        $fileName = 'favicon_' . time() . '.' . $file->getClientOriginalExtension();
                        $file->move($uploadPath, $fileName);
                        $filePath = 'img/logo/' . $fileName;
                        $settings->favicon = $filePath;
                    } else {
                        // Caso contrário, salvar como logo
                        $fileName = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
                        $file->move($uploadPath, $fileName);
                        $filePath = 'img/logo/' . $fileName;
                        $settings->logo = $filePath;
                    }
                    $updated = true;
                } catch (\Exception $e) {
                    // Caso seja uma requisição AJAX, retornar erro específico
                    if ($isFilepondRequest) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Erro ao processar imagem: ' . $e->getMessage()
                        ], 422);
                    }
                    // Se não for AJAX, propagar a exceção
                    throw $e;
                }
            }

            // Validar e processar upload de favicon, se houver
            if ($request->hasFile('filepond_favicon')) {
                $file = $request->file('filepond_favicon');

                try {
                    // Validar MIME type
                    $this->validateImage($file);

                    $fileName = 'favicon_' . time() . '.' . $file->getClientOriginalExtension();
                    $file->move($uploadPath, $fileName);
                    $filePath = 'img/logo/' . $fileName;
                    $settings->favicon = $filePath;
                    $updated = true;
                } catch (\Exception $e) {
                    // Caso seja uma requisição AJAX, retornar erro específico
                    if ($isFilepondRequest) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Erro ao processar favicon: ' . $e->getMessage()
                        ], 422);
                    }
                    // Se não for AJAX, propagar a exceção
                    throw $e;
                }
            }

            // Salvar apenas se houver mudanças
            if ($updated) {
                $settings->save();
            }

            // Se for requisição do FilePond, responder adequadamente
            if ($isFilepondRequest) {
                $response = [
                    'success' => true,
                    'message' => 'Configurações atualizadas com sucesso'
                ];

                // Adicionar caminho do arquivo se disponível
                if (isset($filePath)) {
                    $response['file_path'] = asset($filePath);
                }

                return response()->json($response);
            }

            return response()->json(['success' => true, 'message' => 'Configurações atualizadas com sucesso']);
        } catch (\Exception $e) {
            Log::error('Erro ao salvar configurações: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erro ao salvar configurações: ' . $e->getMessage()], 422);
        }
    }

    public function gateways()
    {
        return view('admin.config.gateways');
    }

    public function atualizarGateway(Request $request)
    {
        try {
            // Validar request - mais flexível, sem restringir os campos
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'nome' => 'required|string',
                'field' => 'required|string',
                'value' => 'present', // Permite valores vazios
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parâmetros inválidos: ' . $validator->errors()->first()
                ], 400);
            }

            $nome = $request->input('nome');
            $field = $request->input('field');
            $value = $request->input('value', ''); // Valor padrão vazio se não for fornecido

            // Validar nome do gateway
            $gateway = Gateways::where('nome', $nome)->first();

            if (!$gateway) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gateway não encontrado'
                ], 404);
            }

            // Processar valores de acordo com o campo
            if ($field === 'active') {
                $value = (int)$value; // Converter para inteiro
            } else {
                // Permitir strings vazias
                $value = (string)$value;
            }

            // Desabilitar temporariamente os eventos do modelo para evitar erros de log
            $gateway->timestamps = false;  // Evitar atualização de timestamps

            // Atualizar o gateway e salvar (sem eventos)
            $gateway->$field = $value;
            $gateway->save(['events' => false]);  // Salvar sem disparar eventos

            return response()->json([
                'success' => true,
                'message' => 'Gateway atualizado com sucesso'
            ]);
        } catch (\Error $e) {
            // Erro de classe não encontrada ou similar
            \Illuminate\Support\Facades\Log::error('Erro crítico ao atualizar gateway: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erro ao atualizar gateway: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor ao atualizar gateway'
            ], 500);
        }
    }

    public function apisgames()
    {
        return view('admin.config.apisgames');
    }

    public function atualizarApi(Request $request)
    {
        try {
            // Validar request
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'nome' => 'required|string',
                'field' => 'required|string',
                'value' => 'present', // Permite valores vazios
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parâmetros inválidos: ' . $validator->errors()->first()
                ], 400);
            }

            $nome = $request->input('nome');
            $field = $request->input('field');
            $value = $request->input('value', ''); // Valor padrão vazio se não for fornecido

            // Obter configurações
            $settings = Settings::first();

            if (!$settings) {
                return response()->json([
                    'success' => false,
                    'message' => 'Configurações não encontradas'
                ], 404);
            }

            // Verificar se o campo existe no modelo Settings
            if (!array_key_exists($field, $settings->getAttributes())) {
                return response()->json([
                    'success' => false,
                    'message' => 'Campo não encontrado nas configurações'
                ], 400);
            }

            // Desabilitar temporariamente os eventos do modelo
            $settings->timestamps = false;

            // Atualizar o campo e salvar
            $settings->$field = $value;
            $settings->save(['events' => false]);

            return response()->json([
                'success' => true,
                'message' => 'API atualizada com sucesso'
            ]);
        } catch (\Error $e) {
            // Erro de classe não encontrada ou similar
            \Illuminate\Support\Facades\Log::error('Erro crítico ao atualizar API: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erro ao atualizar API: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor ao atualizar API'
            ], 500);
        }
    }

    public function banco()
    {
        return view('admin.config.banco');
    }

    public function realizarSaque(Request $request)
    {
        // Implementar lógica de saque bancário
        return response()->json(['success' => true]);
    }

    public function funcoesPermissoes()
    {
        return view('admin.config.funcoesepermissoes');
    }

    public function salvarPermissoes(Request $request)
    {
        $userId = $request->input('user_id');
        $permissions = $request->input('permissions', []);

        Permissions::updateOrCreate(
            ['user_id' => $userId],
            ['permission' => json_encode($permissions)]
        );

        return response()->json(['success' => true]);
    }

    /**
     * Carrega as permissões de um usuário específico
     */
    public function loadPermissions($userId)
    {
        try {
            $user = User::findOrFail($userId);
            $permissions = Permissions::where('user_id', $userId)->first();

            // Lista de permissões disponíveis
            $mapaPermissoes = [
                '1' => 'Personalização',
                '2' => 'Cassino',
                '3' => 'SportsBook',
                '4' => 'Pagamentos',
                '5' => 'Usuários',
                '6' => 'Administração',
                '7' => 'Afiliação',
                '11' => 'WhatsApp'
            ];

            // Obter os valores atuais das permissões
            $valoresPermissoes = [];
            if ($permissions) {
                $permissoesArray = json_decode($permissions->permission, true);
                foreach ($mapaPermissoes as $id => $nome) {
                    $valoresPermissoes[$id] = isset($permissoesArray[$id]) ? (int)$permissoesArray[$id] : 0;
                }
            } else {
                // Sem permissões ainda, inicializar com zeros
                foreach ($mapaPermissoes as $id => $nome) {
                    $valoresPermissoes[$id] = 0;
                }
            }

            // Construir HTML do formulário de permissões
            $html = '<div class="form-group">';
            $html .= '<input type="hidden" id="permission_user_id" value="'.$userId.'">';
            $html .= '<h5>Permissões do usuário: '.$user->name.'</h5>';
            $html .= '<div class="row">';

            foreach ($mapaPermissoes as $id => $nome) {
                $checked = $valoresPermissoes[$id] ? 'checked' : '';

                $html .= '<div class="col-md-4 mb-3">';
                $html .= '<div class="form-check form-switch">';
                $html .= '<input class="form-check-input" type="checkbox" role="switch" name="permission_'.$id.'" id="permission_'.$id.'" '.$checked.'>';
                $html .= '<label class="form-check-label" for="permission_'.$id.'">'.$nome.'</label>';
                $html .= '</div>';
                $html .= '</div>';
            }

            $html .= '</div>';
            $html .= '</div>';

            return $html;

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Salva as permissões de um usuário
     */
    public function savePermissions(Request $request)
    {
        try {
            $userId = $request->input('user_id');
            $permissions = $request->input('permissions', []);

            // Validar usuário
            $user = User::findOrFail($userId);

            // Verificar ou criar o registro de permissões
            $permissionsModel = Permissions::firstOrNew(['user_id' => $userId]);

            // Salvar permissões como JSON
            $permissionsModel->permission = json_encode($permissions);
            $permissionsModel->save();

            return response()->json(['success' => true, 'message' => 'Permissões salvas com sucesso']);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove as permissões de um usuário
     */
    public function deletePermissions($userId)
    {
        try {
            $permissions = Permissions::where('user_id', $userId)->first();

            if ($permissions) {
                $permissions->delete();
                return response()->json(['success' => true, 'message' => 'Permissões removidas com sucesso']);
            }

            return response()->json(['success' => false, 'message' => 'Nenhuma permissão encontrada para este usuário']);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Valida um arquivo de imagem
     *
     * @param \Illuminate\Http\UploadedFile $image
     * @throws \Exception
     */
    private function validateImage($image)
    {
        // Verificar tipo MIME
        if (!in_array($image->getMimeType(), $this->allowedMimes)) {
            $allowedTypesStr = implode(', ', array_map(function($mime) {
                return str_replace(['image/', 'application/'], '', $mime);
            }, $this->allowedMimes));

            throw new \Exception("Tipo de arquivo inválido. Tipos permitidos: {$allowedTypesStr}");
        }

        // Verificar tamanho
        if ($image->getSize() > $this->maxFileSize) {
            $maxSizeMB = $this->maxFileSize / (1024 * 1024);
            throw new \Exception("O arquivo excede o tamanho máximo permitido de {$maxSizeMB}MB");
        }

        // Verificar dimensões para imagens (exceto ícones)
        $imageMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (in_array($image->getMimeType(), $imageMimes)) {
            try {
                $imageInfo = getimagesize($image->getPathname());
                if ($imageInfo) {
                    $width = $imageInfo[0];
                    $height = $imageInfo[1];

                    if ($width > $this->maxWidth || $height > $this->maxHeight) {
                        throw new \Exception("A imagem excede o tamanho máximo permitido de {$this->maxWidth}x{$this->maxHeight} pixels");
                    }
                }
            } catch (\Exception $e) {
                // Registra o erro mas não interrompe o processo
            }
        }
    }
}

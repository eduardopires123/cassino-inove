<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Response;

class ImportUserController extends Controller
{
    /**
     * Mostra a página de importação
     */
    public function index()
    {
        return view('admin.import-users');
    }

    /**
     * Processa o arquivo de importação
     */
    public function upload(Request $request)
    {
        // Log da requisição para debug
        Log::info('Iniciando upload de arquivo de importação', [
            'user_id' => auth()->id(),
            'has_file' => $request->hasFile('arquivo_xls'),
            'request_size' => $request->header('Content-Length'),
            'content_type' => $request->header('Content-Type')
        ]);
        
        // Verificar se o usuário está autenticado
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Usuário não autenticado.'
            ], 401);
        }
        
        // Evitar múltiplas requisições simultâneas
        $lockKey = 'import_upload_lock_' . auth()->id();
        if (Cache::has($lockKey)) {
            Log::warning('Tentativa de upload múltiplo simultâneo', [
                'user_id' => auth()->id()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Já existe um upload em processamento. Aguarde a conclusão.'
            ], 429); // Too Many Requests
        }
        
        // Criar lock por 60 segundos
        Cache::put($lockKey, true, 60);
        
        $validator = Validator::make($request->all(), [
            'arquivo_xls' => 'required|file|mimes:xlsx,xls|max:20480', // 20MB max
        ]);

        if ($validator->fails()) {
            Log::warning('Validação falhou no upload', [
                'user_id' => auth()->id(),
                'errors' => $validator->errors()->toArray(),
                'request_files' => $request->allFiles(),
                'has_file' => $request->hasFile('arquivo_xls')
            ]);
            
            // Remover lock
            Cache::forget($lockKey);
            
            return response()->json([
                'success' => false,
                'message' => 'Arquivo inválido. Certifique-se de que é um arquivo Excel (.xlsx ou .xls) de até 20MB.',
                'errors' => $validator->errors(),
                'debug' => [
                    'has_file' => $request->hasFile('arquivo_xls'),
                    'files' => $request->allFiles()
                ]
            ], 400);
        }

        Log::info('Validação passou, iniciando processamento do arquivo', [
            'user_id' => auth()->id()
        ]);

        try {
            $file = $request->file('arquivo_xls');
            
            Log::info('Arquivo obtido do request', [
                'user_id' => auth()->id(),
                'file_name' => $file ? $file->getClientOriginalName() : 'null',
                'file_size' => $file ? $file->getSize() : 'null',
                'file_type' => $file ? $file->getMimeType() : 'null'
            ]);
            
            if (!$file) {
                Log::error('Nenhum arquivo foi enviado');
                Cache::forget($lockKey);
                return response()->json([
                    'success' => false,
                    'message' => 'Nenhum arquivo foi enviado.'
                ], 400);
            }
            
            if (!$file->isValid()) {
                Log::error('Arquivo enviado está corrompido', [
                    'user_id' => auth()->id(),
                    'error' => $file->getError()
                ]);
                Cache::forget($lockKey);
                return response()->json([
                    'success' => false,
                    'message' => 'Arquivo enviado está corrompido ou inválido.'
                ], 400);
            }
            
            Log::info('Iniciando leitura do arquivo Excel', [
                'user_id' => auth()->id(),
                'file_path' => $file->getPathname()
            ]);
            
            $spreadsheet = IOFactory::load($file->getPathname());
            
            Log::info('Arquivo Excel carregado com sucesso', [
                'user_id' => auth()->id()
            ]);
            
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            Log::info('Dados do Excel convertidos para array', [
                'user_id' => auth()->id(),
                'total_rows' => count($rows)
            ]);

            if (empty($rows)) {
                Log::warning('Arquivo vazio ou não foi possível ler', [
                    'user_id' => auth()->id()
                ]);
                Cache::forget($lockKey);
                return response()->json([
                    'success' => false,
                    'message' => 'Arquivo vazio ou não foi possível ler o conteúdo.'
                ], 400);
            }

            // Remover cabeçalho
            $header = array_shift($rows);
            
            Log::info('Cabeçalho removido', [
                'user_id' => auth()->id(),
                'header' => $header,
                'remaining_rows' => count($rows)
            ]);
            
            if (empty($rows)) {
                Log::warning('Nenhum dado encontrado após remover cabeçalho', [
                    'user_id' => auth()->id()
                ]);
                Cache::forget($lockKey);
                return response()->json([
                    'success' => false,
                    'message' => 'Nenhum dado encontrado no arquivo.'
                ], 400);
            }

            // Validar e limpar dados
            $validRows = [];
            $errors = [];
            $warnings = []; // Avisos que não impedem importação
            $processedEmails = [];
            $processedCpfs = [];

            Log::info('Iniciando validação dos dados', [
                'user_id' => auth()->id(),
                'total_rows_to_process' => count($rows)
            ]);

            foreach ($rows as $index => $row) {
                $lineNumber = $index + 2; // +2 para contar cabeçalho
                
                // Log de progresso a cada 50 linhas
                if ($index % 50 == 0) {
                    Log::info("Processando linha {$lineNumber}", [
                        'user_id' => auth()->id(),
                        'progress' => round(($index / count($rows)) * 100, 2) . '%',
                        'current_row' => $row
                    ]);
                }
                
                if (empty($row[0]) && empty($row[1]) && empty($row[2])) {
                    continue; // Pular linhas vazias
                }

                // Validar dados básicos - apenas nome é obrigatório
                if (empty($row[0])) {
                    $errors[] = "Linha {$lineNumber}: Nome é obrigatório";
                    Log::debug("Erro na linha {$lineNumber}: Nome vazio", [
                        'user_id' => auth()->id(),
                        'row' => $row
                    ]);
                    continue;
                }

                $nome = trim($row[0]);
                $emailRaw = isset($row[1]) ? trim($row[1]) : '';
                $cpfRaw = isset($row[2]) ? trim($row[2]) : '';
                $cpf = preg_replace('/[^0-9]/', '', $cpfRaw); // Limpar caracteres especiais
                $saldo = isset($row[3]) ? floatval(str_replace(',', '.', trim($row[3]))) : 0;

                // Verificar se tem saldo privilegiado (acima de 2 reais)
                $saldoPrivilegiado = $saldo > 2.00;

                // Validar email baseado no saldo
                $email = null;
                if (!empty($emailRaw)) {
                    if ($saldoPrivilegiado) {
                        // Saldo alto: aceitar qualquer formato
                        $email = strtolower($emailRaw);
                    } else {
                        // Saldo baixo: tentar melhorar o email ou aceitar formato simples
                        if (filter_var($emailRaw, FILTER_VALIDATE_EMAIL)) {
                            $email = strtolower($emailRaw);
                        } else {
                            // Se não tem @ mas tem conteúdo, aceitar como email simples
                            // Adicionando um domínio padrão para emails simples
                            $emailSimples = preg_replace('/[^a-zA-Z0-9_.-]/', '', $emailRaw);
                            if (strlen($emailSimples) >= 3) {
                                $email = strtolower($emailSimples . '@gaming.local');
                                $warnings[] = "Linha {$lineNumber}: Email convertido para {$email} (original: {$emailRaw})";
                            } else {
                                $errors[] = "Linha {$lineNumber}: Email muito curto ou inválido ({$emailRaw})";
                                Log::debug("Erro na linha {$lineNumber}: Email muito curto", [
                                    'user_id' => auth()->id(),
                                    'email' => $emailRaw,
                                    'saldo' => $saldo
                                ]);
                                continue;
                            }
                        }
                    }
                } else {
                    // Email vazio só permitido para saldo alto
                    if (!$saldoPrivilegiado) {
                        $errors[] = "Linha {$lineNumber}: Email é obrigatório para saldo ≤ R$ 2,00";
                        Log::debug("Erro na linha {$lineNumber}: Email obrigatório para saldo baixo", [
                            'user_id' => auth()->id(),
                            'saldo' => $saldo
                        ]);
                        continue;
                    }
                }

                // Validar CPF baseado no saldo - aceitar todos os CPFs da lista
                if ($saldoPrivilegiado) {
                    // Saldo alto: aceitar qualquer formato de CPF
                    if (empty($cpf) && !empty($cpfRaw)) {
                        $errors[] = "Linha {$lineNumber}: CPF contém apenas caracteres especiais, será salvo vazio (saldo privilegiado)";
                        $cpf = '';
                    }
                    if (!empty($cpf) && (strlen($cpf) != 11 || !ctype_digit($cpf))) {
                        $errors[] = "Linha {$lineNumber}: CPF com formato inválido será salvo assim mesmo (saldo privilegiado: R$ {$saldo})";
                    }
                } else {
                    // Saldo baixo: aceitar CPFs da lista mesmo que inválidos
                    if (empty($cpf)) {
                        // CPF vazio - permitir mas avisar
                        $warnings[] = "Linha {$lineNumber}: CPF vazio será aceito (saldo baixo)";
                    } else if (strlen($cpf) != 11 || !ctype_digit($cpf)) {
                        // CPF inválido: aceitar como está mas avisar
                        $warnings[] = "Linha {$lineNumber}: CPF inválido será aceito como está ({$cpfRaw})";
                    }
                    // Não fazer nenhuma modificação no CPF - usar exatamente como está na lista
                }

                // Verificar duplicatas no arquivo apenas para CPF (se não estiver vazio)
                if (!empty($cpf) && in_array($cpf, $processedCpfs)) {
                    $errors[] = "Linha {$lineNumber}: CPF duplicado no arquivo ({$cpfRaw})";
                    Log::debug("Erro na linha {$lineNumber}: CPF duplicado no arquivo", [
                        'user_id' => auth()->id(),
                        'cpf' => $cpf
                    ]);
                    continue;
                }

                // Verificar duplicatas no arquivo para email (apenas se não for null)
                if ($email && in_array($email, $processedEmails)) {
                    $errors[] = "Linha {$lineNumber}: Email duplicado no arquivo ({$email})";
                    Log::debug("Erro na linha {$lineNumber}: Email duplicado no arquivo", [
                        'user_id' => auth()->id(),
                        'email' => $email
                    ]);
                    continue;
                }

                // Verificar se já existe no banco (apenas se CPF ou email não estiverem vazios)
                $existingUser = null;
                if (!empty($cpf) || $email) {
                    // Verificar CPF separadamente
                    if (!empty($cpf)) {
                        $existingUser = User::where('cpf', $cpf)->first();
                        if ($existingUser) {
                            $errors[] = "Linha {$lineNumber}: CPF já cadastrado no sistema ({$cpfRaw})";
                            Log::debug("Erro na linha {$lineNumber}: CPF já existe no banco", [
                                'user_id' => auth()->id(),
                                'cpf' => $cpf,
                                'existing_user_id' => $existingUser->id
                            ]);
                            continue;
                        }
                    }
                    
                    // Verificar email separadamente (só se CPF não existir)
                    if (!$existingUser && $email) {
                        $existingUser = User::where('email', $email)->first();
                        if ($existingUser) {
                            $errors[] = "Linha {$lineNumber}: Email já cadastrado no sistema ({$email})";
                            Log::debug("Erro na linha {$lineNumber}: Email já existe no banco", [
                                'user_id' => auth()->id(),
                                'email' => $email,
                                'existing_user_id' => $existingUser->id
                            ]);
                            continue;
                        }
                    }
                }

                // Adicionar aos arrays de controle
                if (!empty($cpf)) {
                    $processedCpfs[] = $cpf;
                }
                if ($email) {
                    $processedEmails[] = $email;
                }

                // Adicionar aos dados válidos
                $validRows[] = [
                    'nome' => $nome,
                    'email' => $email, // Pode ser null
                    'cpf' => $cpf, // Pode ser inválido ou vazio
                    'cpf_raw' => $cpfRaw, // Para mostrar no erro se necessário
                    'saldo' => $saldo,
                    'saldo_privilegiado' => $saldoPrivilegiado,
                    'linha' => $lineNumber
                ];
                
                // Log de linha válida a cada 100 registros válidos
                if (count($validRows) % 100 == 0) {
                    Log::info("Linhas válidas processadas: " . count($validRows), [
                        'user_id' => auth()->id(),
                        'total_errors' => count($errors)
                    ]);
                }
            }

            Log::info('Validação dos dados concluída', [
                'user_id' => auth()->id(),
                'valid_rows' => count($validRows),
                'total_errors' => count($errors)
            ]);

            if (empty($validRows)) {
                Log::warning('Nenhum dado válido encontrado após validação', [
                    'user_id' => auth()->id(),
                    'total_errors' => count($errors),
                    'first_20_errors' => array_slice($errors, 0, 20)
                ]);
                Cache::forget($lockKey);
                return response()->json([
                    'success' => false,
                    'message' => 'Nenhum dado válido encontrado no arquivo.',
                    'errors' => array_slice($errors, 0, 20)
                ], 400);
            }

            // Salvar dados no cache para processamento em lotes
            $sessionKey = 'import_data_' . auth()->id() . '_' . time();
            Cache::put($sessionKey, [
                'rows' => $validRows,
                'total' => count($validRows),
                'processed' => 0,
                'success' => 0,
                'errors' => $errors
            ], 1800); // 30 minutos

            // Calcular número de lotes
            $totalBatches = ceil(count($validRows) / 500);

            Log::info('Arquivo processado com sucesso', [
                'user_id' => auth()->id(),
                'session_key' => $sessionKey,
                'total_rows' => count($validRows),
                'total_batches' => $totalBatches,
                'validation_errors' => count($errors)
            ]);

            // Remover lock
            Cache::forget($lockKey);

            return response()->json([
                'success' => true,
                'message' => 'Arquivo processado com sucesso. Iniciando importação em lotes...',
                'data' => [
                    'session_key' => $sessionKey,
                    'total_rows' => count($validRows),
                    'total_batches' => $totalBatches,
                    'batch_size' => 500,
                    'validation_errors' => array_slice($errors, 0, 20),
                    'validation_warnings' => array_slice($warnings, 0, 20)
                ]
            ]);

        } catch (\Exception $e) {
            // Remover lock em caso de erro
            Cache::forget($lockKey);
            
            Log::error('Erro na preparação da importação: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar arquivo: ' . $e->getMessage(),
                'debug' => [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ], 500);
        }
    }

    public function processBatch(Request $request)
    {
        // Log da requisição para debug
        Log::info('Processando lote de importação', [
            'user_id' => auth()->id(),
            'session_key' => $request->input('session_key'),
            'batch_number' => $request->input('batch_number', 0)
        ]);
        
        // Verificar se o usuário está autenticado
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Usuário não autenticado.'
            ], 401);
        }
        
        try {
            $sessionKey = $request->input('session_key');
            $batchNumber = $request->input('batch_number', 0);
            
            // Validar parâmetros obrigatórios
            if (!$sessionKey) {
                Log::warning('Session key não fornecida', [
                    'user_id' => auth()->id(),
                    'request' => $request->all()
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Sessão inválida - chave não fornecida'
                ], 400);
            }
            
            if (!is_numeric($batchNumber) || $batchNumber < 0) {
                Log::warning('Batch number inválido', [
                    'user_id' => auth()->id(),
                    'batch_number' => $batchNumber,
                    'request' => $request->all()
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Número do lote inválido'
                ], 400);
            }

            $importData = Cache::get($sessionKey);
            if (!$importData) {
                Log::warning('Dados da importação não encontrados no cache', [
                    'user_id' => auth()->id(),
                    'session_key' => $sessionKey,
                    'batch_number' => $batchNumber
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Dados da importação não encontrados ou expirados'
                ], 400);
            }

            $batchSize = 500;
            $startIndex = $batchNumber * $batchSize;
            $batch = array_slice($importData['rows'], $startIndex, $batchSize);

            if (empty($batch)) {
                Log::warning('Lote vazio', [
                    'user_id' => auth()->id(),
                    'session_key' => $sessionKey,
                    'batch_number' => $batchNumber,
                    'start_index' => $startIndex,
                    'total_rows' => count($importData['rows'])
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Lote vazio'
                ], 400);
            }

            $batchSuccess = 0;
            $batchErrors = [];

            try {
                foreach ($batch as $userData) {
                    try {
                        // 1. CONSULTAR NO BANCO PRIMEIRO - Verificar se CPF ou email já existe
                        $existsInDatabase = false;
                        $duplicateReason = '';
                        
                        // Verificar CPF separadamente
                        if (!empty($userData['cpf'])) {
                            $cpfExists = User::where('cpf', $userData['cpf'])->first();
                            if ($cpfExists) {
                                $existsInDatabase = true;
                                $duplicateReason = "CPF já cadastrado no sistema ({$userData['cpf']})";
                            }
                        }
                        
                        // Verificar email separadamente (só se CPF não existir)
                        if (!$existsInDatabase && $userData['email']) {
                            $emailExists = User::where('email', $userData['email'])->first();
                            if ($emailExists) {
                                $existsInDatabase = true;
                                $duplicateReason = "Email já cadastrado no sistema ({$userData['email']})";
                            }
                        }
                        
                        // 2. SE JÁ EXISTE, IGNORAR E CONTINUAR PARA O PRÓXIMO
                        if ($existsInDatabase) {
                            $batchErrors[] = "Linha {$userData['linha']}: {$duplicateReason}";
                            continue;
                        }
                        
                        // 3. SE NÃO EXISTE, CRIAR O USUÁRIO
                        DB::beginTransaction();
                        
                        // Dupla verificação com lock para garantir atomicidade (evitar race condition)
                        $lockCheck = null;
                        
                        // Verificar CPF com lock
                        if (!empty($userData['cpf'])) {
                            $lockCheck = User::where('cpf', $userData['cpf'])->lockForUpdate()->first();
                            if ($lockCheck) {
                                DB::rollBack();
                                $batchErrors[] = "Linha {$userData['linha']}: CPF já cadastrado no sistema ({$userData['cpf']})";
                                continue;
                            }
                        }
                        
                        // Verificar email com lock (só se CPF não existir)
                        if (!$lockCheck && $userData['email']) {
                            $lockCheck = User::where('email', $userData['email'])->lockForUpdate()->first();
                            if ($lockCheck) {
                                DB::rollBack();
                                $batchErrors[] = "Linha {$userData['linha']}: Email já cadastrado no sistema ({$userData['email']})";
                                continue;
                            }
                        }

                        // Gerar senha: primeira letra do nome + 3 primeiros dígitos do CPF + 2 últimos dígitos do CPF
                        $primeiraLetra = strtolower(substr($userData['nome'], 0, 1));
                        
                        // Para CPFs inválidos ou vazios, usar os dígitos disponíveis
                        $cpfLimpo = $userData['cpf'];
                        $tresPrimeiros = '';
                        $doisUltimos = '';
                        
                        if (!empty($cpfLimpo)) {
                            if (strlen($cpfLimpo) >= 3) {
                                $tresPrimeiros = substr($cpfLimpo, 0, 3);
                            } else {
                                $tresPrimeiros = str_pad($cpfLimpo, 3, '0', STR_PAD_LEFT);
                            }
                            
                            if (strlen($cpfLimpo) >= 2) {
                                $doisUltimos = substr($cpfLimpo, -2);
                            } else {
                                $doisUltimos = str_pad($cpfLimpo, 2, '0', STR_PAD_LEFT);
                            }
                        } else {
                            // CPF vazio - usar padrão
                            $tresPrimeiros = '123';
                            $doisUltimos = '45';
                        }
                        
                        $senha = $primeiraLetra . $tresPrimeiros . $doisUltimos;

                        // Criar usuário
                        $user = User::create([
                            'name' => $userData['nome'],
                            'email' => $userData['email'], // Pode ser null
                            'cpf' => $userData['cpf'],
                            'pix' => !empty($userData['cpf']) && strlen($userData['cpf']) == 11 && ctype_digit($userData['cpf']) ? $userData['cpf'] : null, // PIX = CPF se válido
                            'password' => Hash::make($senha),
                            'phone' => null,
                            'date_of_birth' => null,
                            'status' => 1,
                            'role_id' => 2,
                            'email_verified_at' => $userData['email'] ? now() : null, // Só verificar se tem email
                            'is_demo_agent' => 0,
                            'inviter' => null,
                            'affiliate_revenue_share' => 0,
                            'affiliate_cpa' => 0,
                            'affiliate_baseline' => 0,
                            'affiliate_percentage' => 0,
                            'ban' => 0,
                            'bets' => 0,
                            'wins' => 0,
                            'losses' => 0,
                            'last_login' => null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        // Criar carteira
                        Wallet::create([
                            'user_id' => $user->id,
                            'currency' => 'BRL',
                            'symbol' => 'R$',
                            'balance' => $userData['saldo'],
                            'balance_bonus' => 0.00,
                            'balance_withdrawal' => 0.00,
                            'balance_demo' => 0.00,
                            'refer_rewards' => 0.00,
                            'hide_balance' => 0,
                            'active' => 1,
                            'last_used' => now(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        $batchSuccess++;
                        
                        // Fechar transação individual
                        DB::commit();
                        
                    } catch (\Illuminate\Database\QueryException $e) {
                        // Rollback da transação individual se houver erro
                        DB::rollBack();
                        
                        // Tratamento específico para violação de constraint unique
                        if ($e->errorInfo[1] == 1062) { // Duplicate entry error
                            if (str_contains($e->getMessage(), 'users_email_unique')) {
                                $batchErrors[] = "Linha {$userData['linha']}: Email já cadastrado no sistema ({$userData['email']})";
                            } elseif (str_contains($e->getMessage(), 'users_cpf_unique')) {
                                $batchErrors[] = "Linha {$userData['linha']}: CPF já cadastrado no sistema ({$userData['cpf']})";
                            } else {
                                $batchErrors[] = "Linha {$userData['linha']}: Dados já cadastrados no sistema";
                            }
                        } else {
                            $batchErrors[] = "Linha {$userData['linha']}: Erro de banco de dados - " . $e->getMessage();
                        }
                        Log::error("Erro de banco ao criar usuário linha {$userData['linha']}: " . $e->getMessage(), [
                            'exception' => $e,
                            'userData' => $userData
                        ]);
                    } catch (\Exception $e) {
                        // Rollback da transação individual se houver erro
                        DB::rollBack();
                        
                        $batchErrors[] = "Linha {$userData['linha']}: Erro ao criar usuário - " . $e->getMessage();
                        Log::error("Erro ao criar usuário linha {$userData['linha']}: " . $e->getMessage(), [
                            'exception' => $e,
                            'userData' => $userData
                        ]);
                    }
                }

            } catch (\Exception $e) {
                Log::error("Erro no lote {$batchNumber}: " . $e->getMessage(), [
                    'exception' => $e,
                    'batchNumber' => $batchNumber,
                    'user_id' => auth()->id(),
                    'session_key' => $sessionKey,
                    'stack_trace' => $e->getTraceAsString()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Erro no processamento do lote: ' . $e->getMessage()
                ], 500);
            }

            // Atualizar dados da sessão
            $importData['processed'] += count($batch);
            $importData['success'] += $batchSuccess;
            $importData['errors'] = array_merge($importData['errors'], $batchErrors);

            Cache::put($sessionKey, $importData, 1800);

            Log::info('Lote processado com sucesso', [
                'user_id' => auth()->id(),
                'session_key' => $sessionKey,
                'batch_number' => $batchNumber,
                'batch_success' => $batchSuccess,
                'batch_errors' => count($batchErrors),
                'total_processed' => $importData['processed']
            ]);

            return response()->json([
                'success' => true,
                'message' => "Lote " . ($batchNumber + 1) . " processado com sucesso",
                'data' => [
                    'batch_number' => $batchNumber,
                    'batch_success' => $batchSuccess,
                    'batch_errors' => count($batchErrors),
                    'total_processed' => $importData['processed'],
                    'total_success' => $importData['success'],
                    'total_errors' => count($importData['errors']),
                    'progress_percent' => round(($importData['processed'] / $importData['total']) * 100, 2)
                ]
            ]);

        } catch (\Throwable $e) {
            // Capturar qualquer erro e garantir resposta JSON
            Log::error("Erro crítico no processamento do lote {$batchNumber}: " . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all(),
                'user_id' => auth()->id(),
                'stack_trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro interno no processamento do lote: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getResults(Request $request)
    {
        Log::info('Obtendo resultados da importação', [
            'user_id' => auth()->id(),
            'session_key' => $request->input('session_key')
        ]);
        
        $sessionKey = $request->input('session_key');
        
        if (!$sessionKey) {
            return response()->json([
                'success' => false,
                'message' => 'Sessão inválida'
            ], 400);
        }

        $importData = Cache::get($sessionKey);
        if (!$importData) {
            Log::warning('Dados da importação não encontrados no cache - getResults', [
                'user_id' => auth()->id(),
                'session_key' => $sessionKey
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Dados da importação não encontrados'
            ], 400);
        }

        // Limpar cache após obter resultados
        Cache::forget($sessionKey);

        Log::info('Resultados da importação obtidos com sucesso', [
            'user_id' => auth()->id(),
            'session_key' => $sessionKey,
            'total_processed' => $importData['total'],
            'success_count' => $importData['success'],
            'error_count' => count($importData['errors'])
        ]);

        return response()->json([
            'success' => true,
            'message' => "Importação concluída! {$importData['success']} usuários importados com sucesso",
            'data' => [
                'total_processed' => $importData['total'],
                'success_count' => $importData['success'],
                'error_count' => count($importData['errors']),
                'warning_count' => count($importData['warnings'] ?? []),
                'errors' => array_slice($importData['errors'], 0, 20),
                'warnings' => array_slice($importData['warnings'] ?? [], 0, 20)
            ]
        ]);
    }

    /**
     * Método de teste para debug
     */
    public function test(Request $request)
    {
        try {
            Log::info('Teste do ImportUserController funcionando', [
                'user_id' => auth()->id(),
                'request_data' => $request->all(),
                'timestamp' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'ImportUserController está funcionando corretamente',
                'data' => [
                    'user_id' => auth()->id(),
                    'timestamp' => now(),
                    'has_file' => $request->hasFile('arquivo_xls'),
                    'request_size' => $request->header('Content-Length'),
                    'content_type' => $request->header('Content-Type')
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Erro no teste do ImportUserController', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro no teste: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Baixa um template de exemplo para importação
     */
    public function template()
    {
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="template_importacao_usuarios.xlsx"',
        ];

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();
        
        // Cabeçalhos
        $worksheet->setCellValue('A1', 'Nome');
        $worksheet->setCellValue('B1', 'Email');
        $worksheet->setCellValue('C1', 'CPF');
        $worksheet->setCellValue('D1', 'Saldo');
        
        // Exemplos com saldo privilegiado (> R$ 2,00)
        $worksheet->setCellValue('A2', 'João Silva (Privilegiado)');
        $worksheet->setCellValue('B2', 'joaosilva'); // Email sem @
        $worksheet->setCellValue('C2', '123'); // CPF inválido
        $worksheet->setCellValue('D2', '5.00'); // Saldo > 2
        
        $worksheet->setCellValue('A3', 'Maria Santos (Privilegiado)');
        $worksheet->setCellValue('B3', ''); // Email vazio
        $worksheet->setCellValue('C3', ''); // CPF vazio
        $worksheet->setCellValue('D3', '10.50'); // Saldo > 2
        
        // Exemplos com saldo padrão (≤ R$ 2,00)
        $worksheet->setCellValue('A4', 'Carlos Oliveira (Padrão)');
        $worksheet->setCellValue('B4', 'carlos@email.com'); // Email válido obrigatório
        $worksheet->setCellValue('C4', '12345678901'); // CPF válido obrigatório
        $worksheet->setCellValue('D4', '1.50'); // Saldo ≤ 2
        
        $worksheet->setCellValue('A5', 'Ana Costa (Padrão)');
        $worksheet->setCellValue('B5', 'ana@site.com'); // Email válido obrigatório
        $worksheet->setCellValue('C5', '98765432100'); // CPF válido obrigatório
        $worksheet->setCellValue('D5', '0.00'); // Saldo ≤ 2

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        
        return Response::streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, 'template_importacao_usuarios.xlsx', $headers);
    }


} 
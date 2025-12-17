@extends('admin.layouts.app')

@section('content')
    <div class="layout-px-spacing" id="contentaff">
        <div class="middle-content container-xxl p-0">
            <div class="page-meta" style="margin-top:45px;">
                <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Admin</a></li>
                        <li class="breadcrumb-item active" aria-current="page" id="estatde">Importar Usuários</li>
                    </ol>
                </nav>
            </div>

            <div class="row layout-top-spacing">
                <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                    <div class="widget-content widget-content-area br-8">
                        <div id="zero-config_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                            <div class="col-xl-12">
                                <div class="row">
                                    <div class="col-md-12 d-flex flex-wrap justify-content-end gap-2" style="padding-right: 36px; padding-top: 20px;">
                                        <a href="{{ route('admin.import-users.template') }}" class="btn btn-info d-flex align-items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download me-1">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                <polyline points="7 10 12 15 17 10"></polyline>
                                                <line x1="12" y1="15" x2="12" y2="3"></line>
                                            </svg> Baixar Template
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xl-8 col-lg-8 col-md-12 mx-auto">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">Importar Usuários em Massa</h5>
                                        </div>
                                        <div class="card-body">
                                            <p class="mb-4">Faça upload de um arquivo XLSX para importar usuários em massa</p>

                                            <!-- Instruções -->
                                            <div class="alert alert-info">
                                                <h6>Instruções:</h6>
                                                <ul class="mb-0">
                                                    <li>O arquivo deve ter as colunas: <strong>Nome</strong>, <strong>Email</strong>, <strong>CPF</strong>, <strong>Saldo</strong></li>
                                                    <li><strong>Nome é sempre obrigatório</strong> para todos os usuários</li>
                                                    <li><strong>Regras especiais baseadas no saldo:</strong></li>
                                                    <li class="ms-3">📈 <strong>Saldo > R$ 2,00 (Privilegiado):</strong></li>
                                                    <li class="ms-4">• Email e CPF podem ter qualquer formato ou estar vazios</li>
                                                    <li class="ms-4">• Exemplo: "eduardopires", "123", "" - todos aceitos</li>
                                                    <li class="ms-3">📊 <strong>Saldo ≤ R$ 2,00 (Padrão):</strong></li>
                                                    <li class="ms-4">• Email deve ter formato válido (@) e é obrigatório</li>
                                                    <li class="ms-4">• CPF deve ter 11 dígitos válidos e é obrigatório</li>
                                                    <li>CPF será limpo automaticamente (remove pontos, traços e espaços)</li>
                                                    <li><strong>CPF válido (11 dígitos) será salvo também como PIX</strong></li>
                                                    <li>Senhas são geradas automaticamente: primeira letra do nome + dígitos do CPF</li>
                                                    <li>Para CPFs inválidos/vazios, usa dígitos padrão → senha: j12345</li>
                                                </ul>
                                            </div>

                                            <!-- Exemplos -->
                                            <div class="alert alert-warning">
                                                <h6>Exemplos de Validação:</h6>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <strong>✅ Saldo > R$ 2,00:</strong>
                                                        <ul class="small mb-0">
                                                            <li>João | eduardopires | 123 | 5.00</li>
                                                            <li>Maria | | | 10.50</li>
                                                            <li>Carlos | user123 | 1234567890 | 3.00</li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>❌/✅ Saldo ≤ R$ 2,00:</strong>
                                                        <ul class="small mb-0">
                                                            <li>❌ Pedro | pedro | 123 | 1.00</li>
                                                            <li>✅ Pedro | pedro@email.com | 12345678901 | 1.00</li>
                                                            <li>❌ Ana | | | 0.00</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Formulário de Upload -->
                                            <form id="importForm" enctype="multipart/form-data">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="arquivo_xls" class="form-label">Arquivo XLSX</label>
                                                    <input type="file" class="form-control" id="arquivo_xls" name="arquivo_xls" accept=".xlsx,.xls" required>
                                                    <small class="form-text text-muted">Formatos aceitos: .xlsx, .xls (máximo 20MB)</small>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <button type="submit" class="btn btn-success d-grid w-100" id="submitBtn">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-upload me-1">
                                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                            <polyline points="17 8 12 3 7 8"></polyline>
                                                            <line x1="12" y1="3" x2="12" y2="15"></line>
                                                        </svg> Importar Usuários
                                                    </button>
                                                </div>
                                            </form>

                                            <!-- Progress Bar -->
                                            <div id="progressArea" class="mt-4" style="display: none;">
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                                                </div>
                                                <p class="mt-2 text-center">Processando arquivo...</p>
                                            </div>

                                            <!-- Área de Resultado -->
                                            <div id="resultArea" class="mt-4" style="display: none;">
                                                <div id="resultAlert" class="alert" role="alert">
                                                    <div id="resultContent"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('importForm');
    const submitBtn = document.getElementById('submitBtn');
    const resultArea = document.getElementById('resultArea');
    const resultAlert = document.getElementById('resultAlert');
    const resultContent = document.getElementById('resultContent');
    const progressArea = document.getElementById('progressArea');
    const progressBar = document.querySelector('.progress-bar');
    
    let isProcessing = false;
    let importData = null;

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        if (isProcessing) {
            return;
        }
        
        const formData = new FormData(this);
        const fileInput = document.getElementById('arquivo_xls');
        
        if (!fileInput.files.length) {
            showAlert('Selecione um arquivo para importar.', 'danger');
            return;
        }
        
        isProcessing = true;
        showProgress(0);
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Preparando arquivo...';

        try {
            // Passo 1: Upload e preparação do arquivo
            const uploadResponse = await fetch('{{ route("admin.import-users.upload") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                }
            });

            const uploadResult = await uploadResponse.json();
            
            if (!uploadResult.success) {
                showAlert(uploadResult.message, 'danger');
                if (uploadResult.errors) {
                    let errorList = '<ul class="mt-2">';
                    uploadResult.errors.forEach(error => {
                        errorList += `<li>${error}</li>`;
                    });
                    errorList += '</ul>';
                    showAlert(uploadResult.message + errorList, 'danger');
                }
                return;
            }

            importData = uploadResult.data;
            
            // Mostrar erros de validação se houver
            if (importData.validation_errors && importData.validation_errors.length > 0) {
                let errorList = '<div class="alert alert-warning mt-3"><strong>Linhas ignoradas:</strong><ul>';
                importData.validation_errors.forEach(error => {
                    errorList += `<li>${error}</li>`;
                });
                errorList += '</ul></div>';
                showAlert(`Arquivo preparado! ${importData.total_rows} linhas válidas encontradas.${errorList}`, 'info');
            }

            // Passo 2: Processar lotes
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Processando lotes...';
            await processBatches();

        } catch (error) {
            showAlert('Erro ao processar o arquivo: ' + error.message, 'danger');
        } finally {
            isProcessing = false;
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-upload me-1"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg> Importar Usuários';
        }
    });

    async function processBatches() {
        const totalBatches = importData.total_batches;
        let processedBatches = 0;
        let totalSuccess = 0;
        let totalErrors = 0;

        for (let batchNumber = 0; batchNumber < totalBatches; batchNumber++) {
            try {
                const batchResponse = await fetch('{{ route("admin.import-users.process-batch") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        session_key: importData.session_key,
                        batch_number: batchNumber
                    })
                });

                // Verificar se a resposta é válida
                if (!batchResponse.ok) {
                    throw new Error(`HTTP ${batchResponse.status}: ${batchResponse.statusText}`);
                }

                // Verificar se é JSON válido
                const contentType = batchResponse.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const text = await batchResponse.text();
                    console.error('Resposta não é JSON:', text);
                    throw new Error(`Servidor retornou resposta inválida (${contentType}). Verifique os logs.`);
                }

                const batchResult = await batchResponse.json();
                
                if (batchResult.success) {
                    processedBatches++;
                    totalSuccess += batchResult.data.batch_success;
                    totalErrors += batchResult.data.batch_errors;
                    
                    // Atualizar progress bar
                    const progress = Math.round((processedBatches / totalBatches) * 100);
                    showProgress(progress);
                    
                    // Atualizar texto do botão
                    submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span> Processando lote ${processedBatches}/${totalBatches} (${totalSuccess} sucessos, ${totalErrors} erros)`;
                    
                } else {
                    console.error(`Erro no lote ${batchNumber + 1}:`, batchResult.message);
                    totalErrors++;
                    
                    // Continuar com próximo lote mesmo com erro
                    processedBatches++;
                    const progress = Math.round((processedBatches / totalBatches) * 100);
                    showProgress(progress);
                }
                
            } catch (error) {
                console.error(`Erro ao processar lote ${batchNumber + 1}:`, error);
                totalErrors++;
                
                // Para erros críticos, perguntar se quer continuar
                if (error.message.includes('Servidor retornou resposta inválida') || 
                    error.message.includes('HTTP 500')) {
                    const continuar = confirm(`Erro no lote ${batchNumber + 1}: ${error.message}\n\nDeseja continuar com os próximos lotes?`);
                    if (!continuar) {
                        break;
                    }
                }
                
                // Continuar com próximo lote
                processedBatches++;
                const progress = Math.round((processedBatches / totalBatches) * 100);
                showProgress(progress);
            }
        }

        // Passo 3: Obter resultados finais
        await getResults();
    }

    async function getResults() {
        try {
            const resultsResponse = await fetch('{{ route("admin.import-users.get-results") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    session_key: importData.session_key
                })
            });

            const resultsData = await resultsResponse.json();
            
            hideProgress();
            
            if (resultsData.success) {
                showResult(resultsData, 'success');
                form.reset();
            } else {
                showAlert(resultsData.message, 'danger');
            }
            
        } catch (error) {
            hideProgress();
            showAlert('Erro ao obter resultados: ' + error.message, 'danger');
        }
    }

    function showProgress(percent) {
        progressArea.style.display = 'block';
        resultArea.style.display = 'none';
        progressBar.style.width = percent + '%';
        progressBar.textContent = percent + '%';
    }

    function hideProgress() {
        progressArea.style.display = 'none';
        progressBar.style.width = '0%';
        progressBar.textContent = '';
    }

    function showAlert(message, type) {
        resultAlert.className = `alert alert-${type}`;
        resultContent.innerHTML = message;
        resultArea.style.display = 'block';
    }

    function showResult(result, type) {
        let content = `<h6>${result.message}</h6>`;
        
        if (result.data) {
            content += `
                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="badge badge-light-primary fs-6">${result.data.total_processed}</div>
                            <div class="small">Total Processados</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="badge badge-light-success fs-6">${result.data.success_count}</div>
                            <div class="small">Sucessos</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="badge badge-light-danger fs-6">${result.data.error_count}</div>
                            <div class="small">Erros</div>
                        </div>
                    </div>
                </div>
            `;
            
            if (result.data.errors && result.data.errors.length > 0) {
                content += '<h6 class="mt-3">Erros encontrados:</h6>';
                content += '<ul class="list-unstyled">';
                result.data.errors.forEach(error => {
                    content += `<li><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-circle text-danger me-1"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> ${error}</li>`;
                });
                content += '</ul>';
            }
        }
        
        showAlert(content, type);
    }
});
</script>
@endpush 
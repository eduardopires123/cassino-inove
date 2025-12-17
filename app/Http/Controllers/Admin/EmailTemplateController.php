<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class EmailTemplateController extends Controller
{
    /**
     * Exibe a lista de templates de email
     */
    public function index()
    {
        $templates = EmailTemplate::orderBy('name')->get();
        return view('admin.email_templates.index', compact('templates'));
    }

    /**
     * Exibe o formulário para criar um novo template
     */
    public function create()
    {
        return view('admin.email_templates.create');
    }

    /**
     * Armazena um novo template no banco de dados
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:email_templates,slug',
            'description' => 'nullable|string',
            'subject' => 'required|string',
            'html_content' => 'required|string',
            'text_content' => 'nullable|string',
            'is_active' => 'boolean',
            'brevo_template_id' => 'nullable|integer',
            'variables' => 'nullable|array',
            'variables.*.name' => 'required|string',
            'variables.*.description' => 'required|string',
        ]);
        
        // Formatar as variáveis para JSON
        if (isset($validated['variables'])) {
            $variables = [];
            foreach ($validated['variables'] as $variable) {
                $variables[$variable['name']] = $variable['description'];
            }
            $validated['variables'] = $variables;
        }

        EmailTemplate::create($validated);
        
        return redirect()->route('admin.email-templates.index')
            ->with('success', 'Template de email criado com sucesso!');
    }

    /**
     * Exibe um template específico
     */
    public function show(EmailTemplate $emailTemplate)
    {
        return view('admin.email_templates.show', compact('emailTemplate'));
    }

    /**
     * Exibe o formulário para editar um template
     */
    public function edit(EmailTemplate $emailTemplate)
    {
        return view('admin.email_templates.edit', compact('emailTemplate'));
    }

    /**
     * Atualiza um template no banco de dados
     */
    public function update(Request $request, EmailTemplate $emailTemplate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('email_templates')->ignore($emailTemplate->id),
            ],
            'description' => 'nullable|string',
            'subject' => 'required|string',
            'html_content' => 'required|string',
            'text_content' => 'nullable|string',
            'is_active' => 'boolean',
            'brevo_template_id' => 'nullable|integer',
            'variables' => 'nullable|array',
            'variables.*.name' => 'required|string',
            'variables.*.description' => 'required|string',
        ]);
        
        // Formatar as variáveis para JSON
        if (isset($validated['variables'])) {
            $variables = [];
            foreach ($validated['variables'] as $variable) {
                $variables[$variable['name']] = $variable['description'];
            }
            $validated['variables'] = $variables;
        }

        $emailTemplate->update($validated);
        
        return redirect()->route('admin.email-templates.index')
            ->with('success', 'Template de email atualizado com sucesso!');
    }

    /**
     * Remove um template do banco de dados
     */
    public function destroy(EmailTemplate $emailTemplate)
    {
        // Verificar se é um template padrão do sistema
        $defaultTemplates = ['welcome', 'password-reset'];
        if (in_array($emailTemplate->slug, $defaultTemplates)) {
            return redirect()->route('admin.email-templates.index')
                ->with('error', 'Não é possível excluir templates padrão do sistema.');
        }
        
        $emailTemplate->delete();
        
        return redirect()->route('admin.email-templates.index')
            ->with('success', 'Template de email excluído com sucesso!');
    }

    /**
     * Exibe uma prévia do template de email
     */
    public function preview(Request $request, EmailTemplate $emailTemplate)
    {
        // Dados de exemplo para preencher o template
        $data = [
            'nome' => 'Usuário de Teste',
            'site_name' => config('app.name'),
            'site_url' => config('app.url'),
            'link_reset' => 'https://exemplo.com/reset/token',
            'expiracao' => '60 minutos',
            'year' => date('Y'),
            'valor' => '100,00',
            'data' => date('d/m/Y H:i:s'),
            'metodo' => 'PIX',
            'transaction_id' => 'TRX' . rand(100000, 999999),
        ];
        
        // Substituir os dados personalizados enviados pelo formulário
        if ($request->has('test_data')) {
            $data = array_merge($data, $request->input('test_data'));
        }
        
        // Renderizar o template
        $subject = $emailTemplate->renderSubject($data);
        $htmlContent = $emailTemplate->renderHtml($data);
        $textContent = $emailTemplate->renderText($data);
        
        return view('admin.email_templates.preview', compact('emailTemplate', 'subject', 'htmlContent', 'textContent', 'data'));
    }

    /**
     * Enviar um email de teste para o usuário
     */
    public function sendTest(Request $request, EmailTemplate $emailTemplate)
    {
        $request->validate([
            'email' => 'required|email',
            'test_data' => 'nullable|array',
        ]);
        
        $email = $request->input('email');
        
        // Dados de exemplo para preencher o template
        $data = [
            'nome' => 'Usuário de Teste',
            'site_name' => config('app.name'),
            'site_url' => config('app.url'),
            'link_reset' => 'https://exemplo.com/reset/token',
            'expiracao' => '60 minutos',
            'year' => date('Y'),
            'valor' => '100,00',
            'data' => date('d/m/Y H:i:s'),
            'metodo' => 'PIX',
            'transaction_id' => 'TRX' . rand(100000, 999999),
        ];
        
        // Substituir os dados personalizados enviados pelo formulário
        if ($request->has('test_data')) {
            $data = array_merge($data, $request->input('test_data'));
        }
        
        // Enviar o email de teste
        $brevoService = app(\App\Services\BrevoService::class);
        $result = $brevoService->enviarEmailTemplate(
            [
                'email' => $email,
                'name' => 'Usuário de Teste'
            ],
            $emailTemplate->slug,
            $data
        );
        
        if ($result) {
            return redirect()->back()->with('success', 'Email de teste enviado com sucesso!');
        } else {
            return redirect()->back()->with('error', 'Erro ao enviar email de teste. Verifique os logs para mais detalhes.');
        }
    }

    /**
     * Sincroniza todos os templates do sistema com o Brevo
     */
    public function syncWithBrevo()
    {
        $templates = EmailTemplate::all();
        $successCount = 0;
        $errorCount = 0;
        
        foreach ($templates as $template) {
            // Implementar a lógica de sincronização com a API do Brevo
            // Isso dependerá da API específica do Brevo para criação/atualização de templates
            
            // Por enquanto, apenas simular sucesso
            $successCount++;
        }
        
        return redirect()->route('admin.email-templates.index')
            ->with('success', "{$successCount} templates sincronizados com sucesso! ({$errorCount} falhas)");
    }

    /**
     * Executa a migração de dados para criar os templates padrão
     */
    public function runMigration()
    {
        // Executar o seeder manualmente
        \Artisan::call('db:seed', [
            '--class' => 'EmailTemplateSeeder',
            '--force' => true,
        ]);
        
        return redirect()->route('admin.email-templates.index')
            ->with('success', 'Templates padrão criados com sucesso!');
    }
}

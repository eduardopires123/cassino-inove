<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'subject',
        'html_content',
        'text_content',
        'variables',
        'is_active',
        'brevo_template_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Renderiza o conteúdo do template substituindo as variáveis.
     *
     * @param array $data
     * @return string
     */
    public function renderHtml(array $data): string
    {
        $content = $this->html_content;
        
        foreach ($data as $key => $value) {
            $content = str_replace('{{' . $key . '}}', $value, $content);
        }
        
        // Remover qualquer variável restante não substituída
        $content = preg_replace('/\{\{[^}]+\}\}/', '', $content);
        
        return $content;
    }

    /**
     * Renderiza o assunto substituindo as variáveis.
     *
     * @param array $data
     * @return string
     */
    public function renderSubject(array $data): string
    {
        $subject = $this->subject;
        
        foreach ($data as $key => $value) {
            $subject = str_replace('{{' . $key . '}}', $value, $subject);
        }
        
        // Remover qualquer variável restante não substituída
        $subject = preg_replace('/\{\{[^}]+\}\}/', '', $subject);
        
        return $subject;
    }

    /**
     * Renderiza o conteúdo de texto do template substituindo as variáveis.
     *
     * @param array $data
     * @return string|null
     */
    public function renderText(array $data): ?string
    {
        if (empty($this->text_content)) {
            return null;
        }
        
        $content = $this->text_content;
        
        foreach ($data as $key => $value) {
            $content = str_replace('{{' . $key . '}}', $value, $content);
        }
        
        // Remover qualquer variável restante não substituída
        $content = preg_replace('/\{\{[^}]+\}\}/', '', $content);
        
        return $content;
    }

    /**
     * Busca um template pelo slug.
     *
     * @param string $slug
     * @return self|null
     */
    public static function findBySlug(string $slug): ?self
    {
        return self::where('slug', $slug)
            ->where('is_active', true)
            ->first();
    }
}

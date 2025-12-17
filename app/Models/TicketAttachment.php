<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_message_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
    ];

    /**
     * Relacionamento com a mensagem a qual o anexo pertence
     */
    public function message()
    {
        return $this->belongsTo(TicketMessage::class, 'ticket_message_id');
    }

    /**
     * Método para obter o tamanho do arquivo formatado
     */
    public function getFormattedSizeAttribute()
    {
        $bytes = $this->file_size;
        
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    /**
     * Método para verificar se o anexo é uma imagem
     */
    public function getIsImageAttribute()
    {
        return in_array($this->file_type, [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/svg+xml',
            'image/webp',
        ]);
    }

    /**
     * Método para verificar se o anexo é um documento
     */
    public function getIsDocumentAttribute()
    {
        return in_array($this->file_type, [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'text/plain',
        ]);
    }

    /**
     * Método para obter o ícone do tipo de arquivo
     */
    public function getIconAttribute()
    {
        if ($this->is_image) {
            return 'image';
        }

        if (strpos($this->file_type, 'pdf') !== false) {
            return 'file-pdf';
        }

        if (strpos($this->file_type, 'word') !== false) {
            return 'file-word';
        }

        if (strpos($this->file_type, 'excel') !== false || strpos($this->file_type, 'spreadsheet') !== false) {
            return 'file-excel';
        }

        if (strpos($this->file_type, 'powerpoint') !== false || strpos($this->file_type, 'presentation') !== false) {
            return 'file-powerpoint';
        }

        if (strpos($this->file_type, 'zip') !== false || strpos($this->file_type, 'rar') !== false) {
            return 'file-archive';
        }

        if (strpos($this->file_type, 'text') !== false) {
            return 'file-text';
        }

        return 'paperclip';
    }
}
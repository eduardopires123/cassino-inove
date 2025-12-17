<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvolutionApiConfig extends Model
{
    use HasFactory;

    protected $table = 'evolution_api_config';

    protected $fillable = [
        'instance_name',
        'qr_code',
        'status',
        'phone_number',
        'instance_token',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Obter a configuração (singleton)
     */
    public static function getConfig()
    {
        $config = self::first();
        
        if (!$config) {
            $config = self::create([
                'instance_name' => null,
                'qr_code' => null,
                'status' => 'disconnected',
                'phone_number' => null,
                'instance_token' => null,
            ]);
        }
        
        return $config;
    }

    /**
     * Verifica se está conectado
     */
    public function isConnected()
    {
        return $this->status === 'open';
    }

    /**
     * Verifica se já existe uma instância configurada
     */
    public static function hasInstance()
    {
        $config = self::first();
        return $config && $config->instance_name !== null;
    }

    /**
     * Verifica se pode criar uma nova instância
     */
    public static function canCreateInstance()
    {
        return !self::hasInstance();
    }

    /**
     * Limpar dados de conexão
     */
    public function clearConnection()
    {
        $this->qr_code = null;
        $this->status = 'disconnected';
        $this->phone_number = null;
        $this->save();
    }

    /**
     * Deletar instância completamente
     */
    public function deleteInstanceData()
    {
        $this->instance_name = null;
        $this->qr_code = null;
        $this->status = 'disconnected';
        $this->phone_number = null;
        $this->instance_token = null;
        $this->save();
    }
}


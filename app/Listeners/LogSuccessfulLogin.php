<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\LoginHistory;
use Illuminate\Support\Facades\Http;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $ip = request()->ip();
        $userAgent = request()->userAgent();
        
        // Dados de geolocalização (pode ser feito através de um serviço como ipinfo.io)
        $geoData = $this->getGeoData($ip);
        
        // Registra o login
        LoginHistory::create([
            'user_id' => $event->user->id,
            'ip' => $ip,
            'city' => $geoData['city'] ?? null,
            'state' => $geoData['region'] ?? null,
            'country' => $geoData['country'] ?? null,
            'lat' => $geoData['lat'] ?? null,
            'lng' => $geoData['lng'] ?? null,
            'user_agent' => $userAgent
        ]);
    }
    
    /**
     * Obtém dados de geolocalização a partir do IP
     *
     * @param string $ip
     * @return array
     */
    private function getGeoData($ip)
    {
        // Verifica se é um IP local (para desenvolvimento)
        if (in_array($ip, ['127.0.0.1', '::1', 'localhost'])) {
            return [
                'city' => 'Local',
                'region' => 'Dev',
                'country' => 'BR',
                'lat' => '-23.5505',
                'lng' => '-46.6333'
            ];
        }

        try {
            // Usando API gratuita do ipapi.co (limite de 1000 requisições/dia)
            // Você pode substituir por outros serviços como ipinfo.io (requer token)
            $response = Http::get("https://ipapi.co/{$ip}/json/");
            
            if ($response->successful()) {
                $data = $response->json();
                return [
                    'city' => $data['city'] ?? null,
                    'region' => $data['region'] ?? null,
                    'country' => $data['country'] ?? null,
                    'lat' => $data['latitude'] ?? null,
                    'lng' => $data['longitude'] ?? null
                ];
            }
        } catch (\Exception $e) {
            \Log::error('Erro ao obter dados de geolocalização: ' . $e->getMessage());
        }
        
        // Retorna dados vazios em caso de falha
        return [
            'city' => null,
            'region' => null,
            'country' => null,
            'lat' => null,
            'lng' => null
        ];
    }
} 
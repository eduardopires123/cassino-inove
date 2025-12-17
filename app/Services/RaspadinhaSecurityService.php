<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\RaspadinhaHistory;
use Carbon\Carbon;

class RaspadinhaSecurityService
{
    const SESSION_DURATION = 300; // 5 minutos - suficiente para jogar
    const MAX_SESSIONS_PER_USER = 10; // Permite múltiplas abas/sessões
    
    /**
     * Criar nova sessão de jogo segura
     */
    public function createGameSession(int $userId, int $raspadinhaId, array $gameData): array
    {
        $sessionId = $this->generateSecureSessionId();
        $sessionToken = $this->generateSessionToken($userId, $raspadinhaId, $sessionId);
        
        // Dados da sessão
        $sessionData = [
            'session_id' => $sessionId,
            'user_id' => $userId,
            'raspadinha_id' => $raspadinhaId,
            'created_at' => now(),
            'expires_at' => now()->addSeconds(self::SESSION_DURATION),
            'state' => 'created',
            'game_data' => $gameData,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'checksum' => $this->generateDataChecksum($gameData)
        ];
        
        // Limitar sessões por usuário
        $this->cleanupUserSessions($userId);
        
        // Armazenar sessão
        $sessionKey = "raspadinha_session_{$sessionId}";
        Cache::put($sessionKey, $sessionData, self::SESSION_DURATION);
        
        // Indexar por usuário
        $userSessionsKey = "raspadinha_user_sessions_{$userId}";
        $userSessions = Cache::get($userSessionsKey, []);
        $userSessions[] = $sessionId;
        Cache::put($userSessionsKey, array_slice($userSessions, -self::MAX_SESSIONS_PER_USER), self::SESSION_DURATION);
        
        return [
            'session_token' => $sessionToken,
            'session_id' => $sessionId,
            'expires_at' => $sessionData['expires_at']->timestamp
        ];
    }
    
    /**
     * Validar sessão de jogo
     */
    public function validateGameSession(string $sessionToken, int $userId): ?array
    {
        $tokenData = $this->parseSessionToken($sessionToken);
        
        if (!$tokenData || $tokenData['user_id'] !== $userId) {
            return null;
        }
        
        $sessionKey = "raspadinha_session_{$tokenData['session_id']}";
        $sessionData = Cache::get($sessionKey);
        
        if (!$sessionData) {
            return null;
        }
        
        // Verificar expiração
        if (now()->isAfter($sessionData['expires_at'])) {
            $this->destroySession($tokenData['session_id']);
            return null;
        }
        
        // Verificar integridade
        if (!$this->validateSessionIntegrity($sessionData, $tokenData)) {
            $this->destroySession($tokenData['session_id']);
            return null;
        }
        
        return $sessionData;
    }
    
    /**
     * Atualizar estado da sessão
     */
    public function updateSessionState(string $sessionId, string $newState, array $additionalData = []): bool
    {
        $sessionKey = "raspadinha_session_{$sessionId}";
        $sessionData = Cache::get($sessionKey);
        
        if (!$sessionData) {
            return false;
        }
        
        // Estados válidos: created -> playing -> claiming -> completed
        $validTransitions = [
            'created' => ['playing', 'completed'],
            'playing' => ['claiming', 'completed'],
            'claiming' => ['completed'],
            'completed' => []
        ];
        
        $currentState = $sessionData['state'];
        if (!isset($validTransitions[$currentState]) || !in_array($newState, $validTransitions[$currentState])) {
            return false;
        }
        
        // Atualizar dados da sessão
        $sessionData['state'] = $newState;
        $sessionData['updated_at'] = now();
        $sessionData = array_merge($sessionData, $additionalData);
        
        // Recalcular checksum se necessário
        if (isset($additionalData['game_data'])) {
            $sessionData['checksum'] = $this->generateDataChecksum($additionalData['game_data']);
        }
        
        Cache::put($sessionKey, $sessionData, self::SESSION_DURATION);
        
        return true;
    }
    
    /**
     * Validar que claim-prize corresponde à sessão
     */
    public function validateClaimPrize(string $sessionToken, int $historyId): bool
    {
        $sessionData = $this->validateGameSession($sessionToken, Auth::id());
        
        if (!$sessionData) {
            return false;
        }
        
        // Verificar se está no estado correto
        if ($sessionData['state'] !== 'playing') {
            return false;
        }
        
        // Verificar se o history_id corresponde à sessão
        if (!isset($sessionData['history_id']) || $sessionData['history_id'] !== $historyId) {
            return false;
        }
        
        // Verificar se o history existe e pertence ao usuário
        $history = RaspadinhaHistory::where('id', $historyId)
            ->where('user_id', $sessionData['user_id'])
            ->where('status', 'pending')
            ->first();
            
        if (!$history) {
            return false;
        }
        
        // Verificar se não é muito antigo (máximo 5 minutos entre play e claim - permite usuário pensar)
        if (now()->diffInMinutes($history->created_at) > 5) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Gerar token seguro para anti-CSRF
     */
    public function generateAntiCSRFToken(int $userId, string $action): string
    {
        $data = [
            'user_id' => $userId,
            'action' => $action,
            'timestamp' => now()->timestamp,
            'nonce' => Str::random(16)
        ];
        
        $payload = base64_encode(json_encode($data));
        $signature = hash_hmac('sha256', $payload, config('app.key'));
        
        return $payload . '.' . $signature;
    }
    
    /**
     * Validar token anti-CSRF
     */
    public function validateAntiCSRFToken(string $token, int $userId, string $action): bool
    {
        $parts = explode('.', $token);
        if (count($parts) !== 2) {
            return false;
        }
        
        [$payload, $signature] = $parts;
        
        // Verificar assinatura
        $expectedSignature = hash_hmac('sha256', $payload, config('app.key'));
        if (!hash_equals($expectedSignature, $signature)) {
            return false;
        }
        
        // Decodificar dados
        $data = json_decode(base64_decode($payload), true);
        if (!$data) {
            return false;
        }
        
        // Validar dados
        if ($data['user_id'] !== $userId || $data['action'] !== $action) {
            return false;
        }
        
        // Verificar expiração (máximo 1 hora)
        if (now()->timestamp - $data['timestamp'] > 3600) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Gerar checksum para detectar manipulação de dados
     */
    public function generateGameDataChecksum(array $gameData, int $userId, int $raspadinhaId): string
    {
        $data = [
            'user_id' => $userId,
            'raspadinha_id' => $raspadinhaId,
            'results' => $gameData['results'] ?? [],
            'prize_info' => $gameData['prize_info'] ?? [],
            'timestamp' => $gameData['timestamp'] ?? now()->timestamp
        ];
        
        ksort($data);
        $payload = json_encode($data);
        
        return hash_hmac('sha256', $payload, config('app.key'));
    }
    
    /**
     * Validar checksum dos dados do jogo
     */
    public function validateGameDataChecksum(array $gameData, string $expectedChecksum, int $userId, int $raspadinhaId): bool
    {
        $calculatedChecksum = $this->generateGameDataChecksum($gameData, $userId, $raspadinhaId);
        return hash_equals($expectedChecksum, $calculatedChecksum);
    }
    
    /**
     * Destruir sessão
     */
    public function destroySession(string $sessionId): void
    {
        $sessionKey = "raspadinha_session_{$sessionId}";
        $sessionData = Cache::get($sessionKey);
        
        if ($sessionData) {
            // Remover da lista de sessões do usuário
            $userSessionsKey = "raspadinha_user_sessions_{$sessionData['user_id']}";
            $userSessions = Cache::get($userSessionsKey, []);
            $userSessions = array_filter($userSessions, fn($id) => $id !== $sessionId);
            Cache::put($userSessionsKey, $userSessions, self::SESSION_DURATION);
        }
        
        Cache::forget($sessionKey);
    }
    
    // ================================
    // MÉTODOS PRIVADOS
    // ================================
    
    private function generateSecureSessionId(): string
    {
        return Str::uuid()->toString();
    }
    
    private function generateSessionToken(int $userId, int $raspadinhaId, string $sessionId): string
    {
        $data = [
            'user_id' => $userId,
            'raspadinha_id' => $raspadinhaId,
            'session_id' => $sessionId,
            'timestamp' => now()->timestamp
        ];
        
        $payload = base64_encode(json_encode($data));
        $signature = hash_hmac('sha256', $payload, config('app.key'));
        
        return $payload . '.' . $signature;
    }
    
    private function parseSessionToken(string $token): ?array
    {
        $parts = explode('.', $token);
        if (count($parts) !== 2) {
            return null;
        }
        
        [$payload, $signature] = $parts;
        
        // Verificar assinatura
        $expectedSignature = hash_hmac('sha256', $payload, config('app.key'));
        if (!hash_equals($expectedSignature, $signature)) {
            return null;
        }
        
        return json_decode(base64_decode($payload), true);
    }
    
    private function generateDataChecksum(array $data): string
    {
        ksort($data);
        return hash('sha256', json_encode($data));
    }
    
    private function validateSessionIntegrity(array $sessionData, array $tokenData): bool
    {
        // Verificar se dados básicos coincidem
        return $sessionData['user_id'] === $tokenData['user_id'] &&
               $sessionData['raspadinha_id'] === $tokenData['raspadinha_id'] &&
               $sessionData['session_id'] === $tokenData['session_id'];
    }
    
    private function cleanupUserSessions(int $userId): void
    {
        $userSessionsKey = "raspadinha_user_sessions_{$userId}";
        $userSessions = Cache::get($userSessionsKey, []);
        
        // Limitar número de sessões
        if (count($userSessions) >= self::MAX_SESSIONS_PER_USER) {
            // Remover sessões mais antigas
            $sessionsToRemove = array_slice($userSessions, 0, count($userSessions) - self::MAX_SESSIONS_PER_USER + 1);
            
            foreach ($sessionsToRemove as $sessionId) {
                $this->destroySession($sessionId);
            }
        }
    }
} 
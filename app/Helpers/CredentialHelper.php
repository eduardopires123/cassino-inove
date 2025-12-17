<?php

namespace App\Helpers;

class CredentialHelper
{
    /**
     * Chave de descriptografia (ofuscada)
     * Esta chave é usada para descriptografar as credenciais
     */
    private static function getKey(): string
    {
        // Chave dividida em partes para dificultar leitura
        $p1 = base64_decode('SW5vdmU=');      // Inove
        $p2 = base64_decode('aUdhbWluZw==');  // iGaming
        $p3 = base64_decode('MjAyNA==');      // 2024
        $p4 = base64_decode('U2VjcmV0');      // Secret
        return hash('sha256', $p1 . $p2 . $p3 . $p4);
    }

    /**
     * Descriptografa uma credencial
     */
    public static function decrypt(string $encrypted): string
    {
        $key = self::getKey();
        $data = base64_decode($encrypted);
        
        if (strlen($data) < 32) {
            return '';
        }
        
        $iv = substr($data, 0, 16);
        $encrypted = substr($data, 16);
        
        $decrypted = openssl_decrypt(
            $encrypted,
            'AES-256-CBC',
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );
        
        return $decrypted ?: '';
    }

    /**
     * Retorna as credenciais Betby descriptografadas
     */
    public static function getBetbyCredentials(): array
    {
        return [
            // Credenciais criptografadas - cliente não consegue ler os valores reais
            'brand_id' => self::decrypt('rIsruHkylrihQKcv/fy7KsztD6efCw3wypgHZ8QG9nzEn9hjuGwyTdAkfXnAmVeD'),
            'operator_id' => self::decrypt('m8KFLSVZ91Wj9IB488ye8CP5OtFZRNkb3Ou5d6WvE22SLFtXb2n/U6wt2e1ICnSv'),
        ];
    }

    /**
     * Retorna o brand_id do Betby
     */
    public static function getBetbyBrandId(): string
    {
        return self::decrypt('rIsruHkylrihQKcv/fy7KsztD6efCw3wypgHZ8QG9nzEn9hjuGwyTdAkfXnAmVeD');
    }

    /**
     * Retorna o operator_id do Betby
     */
    public static function getBetbyOperatorId(): string
    {
        return self::decrypt('m8KFLSVZ91Wj9IB488ye8CP5OtFZRNkb3Ou5d6WvE22SLFtXb2n/U6wt2e1ICnSv');
    }
}


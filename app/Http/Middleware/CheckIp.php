<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use App\Models\DebugLogs;

class CheckIp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $allowedIps = ['2607:5300:221:7500::', '148.113.224.117', '3.132.137.46', '190.102.41.79', '18.230.84.179', '54.94.41.25', '54.94.28.208', '54.232.253.26', '15.229.188.58', '18.229.231.24', '52.67.132.245', '54.207.198.22', '54.207.79.56', '18.228.59.148', '52.67.63.65', '54.207.178.120', '95.211.46.85'];//,'64.227.21.251'

        $forwarded = $request->header('X-Forwarded-For');
        $ip = $forwarded ? trim(explode(',', $forwarded)[0]) : $request->ip();

        if (!in_array($ip, $allowedIps)) {
            DebugLogs::create([
                'text' => sprintf("IP não autorizado (%s) na rota (%s)", $ip, $request->path()),
            ]);

            return response()->json(["message" => "IP não autorizado."], 403);
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use App\Models\Admin\Permissions;
use Closure;
use Illuminate\Http\Request;

use App\Models\DebugLogs;

class CheckPermission
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
        $route  = $request->route();

        $user   = auth()->user();
        $User   = Permissions::where('user_id', $user->id)->first();

        $allowedRoutes = [
            'admin.afiliacao.estatisticas.gerente',
            'admin.afiliacao.afiliados',
            'admin.afiliacao.afiliados.data',
            'admin.logout',
        ];

        if ($user->is_admin == 3) {
            if (!in_array($route->getName(), $allowedRoutes)) {
                return redirect()->route('admin.afiliacao.estatisticas.gerente');
            }else{
                return $next($request);
            }
        }

        $PermissoesData = json_decode($User->permission, true);

        $areas = [
            "1" => ['admin.personalizacao.banners', 'admin.personalizacao.menu', 'admin.personalizacao.css', 'admin.personalizacao.home', 'admin.footer-settings.edit'],
            "2" => ['admin.cassino.provedores', 'admin.cassino.todos', 'admin.cassino.jogos.data', 'admin.cassino.get-providers-by-status', 'admin.cassino.partidas', 'admin.cassino.partidas.data', 'admin.import.games'],
            "3" => ['admin.sports.sports_apostas', 'admin.sports.apostas.data', 'admin.sports.sports_estatisticas', 'admin.sports-banners.index', 'admin.sports.sports_configuracoes', 'admin.sports.campeonatos_ocultos.data', 'admin.sports.categorias_ocultas.data', 'admin.betby-sports.sports_apostas', 'admin.betby-sports.apostas.data', 'admin.betby-sports.apostas.stats', 'admin.betby-sports.apostas.detalhes', 'admin.betby-sports.sports_estatisticas'],
            "4" => ['admin.pagamentos.depositos', 'admin.pagamentos.depositos.data', 'admin.pagamentos.depositos.pdf', 'admin.pagamentos.saques', 'admin.pagamentos.saques.data', 'admin.pagamentos.saques.pdf', 'admin.pagamentos.saques_pendentes', 'admin.pagamentos.saques_pendentes.data', 'admin.pagamentos.saques_afiliados', 'admin.pagamentos.saques_afiliados.data', 'admin.pagamentos.saques_afiliados.pdf'],
            "5" => ['admin.usuarios.usuarios', 'admin.usuarios.usuarios.data', 'admin.usuarios.carteiras', 'admin.usuarios.carteiras.data', 'admin.usuarios.blacklist', 'admin.usuarios.blacklist.data', 'admin.usuarios.user_news', 'admin.usuarios.user_news.data', 'admin.usuarios.desbloquear', 'admin.usuarios.search'],
            "6" => ['admin.administracao.configuracoes_gerais', 'admin.administracao.banco', 'admin.administracao.gateways', 'admin.administracao.apisgames', 'admin.administracao.funcoesepermissoes', 'admin.logs.logs_edicoes', 'admin.logs.logs_edicoes.data', 'admin.logs.logs_gerais', 'admin.logs.logs_gerais.data', 'admin.platform-update.index', 'admin.clear-cache'],
            "7" => ['admin.notificacoes.notificacoes', 'admin.notificacoes.data', 'admin.notificacoes.sms.data', 'admin.whatsapp.messages', 'admin.whatsapp.messages.data', 'admin.notificacoes.notificacoes_configuracoes'],
            "8" => ['admin.cashback.index', 'admin.cashback.users', 'admin.cashback.report', 'settings.data', 'users.data'],
            "9" => ['admin.afiliacao.afiliados', 'admin.afiliacao.afiliados.data', 'admin.afiliacao.estatisticas', 'admin.afiliacao.gerentes', 'admin.afiliacao.gerentes.data', 'admin.afiliacao.estatisticas.gerente', 'admin.afiliacao.config'],
            "10" => ['admin.lucky-boxes.index', 'admin.lucky-boxes.redemptions.data', 'admin.vip-levels.index', 'admin.vip-levels.redemptions.data', 'admin.missions.index', 'admin.whatsapp.index', 'admin.coupons.index'],
        ];

        foreach ($areas as $key => $pages) {
            if (in_array($route->getName(), $pages)) {
                if (isset($PermissoesData[$key]) && $PermissoesData[$key] == 1) {
                    // Permissão concedida, continue carregando a página
                } else {
                    // Permissão negada, bloqueie o acesso
                    abort(403, 'Você não tem permissão para acessar esta página.');
                }
            }
        }

        return $next($request);
    }
}

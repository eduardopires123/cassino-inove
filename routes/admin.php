<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BannersController;
use App\Http\Controllers\Admin\AfiliacaoController;
use App\Http\Controllers\Admin\CassinoController;
use App\Http\Controllers\Admin\SportsController;
use App\Http\Controllers\Admin\PagamentosController;
use App\Http\Controllers\Admin\UsuariosController;
use App\Http\Controllers\Admin\ConfigController;
use App\Http\Controllers\Admin\PersonalizacaoController;
use App\Http\Controllers\Admin\CustomCSSController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\HomeSectionsController;
use App\Http\Controllers\ExtrasController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\Admin\VipLevelController;
use App\Http\Controllers\Admin\LuckyBoxController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\FooterSettingsController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\Admin\EmailTemplateController;
use App\Http\Controllers\Admin\CashbackController;
use App\Http\Controllers\Admin\InoveImportController;
use App\Http\Controllers\ImportUserController;
use App\Http\Controllers\Admin\RaspadinhaController;
use App\Http\Controllers\Admin\RaspadinhaItemController;

use App\Http\Middleware\CheckPermission;
use App\Http\Controllers\Admin\PDFController;

Route::prefix('admin')->group(function () {
    Route::get('/exportar/{id}', [PDFController::class, 'exportar'])->name('exportar');

    Route::post('/setPermissions', [AdminController::class, 'setPermissions'])->name('setPermissions');

    // Rotas de acesso público
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'login'])->name('admin.login.post');
    Route::get('/check-auth', [AuthController::class, 'checkAuth'])->name('admin.check-auth');


    // Rota principal - aplica middleware admin
    Route::get('/', [DashboardController::class, 'index'])->name('admin.index')->middleware([AdminMiddleware::class, CheckPermission::class]);

    // Rotas protegidas
    Route::middleware([AdminMiddleware::class, CheckPermission::class])->group(function () {
        // Dashboard
        Route::get('/dash', [DashboardController::class, 'index'])->name('admin.dash');
        Route::post('/dash/ggr-data', [DashboardController::class, 'getGgrDataAjax'])->name('admin.dash.ggr-data');
        Route::get('/dash/pix-transactions', [DashboardController::class, 'getPixTransactions'])->name('admin.dash.pix-transactions');
        Route::get('/dash/manual-transactions', [DashboardController::class, 'getManualTransactions'])->name('admin.dash.manual-transactions');
        Route::post('/dash/financial-data', [DashboardController::class, 'getFinancialDataAjax'])->name('admin.dash.financial-data');
        Route::post('/dash/casino-data', [DashboardController::class, 'getCasinoDataAjax'])->name('admin.dash.casino-data');
        Route::post('/dash/sports-data', [DashboardController::class, 'getSportsDataAjax'])->name('admin.dash.sports-data');
        Route::get('/dash/normal-withdrawals', [DashboardController::class, 'getNormalWithdrawals'])->name('admin.dash.normal-withdrawals');
        Route::get('/dash/affiliate-withdrawals', [DashboardController::class, 'getAffiliateWithdrawals'])->name('admin.dash.affiliate-withdrawals');

        // Logout
        Route::get('/logout', [AuthController::class, 'logout'])->name('admin.logout');

        // Perfil do administrador
        Route::get('/profile', [AuthController::class, 'profile'])->name('admin.profile');
        Route::put('/profile', [AuthController::class, 'updateProfile'])->name('admin.profile.update');
        Route::post('/password', [AuthController::class, 'updatePassword'])->name('admin.password.update');

        // Rota para limpar o cache
        Route::post('/clear-cache', [BannersController::class, 'clearCache'])->name('admin.clear-cache');

        // Coupon management routes
        Route::get('/coupons', [CouponController::class, 'index'])->name('admin.coupons.index');
        Route::get('/coupons/create', [CouponController::class, 'create'])->name('admin.coupons.create');
        Route::post('/coupons', [CouponController::class, 'store'])->name('admin.coupons.store');
        Route::get('/coupons/{coupon}/edit', [CouponController::class, 'edit'])->name('admin.coupons.edit');
        Route::put('/coupons/{coupon}', [CouponController::class, 'update'])->name('admin.coupons.update');
        Route::delete('/coupons/{coupon}', [CouponController::class, 'destroy'])->name('admin.coupons.destroy');
        Route::get('/coupons/{coupon}/redemptions', [CouponController::class, 'redemptions'])->name('admin.coupons.redemptions');

        // Níveis VIP
        Route::prefix('vip-levels')->group(function () {
            Route::get('/reset', [VipLevelController::class, 'reset'])->name('admin.vip-levels.reset');

            Route::get('/', [VipLevelController::class, 'index'])->name('admin.vip-levels.index');
            Route::get('/create', [VipLevelController::class, 'create'])->name('admin.vip-levels.create');
            Route::post('/', [VipLevelController::class, 'store'])->name('admin.vip-levels.store');
            Route::get('/{vipLevel}/edit', [VipLevelController::class, 'edit'])->name('admin.vip-levels.edit');
            Route::put('/{vipLevel}', [VipLevelController::class, 'update'])->name('admin.vip-levels.update');
            Route::delete('/{vipLevel}', [VipLevelController::class, 'destroy'])->name('admin.vip-levels.destroy');
            Route::post('/update-order', [VipLevelController::class, 'updateOrder'])->name('admin.vip-levels.update-order');
            Route::get('/{vipLevel}/redemptions', [VipLevelController::class, 'redemptions'])->name('admin.vip-levels.redemptions');
            Route::get('/{vipLevel}/redemptions/data', [VipLevelController::class, 'redemptionsData'])->name('admin.vip-levels.redemptions.data');
        });

        // Templates de Email
        Route::prefix('email-templates')->group(function () {
            Route::get('/', [EmailTemplateController::class, 'index'])->name('admin.email-templates.index');
            Route::get('/create', [EmailTemplateController::class, 'create'])->name('admin.email-templates.create');
            Route::post('/', [EmailTemplateController::class, 'store'])->name('admin.email-templates.store');
            Route::get('/{emailTemplate}', [EmailTemplateController::class, 'show'])->name('admin.email-templates.show');
            Route::get('/{emailTemplate}/edit', [EmailTemplateController::class, 'edit'])->name('admin.email-templates.edit');
            Route::put('/{emailTemplate}', [EmailTemplateController::class, 'update'])->name('admin.email-templates.update');
            Route::delete('/{emailTemplate}', [EmailTemplateController::class, 'destroy'])->name('admin.email-templates.destroy');

            // Rotas adicionais para gerenciamento de templates
            Route::get('/{emailTemplate}/preview', [EmailTemplateController::class, 'preview'])->name('admin.email-templates.preview');
            Route::post('/{emailTemplate}/send-test', [EmailTemplateController::class, 'sendTest'])->name('admin.email-templates.send-test');
            Route::post('/sync-with-brevo', [EmailTemplateController::class, 'syncWithBrevo'])->name('admin.email-templates.sync-with-brevo');
            Route::post('/run-migration', [EmailTemplateController::class, 'runMigration'])->name('admin.email-templates.run-migration');
        });

        // Configurações do Rodapé
        Route::prefix('footer-settings')->group(function () {
            Route::get('/', [FooterSettingsController::class, 'edit'])->name('admin.footer-settings.edit');
            Route::put('/update', [FooterSettingsController::class, 'update'])->name('admin.footer-settings.update');
            Route::post('/update-field', [FooterSettingsController::class, 'updateField'])->name('admin.footer-settings.update-field');
        });

        // Personalização
        Route::prefix('personalizacao')->group(function () {
            Route::get('banners', [BannersController::class, 'index'])->name('banners.index');
            Route::post('banners', [BannersController::class, 'store'])->name('banners.store');
            Route::put('banners/{id}', [BannersController::class, 'update'])->name('banners.update');
            Route::delete('banners/{id}', [BannersController::class, 'destroy'])->name('banners.destroy');
            Route::post('banners/update-order', [BannersController::class, 'updateOrder'])->name('banners.update-order');
            Route::post('banners/{id}/toggle-active', [BannersController::class, 'toggleActive'])->name('banners.toggle-active');
            Route::get('mini_banners', [PersonalizacaoController::class, 'miniBanners'])->name('admin.personalizacao.mini_banners');
            Route::get('banners', [PersonalizacaoController::class, 'banners'])->name('admin.personalizacao.banners');
            Route::get('icones', [PersonalizacaoController::class, 'icones'])->name('admin.personalizacao.icones');
            Route::get('icones/{id}', [App\Http\Controllers\Admin\IconsController::class, 'show'])->name('admin.personalizacao.icones.show');
            Route::post('icones', [App\Http\Controllers\Admin\IconsController::class, 'store'])->name('admin.personalizacao.icones.store');
            Route::put('icones/{id}', [App\Http\Controllers\Admin\IconsController::class, 'update'])->name('admin.personalizacao.icones.update');
            Route::delete('icones/{id}', [App\Http\Controllers\Admin\IconsController::class, 'destroy'])->name('admin.personalizacao.icones.destroy');
            Route::post('icones/update-order', [App\Http\Controllers\Admin\IconsController::class, 'updateOrder'])->name('admin.personalizacao.icones.update-order');
            Route::post('icones/toggle-active', [App\Http\Controllers\Admin\IconsController::class, 'toggleActive'])->name('admin.personalizacao.icones.toggle-active');
            Route::get('menu', [PersonalizacaoController::class, 'menu'])->name('admin.personalizacao.menu');
            Route::get('css', [PersonalizacaoController::class, 'css'])->name('admin.personalizacao.css');
            Route::get('home', [PersonalizacaoController::class, 'home'])->name('admin.personalizacao.home');
            Route::get('sections-order', [HomeSectionsController::class, 'index'])->name('admin.personalizacao.sections-order');
            Route::post('sections-order/update', [HomeSectionsController::class, 'updateOrder'])->name('admin.personalizacao.sections-order.update');
            Route::post('sections-order/toggle', [HomeSectionsController::class, 'toggleSection'])->name('admin.personalizacao.sections-order.toggle');
            Route::post('sections-order/reset', [HomeSectionsController::class, 'resetOrder'])->name('admin.personalizacao.sections-order.reset');
            Route::post('update-variable', [CustomCSSController::class, 'updateVariable'])->name('admin.personalizacao.update-variable');
            Route::post('update-custom', [CustomCSSController::class, 'updateCustom'])->name('admin.personalizacao.update-custom');
            Route::post('update-all', [CustomCSSController::class, 'updateAll'])->name('admin.personalizacao.update-all');
            Route::post('update-theme', [CustomCSSController::class, 'updateTheme'])->name('admin.personalizacao.update-theme');
        });

        // Afiliação
        Route::prefix('afiliacao')->group(function () {
            Route::get('afiliados-afiliados', [AfiliacaoController::class, 'afiliadosAfiliados'])->name('admin.afiliacao.afiliados');
            Route::get('afiliados_data', [AfiliacaoController::class, 'afiliadosData'])->name('admin.afiliacao.afiliados.data');
            Route::get('dados_afiliados', [AfiliacaoController::class, 'dadosAfiliados'])->name('admin.afiliacao.dados_afiliados');
            Route::get('gerentes-afiliados', [AfiliacaoController::class, 'gerentesAfiliados'])->name('admin.afiliacao.gerentes');
            Route::get('gerentes_data', [AfiliacaoController::class, 'gerentesData'])->name('admin.afiliacao.gerentes.data');
            Route::get('config-afiliados', [AfiliacaoController::class, 'configAfiliados'])->name('admin.afiliacao.config');
            Route::post('config-afiliados', [AfiliacaoController::class, 'salvarConfigAfiliados'])->name('admin.afiliacao.config.salvar');
            Route::get('estatisticas-afiliados', [AfiliacaoController::class, 'estatisticasAfiliados'])->name('admin.afiliacao.estatisticas');
            Route::get('estatisticas-gerente', [AfiliacaoController::class, 'estatisticasGerente'])->name('admin.afiliacao.estatisticas.gerente');
            Route::post('pagar-afiliado', [AfiliacaoController::class, 'pagarAfiliado'])->name('admin.afiliacao.pagar');
            Route::post('config-afiliados/salvar', [AfiliacaoController::class, 'salvarConfigAfiliados'])->name('admin.config.afiliados.salvar');
        });

        // Cassino
        Route::prefix('cassino')->group(function () {
            Route::get('jogos-provedores', [CassinoController::class, 'jogosProvedores'])->name('admin.cassino.provedores');
            Route::get('jogos-categorias', [CassinoController::class, 'jogosCategorias'])->name('admin.cassino.categorias');
            Route::get('jogos-todos', [CassinoController::class, 'jogosTodos'])->name('admin.cassino.todos');
            Route::get('jogos-data', [CassinoController::class, 'jogosTodosData'])->name('admin.cassino.jogos.data');
            Route::get('jogos-partidas', [CassinoController::class, 'jogosPartidas'])->name('admin.cassino.partidas');
            Route::get('jogos-partidas-data', [CassinoController::class, 'jogosPartidasData'])->name('admin.cassino.partidas.data');
            Route::post('atualizar-provider', [CassinoController::class, 'atualizarProvider'])->name('admin.cassino.provider.atualizar');
            Route::post('atualizar-jogo', [CassinoController::class, 'updateGameField'])->name('admin.cassino.jogo.atualizar');
            Route::post('atualizar-slug', [CassinoController::class, 'updateSlugField'])->name('admin.cassino.slug.atualizar');
            Route::post('/update-provider-distribution', [CassinoController::class, 'updateProviderDistribution'])->name('admin.cassino.update-provider-distribution');
            Route::post('/update-provider-image', [CassinoController::class, 'updateProviderImage'])->name('admin.cassino.update-provider-image');
            Route::post('/get-provider-image', [CassinoController::class, 'getProviderImage'])->name('admin.cassino.get-provider-image');
            Route::post('/update-game-image', [CassinoController::class, 'updateGameImage'])->name('admin.cassino.update-game-image');
            Route::post('/get-game-image', [CassinoController::class, 'getGameImage'])->name('admin.cassino.get-game-image');
            Route::post('/update-field', [CassinoController::class, 'updateGameField'])->name('admin.games.update-field');
            Route::post('/update-game-details', [CassinoController::class, 'updateGameDetails'])->name('admin.cassino.update-game-details');
            Route::post('/get-game-details', [CassinoController::class, 'getGameDetails'])->name('admin.cassino.get-game-details');
            Route::post('/update-home-section-settings', [CassinoController::class, 'updateHomeSectionSettings'])->name('admin.cassino.update-home-section-settings');
            Route::post('/reset-custom-titles', [CassinoController::class, 'resetCustomTitles'])->name('admin.cassino.reset-custom-titles');
            Route::get('/get-providers', [CassinoController::class, 'getProviders'])->name('admin.cassino.get-providers');
            Route::get('/get-providers-by-status', [CassinoController::class, 'getProvidersByStatus'])->name('admin.cassino.get-providers-by-status');
            Route::get('/get-providers-by-distribution', [CassinoController::class, 'getProvidersByDistribution'])->name('admin.cassino.get-providers-by-distribution');
            Route::get('/get-providers-by-wallet', [CassinoController::class, 'getProvidersByWallet'])->name('admin.cassino.get-providers-by-wallet');
            Route::get('/check-wallets-availability', [CassinoController::class, 'checkWalletsAvailability'])->name('admin.cassino.check-wallets-availability');
            Route::post('/get-provider-use-original', [CassinoController::class, 'getProviderUseOriginal'])->name('admin.cassino.get-provider-use-original');
            Route::post('/atualizar-provider-use-original', [CassinoController::class, 'atualizarProviderUseOriginal'])->name('admin.cassino.atualizar-provider-use-original');
            Route::get('/rpt-gh-3', [App\Http\Controllers\Admin\GameHistoryTable::class, 'index4']);
            
            // Campos Personalizados
            Route::get('/custom-fields', [CassinoController::class, 'getCustomFields'])->name('admin.cassino.custom-fields');
            Route::post('/custom-fields', [CassinoController::class, 'createCustomField'])->name('admin.cassino.custom-fields.create');
            Route::put('/custom-fields/{id}', [CassinoController::class, 'updateCustomField'])->name('admin.cassino.custom-fields.update');
            Route::delete('/custom-fields/{id}', [CassinoController::class, 'deleteCustomField'])->name('admin.cassino.custom-fields.delete');
            Route::get('/custom-fields/{id}/games', [CassinoController::class, 'getCustomFieldGames'])->name('admin.cassino.custom-fields.games');
            Route::post('/custom-fields/{id}/games', [CassinoController::class, 'addGamesToCustomField'])->name('admin.cassino.custom-fields.games.add');
            Route::delete('/custom-fields/{fieldId}/games/{gameId}', [CassinoController::class, 'removeGameFromCustomField'])->name('admin.cassino.custom-fields.games.remove');
            Route::post('/custom-fields/{id}/games/order', [CassinoController::class, 'updateCustomFieldGamesOrder'])->name('admin.cassino.custom-fields.games.order');
            Route::get('/games-for-selection', [CassinoController::class, 'getGamesForSelection'])->name('admin.cassino.games-for-selection');
        });

        // Sports (Digitain)
        Route::prefix('sports')->group(function () {
            Route::get('sports_apostas', [SportsController::class, 'sportsApostas'])->name('admin.sports.sports_apostas');
            Route::get('sports_apostas_data', [SportsController::class, 'sportsApostasData'])->name('admin.sports.apostas.data');
            Route::get('sports_apostas_stats', [SportsController::class, 'sportsApostasStats'])->name('admin.sports.apostas.stats');
            Route::get('sports_estatisticas', [SportsController::class, 'sportsEstatisticas'])->name('admin.sports.sports_estatisticas');
            Route::get('sports_configuracoes', [SportsController::class, 'sportsConfiguracoes'])->name('admin.sports.sports_configuracoes');

        });

        // Betby Sports
Route::prefix('betby-sports')->group(function () {
    Route::get('sports_apostas', [App\Http\Controllers\Admin\BetbySportsController::class, 'sportsApostas'])->name('admin.betby-sports.sports_apostas');
    Route::get('sports_apostas_data', [App\Http\Controllers\Admin\BetbySportsController::class, 'sportsApostasData'])->name('admin.betby-sports.apostas.data');
    Route::get('sports_apostas_stats', [App\Http\Controllers\Admin\BetbySportsController::class, 'sportsApostasStats'])->name('admin.betby-sports.apostas.stats');
    Route::get('apostas_detalhes', [App\Http\Controllers\Admin\BetbySportsController::class, 'getApostaDetalhes'])->name('admin.betby-sports.apostas.detalhes');
    Route::get('sports_estatisticas', [App\Http\Controllers\Admin\BetbySportsController::class, 'sportsEstatisticas'])->name('admin.betby-sports.sports_estatisticas');
    Route::get('sports_estatisticas_table', [App\Http\Controllers\Admin\BetbySportsController::class, 'sportsEstatisticasTable'])->name('admin.betby-sports.sports_estatisticas.table');
});

        // Pagamentos
        Route::prefix('pagamentos')->group(function () {
            Route::get('depositos', [PagamentosController::class, 'depositos'])->name('admin.pagamentos.depositos');
            Route::get('depositos_data', [PagamentosController::class, 'depositosData'])->name('admin.pagamentos.depositos.data');
            Route::get('depositos_pdf', [PagamentosController::class, 'depositosPdf'])->name('admin.pagamentos.depositos.pdf');
            Route::get('saques', [PagamentosController::class, 'saques'])->name('admin.pagamentos.saques');
            Route::get('saques_data', [PagamentosController::class, 'saquesData'])->name('admin.pagamentos.saques.data');
            Route::get('saques_pdf', [PagamentosController::class, 'saquesPdf'])->name('admin.pagamentos.saques.pdf');
            Route::get('saques_pendentes', [PagamentosController::class, 'saquesPendentes'])->name('admin.pagamentos.saques_pendentes');
            Route::get('saques_pendentes_data', [PagamentosController::class, 'saquesPendentesData'])->name('admin.pagamentos.saques_pendentes.data');
            Route::get('saques_afiliados', [PagamentosController::class, 'saquesAfiliados'])->name('admin.pagamentos.saques_afiliados');
            Route::get('saques_afiliados_data', [PagamentosController::class, 'saquesAfiliadosData'])->name('admin.pagamentos.saques_afiliados.data');
            Route::get('saques_afiliados_pdf', [PagamentosController::class, 'saquesAfiliadosPdf'])->name('admin.pagamentos.saques_afiliados.pdf');
            Route::get('historico', [PagamentosController::class, 'historicoPagamentos'])->name('admin.pagamentos.historico');
            Route::get('historico_pagamentos', [PagamentosController::class, 'historicoPagamentos'])->name('admin.pagamentos.historico_pagamentos');
            Route::post('update-saque-status', [PagamentosController::class, 'updateSaqueStatus'])->name('admin.pagamentos.update-saque-status');
        });

        // Usuários
        Route::prefix('usuarios')->group(function () {
            Route::get('usuarios', [UsuariosController::class, 'usuarios'])->name('admin.usuarios.usuarios');
            Route::get('usuarios_data', [UsuariosController::class, 'usuariosData'])->name('admin.usuarios.usuarios.data');
            Route::get('carteiras', [UsuariosController::class, 'carteiras'])->name('admin.usuarios.carteiras');
            Route::get('carteiras_data', [UsuariosController::class, 'carteirasData'])->name('admin.usuarios.carteiras.data');
            Route::get('blacklist', [UsuariosController::class, 'blacklist'])->name('admin.usuarios.blacklist');
            Route::get('blacklist_data', [UsuariosController::class, 'blacklistData'])->name('admin.usuarios.blacklist.data');
            Route::get('user_news', [UsuariosController::class, 'userNews'])->name('admin.usuarios.user_news');
            Route::get('user_news_data', [UsuariosController::class, 'userNewsData'])->name('admin.usuarios.user_news.data');
            Route::post('desbloquear/{id}', [UsuariosController::class, 'desbloquearUsuario'])->name('admin.usuarios.desbloquear');
            Route::get('search', [UsuariosController::class, 'searchUsers'])->name('admin.usuarios.search');
        });

        // Administração
        Route::prefix('administracao')->group(function () {
            Route::get('configuracoes_gerais', [ConfigController::class, 'configuracoes'])->name('admin.administracao.configuracoes_gerais');
            Route::get('banco', [ConfigController::class, 'banco'])->name('admin.administracao.banco');
            Route::get('gateways', [ConfigController::class, 'gateways'])->name('admin.administracao.gateways');
            Route::get('apisgames', [ConfigController::class, 'apisgames'])->name('admin.administracao.apisgames');
            Route::get('funcoesepermissoes', [ConfigController::class, 'funcoesPermissoes'])->name('admin.administracao.funcoesepermissoes');
        });

        // Config
        Route::prefix('config')->group(function () {
            Route::get('configuracoes-gerais', [ConfigController::class, 'configuracoes'])->name('admin.config.gerais');
            Route::post('configuracoes-gerais', [ConfigController::class, 'salvarConfiguracoes'])->name('admin.config.gerais.salvar');
            Route::get('gateways', [ConfigController::class, 'gateways'])->name('admin.config.gateways');
            Route::post('atualizar-gateway', [ConfigController::class, 'atualizarGateway'])->name('admin.config.gateway.atualizar');
            Route::get('apisgames', [ConfigController::class, 'apisgames'])->name('admin.config.apisgames');
            Route::post('atualizar-api', [ConfigController::class, 'atualizarApi'])->name('admin.config.api.atualizar');
            Route::get('banco', [ConfigController::class, 'banco'])->name('admin.config.banco');
            Route::post('realizar-saque', [ConfigController::class, 'realizarSaque'])->name('admin.config.banco.saque');
            Route::get('funcoes-permissoes', [ConfigController::class, 'funcoesPermissoes'])->name('admin.config.permissoes');
            Route::post('salvar-permissoes', [ConfigController::class, 'salvarPermissoes'])->name('admin.config.permissoes.salvar');

            // Rotas para o novo sistema de permissões
            Route::get('load-permissions/{id}', [ConfigController::class, 'loadPermissions'])->name('admin.config.load-permissions');
            Route::post('save-permissions', [ConfigController::class, 'savePermissions'])->name('admin.config.save-permissions');
            Route::post('delete-permissions/{id}', [ConfigController::class, 'deletePermissions'])->name('admin.config.delete-permissions');
        });

        // Menu Management Routes


        Route::prefix('menu')->group(function () {
            Route::get('/load-items/{id}', [MenuController::class, 'loadItems'])->name('admin.menu.load-items');
            Route::post('/update-category', [MenuController::class, 'updateCategory'])->name('admin.menu.update-category');
            Route::post('/update-item', [MenuController::class, 'updateItem'])->name('admin.menu.update-item');
            Route::post('/add-item', [MenuController::class, 'addItem'])->name('admin.menu.add-item');
            Route::post('/delete-item', [MenuController::class, 'deleteItem'])->name('admin.menu.delete-item');
            Route::post('/add-category', [MenuController::class, 'addCategory'])->name('admin.menu.add-category');
            Route::post('/delete-category', [MenuController::class, 'deleteCategory'])->name('admin.menu.delete-category');
        });


        // Lucky Box's
        Route::prefix('lucky-boxes')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\LuckyBoxController::class, 'index'])->name('admin.lucky-boxes.index');
            Route::get('/create', [App\Http\Controllers\Admin\LuckyBoxController::class, 'create'])->name('admin.lucky-boxes.create');
            Route::post('/', [App\Http\Controllers\Admin\LuckyBoxController::class, 'store'])->name('admin.lucky-boxes.store');
            Route::get('/{id}/edit', [App\Http\Controllers\Admin\LuckyBoxController::class, 'edit'])->name('admin.lucky-boxes.edit');
            Route::put('/{id}', [App\Http\Controllers\Admin\LuckyBoxController::class, 'update'])->name('admin.lucky-boxes.update');
            Route::delete('/{id}', [App\Http\Controllers\Admin\LuckyBoxController::class, 'destroy'])->name('admin.lucky-boxes.destroy');
            Route::post('/{id}/toggle-active', [App\Http\Controllers\Admin\LuckyBoxController::class, 'toggleActive'])->name('admin.lucky-boxes.toggle-active');
            Route::post('/update-order', [App\Http\Controllers\Admin\LuckyBoxController::class, 'updateOrder'])->name('admin.lucky-boxes.update-order');
            Route::get('/{id}/redemptions', [App\Http\Controllers\Admin\LuckyBoxController::class, 'redemptions'])->name('admin.lucky-boxes.redemptions');
            Route::get('/{id}/redemptions-data', [App\Http\Controllers\Admin\LuckyBoxController::class, 'redemptionsData'])->name('admin.lucky-boxes.redemptions.data');
        });



        // Ajax para carregar modais
        Route::get('modal/agent/{id}', [AdminController::class, 'modalAgent'])->name('admin.modal.agent');

        // Notificações globais
        Route::post('/notifications/global', [ExtrasController::class, 'createGlobalNotification'])->name('admin.notifications.global');

        // Blog admin
        Route::prefix('blog')->group(function () {
            Route::get('/', [BlogController::class, 'adminIndex'])->name('admin.blog');
            Route::get('posts', [BlogController::class, 'adminPosts'])->name('admin.blog.posts');
            Route::get('post/novo', [BlogController::class, 'create'])->name('admin.blog.create');
            Route::post('post/novo', [BlogController::class, 'store'])->name('admin.blog.store');
            Route::get('post/{id}/editar', [BlogController::class, 'edit'])->name('admin.blog.edit');
            Route::put('post/{id}', [BlogController::class, 'update'])->name('admin.blog.update');
            Route::delete('post/{id}', [BlogController::class, 'destroy'])->name('admin.blog.destroy');

            // Categorias
            Route::get('categorias', [BlogController::class, 'adminCategories'])->name('admin.blog.categories');
            Route::post('categoria', [BlogController::class, 'storeCategory'])->name('admin.blog.category.store');
            Route::put('categoria/{id}', [BlogController::class, 'updateCategory'])->name('admin.blog.category.update');
            Route::delete('categoria/{id}', [BlogController::class, 'destroyCategory'])->name('admin.blog.category.destroy');

            // Comentários
            Route::get('comentarios', [BlogController::class, 'adminComments'])->name('admin.blog.comments');
            Route::put('comentario/{id}', [BlogController::class, 'updateComment'])->name('admin.blog.comment.update');
            Route::delete('comentario/{id}', [BlogController::class, 'destroyComment'])->name('admin.blog.comment.destroy');
        });

        // Gerenciamento de banners
            Route::post('/banner/toggle-status', [BannersController::class, 'toggleActive'])->name('admin.banner.toggle');
    Route::post('/banner/delete', [BannersController::class, 'destroy'])->name('admin.banner.delete');
    Route::post('/banner/update-order', [BannersController::class, 'updateOrder'])->name('admin.banner.order');
    Route::post('/banner/update-mobile', [BannersController::class, 'updateMobile'])->name('admin.banner.mobile');
    Route::post('/banner/update-image', [BannersController::class, 'updateImage'])->name('admin.banner.update-image');
    Route::get('/banner/get-orders', [BannersController::class, 'getOrders'])->name('admin.banner.get-orders');

        // Rotas de Cashback
        Route::prefix('cashback')->name('admin.cashback.')->group(function () {
            Route::get('/', [CashbackController::class, 'index'])->name('index');
            Route::get('/create', [CashbackController::class, 'create'])->name('create');
            Route::post('/', [CashbackController::class, 'store'])->name('store');
            Route::get('/{id}/json', [CashbackController::class, 'getSettingJson'])->name('json');
            Route::get('/{id}/edit', [CashbackController::class, 'edit'])->name('edit');
            Route::put('/{id}', [CashbackController::class, 'update'])->name('update');
            Route::get('/users', [CashbackController::class, 'userCashbacks'])->name('users');
            Route::post('/add/manual', [CashbackController::class, 'addManualCashback'])->name('add.manual');
            Route::post('/{id}/apply', [CashbackController::class, 'applyCashback'])->name('apply');
            Route::post('/process', [CashbackController::class, 'processAll'])->name('process');
            Route::post('/process-scheduled', [CashbackController::class, 'processScheduled'])->name('process.scheduled');
            Route::post('/send-notifications', [CashbackController::class, 'sendNotifications'])->name('send.notifications');
            Route::get('/report', [CashbackController::class, 'report'])->name('report');
            Route::get('/user/{userId}/losses', [CashbackController::class, 'userLossDetails'])->name('user.losses');
            Route::post('/user/{userId}/process', [CashbackController::class, 'processForUser'])->name('user.process');
            Route::delete('/{id}', [CashbackController::class, 'destroy'])->name('destroy');
            Route::get('/settings/data', [CashbackController::class, 'settingsData'])->name('settings.data');
            Route::get('/users/data', [CashbackController::class, 'userCashbacksData'])->name('users.data');
            Route::post('/user/specific', [CashbackController::class, 'storeUserSpecific'])->name('user.specific');
            Route::delete('/{id}/delete', [CashbackController::class, 'deleteCashback'])->name('delete');
            Route::get('/user/losses/ajax', [CashbackController::class, 'userLossDetailsAjax'])->name('user.losses.ajax');
        });


        // Rotas admin para roleta
                Route::prefix('roulette')->group(function () {
        Route::get('/config', [\App\Http\Controllers\Admin\RouletteController::class, 'config'])->name('admin.roulette.config');
        Route::get('/resgates', [\App\Http\Controllers\Admin\RouletteController::class, 'resgates'])->name('admin.roulette.resgates');
        Route::get('/export', [\App\Http\Controllers\Admin\RouletteController::class, 'exportResgates'])->name('admin.roulette.export');
        Route::post('/settings', [\App\Http\Controllers\Admin\RouletteController::class, 'updateSettings'])->name('admin.roulette.settings');
        Route::post('/create', [\App\Http\Controllers\Admin\RouletteController::class, 'createItem'])->name('admin.roulette.create');
        Route::post('/update', [\App\Http\Controllers\Admin\RouletteController::class, 'updateItem'])->name('admin.roulette.update');
        Route::post('/delete', [\App\Http\Controllers\Admin\RouletteController::class, 'deleteItem'])->name('admin.roulette.delete');
        Route::post('/toggle-status', [\App\Http\Controllers\Admin\RouletteController::class, 'toggleItemStatus'])->name('admin.roulette.toggle-status');
    });

        // Rotas para gerenciamento de campeonatos ocultos
        Route::get('/Admin/SportsCampeonatosOcultos', [SportsController::class, 'campeonatosOcultos'])->name('admin.sports.campeonatos_ocultos');
        Route::get('/Admin/ListarCampeonatos', [SportsController::class, 'listarCampeonatos'])->name('admin.sports.listar_campeonatos');
        Route::get('/Admin/ObterCampeonatosOcultos', [SportsController::class, 'obterCampeonatosOcultos'])->name('admin.sports.obter_campeonatos_ocultos');
        Route::get('/Admin/CampeonatosOcultosData', [SportsController::class, 'campeonatosOcultosData'])->name('admin.sports.campeonatos_ocultos.data');
        Route::post('/Admin/SalvarCampeonatoOculto', [SportsController::class, 'salvarCampeonatoOculto'])->name('admin.sports.salvar_campeonato_oculto');
        Route::post('/Admin/MudarStatusCampeonato', [SportsController::class, 'mudarStatusCampeonato'])->name('admin.sports.mudar_status_campeonato');
        Route::post('/Admin/RemoverCampeonatoOculto', [SportsController::class, 'removerCampeonatoOculto'])->name('admin.sports.remover_campeonato_oculto');
        Route::post('/Admin/AlterarStatusEmMassa', [SportsController::class, 'alterarStatusEmMassa'])->name('admin.sports.alterar_status_em_massa');

        // Rotas para gerenciamento de categorias de esportes ocultas
        Route::get('/Admin/CarregarTitulosEsportes', [SportsController::class, 'carregarTitulosEsportes'])->name('admin.sports.carregar_titulos_esportes');
        Route::post('/Admin/SalvarCategoriaOculta', [SportsController::class, 'salvarCategoriaOculta'])->name('admin.sports.salvar_categoria_oculta');
        Route::get('/Admin/ListarCategoriasOcultas', [SportsController::class, 'listarCategoriasOcultas'])->name('admin.sports.listar_categorias_ocultas');
        Route::post('/Admin/RemoverCategoriaOculta', [SportsController::class, 'removerCategoriaOculta'])->name('admin.sports.remover_categoria_oculta');
        Route::post('/Admin/MudarStatusCategoria', [SportsController::class, 'mudarStatusCategoria'])->name('admin.sports.mudar_status_categoria');
        Route::get('/Admin/CategoriasOcultasData', [SportsController::class, 'categoriasOcultasData'])->name('admin.sports.categorias_ocultas.data');

        // Rotas para Inove Gaming Import
        Route::prefix('import-games/inove')->group(function () {
            Route::get('/', [InoveImportController::class, 'index'])->name('admin.inove.index');
            Route::get('/games', [InoveImportController::class, 'getGames'])->name('admin.inove.games');
            Route::post('/import', [InoveImportController::class, 'importGames'])->name('admin.inove.import');
            Route::get('/existing', [InoveImportController::class, 'getExistingGames'])->name('admin.inove.existing-games');
            Route::get('/providers', [InoveImportController::class, 'getProviders'])->name('admin.inove.providers');
            Route::post('/import-providers', [InoveImportController::class, 'importProviders'])->name('admin.inove.import-providers');
            Route::post('/update-providers', [InoveImportController::class, 'updateProviders'])->name('admin.inove.update-providers');
            Route::get('/proxy-image', [InoveImportController::class, 'proxyImage'])->name('admin.inove.proxy-image');
        });

        // Rotas para Importação de Usuários
        Route::prefix('import-users')->group(function () {
            Route::get('/', [ImportUserController::class, 'index'])->name('admin.import-users.index');
            Route::post('/upload', [ImportUserController::class, 'upload'])->name('admin.import-users.upload');
            Route::post('/process-batch', [ImportUserController::class, 'processBatch'])->name('admin.import-users.process-batch');
            Route::post('/get-results', [ImportUserController::class, 'getResults'])->name('admin.import-users.get-results');
            Route::get('/template', [ImportUserController::class, 'template'])->name('admin.import-users.template');
    });

        // Rotas da Raspadinha
        Route::prefix('raspadinha')->name('admin.raspadinha.')->group(function () {
            Route::get('/', [RaspadinhaController::class, 'index'])->name('index');
            Route::get('/create', [RaspadinhaController::class, 'create'])->name('create');
            Route::post('/', [RaspadinhaController::class, 'store'])->name('store');
            Route::get('/{raspadinha}', [RaspadinhaController::class, 'show'])->name('show');
            Route::get('/{raspadinha}/edit', [RaspadinhaController::class, 'edit'])->name('edit');
            Route::put('/{raspadinha}', [RaspadinhaController::class, 'update'])->name('update');
            Route::delete('/{raspadinha}', [RaspadinhaController::class, 'destroy'])->name('destroy');
            Route::patch('/{raspadinha}/toggle-status', [RaspadinhaController::class, 'toggleStatus'])->name('toggle-status');
            Route::post('/{raspadinha}/update-positions', [RaspadinhaController::class, 'updatePositions'])->name('update-positions');
                    Route::get('/historico/jogadas', [RaspadinhaController::class, 'history'])->name('history');
        Route::get('/historico/jogadas/data', [RaspadinhaController::class, 'historyData'])->name('history.data');
        Route::get('/relatorio/estatisticas', [RaspadinhaController::class, 'statistics'])->name('statistics');
        });

        // Rotas dos Itens da Raspadinha
        Route::prefix('raspadinha/{raspadinha}/items')->name('admin.raspadinha-item.')->group(function () {
            Route::get('/', [RaspadinhaItemController::class, 'index'])->name('index');
            Route::get('/create', [RaspadinhaItemController::class, 'create'])->name('create');
            Route::post('/', [RaspadinhaItemController::class, 'store'])->name('store');
            Route::get('/{item}/edit', [RaspadinhaItemController::class, 'edit'])->name('edit');
            Route::put('/{item}', [RaspadinhaItemController::class, 'update'])->name('update');
            Route::delete('/{item}', [RaspadinhaItemController::class, 'destroy'])->name('destroy');
            Route::patch('/{item}/toggle-status', [RaspadinhaItemController::class, 'toggleStatus'])->name('toggle-status');
            Route::get('/check-probabilities', [RaspadinhaItemController::class, 'checkProbabilities'])->name('check-probabilities');
        });
    });
});

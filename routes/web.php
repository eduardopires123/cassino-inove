<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CassinoController;
use App\Http\Controllers\EsportesController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\GamesApiController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\VipController;
use App\Http\Controllers\GamesController;
use Illuminate\Http\Request;
use App\Http\Controllers\UserEditController;
use App\Http\Controllers\ExtrasController;
use App\Http\Controllers\BannerController;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\LuckyBoxController;
use App\Http\Controllers\GatewayController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\SportsWiseController;
use App\Http\Controllers\CouponRedemptionController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\CodeVerificationController;
use App\Http\Controllers\MinigamesController;
use App\Http\Controllers\ModoSurpresaController;
use App\Http\Controllers\RouletteController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PartialsController;
use App\Http\Controllers\Admin\SportsController;
use App\Http\Controllers\RaspadinhaController;
use App\Http\Controllers\BetbySportsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('tabControl', function() { return view('tabControl'); })->name('tabControl');

Route::post('/adm-cn-spt-b7k3', [SportsController::class, 'CancelSportBet']);

// Rota para fechar jogo - protegida
Route::post('/gm-exit-h4n9', [GamesController::class, 'outgame']);
Route::get('/gm-exit-h4n9', [GamesController::class, 'outgame']);


// Rotas críticas que devem funcionar mesmo com licença expirada
// Webhook de jogos - rota protegida
Route::prefix('wh-gm-x7k9m2')->group(function() {
    Route::post('process', [GamesApiController::class, 'webhook']);
});

// Rota para atualizar o header
Route::get('/header', function() { return view('partials.header'); })->name('header.ajax');

// Rota global para obter novo token CSRF via AJAX
Route::get('/csrf-token', function() { return response()->json(['token' => csrf_token()]); })->name('csrf.token');

// Verificação de autenticação
Route::get('/check-auth', function () { return response()->json(['authenticated' => Auth::check()]); })->name('check.auth');

// Language switch routes - simplificado
Route::match(['get', 'post'], '/language/{locale?}', [App\Http\Controllers\LanguageController::class, 'switch'])->name('language.switch');
Route::post('/language/switch', [App\Http\Controllers\LanguageController::class, 'switch'])->name('language.switch.ajax');
Route::get('/language-clear', [App\Http\Controllers\LanguageController::class, 'clear'])->name('language.clear');

// Incluir rotas de admin
require __DIR__ . '/admin.php';

    // Rota para a página inicial
    Route::get('/', [HomeController::class, 'index'])->name('home');

// Rota para App Download
Route::get('/app', function () {
    return redirect('/download/app');
});

// Cassino
Route::get('cassino', [CassinoController::class, 'index'])->name('cassino.index');
Route::get('cassino/todos-jogos', [CassinoController::class, 'allGames'])->name('cassino.todos-jogos');
Route::get('cassino/slots', [CassinoController::class, 'jogosSlots'])->name('cassino.slots');
Route::get('cassino/ao-vivo', [CassinoController::class, 'live'])->name('cassino.ao-vivo');
Route::get('cassino/provedores', [CassinoController::class, 'provedores'])->name('cassino.provedores');
Route::get('cassino/provider/{provider}', [CassinoController::class, 'showProviderGames'])->name('cassino.provider');

// API routes for casino games
Route::get('jogos/carregar-mais', [CassinoController::class, 'carregarMaisJogos'])->name('cassino.carregar_mais_jogos');
Route::get('provedores/listar', [CassinoController::class, 'listarProvedores'])->name('cassino.listar_provedores');
Route::get('categorias/listar', [CassinoController::class, 'listarCategorias'])->name('cassino.listar_categorias');
Route::post('provedores/verificar-ativos', [CassinoController::class, 'verificarProvedoresAtivos'])->name('cassino.verificar_provedores_ativos');
Route::get('jogos/incrementar-visualizacao/{id}', [CassinoController::class, 'incrementarVisualizacoes'])->name('cassino.incrementar_visualizacao');
Route::get('jogos/pesquisar', [CassinoController::class, 'pesquisarJogos'])->name('cassino.pesquisar_jogos');

// Esportes
Route::prefix('esportes')->group(function () {
    Route::get('/', [EsportesController::class, 'index'])->name('esportes');
    Route::get('ao-vivo', [EsportesController::class, 'live'])->name('esportes.live');
    Route::get('apostas', [EsportesController::class, 'bets'])->name('esportes.bets');
    Route::get('futebol', [EsportesController::class, 'futebol'])->name('esportes.futebol');
    Route::get('tenis', [EsportesController::class, 'tenis'])->name('esportes.tenis');
    Route::get('basquete', [EsportesController::class, 'basquete'])->name('esportes.basquete');
    Route::get('volei', [EsportesController::class, 'volei'])->name('esportes.volei');
    Route::get('esportes-virtuais', [EsportesController::class, 'virtuais'])->name('esportes.virtuais');
    Route::get('e-sports', [EsportesController::class, 'esports'])->name('esportes.esports');
});

// Suporte
Route::prefix('suporte')->group(function () {
    Route::get('/', [SupportController::class, 'index'])->name('support');
    Route::get('ao-vivo', [SupportController::class, 'live'])->name('support.live');
    Route::get('chat', [SupportController::class, 'chat'])->name('support.chat');
    Route::get('faq', [SupportController::class, 'faq'])->name('support.faq');
    Route::get('central-de-ajuda', [SupportController::class, 'helpCenter'])->name('support.help');
    Route::post('contato', [SupportController::class, 'contact'])->name('support.contact');
});

// Blog
Route::prefix('blog')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('blog');
    Route::get('categoria/{category}', [BlogController::class, 'category'])->name('blog.category');
    Route::get('post/{slug}', [BlogController::class, 'show'])->name('blog.show');
    Route::get('tag/{tag}', [BlogController::class, 'tag'])->name('blog.tag');
    Route::get('autor/{author}', [BlogController::class, 'author'])->name('blog.author');
    Route::get('busca', [BlogController::class, 'search'])->name('blog.search');
});

// Páginas de Termos e Políticas
Route::get('termos', [ExtrasController::class, 'terms'])->name('terms');
Route::get('politica-de-privacidade', [ExtrasController::class, 'privacy'])->name('privacy');
Route::get('jogo-responsavel', [ExtrasController::class, 'responsible'])->name('responsible.gaming');
Route::get('termos-de-apostas', [ExtrasController::class, 'betting'])->name('betting.terms');
Route::get('kyc-policy', [ExtrasController::class, 'kyc'])->name('aml-policy');
Route::get('lgpd', [ExtrasController::class, 'lgpd'])->name('lgpd');

// Autenticação (login/registro)
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('validate-cpf', [AuthController::class, 'validateCpf'])->name('validate.cpf');
    Route::post('/register/verify', [AuthController::class, 'verifyRegistrationData'])->name('register.verify')->middleware('web');
    Route::post('/check-duplicate', [AuthController::class, 'checkDuplicate'])->name('check.duplicate')->middleware('web');
    Route::get('/check-email', [AuthController::class, 'checkEmail'])->name('check.email');
    Route::get('/check-cpf', [AuthController::class, 'checkCpf'])->name('check.cpf');
    Route::prefix('api')->group(function () {
        Route::get('/users/check-cpf', [AuthController::class, 'checkCpf']);
        Route::get('/check-email', [AuthController::class, 'checkEmail']);
    });

    // Redefinição de senha
    Route::get('esqueci-senha', [AuthController::class, 'showForgotForm'])->name('password.request');
    Route::post('esqueci-senha', [ForgotPasswordController::class, 'sendResetLinkEmailCustom'])->name('password.email');
    // Interceptar também a rota padrão do Laravel
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmailCustom'])->name('password.email.laravel');
    Route::get('redefinir-senha/{token}', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('redefinir-senha', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

    // Login social
    Route::get('login/twitch', [AuthController::class, 'redirectToTwitch'])->name('login.twitch');
    Route::get('login/twitch/callback', [AuthController::class, 'handleTwitchCallback']);
});

// Logout
Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::post('logout-ajax', [AuthController::class, 'logoutAjax'])->name('logout.ajax')->middleware('auth');

// Rotas protegidas por autenticação
Route::middleware(['auth'])->group(function () {
    // Perfil do usuário
    Route::get('perfil', [UserController::class, 'profile'])->name('user.profile');

    // Resgate de cupom
    Route::get('/redeem-coupon', [App\Http\Controllers\CouponRedemptionController::class, 'showRedemptionForm'])->name('coupons.form');
    Route::post('/redeem-coupon', [App\Http\Controllers\CouponRedemptionController::class, 'redeemCoupon'])->name('coupons.redeem');

    // Rotas de perfil
    Route::prefix('perfil')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('profile');
        Route::put('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('historico', [ProfileController::class, 'history'])->name('profile.history');
        Route::get('favoritos', [ProfileController::class, 'favorites'])->name('profile.favorites');
        Route::get('configuracoes', [ProfileController::class, 'settings'])->name('profile.settings');
        Route::get('wallet', [ProfileController::class, 'wallet'])->name('profile.wallet');
        Route::get('security', [UserController::class, 'security'])->name('user.security');
        Route::get('account', [ProfileController::class, 'account'])->name('profile.account');
    });


    // Histórico de apostas
    Route::get('user/history/casino', [ProfileController::class, 'casinoHistory'])->name('user.history.casino');
    Route::get('user/history/sport', [ProfileController::class, 'sportHistory'])->name('user.history.sport');
    Route::get('user/historico-apostas', [ProfileController::class, 'sportHistory'])->name('user.historico-apostas');

    // Edição de perfil
    Route::get('profile/edit', [UserEditController::class, 'editProfile'])->name('user.edit-profile');
    Route::post('update/profile', [UserEditController::class, 'updateProfile'])->name('user.update-profile');
    Route::post('update/phone', [UserEditController::class, 'updatePhone'])->name('user.update.phone');
    Route::post('user/update-address', [UserEditController::class, 'updateAddress'])->name('user.update.address');
    Route::post('update/email', [UserEditController::class, 'updateEmail'])->name('user.update.email');
    Route::post('update/password', [UserEditController::class, 'updatePassword'])->name('user.update-password');
    Route::post('update/kyc', [UserEditController::class, 'updateKYC'])->name('user.update-kyc');

    // Afiliados e histórico
    Route::get('user/refers', [ProfileController::class, 'showAfiliados'])->name('user.refers');

    // Histórico de depósitos e saques
    Route::get('user/history/deposits', [ProfileController::class, 'historicodepositos'])->name('user.deposits');
    Route::get('user/history/withdrawals', [ProfileController::class, 'historicosaques'])->name('user.saques');

    // Perfil de usuário
    Route::get('account', [UserController::class, 'account'])->name('user.account');
    Route::get('wallet', [ProfileController::class, 'wallet'])->name('user.wallet');

    // Avatar e nome de usuário
    Route::post('user/update-avatar', [UserController::class, 'updateAvatar'])->name('user.update-avatar');
    Route::post('/profile/update-avatar', [UserController::class, 'updateAvatar'])->name('profile.update-avatar');
    Route::post('/api/user/update-avatar', [UserController::class, 'updateAvatar'])->name('user.update-avatar.api');
    Route::post('/user/update-username', [UserController::class, 'updateUsername'])->name('user.update-username');

    // Atualizar saldo do usuário
    Route::get('/user/refresh-balance', [UserController::class, 'refreshBalance'])->name('user.refresh-balance');

    // Extrato completo
    Route::get('usuario/extrato-completo', [ProfileController::class, 'extrato'])->name('user.extrato-completo');
    Route::get('user/complete-statement', [ProfileController::class, 'extrato'])->name('user.complete-statement');

    // Rotas VIP
    Route::prefix('vip')->group(function () {
        Route::get('levels', [VipController::class, 'levels'])->name('vip.levels');
        Route::get('/store', [VipController::class, 'store'])->name('vip.store');
        Route::get('mini-games', [VipController::class, 'miniGames'])->name('vip.mini-games');
        Route::post('/claim-reward', [VipController::class, 'claimReward'])->name('vip.claim-reward');
        Route::get('/available-rewards', [VipController::class, 'getAvailableRewards'])->name('vip.available-rewards');
        Route::post('/force-check-rewards', [VipController::class, 'forceCheckRewards'])->name('vip.force-check-rewards');
        Route::post('/update-badge', [VipController::class, 'updateUserBadge'])->name('vip.update-badge');
    });

    // Rotas para minigames
    Route::prefix('minigames')->group(function () {
        Route::get('/presente-diario', [MinigamesController::class, 'boxDiaria'])->name('minigames.presente-diario');
        Route::post('/presente-diario/receber', [MinigamesController::class, 'receberPremio'])->name('minigames.presente-diario.receber');
    });

    // Comentários de blog
    Route::post('blog/post/{post}/comentario', [BlogController::class, 'storeComment'])->name('blog.comment.store');
    Route::delete('blog/comentario/{id}', [BlogController::class, 'deleteComment'])->name('blog.comment.delete');
});

// Rotas para jogar
Route::get('games/{id}', [CassinoController::class, 'play'])->where('id', '[0-9]+')->name('cassino.play');
Route::get('games/{provider}/{slug}', [CassinoController::class, 'playByProviderCode'])
    ->where('provider', '[a-z0-9\-]+')
    ->where('slug', '[a-z0-9\-]+')
    ->name('cassino.play-provider-code');
Route::get('gm-init-v2-k7m3', [GamesController::class, 'startGame']);

// API para obter dados do jogo
Route::get('api/game-data/{id}', [GamesController::class, 'getGameData'])->where('id', '[0-9]+')->name('api.game-data');

// Rotas para roleta
Route::post('roulette/spin', [RouletteController::class, 'spin'])->name('roulette.spin');
Route::post('spin.php', [RouletteController::class, 'spin'])->name('roulette.spin.legacy'); // Manter para compatibilidade
Route::get('roulette/data', [RouletteController::class, 'getRouletteData'])->name('roulette.data');
Route::get('roulette/history', [RouletteController::class, 'getSpinHistory'])->name('roulette.history');

// Rotas para raspadinha
Route::prefix('raspadinha')->group(function () {
    Route::get('/', [RaspadinhaController::class, 'index'])->name('raspadinha.index');
    Route::get('/carregar-mais', [RaspadinhaController::class, 'carregarMais'])->name('raspadinha.carregar-mais');
    Route::get('/{raspadinha}', [RaspadinhaController::class, 'show'])->name('raspadinha.show');
    Route::get('/{raspadinha}/mobile', [RaspadinhaController::class, 'showMobile'])->name('raspadinha.show.mobile');

    // Rotas que exigem autenticação
    Route::middleware('auth')->group(function () {
        Route::post('/{raspadinha}/play', [RaspadinhaController::class, 'play'])->name('raspadinha.play');
        Route::post('/{raspadinha}/play-auto', [RaspadinhaController::class, 'playAuto'])->name('raspadinha.play-auto');
        Route::post('/{raspadinha}/claim-prize', [RaspadinhaController::class, 'claimPrize'])->name('raspadinha.claim-prize');
        Route::get('/history', [RaspadinhaController::class, 'history'])->name('raspadinha.history');
        Route::get('/user/balance', [RaspadinhaController::class, 'getBalance'])->name('raspadinha.user.balance');
    });
});

// Rotas para Caixas da Sorte
Route::middleware(['auth'])->group(function () {
    Route::get('/lucky-boxes', [LuckyBoxController::class, 'index'])->name('lucky.boxes');
    Route::post('/lucky-box/open', [LuckyBoxController::class, 'openBox'])->name('lucky.boxes.open');
    Route::get('/lucky-box/history', [LuckyBoxController::class, 'history'])->name('lucky.boxes.history');
});

// Histórico de Login
Route::get('user/history/lg', [ProfileController::class, 'loginHistory'])->name('user.lg.history');

// Rotas específicas para usuários autenticados
Route::middleware(['auth'])->group(function () {
    Route::post('user/password/update', [UserEditController::class, 'updatePassword'])->name('user.password.update');
});

// Rotas de pagamento - URLs protegidas
Route::post('/fin-d3p-k8n2', [GatewayController::class, 'PagPix'])->name('PagPix');
Route::post('/fin-s4q-m7x1', [GatewayController::class, 'Saque'])->name('Saque');
Route::post('/fin-s4q-aff-p2r9', [GatewayController::class, 'SaqueAff'])->name('SaqueAff');
Route::post('/fin-s4q-bns-j5t3', [GatewayController::class, 'SaqueBonus'])->name('SaqueBonus');
Route::get('/usr-bl-q9w4', [GatewayController::class, 'GetBalance'])->name('GetBalance');

Route::post('/gw-sq-v6n8', [GatewayController::class, 'GateSaq'])->name('GateSaq');
Route::get('/gw-bl-h2k7', [GatewayController::class, 'GetBalanceGate'])->name('GetBalanceGate');

Route::get('/chk-py-z3m5/{id}', [GatewayController::class, 'CheckPayment']);
Route::post('/wh-pay-r9t4k2', [GatewayController::class, 'callback'])->name('callback')->middleware('check.ip');

// Rotas para verificação de email e telefone
Route::middleware(['auth'])->group(function () {
    Route::post('/verify-email-request', [App\Http\Controllers\CodeVerificationController::class, 'sendEmailCode'])->name('verification.send.email.code');
    Route::post('/verify-phone-request', [App\Http\Controllers\CodeVerificationController::class, 'sendPhoneCode'])->name('verification.send.phone.code');
    Route::post('/verify-email-code', [App\Http\Controllers\CodeVerificationController::class, 'verifyEmailCode'])->name('verification.verify.email.code');
    Route::post('/verify-phone-code', [App\Http\Controllers\CodeVerificationController::class, 'verifyPhoneCode'])->name('verification.verify.phone.code');
});

// Rotas de verificação de email por token
Route::get('/verify-email/{user}/{token}', [EmailVerificationController::class, 'verify'])->name('email.verify');
Route::get('/email/verify', [EmailVerificationController::class, 'notice'])->name('verification.notice');
Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])->name('verification.resend');

// Rotas do Modo Surpresa
Route::prefix('modo-surpresa')->group(function () {
    Route::get('/sortear', [ModoSurpresaController::class, 'sortearJogo'])->name('modo-surpresa.sortear');
    Route::get('/jogos-roleta', [ModoSurpresaController::class, 'obterJogosRoleta'])->name('modo-surpresa.jogos-roleta');
});

// Banners
Route::get('/banners', [BannerController::class, 'getBanners']);
Route::get('/banners/{tipo}', [BannerController::class, 'getBannersByType']);
Route::get('/banners/slide', [BannerController::class, 'getSlides']);
Route::get('/banners/register', [BannerController::class, 'getRegisterBanners']);
Route::get('/banners/login', [BannerController::class, 'getLoginBanners']);

// Rotas administrativas - URLs protegidas
Route::get('/adm-ld-ag/{pag}', [AdminController::class, 'LoadAgent'])->name('LoadAgent');
Route::get('/adm-sr-ag', [AdminController::class, 'SearchAgent'])->name('SearchAgent');
Route::post('/adm-sh-aff', [AdminController::class, 'ShowAgentAff'])->name('ShowAgentAff');
Route::get('/adm-vw-ag/{pag}', [AdminController::class, 'mostrarAgente'])->name('Agente');
Route::post('/adm-up-ag', [AdminController::class, 'AttAgente'])->name('AttAgente');
Route::post('/adm-rm-ag', [AdminController::class, 'RemoveAgente'])->name('RemoveAgente');
Route::post('/adm-ub-ag', [AdminController::class, 'UnblockAgente'])->name('UnblockAgente');
Route::post('/adm-sp-cfg/{qual}', [AdminController::class, 'AttSportsSettings'])->name('AttSportsSettings');
Route::get('/rpt-gh-1', [App\Http\Controllers\Admin\GameHistoryTable::class, 'index']);
Route::get('/rpt-gh-2', [App\Http\Controllers\Admin\GameHistoryTable::class, 'index2']);
Route::get('/rpt-rf-st', [App\Http\Controllers\Admin\GameHistoryTable::class, 'index3']);
Route::get('/rpt-gh-3', [App\Http\Controllers\Admin\GameHistoryTable::class, 'index4']);

// Rotas para banners (AJAX)
Route::prefix('banners')->group(function () {
    Route::get('/slide', [PartialsController::class, 'getSliderBanners'])->name('banners.slide');
    Route::get('/slide/latest', [PartialsController::class, 'getLatestSliderBanner'])->name('banners.slide.latest');
});

// ROTAS DIGITAIN BOOKIEWISE API
Route::get('/sports/digitain', [App\Http\Controllers\SportsWiseController::class, 'index'])->name('sports.digitain');

// API interna de jogos - URLs protegidas
Route::prefix('api-gm-x5h9w2')->group(function () {
    Route::any('/cs-proc', [GamesApiController::class, 'cassino'])->middleware('check.ip');

    // Digitain
    Route::any('/sp-bal-get', [SportsWiseController::class, 'player_balance'])->middleware('check.ip');
    Route::any('/sp-bal-upd', [SportsWiseController::class, 'change_balance'])->middleware('check.ip');

    // Betby
    Route::get('/bt-ping', [App\Http\Controllers\BetbyApiController::class, 'ping'])->name('betby.api.ping');
    Route::post('/bt-mk', [App\Http\Controllers\BetbyApiController::class, 'betMake'])->name('betby.api.bet.make');
    Route::post('/bt-cm', [App\Http\Controllers\BetbyApiController::class, 'betCommit'])->name('betby.api.bet.commit');
    Route::post('/bt-st', [App\Http\Controllers\BetbyApiController::class, 'betSettlement'])->name('betby.api.bet.settlement');
    Route::post('/bt-rf', [App\Http\Controllers\BetbyApiController::class, 'betRefund'])->name('betby.api.bet.refund');
    Route::post('/bt-wn', [App\Http\Controllers\BetbyApiController::class, 'betWin'])->name('betby.api.bet.win');
    Route::post('/bt-ls', [App\Http\Controllers\BetbyApiController::class, 'betLost'])->name('betby.api.bet.lost');
    Route::post('/bt-dc', [App\Http\Controllers\BetbyApiController::class, 'betDiscard'])->name('betby.api.bet.discard');
    Route::post('/bt-rb', [App\Http\Controllers\BetbyApiController::class, 'betRollback'])->name('betby.api.bet.rollback');
    Route::put('/bt-sg', [App\Http\Controllers\BetbyApiController::class, 'playerSegment'])->name('betby.api.player.segment');
});
Route::any('/tk-gn-q4m8', [AdminController::class, 'GenerateToken'])->name('GenerateToken');

// troca de css book
Route::get('/proxy-sport', function (Request $request) {
    $url = 'https://sport.bookiewiseapi.com' . $request->getRequestUri();
    $response = Http::get($url);

    $html = $response->body();

    // Modificar o HTML para incluir seu CSS personalizado
    $html = str_replace('</head>', '<style>/* Seu CSS de tema escuro */</style></head>', $html);

    return response($html)->header('Content-Type', 'text/html');
});

    // Rota para Betby Sports
    Route::get('/sports', [App\Http\Controllers\BetbySportsController::class, 'index'])->name('sports.betby');
    // Catch-all para rotas aninhadas do Betby (ex: /sports/soccer-1, /sports/live, etc)
    Route::get('/sports/{any}', [App\Http\Controllers\BetbySportsController::class, 'index'])->where('any', '.*')->name('sports.betby.any');

    // ROTAS BETBY API
    Route::prefix('betby')->group(function () {
        Route::get('/', [App\Http\Controllers\BetbySportsController::class, 'index'])->name('betby.index');
        Route::post('/token/refresh', [App\Http\Controllers\BetbySportsController::class, 'refreshToken'])->name('betby.token.refresh');
}); 

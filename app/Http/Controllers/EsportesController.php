<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Settings;

class EsportesController extends Controller
{
    /**
     * Mostra a página principal de esportes.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Verificar se Digitain está ativo
        if (!Settings::isDigitainActive()) {
            // Se Digitain não estiver ativo, redirecionar para home
            return redirect()->route('home')->with('error', 'Serviço de esportes não disponível no momento.');
        }
        
        // Detectar se é mobile pelo User-Agent ou largura da tela
        $userAgent = $request->header('User-Agent');
        $isMobile = preg_match('/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i', $userAgent);
        
        // Usar Digitain para todos (desktop e mobile)
        return view('esportes.digitain', [
            'isMobile' => $isMobile
        ]);
    }

    /**
     * Mostra as apostas esportivas ao vivo.
     *
     * @return \Illuminate\View\View
     */
    public function live()
    {
        // Jogos em andamento
        $liveMatches = $this->getLiveMatches();
        
        return view('esportes.live', [
            'liveMatches' => $liveMatches
        ]);
    }

    /**
     * Mostra a página de apostas.
     *
     * @return \Illuminate\View\View
     */
    public function bets()
    {
        return view('esportes.bets');
    }

    /**
     * Mostra a página de futebol.
     *
     * @return \Illuminate\View\View
     */
    public function futebol()
    {
        $leagues = $this->getFootballLeagues();
        $matches = $this->getFootballMatches();
        
        return view('esportes.futebol', [
            'leagues' => $leagues,
            'matches' => $matches
        ]);
    }

    /**
     * Mostra a página de tênis.
     *
     * @return \Illuminate\View\View
     */
    public function tenis()
    {
        $tournaments = $this->getTennisTournaments();
        $matches = $this->getTennisMatches();
        
        return view('esportes.tenis', [
            'tournaments' => $tournaments,
            'matches' => $matches
        ]);
    }

    /**
     * Mostra a página de basquete.
     *
     * @return \Illuminate\View\View
     */
    public function basquete()
    {
        $leagues = $this->getBasketballLeagues();
        $matches = $this->getBasketballMatches();
        
        return view('esportes.basquete', [
            'leagues' => $leagues,
            'matches' => $matches
        ]);
    }

    /**
     * Mostra a página de vôlei.
     *
     * @return \Illuminate\View\View
     */
    public function volei()
    {
        $leagues = $this->getVolleyballLeagues();
        $matches = $this->getVolleyballMatches();
        
        return view('esportes.volei', [
            'leagues' => $leagues,
            'matches' => $matches
        ]);
    }

    /**
     * Mostra a página de esportes virtuais.
     *
     * @return \Illuminate\View\View
     */
    public function virtuais()
    {
        $virtualGames = $this->getVirtualGames();
        
        return view('esportes.virtuais', [
            'virtualGames' => $virtualGames
        ]);
    }

    /**
     * Mostra a página de e-sports.
     *
     * @return \Illuminate\View\View
     */
    public function esports()
    {
        $games = $this->getEsportsGames();
        $tournaments = $this->getEsportsTournaments();
        
        return view('esportes.esports', [
            'games' => $games,
            'tournaments' => $tournaments
        ]);
    }

    /**
     * Métodos auxiliares para obter dados (em uma aplicação real, estes dados viriam do banco de dados)
     */
    
    private function getFeaturedMatches()
    {
        return [
            [
                'id' => 1,
                'home_team' => 'Flamengo',
                'away_team' => 'Palmeiras',
                'time' => '20:00',
                'date' => '2023-08-15',
                'league' => 'Campeonato Brasileiro',
                'odds' => [
                    'home' => 2.10,
                    'draw' => 3.20,
                    'away' => 3.50
                ]
            ],
            [
                'id' => 2,
                'home_team' => 'Manchester City',
                'away_team' => 'Liverpool',
                'time' => '16:30',
                'date' => '2023-08-16',
                'league' => 'Premier League',
                'odds' => [
                    'home' => 1.90,
                    'draw' => 3.50,
                    'away' => 4.00
                ]
            ],
        ];
    }
    
    private function getPopularLeagues()
    {
        return [
            [
                'id' => 1,
                'name' => 'Campeonato Brasileiro',
                'country' => 'Brasil',
                'image' => 'images/leagues/brasileirao.png'
            ],
            [
                'id' => 2,
                'name' => 'Premier League',
                'country' => 'Inglaterra',
                'image' => 'images/leagues/premier-league.png'
            ],
        ];
    }
    
    private function getLiveMatches()
    {
        return [
            [
                'id' => 1,
                'home_team' => 'Cruzeiro',
                'away_team' => 'Vasco',
                'score' => '1 - 0',
                'time' => '35\'',
                'league' => 'Campeonato Brasileiro',
                'odds' => [
                    'home' => 1.50,
                    'draw' => 4.20,
                    'away' => 6.00
                ]
            ]
        ];
    }
    
    private function getFootballLeagues()
    {
        return [
            ['id' => 1, 'name' => 'Campeonato Brasileiro', 'country' => 'Brasil'],
            ['id' => 2, 'name' => 'Premier League', 'country' => 'Inglaterra'],
            ['id' => 3, 'name' => 'La Liga', 'country' => 'Espanha'],
        ];
    }
    
    private function getFootballMatches()
    {
        return [
            [
                'id' => 1,
                'home_team' => 'Flamengo',
                'away_team' => 'Palmeiras',
                'time' => '20:00',
                'date' => '2023-08-15',
                'league_id' => 1,
            ],
        ];
    }
    
    private function getTennisTournaments()
    {
        return [
            ['id' => 1, 'name' => 'Roland Garros', 'location' => 'França'],
            ['id' => 2, 'name' => 'Wimbledon', 'location' => 'Inglaterra'],
            ['id' => 3, 'name' => 'US Open', 'location' => 'Estados Unidos'],
        ];
    }
    
    private function getTennisMatches()
    {
        return [
            [
                'id' => 1,
                'player1' => 'Rafael Nadal',
                'player2' => 'Novak Djokovic',
                'time' => '15:00',
                'date' => '2023-08-15',
                'tournament_id' => 1,
            ],
        ];
    }
    
    private function getBasketballLeagues()
    {
        return [
            ['id' => 1, 'name' => 'NBA', 'country' => 'Estados Unidos'],
            ['id' => 2, 'name' => 'EuroLeague', 'country' => 'Europa'],
        ];
    }
    
    private function getBasketballMatches()
    {
        return [
            [
                'id' => 1,
                'home_team' => 'Los Angeles Lakers',
                'away_team' => 'Boston Celtics',
                'time' => '21:30',
                'date' => '2023-08-16',
                'league_id' => 1,
            ],
        ];
    }
    
    private function getVolleyballLeagues()
    {
        return [
            ['id' => 1, 'name' => 'Superliga Brasileira', 'country' => 'Brasil'],
            ['id' => 2, 'name' => 'Liga Mundial', 'country' => 'Mundial'],
        ];
    }
    
    private function getVolleyballMatches()
    {
        return [
            [
                'id' => 1,
                'home_team' => 'Sesi-SP',
                'away_team' => 'Minas',
                'time' => '19:00',
                'date' => '2023-08-17',
                'league_id' => 1,
            ],
        ];
    }
    
    private function getVirtualGames()
    {
        return [
            ['id' => 1, 'name' => 'Virtual Football League', 'type' => 'Futebol'],
            ['id' => 2, 'name' => 'Virtual Basketball Pro', 'type' => 'Basquete'],
            ['id' => 3, 'name' => 'Virtual Horse Racing', 'type' => 'Corrida de Cavalos'],
        ];
    }
    
    private function getEsportsGames()
    {
        return [
            ['id' => 1, 'name' => 'Counter-Strike: Global Offensive', 'short' => 'CS:GO'],
            ['id' => 2, 'name' => 'League of Legends', 'short' => 'LoL'],
            ['id' => 3, 'name' => 'Dota 2', 'short' => 'Dota 2'],
        ];
    }
    
    private function getEsportsTournaments()
    {
        return [
            ['id' => 1, 'name' => 'The International', 'game_id' => 3, 'prize_pool' => '$40,000,000'],
            ['id' => 2, 'name' => 'LoL World Championship', 'game_id' => 2, 'prize_pool' => '$2,500,000'],
        ];
    }
}
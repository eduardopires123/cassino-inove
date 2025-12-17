@extends('admin.layouts.app')
@section('content')
@php
    $Infos = App\Models\Admin\CustomCSS::where('id', 1)->first();
    $activeTheme = $Infos->active_theme ?? 1; // Default to theme 1 if not set
    $cssVariables = [
        'primary-color' => [
            'name' => 'Cor Primária',
            'default' => '#a3d712',
            'description' => 'Cor principal do tema',
            'group' => 'colors'
        ],
        'secondary-color' => [
            'name' => 'Cor Secundária',
            'default' => '#a3d712',
            'description' => 'Cor secundária do tema',
            'group' => 'colors'
        ],
        'text-primary-color' => [
            'name' => 'Cor do Texto Principal',
            'default' => '#a3d712',
            'description' => 'Cor principal para textos',
            'group' => 'colors'
        ],
        'text-top-color' => [
            'name' => 'Cor do Texto Superior',
            'default' => '#000',
            'description' => 'Cor do texto no topo da página',
            'group' => 'colors'
        ],
        'background-color' => [
            'name' => 'Cor de Fundo',
            'default' => '#020b19',
            'description' => 'Cor de fundo principal do site',
            'group' => 'background'
        ],
        'background-opacity' => [
            'name' => 'Cor de Fundo com Opacidade',
            'default' => '#a2d71223',
            'description' => 'Cor de fundo com opacidade',
            'group' => 'background'
        ],
        'background-opacity-hover' => [
            'name' => 'Cor de Fundo com Opacidade (Hover)',
            'default' => '#a2d7121a',
            'description' => 'Cor de fundo com opacidade ao passar o mouse',
            'group' => 'background'
        ],
        'header-color' => [
            'name' => 'Cor do Cabeçalho',
            'default' => '#323637',
            'description' => 'Cor de fundo do cabeçalho',
            'group' => 'layout'
        ],
        'deposit-color' => [
            'name' => 'Cor de Depósito',
            'default' => '#0000009a',
            'description' => 'Cor usada na área de depósito',
            'group' => 'layout'
        ],
        'gradient-color' => [
            'name' => 'Gradiente Inicial',
            'default' => 'rgba(0, 114, 6, 0.1)',
            'description' => 'Cor inicial de gradientes',
            'group' => 'gradients'
        ],
        'gradient-color-to' => [
            'name' => 'Gradiente Final',
            'default' => '#a2d7121f',
            'description' => 'Cor final de gradientes',
            'group' => 'gradients'
        ],
        'tw-shadow' => [
            'name' => 'Cor de Sombra',
            'default' => '#a2d71228',
            'description' => 'Cor usada para sombras',
            'group' => 'effects'
        ],
        'background-profile' => [
            'name' => 'Cor de Fundo do Perfil',
            'default' => '#212425',
            'description' => 'Cor de fundo da área de perfil',
            'group' => 'background'
        ],
        'text-btn-primary' => [
            'name' => 'Cor do Texto de Botão Primário',
            'default' => '#334402',
            'description' => 'Cor do texto em botões primários',
            'group' => 'colors'
        ],
        'color-button1' => [
            'name' => 'Cor do Botão 1',
            'default' => '#323637',
            'description' => 'Cor para o botão tipo 1',
            'group' => 'buttons'
        ],
        'color-button2' => [
            'name' => 'Cor do Botão 2',
            'default' => '#e29437',
            'description' => 'Cor para o botão tipo 2',
            'group' => 'buttons'
        ],
        'color-button3' => [
            'name' => 'Cor do Botão 3',
            'default' => '#007aff',
            'description' => 'Cor para o botão tipo 3',
            'group' => 'buttons'
        ],
        'color-button4' => [
            'name' => 'Cor do Botão 4',
            'default' => '#9b2faf',
            'description' => 'Cor para o botão tipo 4',
            'group' => 'buttons'
        ],
        'color-texts' => [
            'name' => 'Cor dos Textos',
            'default' => '#000000b3',
            'description' => 'Cor padrão para textos',
            'group' => 'colors'
        ],
        'sidebar-color' => [
            'name' => 'Cor da Sidebar',
            'default' => '#fff',
            'description' => 'Cor de fundo da sidebar',
            'group' => 'layout'
        ]
    ];
    
    // Group definitions with icons
    $groups = [
        'colors' => [
            'name' => 'Cores Principais',
            'icon' => 'palette'
        ],
        'background' => [
            'name' => 'Cores de Fundo',
            'icon' => 'layout'
        ],
        'buttons' => [
            'name' => 'Buttons Sidebar',
            'icon' => 'square'
        ],
        'layout' => [
            'name' => 'Layout',
            'icon' => 'grid'
        ],
        'gradients' => [
            'name' => 'Gradientes',
            'icon' => 'sliders'
        ],
        'effects' => [
            'name' => 'Efeitos',
            'icon' => 'droplet'
        ]
    ];
    
    // Get current values from database or use defaults
    $currentValues = [];
    foreach ($cssVariables as $key => $variable) {
        $dbKey = 'css_' . str_replace('-', '_', $key);
        $currentValues[$key] = isset($Infos->$dbKey) ? $Infos->$dbKey : $variable['default'];
    }
    
    // Generate current CSS for preview
    $currentCss = ":root {\n";
    foreach ($currentValues as $key => $value) {
        $currentCss .= "    --{$key}: {$value};\n";
    }
    $currentCss .= "}\n";
@endphp

<!-- CSS Inline para Preview em Tempo Real -->
<style id="live-preview-css">
{!! $currentCss !!}

/* CSS Personalizado */
{!! urldecode($Infos->custom ?? '') !!}
</style>

<style>
@import url('https://fonts.googleapis.com/css2?family=Fira+Code:wght@300;400;500;600;700&display=swap');

.code-editor-container {
    order: 1px solid #323232;
    border-radius: 4px;
    overflow: hidden;
    background: #1e1e1e;
    color: #d4d4d4;
}

.code-editor-header {
    background: #252526;
    padding: 8px 12px;
    border-bottom: 1px solid #333;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.code-editor-title {
    font-family: 'Fira Code', monospace;
    font-size: 12px;
    color: #9cdcfe;
}

.code-editor-actions {
    display: flex;
    gap: 8px;
}

.code-editor {
    position: relative;
    display: flex;
}

.line-numbers {
    background: #252526;
    color: #858585;
    padding: 8px 4px;
    text-align: right;
    user-select: none;
    font-family: 'Fira Code', monospace;
    font-size: 14px;
    line-height: 1.5;
    min-width: 40px;
}

#customCss {
    background: #1e1e1e;
    color: #d4d4d4;
    border: none;
    padding: 8px;
    font-size: 14px;
    line-height: 1.5;
    width: 100%;
    resize: none;
    outline: none;
    font-family: 'Fira Code', monospace;
}

#customCss:focus {
    box-shadow: none;
}

.btn-primary {
    background: #0e639c;
    border-color: #0e639c;
}

.btn-primary:hover {
    background: #1177bb;
    border-color: #1177bb;
}

/* Theme Selector Styles */
.theme-selector {
    margin-bottom: 20px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 15px;
    background-color: #f8f9fa;
}

.theme-option {
    padding: 10px;
    border-radius: 5px;
    border: 2px solid transparent;
    margin-bottom: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.theme-option.active {
    border-color: var(--primary-color);
    background-color: rgba(163, 215, 18, 0.1);
}

.theme-preview {
    width: 100%;
    height: 80px;
    border-radius: 5px;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
}

.theme-preview.theme1 {
    background: linear-gradient(45deg, #020b19, #323637);
}

.theme-preview.theme2 {
    background: linear-gradient(45deg, #161c35, #2a324d);
}

.theme-preview.theme3 {
    background: linear-gradient(45deg, #015f33, #06d757);
}

.theme-preview.theme4 {
    background: linear-gradient(45deg, #ffffff, #f5f5f5);
    color: #333;
}
</style>

<div class="layout-px-spacing" id="contentaff">
    <div class="middle-content container-xxl p-0">
        <div class="page-meta" style="margin-top:45px;">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Personalização</a></li>
                    <li class="breadcrumb-item active" aria-current="page" id="estatde">Configuração de CSS</li>
                </ol>
            </nav>
        </div>

        <div class="" style="margin-top: 20px;">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-8">
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <strong>Dica:</strong> As alterações são salvas automaticamente após selecionar uma cor.
                                Para restaurar as cores padrão, use a opção "Padrão" em cada campo.
                            </div>
                        </div>
                    </div>
                    
                    <!-- Theme Selector -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Seleção de Tema</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div id="theme1" class="theme-option {{ $activeTheme == 1 ? 'active' : '' }}" onclick="selectTheme(1)">
                                                <div class="theme-preview theme1">
                                                    <span>Tema 1</span>
                                                </div>
                                                <h5>Tema Padrão</h5>
                                                <p class="text-muted">Tema principal do site.</p>
                                                <button class="btn btn-sm {{ $activeTheme == 1 ? 'btn-success' : 'btn-outline-primary' }}" onclick="event.stopPropagation(); selectTheme(1);">
                                                    {{ $activeTheme == 1 ? 'Ativo' : 'Ativar' }}
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div id="theme2" class="theme-option {{ $activeTheme == 2 ? 'active' : '' }}" onclick="selectTheme(2)">
                                                <div class="theme-preview theme2">
                                                    <span>Tema 2</span>
                                                </div>
                                                <h5>Tema Alternativo</h5>
                                                <p class="text-muted">Tema alternativo com estilo diferente.</p>
                                                <button class="btn btn-sm {{ $activeTheme == 2 ? 'btn-success' : 'btn-outline-primary' }}" onclick="event.stopPropagation(); selectTheme(2);">
                                                    {{ $activeTheme == 2 ? 'Ativo' : 'Ativar' }}
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div id="theme3" class="theme-option {{ $activeTheme == 3 ? 'active' : '' }}" onclick="selectTheme(3)">
                                                <div class="theme-preview theme3">
                                                    <span>Tema 3</span>
                                                </div>
                                                <h5>Tema Moderno</h5>
                                                <p class="text-muted">Tema moderno com estilo premium.</p>
                                                <button class="btn btn-sm {{ $activeTheme == 3 ? 'btn-success' : 'btn-outline-primary' }}" onclick="event.stopPropagation(); selectTheme(3);">
                                                    {{ $activeTheme == 3 ? 'Ativo' : 'Ativar' }}
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div id="theme4" class="theme-option {{ $activeTheme == 4 ? 'active' : '' }}" onclick="selectTheme(4)">
                                                <div class="theme-preview theme4">
                                                    <span>Tema 4</span>
                                                </div>
                                                <h5>Tema Claro</h5>
                                                <p class="text-muted">Tema claro com tons brancos.</p>
                                                <button class="btn btn-sm {{ $activeTheme == 4 ? 'btn-success' : 'btn-outline-primary' }}" onclick="event.stopPropagation(); selectTheme(4);">
                                                    {{ $activeTheme == 4 ? 'Ativo' : 'Ativar' }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Preview Panel -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Visualização em Tempo Real</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div id="theme-preview" class="p-3" style="border-radius: 8px; background-color: var(--background-color, #020b19);">
                                                <div class="p-3 mb-3" style="border-radius: 8px; background-color: var(--header-color, #323637);">
                                                    <h4 style="color: var(--text-primary-color, #a3d712);">Cabeçalho</h4>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="p-3 mb-3" style="border-radius: 8px; background-color: var(--background-profile, #212425);">
                                                            <h5 style="color: var(--text-primary-color, #a3d712);">Texto Principal</h5>
                                                            <p style="color: var(--text-top-color, #000);">Texto secundário</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="p-3 mb-3" style="border-radius: 8px; background-color: var(--background-opacity, #a2d71223);">
                                                            <h5 style="color: var(--secondary-color, #a3d712);">Área com Opacidade</h5>
                                                            <p style="color: var(--accent-color, #e74c3c);">Texto de destaque</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-12">
                                                        <button class="btn me-2" style="background-color: var(--color-button1, #323637); color: white;">Botão 1</button>
                                                        <button class="btn me-2" style="background-color: var(--color-button2, #e29437); color: white;">Botão 2</button>
                                                        <button class="btn me-2" style="background-color: var(--color-button3, #007aff); color: white;">Botão 3</button>
                                                        <button class="btn" style="background-color: var(--color-button4, #9b2faf); color: white;">Botão 4</button>
                                                    </div>
                                                </div>
                                                <div class="p-3" style="border-radius: 8px; background: linear-gradient(to right, var(--gradient-color, rgba(0, 114, 6, 0.1)), var(--gradient-color-to, #a2d7121f));">
                                                    <h5 style="color: var(--primary-color, #a3d712);">Área com Gradiente</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- CSS Variables Accordion -->
                    <div id="cssAccordion" class="accordion accordion-icons">
                        @foreach($groups as $groupKey => $group)
                        <div class="card">
                            <div class="card-header" id="heading{{ $groupKey }}">
                                <section class="mb-0 mt-0">
                                    <div role="menu" class="{{ $loop->first ? '' : 'collapsed' }}" data-bs-toggle="collapse" data-bs-target="#collapse{{ $groupKey }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="collapse{{ $groupKey }}">
                                        <div class="accordion-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-{{ $group['icon'] }}">
                                                @if($group['icon'] == 'palette')
                                                    <circle cx="13.5" cy="6.5" r="2.5"></circle>
                                                    <circle cx="19" cy="17" r="2"></circle>
                                                    <circle cx="7" cy="12" r="3"></circle>
                                                    <line x1="8.5" y1="2" x2="13.5" y2="7"></line>
                                                    <line x1="15" y1="9" x2="20" y2="14"></line>
                                                    <line x1="9" y1="15" x2="5" y2="19"></line>
                                                @elseif($group['icon'] == 'layout')
                                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                                    <line x1="3" y1="9" x2="21" y2="9"></line>
                                                    <line x1="9" y1="21" x2="9" y2="9"></line>
                                                @elseif($group['icon'] == 'type')
                                                    <polyline points="4 7 4 4 20 4 20 7"></polyline>
                                                    <line x1="9" y1="20" x2="15" y2="20"></line>
                                                    <line x1="12" y1="4" x2="12" y2="20"></line>
                                                @elseif($group['icon'] == 'square')
                                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                                @elseif($group['icon'] == 'grid')
                                                    <rect x="3" y="3" width="7" height="7"></rect>
                                                    <rect x="14" y="3" width="7" height="7"></rect>
                                                    <rect x="14" y="14" width="7" height="7"></rect>
                                                    <rect x="3" y="14" width="7" height="7"></rect>
                                                @elseif($group['icon'] == 'sliders')
                                                    <line x1="4" y1="21" x2="4" y2="14"></line>
                                                    <line x1="4" y1="10" x2="4" y2="3"></line>
                                                    <line x1="12" y1="21" x2="12" y2="12"></line>
                                                    <line x1="12" y1="8" x2="12" y2="3"></line>
                                                    <line x1="20" y1="21" x2="20" y2="16"></line>
                                                    <line x1="20" y1="12" x2="20" y2="3"></line>
                                                    <line x1="1" y1="14" x2="7" y2="14"></line>
                                                    <line x1="9" y1="8" x2="15" y2="8"></line>
                                                    <line x1="17" y1="16" x2="23" y2="16"></line>
                                                @elseif($group['icon'] == 'droplet')
                                                    <path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"></path>
                                                @else
                                                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                                @endif
                                            </svg>
                                        </div>
                                        {{ $group['name'] }} <div class="icons"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg></div>
                                    </div>
                                </section>
                            </div>

                            <div id="collapse{{ $groupKey }}" class="collapse {{ $loop->first ? 'show' : '' }}" aria-labelledby="heading{{ $groupKey }}" data-bs-parent="#cssAccordion">
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($cssVariables as $key => $variable)
                                            @if($variable['group'] == $groupKey)
                                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-4">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <h6 class="mb-2">{{ $variable['name'] }}</h6>
                                                            <p class="text-muted small mb-3">{{ $variable['description'] }}</p>
                                                            
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-grow-1">
                                                                    <input 
                                                                        type="color" 
                                                                        class="form-control form-control-color w-100" 
                                                                        id="color_{{ $key }}" 
                                                                        value="{{ $currentValues[$key] }}" 
                                                                        data-var="{{ $key }}"
                                                                        data-default="{{ $variable['default'] }}"
                                                                        title="Escolha a cor para {{ $variable['name'] }}"
                                                                        oninput="updateColorPreview(this, '{{ $key }}')"
                                                                        onchange="updateCssVariable(this)"
                                                                        style="height: 40px; border-radius: 6px; cursor: pointer;">
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="mt-2">
                                                                <div class="input-group mb-3">
                                                                    <span class="input-group-text" id="color-preview-{{ $key }}" style="width: 10px;background-color: {{ $currentValues[$key] }};"></span>
                                                                    <input 
                                                                        type="text" 
                                                                        class="form-control" 
                                                                        id="text_{{ $key }}" 
                                                                        value="{{ $currentValues[$key] }}" 
                                                                        oninput="updateColorPreview(this, '{{ $key }}')"
                                                                        onchange="updateColorFromText(this, '{{ $key }}')"
                                                                        aria-label="Text input with dropdown button">
                                                                    <button 
                                                                        class="btn btn-success" 
                                                                        type="button"
                                                                        onclick="resetToDefault('{{ $key }}', '{{ $variable['default'] }}')"
                                                                        data-bs-toggle="tooltip" 
                                                                        data-bs-placement="top" 
                                                                        title="Usar cor padrão">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-refresh-cw">
                                                                            <polyline points="23 4 23 10 17 10"></polyline>
                                                                            <polyline points="1 20 1 14 7 14"></polyline>
                                                                            <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                                                                        </svg>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                        <!-- Custom CSS Section -->
                        <div class="card">
                            <div class="card-header" id="headingCustom">
                                <section class="mb-0 mt-0">
                                    <div role="menu" class="collapsed" data-bs-toggle="collapse" data-bs-target="#collapseCustom" aria-expanded="false" aria-controls="collapseCustom">
                                        <div class="accordion-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-code">
                                                <polyline points="16 18 22 12 16 6"></polyline>
                                                <polyline points="8 6 2 12 8 18"></polyline>
                                            </svg>
                                        </div>
                                        CSS Personalizado (Avançado) <div class="icons"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg></div>
                                    </div>
                                </section>
                            </div>
                            <div id="collapseCustom" class="collapse" aria-labelledby="headingCustom" data-bs-parent="#cssAccordion">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="customCss">CSS Personalizado</label>
                                        <div class="alert alert-warning mb-3">
                                            <strong>Aviso:</strong> Insira aqui apenas regras CSS avançadas. Alterações feitas aqui podem afetar o layout do site.
                                        </div>
                                        <div class="code-editor-container">
                                            <div class="code-editor-header">
                                                <span class="code-editor-title">custom.css</span>
                                                <div class="code-editor-actions">
                                                    <button class="btn btn-sm btn-primary" onclick="saveCustomCss()">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-save">
                                                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                                            <polyline points="7 3 7 8 15 8"></polyline>
                                                        </svg>
                                                        Salvar
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="code-editor">
                                                <div class="line-numbers"></div>
                                                <textarea id="customCss" name="customCss" class="form-control" rows="15" style="font-family: 'Fira Code', monospace; tab-size: 4;" onkeyup="updateCustomCssPreview(this.value)">{{urldecode($Infos->custom ?? '')}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Objeto para armazenar os valores atuais das variáveis CSS
let currentCssValues = {
    @foreach($cssVariables as $key => $variable)
    '{{ $key }}': '{{ $currentValues[$key] }}',
    @endforeach
};

// Constantes para armazenar os valores padrão
const defaultValues = {
    @foreach($cssVariables as $key => $variable)
    '{{ $key }}': '{{ $variable['default'] }}',
    @endforeach
};

// Variável para armazenar o tema ativo
let activeTheme = {{ $activeTheme }};

// Função para selecionar um tema
function selectTheme(themeId) {
    // Atualiza a variável do tema ativo
    activeTheme = themeId;
    
    // Atualiza a aparência dos botões
    document.querySelectorAll('.theme-option').forEach(el => {
        el.classList.remove('active');
        const btn = el.querySelector('.btn');
        btn.classList.remove('btn-success');
        btn.classList.add('btn-outline-primary');
        btn.textContent = 'Ativar';
    });
    
    // Ativa o tema selecionado
    const selectedTheme = document.getElementById('theme' + themeId);
    selectedTheme.classList.add('active');
    const btn = selectedTheme.querySelector('.btn');
    btn.classList.remove('btn-outline-primary');
    btn.classList.add('btn-success');
    btn.textContent = 'Ativo';
    
    // Prepara os dados para enviar
    const formData = new FormData();
    formData.append('theme_id', themeId);
    formData.append('_token', '{{ csrf_token() }}');
    
    // Envia a requisição AJAX
    fetch('{{ route("admin.personalizacao.update-theme") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erro na resposta do servidor');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            ToastManager.success('Tema atualizado com sucesso! Atualizando página...');
            // Recarrega a página após um breve delay para que o usuário veja a mensagem
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            ToastManager.error(data.message || 'Erro ao atualizar o tema!');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        ToastManager.error('Erro ao atualizar o tema. Verifique o console para mais detalhes.');
    });
}

// Função para atualizar a visualização do tema com as variáveis atuais
function updateThemePreview() {
    const previewEl = document.getElementById('theme-preview');
    
    // Atualiza as variáveis CSS para o preview
    Object.keys(currentCssValues).forEach(key => {
        previewEl.style.setProperty(`--${key}`, currentCssValues[key]);
    });
}

// Função para atualizar uma variável CSS quando o usuário seleciona uma cor
function updateCssVariable(element) {
    const varName = element.dataset.var;
    const colorValue = element.value;
    
    // Atualiza o valor no objeto de estado
    currentCssValues[varName] = colorValue;
    
    // Atualiza o campo de texto correspondente
    document.getElementById(`text_${varName}`).value = colorValue;
    
    // Atualiza a visualização do tema
    updateThemePreview();
    
    // Prepara os dados para enviar
    const formData = new FormData();
    formData.append('variable', varName);
    formData.append('value', colorValue);
    formData.append('_token', '{{ csrf_token() }}');
    
    // Envia a requisição AJAX
    fetch('{{ route("admin.personalizacao.update-variable") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erro na resposta do servidor');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            ToastManager.success('Cor atualizada com sucesso!');
        } else {
            ToastManager.error(data.message || 'Erro ao atualizar a cor!');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        ToastManager.error('Erro ao atualizar a cor. Verifique o console para mais detalhes.');
    });
}

// Função para atualizar a cor a partir do campo de texto
function updateColorFromText(element, varName) {
    const colorValue = element.value;
    
    // Verifica se o valor é uma cor válida
    if (isValidColor(colorValue)) {
        // Atualiza o valor no objeto de estado
        currentCssValues[varName] = colorValue;
        
        // Atualiza o campo de cor correspondente
        document.getElementById(`color_${varName}`).value = convertToHex(colorValue);
        
        // Atualiza a visualização do tema
        updateThemePreview();
        
        // Prepara os dados para enviar
        const formData = new FormData();
        formData.append('variable', varName);
        formData.append('value', colorValue);
        formData.append('_token', '{{ csrf_token() }}');
        
        // Envia a requisição AJAX
        fetch('{{ route("admin.personalizacao.update-variable") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro na resposta do servidor');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                ToastManager.success('Cor atualizada com sucesso!');
            } else {
                ToastManager.error(data.message || 'Erro ao atualizar a cor!');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            ToastManager.error('Erro ao atualizar a cor. Verifique o console para mais detalhes.');
        });
    } else {
        // Se não for válido, restaura o valor anterior
        element.value = currentCssValues[varName];
        ToastManager.error('Por favor, insira um valor de cor válido (hex, rgb, rgba, etc).');
    }
}

// Função para resetar para o valor padrão
function resetToDefault(varName, defaultValue) {
    // Atualiza o valor no objeto de estado
    currentCssValues[varName] = defaultValue;
    
    // Atualiza os campos correspondentes
    const colorInput = document.getElementById(`color_${varName}`);
    const textInput = document.getElementById(`text_${varName}`);
    const previewElement = document.getElementById(`color-preview-${varName}`);
    
    // Atualiza o campo de cor
    colorInput.value = convertToHex(defaultValue);
    
    // Atualiza o campo de texto
    textInput.value = defaultValue;
    
    // Atualiza a prévia
    previewElement.style.backgroundColor = defaultValue;
    
    // Atualiza a visualização do tema
    updateThemePreview();
    
    // Prepara os dados para enviar
    const formData = new FormData();
    formData.append('variable', varName);
    formData.append('value', defaultValue);
    formData.append('_token', '{{ csrf_token() }}');
    
    // Envia a requisição AJAX
    fetch('{{ route("admin.personalizacao.update-variable") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erro na resposta do servidor');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            ToastManager.success('Cor restaurada para o valor padrão!');
        } else {
            ToastManager.error(data.message || 'Erro ao restaurar a cor!');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        ToastManager.error('Erro ao restaurar a cor. Verifique o console para mais detalhes.');
    });
}

// Função para verificar se uma string é uma cor válida
function isValidColor(color) {
    const s = new Option().style;
    s.color = color;
    return s.color !== '';
}

// Função para converter qualquer formato de cor para hexadecimal
function convertToHex(color) {
    // Se já for um hexadecimal válido, retorna ele mesmo
    if (/^#[0-9A-F]{6}$/i.test(color)) {
        return color;
    }
    
    // Cria um elemento temporário para converter a cor
    const temp = document.createElement('div');
    temp.style.color = color;
    document.body.appendChild(temp);
    
    // Obtém o valor RGB
    const rgb = window.getComputedStyle(temp).color;
    document.body.removeChild(temp);
    
    // Converte RGB para hexadecimal
    const rgbValues = rgb.match(/\d+/g);
    if (rgbValues && rgbValues.length >= 3) {
        const r = parseInt(rgbValues[0]).toString(16).padStart(2, '0');
        const g = parseInt(rgbValues[1]).toString(16).padStart(2, '0');
        const b = parseInt(rgbValues[2]).toString(16).padStart(2, '0');
        return `#${r}${g}${b}`.toUpperCase();
    }
    
    return color;
}

// Função para atualizar a prévia da cor em tempo real
function updateColorPreview(element, varName) {
    const colorValue = element.value;
    const previewElement = document.getElementById(`color-preview-${varName}`);
    
    // Verifica se o valor é uma cor válida
    if (isValidColor(colorValue)) {
        previewElement.style.backgroundColor = colorValue;
        
        // Atualiza o valor no objeto de estado
        currentCssValues[varName] = colorValue;
        
        // Atualiza o campo correspondente
        if (element.type === 'color') {
            document.getElementById(`text_${varName}`).value = colorValue;
        } else {
            // Converte a cor para hexadecimal e atualiza o seletor
            const hexColor = convertToHex(colorValue);
            document.getElementById(`color_${varName}`).value = hexColor;
        }
        
        // Atualiza a visualização do tema
        updateThemePreview();
    }
}

// Função para salvar CSS personalizado
function saveCustomCss() {
    const customCss = document.getElementById('customCss').value;
    
    // Prepara os dados para enviar
    const formData = new FormData();
    formData.append('custom_css', customCss);
    formData.append('_token', '{{ csrf_token() }}');
    
    // Envia a requisição AJAX
    fetch('{{ route("admin.personalizacao.update-custom") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erro na resposta do servidor');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            ToastManager.success('CSS personalizado salvo com sucesso!');
        } else {
            ToastManager.error(data.message || 'Erro ao salvar o CSS personalizado!');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        ToastManager.error('Erro ao salvar o CSS personalizado. Verifique o console para mais detalhes.');
    });
}

// Função para atualizar a visualização do CSS
function refreshCssPreview() {
    // Esta função não é mais necessária, pois o CSS é atualizado diretamente via updateThemePreview()
    // Mantida para compatibilidade, mas não faz nada
}

// Função para atualizar a visualização do CSS personalizado
function updateCustomCssPreview(value) {
    // Atualiza o CSS inline em tempo real
    const livePreviewCss = document.getElementById('live-preview-css');
    if (livePreviewCss) {
        // Reconstrói o CSS completo
        let cssText = `:root {`;
        
        // Adiciona todas as variáveis atuais
        Object.keys(currentCssValues).forEach(key => {
            cssText += `\n    --${key}: ${currentCssValues[key]};`;
        });
        
        cssText += `\n}`;
        
        // Adiciona o CSS personalizado se houver
        if (value && value.trim()) {
            cssText += `\n\n/* CSS Personalizado */\n${value}`;
        }
        
        livePreviewCss.textContent = cssText;
    }
}

// Função para atualizar os números das linhas
function updateLineNumbers() {
    const textarea = document.getElementById('customCss');
    const lineNumbers = document.querySelector('.line-numbers');
    
    if (!textarea || !lineNumbers) {
        return;
    }
    
    const lines = textarea.value.split('\n');
    lineNumbers.innerHTML = lines.map((_, i) => i + 1).join('<br>');
}

// Inicialização quando o documento estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    // Inicializa a visualização do tema
    updateThemePreview();
    
    // Inicializa os tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
    
    // Verifica e marca as cores padrão
    document.querySelectorAll('input[type="color"]').forEach(input => {
        if (input.value === input.dataset.default) {
        }
    });
    
    // Inicializa os números das linhas
    updateLineNumbers();
    
    // Atualiza os números das linhas quando o texto muda
    const customCssTextarea = document.getElementById('customCss');
    if (customCssTextarea) {
        customCssTextarea.addEventListener('input', updateLineNumbers);
        customCssTextarea.addEventListener('scroll', function() {
            const lineNumbers = document.querySelector('.line-numbers');
            if (lineNumbers) {
                lineNumbers.scrollTop = this.scrollTop;
            }
        });
    }
});
</script>

@endsection
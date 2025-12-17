@extends('admin.layouts.app')
@section('content')
<div class="layout-px-spacing" id="contentaff">
    <div class="middle-content container-xxl p-0">
        <div class="page-meta" style="margin-top:45px;">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Personalização</a></li>
                    <li class="breadcrumb-item active" aria-current="page" id="estatde">Configurações do Rodapé</li>
                </ol>
            </nav>
        </div>

        <div class="" style="margin-top: 20px;">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-8">
                    <div class="row">
                        <div class="col-12">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show">
                                    {{ session('success') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Footer Settings Accordion -->
                    <div id="footerAccordion" class="accordion accordion-icons">
                        <!-- Barra de Aviso Superior Section -->
                        <div class="card">
                            <div class="card-header" id="headingTopBar">
                                <section class="mb-0 mt-0">
                                    <div role="menu" class="collapsed" data-bs-toggle="collapse" data-bs-target="#collapseTopBar" aria-expanded="false" aria-controls="collapseTopBar">
                                        <div class="accordion-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-octagon">
                                                <polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon>
                                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                            </svg>
                                        </div>
                                        Barra de Aviso Superior <div class="icons"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg></div>
                                    </div>
                                </section>
                            </div>

                            <div id="collapseTopBar" class="collapse" aria-labelledby="headingTopBar" data-bs-parent="#footerAccordion">
                                <div class="card-body">
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input auto-save-checkbox" id="show_topbar" name="show_topbar" data-field="show_topbar" {{ old('show_topbar', $footerSettings->show_topbar) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="show_topbar">Exibir Barra de Aviso Superior</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6 class="mb-2">Texto do Aviso</h6>
                                                    <p class="text-muted small mb-3">Texto que será exibido na barra de aviso superior</p>
                                                    
                                                    <div class="form-group">
                                                        <input type="text" name="topbar_text" id="topbar_text" class="form-control auto-save @error('topbar_text') is-invalid @enderror" value="{{ old('topbar_text', $footerSettings->topbar_text) }}" data-field="topbar_text">
                                                        @error('topbar_text')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6 class="mb-2">Texto do Botão</h6>
                                                    <p class="text-muted small mb-3">Texto que será exibido no botão da barra de aviso</p>
                                                    
                                                    <div class="form-group">
                                                        <input type="text" name="topbar_button_text" id="topbar_button_text" class="form-control auto-save @error('topbar_button_text') is-invalid @enderror" value="{{ old('topbar_button_text', $footerSettings->topbar_button_text) }}" data-field="topbar_button_text">
                                                        @error('topbar_button_text')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6 class="mb-2">URL do Botão</h6>
                                                    <p class="text-muted small mb-3">URL para onde o botão irá redirecionar</p>
                                                    
                                                    <div class="form-group">
                                                        <input type="text" name="topbar_button_url" id="topbar_button_url" class="form-control auto-save @error('topbar_button_url') is-invalid @enderror" value="{{ old('topbar_button_url', $footerSettings->topbar_button_url) }}" data-field="topbar_button_url">
                                                        @error('topbar_button_url')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="alert alert-info">
                                                <strong>Visualização:</strong>
                                                <div class="topbar-preview mt-2 p-3" style="border-radius: 8px; background-color: var(--background-color, #020b19);">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span style="color: var(--text-top-color, #fff);">{{ $footerSettings->topbar_text }}</span>
                                                        <button class="btn btn-sm btn-primary">{{ $footerSettings->topbar_button_text }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Textos do Rodapé Section -->
                        <div class="card">
                            <div class="card-header" id="headingTextos">
                                <section class="mb-0 mt-0">
                                    <div role="menu" class="" data-bs-toggle="collapse" data-bs-target="#collapseTextos" aria-expanded="true" aria-controls="collapseTextos">
                                        <div class="accordion-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-type">
                                                <polyline points="4 7 4 4 20 4 20 7"></polyline>
                                                <line x1="9" y1="20" x2="15" y2="20"></line>
                                                <line x1="12" y1="4" x2="12" y2="20"></line>
                                            </svg>
                                        </div>
                                        Textos do Rodapé <div class="icons"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg></div>
                                    </div>
                                </section>
                            </div>

                            <div id="collapseTextos" class="collapse show" aria-labelledby="headingTextos" data-bs-parent="#footerAccordion">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6 class="mb-2">Texto Principal (h2)</h6>
                                                    <p class="text-muted small mb-3">Texto de destaque exibido no rodapé do site</p>
                                                    
                                                    <div class="form-group">
                                                        <textarea name="footer_text" id="footer_text" rows="5" class="form-control auto-save @error('footer_text') is-invalid @enderror" data-field="footer_text">{{ old('footer_text', $footerSettings->footer_text) }}</textarea>
                                                        @error('footer_text')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6 class="mb-2">Subtexto (p)</h6>
                                                    <p class="text-muted small mb-3">Texto secundário exibido abaixo do texto principal</p>
                                                    
                                                    <div class="form-group">
                                                        <textarea name="footer_subtext" id="footer_subtext" rows="5" class="form-control auto-save @error('footer_subtext') is-invalid @enderror" data-field="footer_subtext">{{ old('footer_subtext', $footerSettings->footer_subtext) }}</textarea>
                                                        @error('footer_subtext')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mídias Sociais Section -->
                        <div class="card">
                            <div class="card-header" id="headingSocial">
                                <section class="mb-0 mt-0">
                                    <div role="menu" class="collapsed" data-bs-toggle="collapse" data-bs-target="#collapseSocial" aria-expanded="false" aria-controls="collapseSocial">
                                        <div class="accordion-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share-2">
                                                <circle cx="18" cy="5" r="3"></circle>
                                                <circle cx="6" cy="12" r="3"></circle>
                                                <circle cx="18" cy="19" r="3"></circle>
                                                <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
                                                <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
                                            </svg>
                                        </div>
                                        Mídias Sociais <div class="icons"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg></div>
                                    </div>
                                </section>
                            </div>

                            <div id="collapseSocial" class="collapse" aria-labelledby="headingSocial" data-bs-parent="#footerAccordion">
                                <div class="card-body">
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input auto-save-checkbox" id="show_social_links" name="show_social_links" data-field="show_social_links" {{ old('show_social_links', $footerSettings->show_social_links) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="show_social_links">Exibir Mídias Sociais</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6 class="mb-2">Instagram</h6>
                                                    <p class="text-muted small mb-3">Link para a página do Instagram</p>
                                                    
                                                    <div class="form-group">
                                                        <div class="custom-control custom-switch mb-2">
                                                            <input type="checkbox" class="custom-control-input auto-save-checkbox" id="show_instagram" name="show_instagram" data-field="show_instagram" {{ old('show_instagram', $socialLinks->show_instagram) ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="show_instagram">Exibir Instagram</label>
                                                        </div>
                                                        <div class="input-group mb-3">
                                                            <span class="input-group-text">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-instagram">
                                                                    <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                                                    <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                                                    <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                                                                </svg>
                                                            </span>
                                                            <input type="text" name="instagram" id="instagram" class="form-control auto-save @error('instagram') is-invalid @enderror" value="{{ old('instagram', $socialLinks->instagram) }}" data-field="instagram">
                                                        </div>
                                                        @error('instagram')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6 class="mb-2">Facebook</h6>
                                                    <p class="text-muted small mb-3">Link para a página do Facebook</p>
                                                    
                                                    <div class="form-group">
                                                        <div class="custom-control custom-switch mb-2">
                                                            <input type="checkbox" class="custom-control-input auto-save-checkbox" id="show_facebook" name="show_facebook" data-field="show_facebook" {{ old('show_facebook', $socialLinks->show_facebook) ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="show_facebook">Exibir Facebook</label>
                                                        </div>
                                                        <div class="input-group mb-3">
                                                            <span class="input-group-text">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-facebook">
                                                                    <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                                                                </svg>
                                                            </span>
                                                            <input type="text" name="facebook" id="facebook" class="form-control auto-save @error('facebook') is-invalid @enderror" value="{{ old('facebook', $socialLinks->facebook) }}" data-field="facebook">
                                                        </div>
                                                        @error('facebook')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6 class="mb-2">WhatsApp</h6>
                                                    <p class="text-muted small mb-3">Link para contato via WhatsApp</p>
                                                    
                                                    <div class="form-group">
                                                        <div class="custom-control custom-switch mb-2">
                                                            <input type="checkbox" class="custom-control-input auto-save-checkbox" id="show_whatsapp" name="show_whatsapp" data-field="show_whatsapp" {{ old('show_whatsapp', $socialLinks->show_whatsapp) ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="show_whatsapp">Exibir WhatsApp</label>
                                                        </div>
                                                        <div class="input-group mb-3">
                                                            <span class="input-group-text">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle">
                                                                    <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                                                                </svg>
                                                            </span>
                                                            <input type="text" name="whatsapp" id="whatsapp" class="form-control auto-save @error('whatsapp') is-invalid @enderror" value="{{ old('whatsapp', $socialLinks->whatsapp) }}" data-field="whatsapp">
                                                        </div>
                                                        @error('whatsapp')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6 class="mb-2">Telegram</h6>
                                                    <p class="text-muted small mb-3">Link para contato via Telegram</p>
                                                    
                                                    <div class="form-group">
                                                        <div class="custom-control custom-switch mb-2">
                                                            <input type="checkbox" class="custom-control-input auto-save-checkbox" id="show_telegram" name="show_telegram" data-field="show_telegram" {{ old('show_telegram', $socialLinks->show_telegram) ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="show_telegram">Exibir Telegram</label>
                                                        </div>
                                                        <div class="input-group mb-3">
                                                            <span class="input-group-text">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-send">
                                                                    <line x1="22" y1="2" x2="11" y2="13"></line>
                                                                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                                                                </svg>
                                                            </span>
                                                            <input type="text" name="telegram" id="telegram" class="form-control auto-save @error('telegram') is-invalid @enderror" value="{{ old('telegram', $socialLinks->telegram) }}" data-field="telegram">
                                                        </div>
                                                        @error('telegram')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Outros Elementos Section -->
                        <div class="card">
                            <div class="card-header" id="headingOutros">
                                <section class="mb-0 mt-0">
                                    <div role="menu" class="collapsed" data-bs-toggle="collapse" data-bs-target="#collapseOutros" aria-expanded="false" aria-controls="collapseOutros">
                                        <div class="accordion-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-sliders">
                                                <line x1="4" y1="21" x2="4" y2="14"></line>
                                                <line x1="4" y1="10" x2="4" y2="3"></line>
                                                <line x1="12" y1="21" x2="12" y2="12"></line>
                                                <line x1="12" y1="8" x2="12" y2="3"></line>
                                                <line x1="20" y1="21" x2="20" y2="16"></line>
                                                <line x1="20" y1="12" x2="20" y2="3"></line>
                                                <line x1="1" y1="14" x2="7" y2="14"></line>
                                                <line x1="9" y1="8" x2="15" y2="8"></line>
                                                <line x1="17" y1="16" x2="23" y2="16"></line>
                                            </svg>
                                        </div>
                                        Outros Elementos <div class="icons"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg></div>
                                    </div>
                                </section>
                            </div>

                            <div id="collapseOutros" class="collapse" aria-labelledby="headingOutros" data-bs-parent="#footerAccordion">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6 class="mb-2">Botão de Contato</h6>
                                                    <p class="text-muted small mb-3">URL para onde o botão "Fale Conosco" irá redirecionar</p>
                                                    
                                                    <div class="form-group">
                                                        <div class="input-group mb-3">
                                                            <span class="input-group-text">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-link">
                                                                    <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                                                                    <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                                                                </svg>
                                                            </span>
                                                            <input type="text" name="contact_button_url" id="contact_button_url" class="form-control auto-save @error('contact_button_url') is-invalid @enderror" value="{{ old('contact_button_url', $footerSettings->contact_button_url) }}" data-field="contact_button_url">
                                                        </div>
                                                        @error('contact_button_url')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6 class="mb-2">Seção "Autorizado Cassino"</h6>
                                                    <p class="text-muted small mb-3">A seção com informações sobre autorização e licença da plataforma</p>
                                                    
                                                    <div class="form-group">
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" class="custom-control-input auto-save-checkbox" id="show_autorizado_cassino" name="show_autorizado_cassino" data-field="show_autorizado_cassino" {{ old('show_autorizado_cassino', $footerSettings->show_autorizado_cassino) ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="show_autorizado_cassino">Exibir Seção "Autorizado Cassino"</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Links de Suporte Section -->
                        <div class="card">
                            <div class="card-header" id="headingSupport">
                                <section class="mb-0 mt-0">
                                    <div role="menu" class="collapsed" data-bs-toggle="collapse" data-bs-target="#collapseSupport" aria-expanded="false" aria-controls="collapseSupport">
                                        <div class="accordion-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-headphones">
                                                <path d="M3 18v-6a9 9 0 0 1 18 0v6"></path>
                                                <path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"></path>
                                            </svg>
                                        </div>
                                        Links de Suporte <div class="icons"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg></div>
                                    </div>
                                </section>
                            </div>

                            <div id="collapseSupport" class="collapse" aria-labelledby="headingSupport" data-bs-parent="#footerAccordion">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6 class="mb-2">Ouvidoria</h6>
                                                    <p class="text-muted small mb-3">URL para a página de Ouvidoria</p>
                                                    
                                                    <div class="form-group">
                                                        <div class="input-group mb-3">
                                                            <span class="input-group-text">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-link">
                                                                    <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                                                                    <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                                                                </svg>
                                                            </span>
                                                            <input type="text" name="ouvidoria_url" id="ouvidoria_url" class="form-control auto-save @error('ouvidoria_url') is-invalid @enderror" value="{{ old('ouvidoria_url', $footerSettings->ouvidoria_url ?? 'https://ajuda.inove.com') }}" data-field="ouvidoria_url">
                                                        </div>
                                                        @error('ouvidoria_url')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6 class="mb-2">Denúncias</h6>
                                                    <p class="text-muted small mb-3">URL para a página de Denúncias</p>
                                                    
                                                    <div class="form-group">
                                                        <div class="input-group mb-3">
                                                            <span class="input-group-text">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-link">
                                                                    <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                                                                    <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                                                                </svg>
                                                            </span>
                                                            <input type="text" name="denuncias_url" id="denuncias_url" class="form-control auto-save @error('denuncias_url') is-invalid @enderror" value="{{ old('denuncias_url', $footerSettings->denuncias_url ?? 'https://ajuda.inove.com') }}" data-field="denuncias_url">
                                                        </div>
                                                        @error('denuncias_url')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6 class="mb-2">Suporte ao Jogador</h6>
                                                    <p class="text-muted small mb-3">URL para a página de Suporte ao Jogador</p>
                                                    
                                                    <div class="form-group">
                                                        <div class="input-group mb-3">
                                                            <span class="input-group-text">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-link">
                                                                    <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                                                                    <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                                                                </svg>
                                                            </span>
                                                            <input type="text" name="suporte_jogador_url" id="suporte_jogador_url" class="form-control auto-save @error('suporte_jogador_url') is-invalid @enderror" value="{{ old('suporte_jogador_url', $footerSettings->suporte_jogador_url ?? 'https://ajuda.inove.com') }}" data-field="suporte_jogador_url">
                                                        </div>
                                                        @error('suporte_jogador_url')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6 class="mb-2">E-mail de Suporte</h6>
                                                    <p class="text-muted small mb-3">E-mail de atendimento exibido no rodapé</p>
                                                    
                                                    <div class="form-group">
                                                        <div class="input-group mb-3">
                                                            <span class="input-group-text">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail">
                                                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                                                    <polyline points="22,6 12,13 2,6"></polyline>
                                                                </svg>
                                                            </span>
                                                            <input type="email" name="support_email" id="support_email" class="form-control auto-save @error('support_email') is-invalid @enderror" value="{{ old('support_email', $footerSettings->support_email ?? 'atendimento@' . parse_url(URL::to('/'), PHP_URL_HOST)) }}" data-field="support_email">
                                                        </div>
                                                        @error('support_email')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Preview Panel -->
                    <div class="row mt-4 mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Visualização do Rodapé</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div id="footer-preview" class="p-3" style="border-radius: 8px; background-color: var(--background-color, #020b19);">
                                                <div class="text-center mb-4">
                                                    <h4 style="color: var(--text-primary-color, #a3d712);">{{ $footerSettings->footer_text }}</h4>
                                                    <p style="color: var(--text-top-color, #fff);">{{ $footerSettings->footer_subtext }}</p>
                                                </div>
                                                
                                                @if($footerSettings->show_social_links)
                                                <div class="text-center mb-3">
                                                    <div class="d-flex justify-content-center gap-3">
                                                        @if($socialLinks->instagram && $socialLinks->show_instagram)
                                                        <a href="#" class="btn" style="background-color: var(--color-button1, #323637); color: white;">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-instagram">
                                                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                                                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                                                            </svg>
                                                        </a>
                                                        @endif
                                                        
                                                        @if($socialLinks->facebook && $socialLinks->show_facebook)
                                                        <a href="#" class="btn" style="background-color: var(--color-button2, #e29437); color: white;">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-facebook">
                                                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                                                            </svg>
                                                        </a>
                                                        @endif
                                                        
                                                        @if($socialLinks->whatsapp && $socialLinks->show_whatsapp)
                                                        <a href="#" class="btn" style="background-color: var(--color-button3, #007aff); color: white;">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle">
                                                                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                                                            </svg>
                                                        </a>
                                                        @endif
                                                        
                                                        @if($socialLinks->telegram && $socialLinks->show_telegram)
                                                        <a href="#" class="btn" style="background-color: var(--color-button4, #9b2faf); color: white;">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-send">
                                                                <line x1="22" y1="2" x2="11" y2="13"></line>
                                                                <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                                                            </svg>
                                                        </a>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endif
                                                
                                                @if($footerSettings->contact_button_url)
                                                <div class="text-center">
                                                    <a href="#" class="btn" style="background-color: var(--primary-color, #a3d712); color: var(--text-btn-primary, #334402);">Fale Conosco</a>
                                                </div>
                                                @endif
                                                
                                                @if($footerSettings->show_autorizado_cassino)
                                                <div class="mt-4 p-3" style="border-radius: 8px; background-color: var(--background-opacity, #a2d71223);">
                                                    <div class="text-center">
                                                        <p style="color: var(--text-top-color, #fff);">Autorizado Cassino - Plataforma licenciada</p>
                                                    </div>
                                                </div>
                                                @endif
                                                
                                                <!-- Support Links Preview -->
                                                <div class="mt-4 p-3">
                                                    <div class="d-flex flex-wrap justify-content-center gap-3">
                                                        <a href="#" class="btn btn-sm" style="background-color: var(--color-button1, #323637); color: white;">Ouvidoria</a>
                                                        <a href="#" class="btn btn-sm" style="background-color: var(--color-button2, #e29437); color: white;">Denúncias</a>
                                                        <a href="#" class="btn btn-sm" style="background-color: var(--color-button3, #007aff); color: white;">Suporte ao Jogador</a>
                                                    </div>
                                                    <div class="text-center mt-2">
                                                        <p style="color: var(--text-top-color, #fff);">
                                                            <span>Suporte:</span> 
                                                            <a href="#" style="color: var(--primary-color, #a3d712);">
                                                                <span id="preview-support-email">{{ $footerSettings->support_email ?? 'atendimento@' . parse_url(URL::to('/'), PHP_URL_HOST) }}</span>
                                                            </a>
                                                        </p>
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
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializa variável para controlar requisições em andamento
    window.isUpdatingField = false;
    
    // Função para atualizar campo via AJAX
    function updateField(field, value) {
        // Evitar múltiplas chamadas simultâneas
        if (window.isUpdatingField) {
            return;
        }
        
        window.isUpdatingField = true;
        
        // Mostrar indicador de carregamento
        const toast = ToastManager.info('Salvando alterações...');
        
        // Enviar requisição AJAX
        fetch('{{ route('admin.footer-settings.update-field') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                field: field,
                value: value
            })
        })
        .then(response => response.json())
        .then(data => {
            // Remover toast de carregamento
            toast.remove();
            
            if (data.success) {
                // Atualizar visualização do rodapé
                updatePreview();
                // Mostrar mensagem de sucesso
                ToastManager.success('Alterações salvas com sucesso!');
            } else {
                // Mostrar mensagem de erro
                ToastManager.error('Erro ao salvar: ' + data.message);
            }
            
            // Liberar flag para permitir novas requisições
            window.isUpdatingField = false;
        })
        .catch(error => {
            // Remover toast de carregamento
            toast.remove();
            
            console.error('Erro:', error);
            ToastManager.error('Erro ao salvar as alterações.');
            
            // Liberar flag para permitir novas requisições
            window.isUpdatingField = false;
        });
    }
    
    // Função para atualizar a visualização do rodapé
    function updatePreview() {
        // Atualizar o texto principal e subtexto na visualização
        const previewTitle = document.querySelector('#footer-preview h4');
        const previewText = document.querySelector('#footer-preview p');
        
        if (previewTitle) {
            previewTitle.textContent = document.getElementById('footer_text').value;
        }
        
        if (previewText) {
            previewText.textContent = document.getElementById('footer_subtext').value;
        }
        
        // Atualizar visibilidade de mídias sociais
        const socialLinksSection = document.querySelector('#footer-preview .d-flex.justify-content-center');
        if (socialLinksSection) {
            const socialLinksParent = socialLinksSection.closest('div');
            // Verificar se a seção de mídias sociais está ativada
            socialLinksParent.style.display = 
                document.getElementById('show_social_links').checked ? 'block' : 'none';
                
            // Atualizar a visibilidade de cada rede social individualmente
            const socialButtons = socialLinksSection.querySelectorAll('a');
            
            // Mapear cada rede social com seu respectivo checkbox
            const socialNetworks = [
                { element: socialButtons[0], checkbox: 'show_instagram', input: 'instagram' },
                { element: socialButtons[1], checkbox: 'show_facebook', input: 'facebook' },
                { element: socialButtons[2], checkbox: 'show_whatsapp', input: 'whatsapp' },
                { element: socialButtons[3], checkbox: 'show_telegram', input: 'telegram' }
            ];
            
            // Atualizar cada rede social
            socialNetworks.forEach(social => {
                if (social.element) {
                    const isChecked = document.getElementById(social.checkbox).checked;
                    const hasValue = document.getElementById(social.input).value.trim() !== '';
                    social.element.style.display = (isChecked && hasValue) ? 'inline-flex' : 'none';
                }
            });
        }
        
        // Atualizar visibilidade da seção "Autorizado Cassino"
        const autorizadoSection = document.querySelector('#footer-preview .mt-4.p-3');
        if (autorizadoSection) {
            autorizadoSection.style.display = 
                document.getElementById('show_autorizado_cassino').checked ? 'block' : 'none';
        }
        
        // Atualizar o email de suporte
        const supportEmailPreview = document.getElementById('preview-support-email');
        if (supportEmailPreview && document.getElementById('support_email')) {
            supportEmailPreview.textContent = document.getElementById('support_email').value;
        }
    }
    
    // Event listeners para campos de texto (blur = quando o usuário sai do campo)
    document.querySelectorAll('.auto-save').forEach(input => {
        input.addEventListener('blur', function() {
            updateField(this.dataset.field, this.value);
            
            // Adicionar listener para o evento keyup para atualizar a visualização em tempo real
            input.addEventListener('keyup', function() {
                updatePreview();
            });
        });
    });
    
    // Event listeners para checkboxes
    document.querySelectorAll('.auto-save-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateField(this.dataset.field, this.checked ? 1 : 0);
            
            // Atualizar interface imediatamente para feedback visual
            updatePreview();
            
            // Adicionar código para habilitar/desabilitar os campos correspondentes
            if (checkbox.id.startsWith('show_')) {
                const socialInput = document.getElementById(checkbox.id.replace('show_', ''));
                if (socialInput) {
                    socialInput.disabled = !checkbox.checked;
                    
                    // Encontrar o elemento input-group correto
                    const inputGroup = socialInput.closest('.input-group');
                    
                    if (checkbox.checked) {
                        if (inputGroup) inputGroup.classList.remove('disabled-input');
                        socialInput.classList.remove('bg-light');
                        socialInput.style.backgroundColor = '';
                        socialInput.style.opacity = '1';
                    } else {
                        if (inputGroup) inputGroup.classList.add('disabled-input');
                        socialInput.classList.add('bg-light');
                        socialInput.style.backgroundColor = '#e9ecef';
                        socialInput.style.opacity = '0.65';
                    }
                }
            }
        });
    });
    
    // Inicializa os estados dos campos de redes sociais baseado nos checkboxes
    document.querySelectorAll('.auto-save-checkbox').forEach(checkbox => {
        if (checkbox.id.startsWith('show_')) {
            const socialInput = document.getElementById(checkbox.id.replace('show_', ''));
            if (socialInput) {
                socialInput.disabled = !checkbox.checked;
                
                // Encontrar o elemento input-group correto
                const inputGroup = socialInput.closest('.input-group');
                
                // Aplicar os mesmos estilos na inicialização
                if (checkbox.checked) {
                    if (inputGroup) inputGroup.classList.remove('disabled-input');
                    socialInput.classList.remove('bg-light');
                    socialInput.style.backgroundColor = '';
                    socialInput.style.opacity = '1';
                } else {
                    if (inputGroup) inputGroup.classList.add('disabled-input');
                    socialInput.classList.add('bg-light');
                    socialInput.style.backgroundColor = '#e9ecef';
                    socialInput.style.opacity = '0.65';
                }
            }
        }
    });
    
    // Inicializa os tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
    
    // Executa a atualização da visualização inicialmente
    updatePreview();
});
</script>

<style>
.disabled-input .input-group-text {
    background-color: #e9ecef;
    opacity: 0.65;
}
</style>
@endsection 
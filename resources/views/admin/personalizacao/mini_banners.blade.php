@extends('admin.layouts.app')
@section('content')
<div class="layout-px-spacing" id="contentaff">
        <div class="middle-content container-xxl p-0">
        <div class="page-meta" style="margin-top:45px;">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Personalização</a></li>
                <li class="breadcrumb-item active" aria-current="page" id="estatde">Banner</li>
            </ol>
        </nav>
    </div>
    <div class="row" style="margin-top: 20px;">
        <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
            <div id="iconsAccordion" class="accordion-icons accordion">
                <div class="card">
                    <div class="card-header" id="...">
                        <section class="mb-0 mt-0">
                            <div role="menu" class="" data-bs-toggle="collapse" data-bs-target="#iconAccordionOne" aria-expanded="true" aria-controls="iconAccordionOne">
                                <div class="accordion-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layout"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg></div>
                                Mini Banners
                            </div>
                        </section>
                    </div>

                    <div class="card-body" style="color: #888ea8;">
                        <form method="POST" id="settings-register" name="settings-register" action="">
                            @csrf
                            <div class="row">
                                <div class="col" id="form-container-register">
                                    @php
                                        $registerBannersTable = App\Models\Admin\Banners::where('tipo', 'mini')->orderBy('ordem', 'asc')->get();
                                        $registerCountTable = App\Models\Admin\Banners::where('tipo', 'mini')->orderBy('ordem', 'asc')->count();
                                    @endphp
                                    <div id="register-count" class="hidden">{{$registerCountTable}}</div>

                                    @foreach($registerBannersTable as $registro)
                                        <div class="form-group banner-container" draggable="true" data-type="type-1" id="form-group-{{$registro->id}}">
                                            <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected banner-control-group">
                                                <span class="input-group-btn input-group-prepend banner-btn-container">
                                                    <button class="btn btn-dark bootstrap-touchspin-down _effect--ripple waves-effect waves-light btn-icon-banner" style="background-color: rgba(18, 22, 43, 0.8); border-color: rgba(18, 22, 43, 0.8);" type="button">
                                                        <input class="form-check-input" type="checkbox" aria-label="" onclick="DesativaBanner('{{$registro->id}}');" {{($registro->active) ? "checked" : ""}}>
                                                    </button>
                                                </span>

                                                <div id="bg{{$registro->id}}" class="image-background form-control" style="background-image: url({{$registro->imagem}}); background-size: cover; background-repeat: no-repeat; background-position: top center; transition: background-position 1.5s ease; cursor: pointer;" onclick="document.getElementById('ff{{$registro->id}}').click();" onmouseenter="this.style.backgroundPosition = 'bottom center';" onmouseleave="this.style.backgroundPosition = 'top center';">
                                                    <div id="bgl{{$registro->id}}" {!! ($registro->imagem == "") ? null : 'style="display: none;"' !!}></div>
                                                    <input id="ff{{$registro->id}}" type="file" style="display: none;" onchange="OnChangeInput('{{$registro->id}}', 'ff{{$registro->id}}');" class="form-control">
                                                </div>

                                                <span class="input-group-addon input-group-append btn btn-mobile-type btn-icon-banner banner-btn-container">
                                                    <button class="btn btn-mobile-type btn-icon-banner" type="button" title="{{$registro->mobile == 'sim' ? 'Mobile' : 'Desktop'}}">
                                                    <i class="fa {{$registro->mobile == 'sim' ? 'fa-mobile' : 'fa-desktop'}}" aria-hidden="true"></i>
                                                    </button>
                                                </span>

                                                <span class="input-group-addon input-group-append btn btn-info btn-icon-banner banner-btn-container" style="background-color: rgba(33, 150, 243, 0.7); border-color: rgba(33, 150, 243, 0.7);">
                                                    <button class="btn btn-info btn-icon-banner" style="background-color: transparent; border: none;" type="button">
                                                    <i class="fa fa-arrows" aria-hidden="true"></i>
                                                    </button>
                                                </span>
                                                <span class="input-group-btn input-group-append banner-btn-container">
                                                    <button class="btn btn-danger bootstrap-touchspin-up _effect--ripple waves-effect waves-light btn-icon-banner" style="background-color: rgba(220, 53, 69, 0.7); border-color: rgba(220, 53, 69, 0.7);" type="button" onclick="RemoveBanner('{{$registro->id}}');">
                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                    </button>
                                                </span>
                                            </div>

                                            <div id="progressContainer{{$registro->id}}" class="progress br-30 progress-sm" style="border-radius: 6px !important; height: 20px; margin-top: -14px; {!! ($registro->imagem == "") ? null : 'display: none;' !!}">
                                                <div id="progressBar{{$registro->id}}" class="progress-bar bg-danger" role="progressbar" style="width: 0%; border-radius: 0 !important;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <br>
                                        </div>

                                    @endforeach
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 
<style>
.btn-mobile-type {
    background-color: transparent !important;
    border: none !important;
    padding: 0.3rem 0.5rem !important;
    min-width: 30px !important;
}

.btn-mobile-type i {
    font-size: 14px !important;
}

.btn-mobile-type i.fa-mobile {
    color: #ff9800 !important;
}

.btn-mobile-type i.fa-desktop {
    color: #0275d8 !important;
}

.banner-btn-container {
    display: flex;
    align-items: center;
    justify-content: center;
}
.input-group-addon.input-group-append.btn.btn-mobile-type.btn-icon-banner.banner-btn-container{
    background: #0f1427 !important;
}
</style> 
@endsection
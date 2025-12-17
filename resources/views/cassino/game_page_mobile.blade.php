@extends('layouts.app')

@section('content')
<div data-v-bba270d3="" data-v-9dae45d3="" class="gamesBar flex flex-col items-center w-full">
    @include('payment.deposit-modal')
    <div data-v-bba270d3="" class="menu flex flex-row items-center justify-between w-full h-10 px-2 border-bg-primary/60 border-b border-solid">
        <div data-v-bba270d3="" class="burger justify-start w-1/3" onclick="goBack();">
            <span data-v-bba270d3="" class="nuxt-icon nuxt-icon--fill menuBtn home">
                <svg height="1em" viewBox="0 0 320 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                    <path d="M41.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.3 256 246.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z" fill="currentColor"></path>
                </svg>
            </span>
        </div>
        <div data-v-bba270d3="" class="l6oz0 v1a-c justify-center logo w-1/3 px-4 py-0.5">
            <a aria-label="{{ config('app.name') }}" class="bwSJI v1a-c" href="{{ getCassinoUrl() }}">
                @php
                   $settings = \App\Models\Setting::first();
                @endphp
                <img alt="{{ \App\Models\Setting::first()->name ?? config('app.name') }}" class="j2x6J" src="{{ asset($settings->logo) }}" />
            </a>
        </div>
        <div data-v-bba270d3="" class="deposit flex justify-end w-1/3">
            <a data-v-bba270d3="" href="{{ route('user.wallet') }}" class="btn btn-deposit" type="button" id="deposit-btn">{{ __('game-page.deposit') }}</a>
            </div>
    </div>
</div>
<section data-v-1d35be9f="" id="casino">
    <section data-v-9dae45d3="" data-v-1d35be9f="" id="frame_game" class="fullScreenOn"> 
       <iframe data-v-9dae45d3="" id="gameIframe" src="{{$gameURL}}" allow="" frameborder="0"></iframe>
    </section>
</section>

<style>
    /* Estilos específicos para a página de jogo mobile */
    #casino {
        width: 100%;
        min-height: 100vh;
    }

   
    
    #frame_game {
        width: 100%;
        height: calc(100vh - 50px);
        position: relative;
    }
    
    #gameIframe {
        width: 100%;
        height: 100%;
        border: none;
    }
    #divPageHeaderWrapper{
        display:none!important;
    }
    #divMobileMenu{
        display:none!important;
    }
    .gamesBar .menu {
        background: var(--bg-primary);
        border-bottom: 1px solid var(--border-color);
    }
    
    .menuBtn {
        cursor: pointer;
        padding: 0.5rem;
    }
    
    .menuBtn:hover {
        opacity: 0.7;
    }
    
    .btn-deposit {
        font-size: 0.8rem;
        padding: 0.4rem 0.8rem;
    }
    .gamesBar .menu .btn-deposit[data-v-bba270d3] {
    line-height: 0.6rem;
    }
</style>

<script>
function goBack() {
    window.location.href = "{{ getCassinoUrl() }}";
}
</script>
@endsection 
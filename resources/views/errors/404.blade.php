@extends('layouts.app')
@section('content')
<div>
    <div class="Wy7HC">
        <img alt="inove" src="{{ completeImageUrl('img/404.webp') }}" />
        <div class="eDIWU">
            <h2>404</h2>
            <strong>{{ __('validation.page-not-found') }}</strong><small>{{ __('validation.page-not-found-description') }}</small>
        </div>
        <div class="yGNHK"><a href="{{ url('/') }}" class="btn-primary">{{ __('validation.back-home') }}</a></div>
    </div>
</div>
@endsection
<style>
    .F2Y1D{
        display: none!important;
    }
</style>
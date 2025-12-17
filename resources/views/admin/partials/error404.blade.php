@extends('admin.layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 mr-auto mt-5 text-md-left text-center">
                <a href="{{ route('admin.dash') }}" class="ml-md-5">
                <img src="{{ asset(\App\Models\Settings::first()->favicon) }}" alt="logo" />
                </a>
            </div>
        </div>
    </div>
    <div class="container-fluid error-content">
        <div class="">
            <h1 class="error-number">404</h1>
            <p class="mini-text">Ooops!</p>
            <p class="error-text mb-5 mt-1">A página que você requisitou não foi encontrada!</p>
            <img src="{{ asset('assets/img/error.svg') }}" alt="cork-admin-404" class="error-img">
            <a href="{{ route('admin.dash') }}" class="btn btn-dark mt-5">Voltar</a>
        </div>
    </div>    
@endsection
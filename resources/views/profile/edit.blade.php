{{-- resources/views/profile/edit.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="garden-profile-wrapper py-5" style="background-color: #f3f6f2; min-height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                
                {{-- Navigasi Atas --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none" style="color: #6b7c65;">Home</a></li>
                            <li class="breadcrumb-item active fw-bold" style="color: #2d5a27;" aria-current="page">Profil Saya</li>
                        </ol>
                    </nav>
                    
                    <a href="{{ url('/') }}" class="btn btn-outline-garden rounded-pill px-4 btn-sm fw-bold shadow-sm">
                        <i class="bi bi-house-door me-2"></i>Ke Beranda
                    </a>
                </div>

                <h2 class="fw-bold mb-4" style="color: #1e351b;">Pengaturan Akun</h2>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- 1. Avatar Information --}}
                <div class="card mb-4 border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white fw-bold py-3 border-bottom" style="color: #2d5a27;">
                        <i class="bi bi-person-circle me-2"></i>Foto Profil
                    </div>
                    <div class="card-body">
                        @include('profile.partials.update-avatar-form')
                    </div>
                </div>

                {{-- 2. Profile Information --}}
                <div class="card mb-4 border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white fw-bold py-3 border-bottom" style="color: #2d5a27;">
                        <i class="bi bi-info-circle me-2"></i>Informasi Profil
                    </div>
                    <div class="card-body">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                {{-- 3. Update Password --}}
                <div class="card mb-4 border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white fw-bold py-3 border-bottom" style="color: #2d5a27;">
                        <i class="bi bi-shield-lock me-2"></i>Update Password
                    </div>
                    <div class="card-body">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                {{-- 4. Connected Accounts --}}
                <div class="card mb-4 border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white fw-bold py-3 border-bottom" style="color: #2d5a27;">
                        <i class="bi bi-google me-2"></i>Akun Terhubung
                    </div>
                    <div class="card-body">
                        @include('profile.partials.connected-accounts')
                    </div>
                </div>

                {{-- 5. Delete Account --}}
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
                    <div class="card-header bg-danger text-white fw-bold py-3">
                        <i class="bi bi-exclamation-triangle me-2"></i>Zona Bahaya
                    </div>
                    <div class="card-body bg-light-danger">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    .btn-outline-garden {
        color: #2d5a27;
        border: 2px solid #2d5a27;
        transition: all 0.3s ease;
    }
    .btn-outline-garden:hover {
        background-color: #2d5a27;
        color: white;
        transform: translateY(-2px);
    }
    .bg-light-danger {
        background-color: #fff5f5;
    }
    .card {
        transition: transform 0.2s ease;
    }
    .breadcrumb-item + .breadcrumb-item::before {
        content: "›";
        font-size: 1.2rem;
        line-height: 1;
        vertical-align: middle;
    }
</style>
@endsection
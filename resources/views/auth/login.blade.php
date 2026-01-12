@extends('layouts.app')

@section('content')
<style>
    /* Custom CSS untuk mempercantik tampilan login */
    body {
        background-color: #f0f4f8; /* Background lebih soft */
    }

    .login-container {
        margin-top: 5rem;
        margin-bottom: 5rem;
    }

    .card {
        border: none;
        border-radius: 20px; /* Lebih membulat agar modern */
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .card-header {
        padding: 2.5rem !important;
        /* Gradasi Hijau Emerald Modern */
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important;
        border: none;
    }

    .card-header h4 {
        font-weight: 800;
        letter-spacing: 0.5px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .card-body {
        padding: 3rem 2.5rem !important;
    }

    .form-label {
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .form-control {
        padding: 0.8rem 1.2rem;
        border-radius: 12px;
        border: 2px solid #edf2f7; /* Border sedikit lebih tebal */
        background-color: #f8fafc;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        box-shadow: 0 0 0 4px rgba(56, 239, 125, 0.15);
        border-color: #11998e;
        background-color: #fff;
    }

    /* Tombol Masuk Utama */
    .btn-login {
        padding: 0.9rem;
        font-weight: 700;
        border-radius: 12px;
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        border: none;
        color: white;
        transition: all 0.3s ease;
    }

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(17, 153, 142, 0.3);
        filter: brightness(1.05);
    }

    /* Tombol Google yang Lebih Menarik */
    .btn-outline-secondary {
        border-radius: 12px;
        padding: 0.8rem;
        font-weight: 700;
        border: 2px solid #e2e8f0;
        background: white;
        color: #4a5568;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        position: relative;
    }

    .btn-outline-secondary:hover {
        background: #ffffff;
        border-color: #11998e;
        color: #11998e;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transform: translateY(-1px);
    }

    .btn-outline-secondary img {
        transition: transform 0.3s ease;
    }

    .btn-outline-secondary:hover img {
        transform: scale(1.1) rotate(5deg);
    }

    .divider {
        display: flex;
        align-items: center;
        text-align: center;
        margin: 2rem 0;
        color: #a0aec0;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .divider::before, .divider::after {
        content: '';
        flex: 1;
        border-bottom: 2px solid #f1f5f9;
    }

    .divider:not(:empty)::before { margin-right: 1em; }
    .divider:not(:empty)::after { margin-left: 1em; }

    .forgot-link {
        font-size: 0.85rem;
        color: #718096;
        font-weight: 600;
        text-decoration: none;
    }

    .forgot-link:hover {
        color: #11998e;
    }

    .register-link {
        color: #11998e;
        text-decoration: none;
    }

    .register-link:hover {
        text-decoration: underline;
    }

    .form-check-input:checked {
        background-color: #11998e;
        border-color: #11998e;
    }
</style>

<div class="container login-container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg">
                {{-- Header dengan Gradasi Hijau --}}
                <div class="card-header text-white text-center">
                    <h4 class="mb-0">🔐 Masuk Akun</h4>
                    <p class="mb-0 opacity-75 mt-2" style="font-size: 0.9rem;">Selamat datang kembali! Silakan login.</p>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        {{-- Email Field --}}
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input id="email" type="email" 
                                class="form-control @error('email') is-invalid @enderror" 
                                name="email" value="{{ old('email') }}" 
                                required autocomplete="email" autofocus 
                                placeholder="nama@email.com">
                            
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        {{-- Password Field --}}
                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <input id="password" type="password" 
                                class="form-control @error('password') is-invalid @enderror" 
                                name="password" required 
                                autocomplete="current-password" 
                                placeholder="••••••••">

                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        {{-- Remember Me & Forgot Password --}}
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label text-secondary" for="remember" style="font-size: 0.85rem;">
                                    Ingat Saya
                                </label>
                            </div>
                            @if (Route::has('password.request'))
                                <a class="forgot-link" href="{{ route('password.request') }}">
                                    Lupa Password?
                                </a>
                            @endif
                        </div>

                        {{-- Submit Button --}}
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-login">
                                Masuk Sekarang
                            </button>
                        </div>

                        <div class="divider">ATAU</div>

                        {{-- Social Login (Google) - Dibuat Lebih Menarik --}}
                         <div class="d-grid gap-2">

              <a href="{{route('auth.google')}}" class="btn btn-outline-secondary">
                <img
                  src="https://www.svgrepo.com/show/475656/google-color.svg" width="22"class="me-3" alt="Google Logo"/>
                <span>Lanjutkan dengan Google</span>
              </a>
            </div>
                        {{-- Register Link --}}
                        <p class="mt-5 text-center text-secondary mb-0" style="font-size: 0.9rem;">
                            Belum punya akun? 
                            <a href="{{ route('register') }}" class="register-link fw-bold">
                                Daftar Sekarang
                            </a>
                        </p>
                    </form>
                </div>
            </div>
            
            <div class="text-center mt-4 text-muted" style="font-size: 0.8rem;">
                &copy; {{ date('Y') }} Aplikasi Anda. All rights reserved.
            </div>
        </div>
    </div>
</div>
@endsection
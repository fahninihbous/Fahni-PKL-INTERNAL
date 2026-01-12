@extends('layouts.app')

@section('content')
<style>
    /* CSS Tema Hijau Emerald Modern Tanpa Foto */
    body {
        background-color: #f0f4f8;
        font-family: 'Poppins', sans-serif;
    }

    .register-wrapper {
        display: flex;
        background: #fff;
        border-radius: 30px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        overflow: hidden;
        max-width: 1000px;
        margin: 60px auto;
        min-height: 550px;
    }

    /* SISI KIRI: FORM */
    .form-side {
        flex: 1.2;
        padding: 50px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .form-control {
        background-color: #f3f3f3;
        border: 2px solid transparent;
        padding: 12px 15px;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        background-color: #fff;
        border-color: #11998e;
        box-shadow: 0 0 0 4px rgba(17, 153, 142, 0.1);
    }

    .btn-submit {
        border-radius: 25px;
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
        font-weight: bold;
        padding: 12px 50px;
        border: none;
        text-transform: uppercase;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(17, 153, 142, 0.3);
    }

    /* SISI KANAN: PANEL INFO HIJAU (Tanpa Foto) */
    .info-side {
        flex: 0.8;
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        padding: 40px;
        border-radius: 150px 0 0 150px; /* Lengkungan ikonik */
    }

    .info-side h2 { 
        font-weight: 800; 
        font-size: 2.2rem; 
        margin-bottom: 20px; 
    }

    .info-side p { 
        opacity: 0.9; 
        margin-bottom: 30px; 
        font-size: 0.95rem;
        line-height: 1.6;
    }

    .btn-outline-white {
        background-color: transparent;
        border: 2px solid white;
        color: white;
        border-radius: 25px;
        padding: 10px 40px;
        text-decoration: none;
        font-weight: bold;
        transition: all 0.3s ease;
    }

    .btn-outline-white:hover {
        background: white;
        color: #11998e;
    }

    /* Responsif untuk layar kecil */
    @media (max-width: 768px) {
        .register-wrapper { flex-direction: column; margin: 20px; }
        .info-side { border-radius: 0; order: -1; padding: 50px 20px; }
    }
</style>

<div class="container">
    <div class="register-wrapper">
        <div class="form-side">
            <h2 class="text-center fw-bold mb-4" style="color: #2d3748;">Buat Akun Baru</h2>
            
            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label d-none">Name</label>
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Nama Lengkap">
                    @error('name')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label d-none">Email</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Alamat Email">
                    @error('email')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label d-none">Password</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Password">
                    @error('password')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label d-none">Confirm Password</label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Konfirmasi Password">
                </div>

                <div class="text-center">
                    <button type="submit" class="btn-submit">Sign Up</button>
                </div>
            </form>
        </div> <div class="info-side">
            <h2>Halo, Teman!</h2>
            <p>Sudah punya akun? Masuk sekarang untuk melihat koleksi terbaru kami.</p>
            <a href="{{ route('login') }}" class="btn-outline-white">LOG IN</a>
        </div>
    </div>
 </div> @endsection
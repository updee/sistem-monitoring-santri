@extends('layouts.auth')
@section('title', 'Login')

@section('content')
<div class="auth-card">

    <div class="auth-left">
        <div class="auth-logo-box">AS</div>
        <div class="auth-school-name">Sistem Monitoring<br>Santri</div>
        <div class="auth-school-sub">
            Silakan masuk untuk mengakses dashboard sesuai role Anda (Admin, Ustadz, atau Wali Santri).
        </div>
        <div class="auth-motto">
            "Pemantauan aktif adalah bagian dari amanah pendidikan"
        </div>
        <div class="auth-accent-bars">
            <div class="auth-bar active" style="flex:2;"></div>
            <div class="auth-bar" style="flex:1;"></div>
            <div class="auth-bar" style="flex:1;"></div>
        </div>
    </div>

    <div class="auth-right">
        <div class="auth-form-title">Masuk</div>
        <div class="auth-form-sub">Gunakan email & password yang terdaftar</div>

        @if (session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert-error">
                <ul style="margin:0;padding-left:16px;">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group-auth">
                <label class="form-label-custom">Email <span style="color:#e53935;">*</span></label>
                <input type="email" name="email"
                       class="form-input-custom {{ $errors->has('email') ? 'is-invalid' : '' }}"
                       value="{{ old('email') }}"
                       placeholder="email@contoh.com" required autofocus>
            </div>

            <div class="form-group-auth">
                <label class="form-label-custom">Password <span style="color:#e53935;">*</span></label>
                <input type="password" name="password"
                       class="form-input-custom {{ $errors->has('password') ? 'is-invalid' : '' }}"
                       placeholder="Masukkan password" required>
            </div>

            <div style="display:flex;align-items:center;justify-content:space-between;margin: 4px 0 18px;">
                <label style="display:flex;gap:8px;align-items:center;font-size:12px;color:var(--txt2);">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    Ingat saya
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="auth-footer-link" style="font-size:12px;">
                        Lupa password?
                    </a>
                @endif
            </div>

            <button type="submit" class="btn-auth">Masuk</button>

            <div class="auth-divider">atau</div>
            <div style="text-align:center;font-size:13px;color:var(--txt3);">
                Wali Santri belum punya akun?
                <a href="{{ route('register') }}" class="auth-footer-link">Daftar di sini</a>
            </div>
        </form>
    </div>
</div>
@endsection

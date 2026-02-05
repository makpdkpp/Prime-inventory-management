@extends('layouts.auth')

@section('title', 'เข้าสู่ระบบ')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header text-center">
        <a href="/" class="h1"><b>Inventory</b></a>
    </div>
    <div class="card-body">
        <p class="login-box-msg">เข้าสู่ระบบเพื่อใช้งาน</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="input-group mb-3">
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                       placeholder="อีเมล" value="{{ old('email') }}" required autofocus>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            
            <div class="input-group mb-3">
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                       placeholder="รหัสผ่าน" required>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            
            <div class="row">
                <div class="col-8">
                    <div class="icheck-primary">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember">จดจำการเข้าสู่ระบบ</label>
                    </div>
                </div>
                <div class="col-4">
                    <button type="submit" class="btn btn-primary btn-block">เข้าสู่ระบบ</button>
                </div>
            </div>
        </form>

        @if (Route::has('password.request'))
        <p class="mb-1">
            <a href="{{ route('password.request') }}">ลืมรหัสผ่าน?</a>
        </p>
        @endif
        
        @if (Route::has('register'))
        <p class="mb-0">
            <a href="{{ route('register') }}" class="text-center">สมัครสมาชิกใหม่</a>
        </p>
        @endif
    </div>
</div>
@endsection

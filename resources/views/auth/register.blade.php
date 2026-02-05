@extends('layouts.auth')

@section('title', 'สมัครสมาชิก')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header text-center">
        <a href="/" class="h1"><b>Inventory</b></a>
    </div>
    <div class="card-body">
        <p class="login-box-msg">สมัครสมาชิกใหม่</p>

        <form method="POST" action="{{ route('register') }}">
            @csrf
            
            <div class="input-group mb-3">
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                       placeholder="ชื่อ-นามสกุล" value="{{ old('name') }}" required autofocus>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-user"></span>
                    </div>
                </div>
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            
            <div class="input-group mb-3">
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                       placeholder="อีเมล" value="{{ old('email') }}" required>
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
            
            <div class="input-group mb-3">
                <input type="password" name="password_confirmation" class="form-control" 
                       placeholder="ยืนยันรหัสผ่าน" required>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-8">
                    <a href="{{ route('login') }}" class="text-center">มีบัญชีอยู่แล้ว? เข้าสู่ระบบ</a>
                </div>
                <div class="col-4">
                    <button type="submit" class="btn btn-primary btn-block">สมัครสมาชิก</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

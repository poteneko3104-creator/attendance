@extends('layouts.app')
@section('title')
<title>ログイン</title>
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('css/login.css')}}">
@endsection

@section('content')    
    
    <div class="main-container">
        <div class="login-box">
        <h1 class="page-title">ログイン</h1>
        
        <form action="/login" method="POST" class="login-form">
            @csrf
            <!-- メールアドレス -->
            <div class="form-group">
            <label for="email" class="form-label">メールアドレス</label>
            <input type="email" id="email" name="email" class="form-input" required>
            </div>
            @if($errors->any('email'))
                <span class="alert-text">{{$errors->first('email')}}</span>
            @endif

            <!-- パスワード -->
            <div class="form-group">
            <label for="password" class="form-label">パスワード</label>
            <input type="password" id="password" name="password" class="form-input" required>
            </div>
            @if($errors->any('password'))
                <span class="alert-text">{{$errors->first('password')}}</span>
            @endif

            <!-- ログインボタン -->
            <button type="submit" class="submit-btn">ログインする</button>
        </form>

        <!-- リンク -->
        <div class="register-link-wrap">
            <a href="/register" class="register-link">会員登録はこちら</a>
        </div>
        </div>
    </div>
    



    @endsection
@extends('layouts.app')
@section('title')
<title>会員登録</title>
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('css/register.css')}}">
@endsection

@section('content')
<div class="container">
    <h1 class="form-title">会員登録</h1>

        <form action="/register" method="POST" class="register-form" novalidate>
            @csrf
            <div class="form-group">
                <label for="name">名前</label>
                <input type="text" id="name" name="name" required>
            </div>
            @if($errors->any('name'))
                <span class="alert-text">{{$errors->first('name')}}</span>
            @endif


            <div class="form-group">
                <label for="email">メールアドレス</label>
                <input type="email" id="email" name="email" required>
            </div>
            @if($errors->any('email'))
                <span class="alert-text">{{$errors->first('email')}}</span>
            @endif

            <div class="form-group">
                <label for="password">パスワード</label>
                <input type="password" id="password" name="password" required>
            </div>
            @if($errors->any('password'))
                <span class="alert-text">{{$errors->first('password')}}</span>
            @endif

            <div class="form-group">
                <label for="password_confirmation">パスワード確認</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

            <button type="submit" class="submit-btn">登録する</button>
        </form>

        <div class="login-redirect">
            <a href="/login">ログインはこちら</a>
        </div>
</div>
@endsection
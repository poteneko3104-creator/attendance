@extends('layouts.app')
@section('title')
  <title>管理者ログイン</title>
@endsection

@section('css')
  <link rel="stylesheet" href="{{asset('css/admin_login.css')}}">
@endsection

@section('content')
  <div class="login-container">
    <h1 class="login-title">管理者ログイン</h1>

    <form action="/admin/login" method="POST" class="login-form">
      @csrf
      <div class="form-group">
        <label for="email" class="form-label">メールアドレス</label>
        <input type="email" id="email" name="email" class="form-input" value="{{ old('email') }}" required>
      </div>
      @if($errors->any('email'))
        <span class="alert-text">{{$errors->first('email')}}</span>
      @endif
      <div class="form-group">
        <label for="password" class="form-label">パスワード</label>
        <input type="password" id="password" name="password" class="form-input" required>
      </div>
      @if($errors->any('password'))
        <span class="alert-text">{{$errors->first('password')}}</span>
      @endif
      <button type="submit" class="submit-button">管理者ログインする</button>
    </form>
  </div>
@endsection
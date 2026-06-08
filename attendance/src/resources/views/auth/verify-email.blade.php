@extends('layouts.app')
@section('title')
<title>メール認証誘導</title>
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('css/verify-email.css')}}">
@endsection

@section('content')
<div class="main-container">
   <div class="content-box">
      <p class="message-text">
        登録していただいたメールアドレスに認証メールを送付しました。<br>
        メール認証を完了してください。
      </p>
      
      <button class="auth-button">認証はこちらから</button>

    <form class="resend-form" method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="resend-link">認証メールを再送する</button>
    </form>

    </div> 
</div>
@endsection
@extends('layouts.app')
@section('title')
    <title>申請一覧</title>
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('css/application_list.css')}}">
@endsection

@section('content')
<div class="container">
    <h1 class="page-title">申請一覧</h1>

    <div class="tabs">
        <a href="{{route('application_list',['tab'=>''])}}"class="tab-btn">承認待ち</a>
        <a href="{{route('application_list',['tab'=>''])}}"class="tab-btn">承認済み</a>
    </div>
    <div class="table-wrapper">
        <table class="application-table">
            <thead>
                <tr>
                    <th>状態</th>
                    <th>名前</th>
                    <th>対象日時</th>
                    <th>申請理由</th>
                    <th>申請日時</th>
                    <th>詳細</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>承認待ち</th>
                    <th>西れいな</th>
                    <th>2023/06/02</th>
                    <th>遅延の</th>
                    <th>2023/06/02</th>
                    <th><a href="" class="detail-link">詳細</a></th>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
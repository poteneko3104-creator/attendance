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
            <a href="{{route('application_list', ['tab' => 'pending'])}}"
                class="tab-btn {{ request('tab') !== 'approved' ? 'active' : '' }}">承認待ち</a>
            <a href="{{route('application_list', ['tab' => 'approved'])}}"
                class="tab-btn {{ request('tab') === 'approved' ? 'active' : '' }}">承認済み</a>
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
                @foreach($lists as $list)
                    <tbody>
                        <tr>
                            @if($list->status == 2)
                                <th>承認待ち</th>
                            @elseif($list->status == 1)
                                <th>承認済み</th>
                            @endif
                            <th>{{$list->user->name}}</th>
                            <th>{{\Carbon\Carbon::parse($list->date->date)->format('Y/m/d')}}</th>
                            <th>{{$list->date->remarks}}</th>
                            <th>{{\Carbon\Carbon::parse($list->approved_date)->format('Y/m/d')}}</th>
                            <th><a href="{{ route('attendance-detail', ['date' => $list->date->date]) }}"
                                    class="detail-link">詳細</a></th>
                        </tr>
                    </tbody>
                @endforeach
            </table>
        </div>
    </div>
@endsection
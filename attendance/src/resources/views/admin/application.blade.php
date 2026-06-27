@extends('admin.layouts.app')
@section('title')
    <title>申請一覧画面</title>
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('css/admin_application.css')}}">
@endsection

@section('content')
    <div class="container">
        <h1 class="page-title">申請一覧</h1>

        <!-- タブ切り替え -->
        <ul class="tab-menu">
            <li class="tab-item {{ request('tab') !== 'approved' ? 'active' : '' }}">
                <a href="{{ route('admin_application-list', ['tab' => 'pending']) }}">
                    承認待ち
                </a>
            </li>
            <li class="tab-item {{ request('tab') === 'approved' ? 'active' : '' }}">
                <a href="{{ route('admin_application-list', ['tab' => 'approved']) }}">
                    承認済み
                </a>
            </li>
        </ul>

        <!-- テーブルカード -->
        <div class="table-wrapper">
            <table class="data-table">
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
                    @foreach ($application_items as $data)
                        <tr>
                            @if($data->status == 2)
                                <td>承認待ち</td>
                            @elseif($data->status == 1)
                                <td>承認済み</td>
                            @endif
                            <td>{{ $data->user->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($data->date->date)->format('Y/m/d') }}</td>
                            <td>{{ $data->date->remarks }}</td>
                            <td>{{ \Carbon\Carbon::parse($data->application_date)->format('Y/m/d') }}</td>
                            <td><a href="{{ route('admin_approval', ['date_id' => $data->date->id]) }}"
                                    class="link-detail">詳細</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
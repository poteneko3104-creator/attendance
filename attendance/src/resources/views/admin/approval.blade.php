@extends('admin.layouts.app')
@section('title')
    <title>申請一覧画面</title>
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('css/admin_approval.css')}}">
@endsection

@section('content')
    <div class="container">
        <h1 class="page-title">勤怠詳細</h1>

        <!-- 詳細カード -->
        <table class="detail-card">
            <tbody>
                <tr>
                    <th>名前</th>
                    <td>{{ $report->user->name }}</td>
                </tr>
                <tr>
                    <th>日付</th>
                    <td>{{ \Carbon\Carbon::parse($report->date)->format('Y年m月d日') }}</td>
                </tr>
                @foreach ($report->attendance as $item){
                    @if($item->status == 2)
                    <tr>
                        @if($item->category = '出勤'){
                            <th>出勤・退勤</th>
                            }
                        @elseif($item->category = '休憩'){
                            <th>休憩</th>
                        }@endif
                        <td>{{ \Carbon\Carbon::parse($item->start_time)->format('H:i') }}<span class="time-separator">〜</span>{{ \Carbon\Carbon::parse($item->start_time)->format('H:i') }}</td>
                    </tr>
                    }
                    @endif
                @endforeach
                <tr>
                    <th>備考</th>
                    <td>{{ $report->remarks }}</td>
                </tr>
            </tbody>
        </table>

        <!-- 承認アクションボタン -->
        <div class="actions-area">
            <form action="#" method="POST">
                @csrf
                <button type="submit" class="btn-approve">承認</button>
            </form>
        </div>
    </div>
@endsection
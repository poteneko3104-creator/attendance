@extends('admin.layouts.app')
@section('title')
    <title>修正申請承認</title>
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('css/admin_approval.css')}}">
@endsection

@section('content')
    <div class="container">
        <h1 class="page-title">勤怠詳細</h1>

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

                @foreach ($report->attendance as $item)
                    <tr>
                        @if($item->category == '出勤')
                            <th>出勤・退勤</th>

                        @elseif($item->category == '休憩')
                            <th>休憩</th>
                        @endif
                        <td>
                            {{ \Carbon\Carbon::parse($item->start_time)->format('H:i') }}
                            <span class="time-separator">〜</span>
                            {{ \Carbon\Carbon::parse($item->end_time)->format('H:i') }}
                        </td>
                    </tr>

                @endforeach
                <tr>
                    <th>備考</th>
                    <td>{{ $report->remarks }}</td>
                </tr>
            </tbody>
        </table>

        <div class="actions-area">
            <form action="#" method="POST">
                @csrf
                <input type="hidden" name="date_id" value="{{ $report->id }}">
                @if($report->application == 2)
                    <button type="submit" class="btn-approve">承認</button>
                @elseif($report->application == 1)
                    <div class="btn-nonactive">承認済み</div>
                @endif
            </form>
        </div>
    </div>
@endsection
@extends('layouts.app')
@section('title')
    <title>勤怠一覧</title>
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('css/list.css')}}">
@endsection

@section('content')
<div class="container">
     <!-- タイトルエリア -->
    <div class="page-title">
      <h1>勤怠一覧</h1>
    </div>

    <!-- カレンダーナビゲーション -->
    <div class="calendar-nav">
      <a href="{{ route('attendance-list', ['month' => $prevMonth]) }}" class="nav-btn">&larr; 前月</a>
      <div class="current-month">
        <span class="calendar-icon">📅</span> {{ $currentMonth->format('Y/m') }}
      </div>
      <a href="{{ route('attendance-list', ['month' => $nextMonth]) }}" class="nav-btn">翌月 &rarr;</a>
    </div>

    <!-- 勤怠テーブル -->
    <div class="table-wrapper">
      <table class="attendance-table">
        <thead>
          <tr>
            <th>日付</th>
            <th>出勤</th>
            <th>退勤</th>
            <th>休憩</th>
            <th>合計</th>
            <th>詳細</th>
          </tr>
        </thead>
        <tbody>
        @foreach($dailyReports as $report)
            <tr>
                <td>{{ $report['date'] }}{{ $report['day_of_week'] }}</td>
                <td>{{ $report['clock_in'] }}</td>
                <td>{{ $report['clock_out'] }}</td>
                <td>{{ $report['rest_time'] }}</td>
                <td>{{ $report['total_time'] }}</td>
                <td><a href="{{ route('attendance-detail', ['date' => $report['date']]) }}">詳細</a></td>
            </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  
</div>
@endsection
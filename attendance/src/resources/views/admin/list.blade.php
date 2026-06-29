@extends('admin.layouts.app')
@section('title')
  <title>勤怠一覧</title>
@endsection

@section('css')
  <link rel="stylesheet" href="{{asset('css/list.css')}}">
@endsection

@section('content')
  <div class="container">

    <div class="page-title">
      <h1>{{$dateObject->format('Y年m月d日')}}</h1>
    </div>

    <div class="calendar-nav">
      <a href="{{ route('admin_attendance-list', ['date' => $prevDate]) }}" class="nav-btn">&larr; 前日</a>
      <div class="current-month">
        <span class="calendar-icon">📅</span> {{ $dateObject->format('Y/m/d') }}
      </div>
      <a href="{{ route('admin_attendance-list', ['date' => $nextDate]) }}" class="nav-btn">翌日 &rarr;</a>
    </div>

    <div class="table-wrapper">
      <table class="attendance-table">
        <thead>
          <tr>
            <th>名前</th>
            <th>出勤</th>
            <th>退勤</th>
            <th>休憩</th>
            <th>合計</th>
            <th>詳細</th>
          </tr>
        </thead>
        <tbody>
          @foreach($staffReports as $report)
            <tr>
              <td>{{ $report['name'] }}</td>
              <td>{{ $report['clock_in'] }}</td>
              <td>{{ $report['clock_out'] }}</td>
              <td>{{ $report['rest_time'] }}</td>
              <td>{{ $report['total_time'] }}</td>
              <td><a href="{{ route('admin_attendance-detail', ['date_id' => $report['id']]) }}">詳細</a></td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

  </div>

@endsection
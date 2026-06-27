@extends('admin.layouts.app')
@section('title')
    <title>スタッフ別勤怠一覧</title>
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('css/admin_attendance_list.css')}}">
@endsection

@section('content')
    <div class="main-container">
        <h1 class="page-title">{{$user->name}}さんの勤怠</h1>

        <!-- 日付ナビゲーション -->
        <div class="date-pager">
            <a href="{{ route('admin_staff-attendance', ['month' => $prevMonth, 'user_id' => $user->id]) }}"
                class="date-pager__arrow">← 前月</a>
            <div class="date-pager__current">
                <span class="icon-calendar">📅</span> {{ $currentMonth->format('Y/m') }}
            </div>
            <a href="{{ route('admin_staff-attendance', ['month' => $nextMonth, 'user_id' => $user->id]) }}"
                class="date-pager__arrow">翌月 →</a>
        </div>

        <div class="table-card scrollable-table">
            <table class="staff-table">
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
                            <td>{{ $report['date']->copy()->format('m/d') }}{{ $report['day_of_week'] }}</td>
                            <td>{{ $report['clock_in'] }}</td>
                            <td>{{ $report['clock_out'] }}</td>
                            <td>{{ $report['rest_time'] }}</td>
                            <td>{{ $report['total_time'] }}</td>
                            <td><a href="{{ route('admin_attendance-detail', ['date_id' => $report['date_id']]) }}">詳細</a>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        <!-- アクションエリア（右寄せボタン） -->
        <div class="action-area">
            <button type="button" class="btn-csv">CSV出力</button>
        </div>
    </div>
@endsection
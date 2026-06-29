@extends('layouts.app')
@section('title')
    <title>マイ勤怠レポート</title>
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('css/myreport.css')}}">
@endsection

@section('content')
    <div class="container">
        <h1 class="page-title">マイ勤怠レポート</h1>
        <p class="page-subtitle">過去6ヶ月の勤怠データから集計しています。</p>

        <section class="section">
            <h2 class="section-title">基本サマリー</h2>
            <div class="summary-grid">
                <div class="card">
                    <div class="card-label">総労働時間</div>
                    <div class="card-value">{{ $summary['total_work'] }}</div>
                </div>
                <div class="card">
                    <div class="card-label">総残業時間</div>
                    <div class="card-value">{{ $summary['total_overtime'] }}</div>
                </div>
                <div class="card">
                    <div class="card-label">平均労働時間 / 日</div>
                    <div class="card-value">{{ $summary['average_work'] }}</div>
                </div>
            </div>
        </section>

        <section class="section">
            <h2 class="section-title">月次推移（過去6ヶ月）</h2>
            <div class="table-wrapper">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>月</th>
                            <th>労働時間</th>
                            <th>残業時間</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($monthlyTrends as $trend)
                            <tr>
                                <td>{{ $trend['month'] }}</td>
                                <td>{{ $trend['total_hours'] }}</td>
                                <td>{{ $trend['overtime_hours'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <section class="section">
            <h2 class="section-title">今月の異常検知</h2>
            <p class="section-subtitle">基準：始業 09:00 / 終業 18:00 / 長時間労働は1日10時間超</p>
            <div class="summary-grid">
                <div class="card">
                    <div class="card-label">遅刻回数</div>
                    <div class="card-value">{{ $anomalies['late'] }}回</div>
                </div>
                <div class="card">
                    <div class="card-label">早退回数</div>
                    <div class="card-value">{{ $anomalies['early'] }} 回</div>
                </div>
                <div class="card">
                    <div class="card-label">長時間労働日数</div>
                    <div class="card-value">{{ $anomalies['long_work'] }} 日</div>
                </div>
            </div>
        </section>
    </div>
@endsection
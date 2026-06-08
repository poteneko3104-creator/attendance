@extends('layouts.app')
@section('title')
    <title>勤怠一覧</title>
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('css/detail.css')}}">
@endsection

@section('content')
<div class="attendance-container">
    <h2 class="title">勤怠詳細</h2>

    <form action="#" method="POST" class="detail-card">
        <!-- 名前 -->
        <div class="form-group">
            <label class="form-label">名前</label>
            <div class="form-value font-bold">{{$user->name}}</div>
        </div>

        <!-- 日付 -->
        <div class="form-group">
            <label class="form-label">日付</label>
            <div class="form-value font-bold date-layout">
                <span>2023年</span>
                <span>6月1日</span>
            </div>
        </div>

        <!-- 出勤・退勤 -->
        <div class="form-group">
            <label class="form-label">出勤・退勤</label>
            <div class="form-value time-range">
                <input type="text" class="input-time text-center" value={{$attendances->attendace->start_time}}>
                <span class="tilde">〜</span>
                <input type="text" class="input-time text-center" value="18:00">
            </div>
        </div>

        <!-- 休憩 -->
        <div class="form-group">
            <label class="form-label">休憩</label>
            <div class="form-value time-range">
                <input type="text" class="input-time text-center" value="12:00">
                <span class="tilde">〜</span>
                <input type="text" class="input-time text-center" value="13:00">
            </div>
        </div>

        <!-- 休憩2 -->
        <div class="form-group">
            <label class="form-label">休憩2</label>
            <div class="form-value time-range">
                <input type="text" class="input-time text-center" value="">
                <span class="tilde">〜</span>
                <input type="text" class="input-time text-center" value="">
            </div>
        </div>

        <!-- 備考 -->
        <div class="form-group">
            <label class="form-label">備考</label>
            <div class="form-value">
                <input type="text" class="input-remarks" value="電車遅延のため">
            </div>
        </div>

        <!-- ボタンエリア -->
        <div class="btn-container">
            <button type="submit" class="btn-submit">修正</button>
        </div>
    </form>
</div>
@endsection
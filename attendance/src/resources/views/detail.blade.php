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
                <span>{{date('Y', strtotime($date->date)) }}年</span>
                <span>{{date('m', strtotime($date->date)) }}月{{date('d', strtotime($date->date)) }}日</span>
            </div>
        </div>
        <input type="hidden" name="date_id" value="{{ $date->id }}">

        @foreach($date->attendance->where('category', '出勤') as $attendance)
        <div class="form-group">           
            <label class="form-label">出勤・退勤</label>
            <div class="form-value time-range">
                <input type="hidden" name="new_attendances[][category]" value="出勤">
                <input type="text" name="new_attendances[start_time]" class="input-time text-center" value="{{ $attendance->start_time->format('H:i') }}">
                <span class="tilde">〜</span>
                <input type="text" name="new_attendances[end_time]" class="input-time text-center" value="{{ $attendance->end_time->format('H:i') }}">
            </div>
            @error('new_attendances.'.$loop->index.'.start_time'||'new_attendances.'.$loop->index.'.end_time')
                <div class="aleart-message">
                    {{$message}}
                </div>
            @enderror
        </div>
        @endforeach
        @php
            $attendanceCount = $date->attendance->where('category','出勤')->count();
        @endphp
        @foreach($date->attendance->where('category', '休憩') as $attendance)
        @php
            $currentIndex = $attendanceCount + $loop->index;
        @endphp
        <div class="form-group">           
            <label class="form-label">休憩</label>
            <div class="form-value time-range">
                <input type="hidden" name="new_attendances[][category]" value="休憩">
                <input type="text" name="new_attendances[start_time]" class="input-time text-center" value="{{ $attendance->start_time->format('H:i') }}">
                <span class="tilde">〜</span>
                <input type="text" name="new_attendances[end_time]" class="input-time text-center" value="{{ $attendance->end_time->format('H:i') }}">
            </div>
            @error('new_attendances.'.$currentIndex.'.start_time'||'new_attendances.'.$currentIndex.'.end_time')
                <div class="aleart-message">
                    {{$message}}
                </div>
            @enderror
        </div>
        @endforeach
        @php 
             $totalCount = $attendanceCount + $date->attendance->where('category', '休憩')->count(); 
        @endphp

        <div class="form-group">
            <label class="form-label">休憩</label>
            <div class="form-value time-range">
                <input type="hidden" name="new_attendances[][category]" value="休憩">
                <input type="text" class="input-time text-center" name="new_attendances[start_time]" value="">
                <span class="tilde">〜</span>
                <input type="text" class="input-time text-center" name="new_attendances[end_time]" value="">
            </div>
            @error('new_attendances.'.$totalCount.'.start_time'||'new_attendances.'.$totalCount.'.end_time')
            <div class="aleart-message" >
                {{ $message }}
            </div>
            @enderror
        </div>

        <!-- 備考 -->
        <div class="form-group">
            <label class="form-label">備考</label>
            <div class="form-value">
                <input type="text" class="input-remarks" name="remarks" value="">
            </div>
        </div>

        <!-- ボタンエリア -->
        @if($date->application==2)
        <div class="aleart-message">*承認待ちのため申請できません</div>
        @else
        <div class="btn-container">
            <button type="submit" class="btn-submit">修正</button>
        </div>
        @endif
    </form>
</div>
@endsection
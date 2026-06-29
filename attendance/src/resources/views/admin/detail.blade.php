@extends('admin.layouts.app')
@section('title')
    <title>勤怠詳細</title>
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('css/detail.css')}}">
@endsection

@section('content')
<div class="attendance-container">
    <h1 class="title">勤怠詳細</h1>

    <form action="/admin/attendance/detail" method="POST" class="detail-card">
        @csrf
        <div class="form-group">
            <label class="form-label">名前</label>            
            <div class="form-value font-bold">{{$report->user->name}}</div>
            <input type="hidden" name="user_id" value="{{$report->user->id}}">
        </div>

        <!-- 日付 -->
        <div class="form-group">
            <label class="form-label">日付</label>
            <div class="form-value font-bold date-layout">
                <span>{{date('Y', strtotime($report->date)) }}年</span>
                <span>{{date('m', strtotime($report->date)) }}月{{date('d', strtotime($report->date)) }}日</span>
            </div>
            <input type="hidden" name="date" value="{{$report->date}}">
        </div>
        <input type="hidden" name="date_id" value="{{ $report->id }}">

        @foreach($report->attendance()->where('status',$status)->where('category', '出勤')->get() as $key => $attendance)
        <div class="form-group">           
            <label class="form-label">出勤・退勤</label>
            <div class="form-value time-range">
                <input type="hidden" name="new_attendances[{{$key}}][category]" value="出勤">
                <input type="text" name="new_attendances[{{$key}}][start_time]" class="input-time text-center" value="{{ old('new_attendances.'.$loop->index.'.start_time',$attendance->start_time->format('H:i')) }}">
                <span class="tilde">〜</span>
                <input type="text" name="new_attendances[{{$key}}][end_time]" class="input-time text-center" value="{{ old('new_attendances.'.$loop->index.'.end_time',$attendance->end_time?->format('H:i')) }}">
            </div>
            @if($errors->has('new_attendances.'.$loop->index.'.start_time') || $errors->has('new_attendances.'.$loop->index.'.end_time'))
                <div class="aleart-message">
                    {{ $errors->first('new_attendances.'.$loop->index.'.start_time') }}                    
                    {{ $errors->first('new_attendances.'.$loop->index.'.end_time') }}
                </div>
            @endif
        </div>
        @endforeach
        @php
            $attendanceCount = $report->attendance->where('status',$status)->where('category','出勤')->count();
        @endphp
        
        @foreach($report->attendance()->where('status', $status)->where('category', '休憩')->get() as $attendance)
        @php
            $currentIndex = $attendanceCount + $loop->index;
        @endphp
        <div class="form-group">           
            <label class="form-label">休憩</label>
            <div class="form-value time-range">
                <input type="hidden" name="new_attendances[{{$currentIndex}}][category]" value="休憩">
                <input type="text" name="new_attendances[{{$currentIndex}}][start_time]" class="input-time text-center" value="{{ old('new_attendances.'.$currentIndex.'.start_time',$attendance->start_time->format('H:i')) }}">
                <span class="tilde">〜</span>
                <input type="text" name="new_attendances[{{$currentIndex}}][end_time]" class="input-time text-center" value="{{ old('new_attendances.'.$currentIndex.'.end_time',$attendance->end_time?->format('H:i')) }}">
            </div>
            @if($errors->has('new_attendances.'.$currentIndex.'.start_time') || $errors->has('new_attendances.'.$currentIndex.'.end_time'))
                <div class="aleart-message">
                    {{ $errors->first('new_attendances.'.$currentIndex.'.start_time') }}                    
                    {{ $errors->first('new_attendances.'.$currentIndex.'.end_time') }}
                </div>
            @endif
        </div>
        @endforeach
        
        @php 
             $totalCount = $attendanceCount + $report->attendance->where('status',$status)->where('category', '休憩')->count(); 
        @endphp

        <div class="form-group">
            <label class="form-label">休憩</label>
            <div class="form-value time-range">
                <input type="hidden" name="new_attendances[{{$totalCount}}][category]" value="休憩">
                <input type="text" class="input-time text-center" name="new_attendances[{{$totalCount}}][start_time]" value="{{old('new_attendances.'.$totalCount.'.start_time')}}">
                <span class="tilde">〜</span>
                <input type="text" class="input-time text-center" name="new_attendances[{{$totalCount}}][end_time]" value="{{old('new_attendances.'.$totalCount.'.end_time')}}">
            </div>
            @if($errors->has('new_attendances.'.$totalCount.'.start_time') || $errors->has('new_attendances.'.$totalCount.'.end_time'))
                <div class="aleart-message">
                    {{ $errors->first('new_attendances.'.$totalCount.'.start_time') }}
                    
                    {{ $errors->first('new_attendances.'.$totalCount.'.end_time') }}
                </div>
            @endif
        </div>

        <!-- 備考 -->
        <div class="form-group">
            <label class="form-label">備考</label>
            <div class="form-value">
                <input type="text" class="input-remarks" name="remarks" value="{{old('remarks',$report->remarks)}}">
            </div>
            @error('remarks')
            <div class="aleart-message" >
                {{ $message }}
            </div>
            @enderror
        </div>

        <!-- ボタンエリア -->
        @if($status==2)
        <div class="aleart-message">*承認待ちのため申請できません</div>
        @else
        <div class="btn-container">
            <button type="submit" class="btn-submit">修正</button>
        </div>
        @endif
    </form>
</div>
@endsection
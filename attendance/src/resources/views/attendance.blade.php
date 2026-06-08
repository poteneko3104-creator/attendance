@extends('layouts.app')
@section('title')
    <title>勤怠登録</title>
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('css/attendance.css')}}">
@endsection

@section('content')
    <div class="main-content">
        @if($date?->status == 1)
            <div class="attendance-status">
                出勤中
            </div>
        @elseif($date?->status == 2)
            <div class="attendance-status">
                休憩中
            </div>
        @elseif($date?->status == 3)
            <div class="attendance-status">
                退勤済
            </div>
        @else
            <div class="attendance-status">
                勤務外
            </div>
        @endif
        <div class="date-display" id="date-display">{{now()->format('Y-m-d')}}</div>
        <div class="time-display" id="time-display">--:--</div>
        @if($date?->status == 3)
            <div class="end_message">お疲れ様でした</div>
        @else
            <form action="/attendance" method="post">
                @csrf
                @if($date?->status == 1)
                    <button type="submit" class="attendance-btn" name="action" value="leave">退勤</button>
                    <button type="submit" class="attendance-btn" name="action" value="break">休憩入り</button>
                @elseif($date?->status == 2)
                    <button type="submit" class="attendance-btn" name="action" value="back">休憩戻り</button>
                @else
                    <button type="submit" class="attendance-btn" name="action" value="attend">出勤</button>
                @endif
            </form>
        @endif
    </div>
    <script>
function updateClock() {
    const now = new Date();
    
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0'); // 月は0から始まるため+1
    const date = String(now.getDate()).padStart(2, '0');
    const dayNames = ['日', '月', '火', '水', '木', '金', '土'];
    const dayOfWeek = dayNames[now.getDay()];

    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    
    const dateString = `${year}年${month}月${date}日（${dayOfWeek}）`
    const timeString = `${hours}:${minutes}`;
    
    document.getElementById('date-display').textContent = dateString;
    document.getElementById('time-display').textContent = timeString;
}


setInterval(updateClock, 1000);

updateClock();
</script>
@endsection
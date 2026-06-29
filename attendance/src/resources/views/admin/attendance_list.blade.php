@extends('admin.layouts.app')
@section('title')
    <title>スタッフ別勤怠一覧</title>
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('css/admin_attendance_list.css')}}">
@endsection

@section('content')
    <div class="main-container">
        <h1 id="csv-title" class="page-title">{{$user->name}}さんの勤怠</h1>

        <div class="date-pager">
            <a href="{{ route('admin_staff-attendance', ['month' => $prevMonth, 'user_id' => $user->id]) }}"
                class="date-pager__arrow">← 前月</a>
            <div id="csv-month" class="date-pager__current">
                <span class="icon-calendar">📅</span> {{ $currentMonth->format('Y/m') }}
            </div>
            <a href="{{ route('admin_staff-attendance', ['month' => $nextMonth, 'user_id' => $user->id]) }}"
                class="date-pager__arrow">翌月 →</a>
        </div>

        <div class="table-card scrollable-table">
            <table class="staff-table" id="attendance-table">
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

        <div class="action-area">
            <button type="button" id="download-csv-btn" class="btn-csv">CSV出力</button>
        </div>
    </div>
    <script>
        document.getElementById('download-csv-btn').addEventListener('click', function () {
            const titleText = document.getElementById('csv-title').innerText.trim();
            const monthText = document.getElementById('csv-month').innerText.trim();

            const monthMatch = monthText.match(/\d{4}\/\d{2}/);
            const monthStr = monthMatch ? '_' + monthMatch[0].replace('/', '') : '';

            const fileName = `${titleText}${monthStr}.csv`;


            const table = document.getElementById('attendance-table');
            const rows = table.querySelectorAll('tr');
            let csvContent = [];

            rows.forEach(row => {
                const cols = row.querySelectorAll('th, td');
                let rowData = [];


                for (let i = 0; i < cols.length - 1; i++) {
                    let text = cols[i].innerText.trim().replace(/"/g, '""');
                    rowData.push(`"${text}"`);
                }

                csvContent.push(rowData.join(','));
            });

            const csvString = csvContent.join('\r\n');

            const bom = new Uint8Array([0xEF, 0xBB, 0xBF]); // Excel文字化け対策のBOM
            const blob = new Blob([bom, csvString], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);

            link.setAttribute('href', url);
            link.setAttribute('download', fileName);
            link.style.visibility = 'hidden';

            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });
    </script>
@endsection
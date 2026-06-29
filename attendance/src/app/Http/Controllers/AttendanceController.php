<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Date;
use App\Models\Attendance;
use App\Models\Application;
use App\Models\User;
use Illuminate\Support\Carbon;
use App\Http\Requests\ApplicationRequest;

class AttendanceController extends Controller
{
    //
    public function attendance()
    {
        $date = Date::where('user_id', Auth::id())->where('date', Carbon::today())->first();
        return view('attendance', compact('date'));
    }
    public function submit(Request $request)
    {
        $action = $request->action;
        if ($action == 'attend') {
            $post = Date::create([
                'user_id' => Auth::id(),
                'date' => Carbon::today(),
                'application' => 1,
                'status' => 1,
            ]);
            Attendance::create([
                'user_id' => Auth::id(),
                'date_id' => $post->id,
                'start_time' => Carbon::now(),
                'category' => '出勤',
                'status' => 1,
            ]);
        } elseif ($action == 'break') {
            $post = Date::where('user_id', Auth::id())->where('date', Carbon::today())->first();
            if ($post) {
                $post->update(['status' => 2,]);
                Attendance::create([
                    'user_id' => Auth::id(),
                    'date_id' => $post->id,
                    'start_time' => Carbon::now(),
                    'category' => '休憩',
                    'status' => 1,
                ]);
            }
        } elseif ($action == 'back') {
            $post = Date::where('user_id', Auth::id())->where('date', Carbon::today())->first();
            if ($post) {
                $post->update(['status' => 1]);
                Attendance::where('user_id', Auth::id())
                    ->where('date_id', $post->id)
                    ->where('category', '休憩')
                    ->where('status', 1)
                    ->latest()
                    ->limit(1)
                    ->update(
                        [
                            'end_time' => Carbon::now(),
                        ]
                    );
            }
        } elseif ($action == 'leave') {
            $post = Date::where('user_id', Auth::id())->where('date', Carbon::today())->first();
            if ($post) {
                $post->update(['status' => 3]);
                Attendance::where('user_id', Auth::id())
                    ->where('date_id', $post->id)
                    ->where('category', '出勤')
                    ->where('status', 1)
                    ->latest()
                    ->limit(1)
                    ->update(
                        [
                            'end_time' => Carbon::now(),
                        ]
                    );
            }
        }
        return redirect('/attendance');
    }

    public function list(Request $request)
    {
        $monthParam = $request->query('month', Carbon::now()->format('Y-m'));

        try {

            $currentMonth = Carbon::parse($monthParam . '-01');
        } catch (\Exception $e) {

            $currentMonth = Carbon::now()->startOfMonth();
        }

        $prevMonth = $currentMonth->copy()->subMonth()->format('Y-m');
        $nextMonth = $currentMonth->copy()->addMonth()->format('Y-m');

        $daysInMonth = [];
        $startDate = $currentMonth->copy()->startOfMonth();
        $endDate = $currentMonth->copy()->endOfMonth();

        $attendances = Date::where('user_id', Auth::id())
            ->whereBetween('date', [$startDate, $endDate])
            ->with('attendance')
            ->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->date)->format('Y-m-d');
            });

        $dailyReports = [];
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $daysInMonth[] = $date->copy();
            $dayOfWeek = $date->isoFormat('dd');
            $dateString = $date->format('Y-m-d');
            if ($attendances->has($dateString)) {
                $dayAttendances = $attendances[$dateString];
                $dayRecord = $attendances->get($dateString)?->first();
                $attend_start = $dayRecord?->attendance?->where('category', '出勤')->where('status', 1)->first()?->start_time;
                $clockIn = Carbon::parse($attend_start);
                $attend_end = $dayRecord?->attendance?->where('category', '出勤')->where('status', 1)->first()?->end_time;
                $clockOut = $attend_end ? Carbon::parse($attend_end) : null;
                $rests = $dayRecord?->attendance?->where('category', '休憩')->where('status', 1)->all();
                $totalRestSeconds = 0;
                if (isset($rests)) {
                    foreach ($rests as $rest) {
                        if ($rest->end_time) {
                            $totalRestSeconds += Carbon::parse($rest->start_time)->diffInSeconds(Carbon::parse($rest->end_time));
                        }
                    }
                }
                $totalWorkSeconds = $clockOut ? $clockIn->diffInSeconds($clockOut) : 0;
                $actualWork_Seconds = max(0, $totalWorkSeconds - $totalRestSeconds);

                $dailyReports[] = [
                    'date' => $date->copy(),
                    'day_of_week' => "({$dayOfWeek})",
                    'clock_in' => $clockIn->format('H:i'),
                    'clock_out' => $clockOut ? $clockOut->format('H:i') : '-',
                    'rest_time' => $this->formatSecondsToHours($totalRestSeconds),
                    'total_time' => $clockOut ? $this->formatSecondsToHours($actualWork_Seconds) : '-',
                ];
            } else {
                $dailyReports[] = [
                    'date' => $date->copy(),
                    'day_of_week' => "({$dayOfWeek})",
                    'clock_in' => '-',
                    'clock_out' => '-',
                    'rest_time' => '-',
                    'total_time' => '-',
                ];

            }
        }

        return view('list', [
            'daysInMonth' => $daysInMonth,
            'currentMonth' => $currentMonth,
            'prevMonth' => $prevMonth,
            'nextMonth' => $nextMonth,
            'dailyReports' => $dailyReports,
        ]);
    }
    private function formatSecondsToHours($seconds)
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        return sprintf('%02d:%02d', $hours, $minutes);
    }
    public function detail(Request $request)
    {
        $dateParam = $request->date;
        //$formattedDate = $dateParam->format('Y-m-d');
        $user = User::where('id', Auth::id())->first();
        $date = Date::where('user_id', Auth::id())->where('date', $dateParam)->with('attendance')->first();
        if ($date->application == 2) {
            $status = 2;
        } else {
            $status = 1;
        }
        return view('detail', compact('user', 'date', 'status'));
    }
    public function sendApplication(ApplicationRequest $request)
    {
        $dateRecord = Date::find($request->input('date_id'));
        $dateRecord->update([
            'application' => 2,
            'remarks' => $request->input('remarks'),
        ]);
        $ymd = Carbon::parse($dateRecord->date)->format('Y-m-d');
        foreach ($request->input('new_attendances') as $data) {
            $fullStartTime = Carbon::parse($ymd . ' ' . $data['start_time']);
            $fullEndTime = Carbon::parse($ymd . ' ' . $data['end_time']);
            Attendance::create([
                'user_id' => Auth::id(),
                'date_id' => $dateRecord->id,
                'category' => $data['category'],
                'start_time' => $fullStartTime,
                'end_time' => $fullEndTime,
                'status' => 2,
            ]);
        }
        Application::create([
            'user_id' => Auth::id(),
            'date_id' => $dateRecord->id,
            'application_date' => Carbon::now(),
            'status' => 2,
        ]);
        return redirect()->route('attendance-detail', ['date' => $dateRecord->date])->with('success', '申請しました');
    }

    public function applicationList(Request $request)
    {
        $activeTab = $request->query('tab', 'pending');
        if ($activeTab === 'pending') {
            $lists = Application::where('user_id', Auth::id())->where('status', 2)->orderBy('application_date', 'asc')->with('date', 'user')->get();
        } elseif ($activeTab === 'approved') {
            $lists = Application::where('user_id', Auth::id())->where('status', 1)->orderBy('approved_date', 'desc')->with('date', 'user')->get();
        }
        return view('application_list', compact('lists'));
    }
    public function myreport()
    {
        $userId = Auth::id() ?? 1;

        // 過去6ヶ月の範囲を設定（今月を含む6ヶ月前の一日まで）
        $sixMonthsAgo = Carbon::now()->subMonths(5)->startOfMonth();
        $today = Carbon::now()->endOfMonth();

        // datesと、有効なattendances（statusが0以外の登録済み・申請中データ）をEager Loading
        $attendanceData = Date::where('user_id', $userId)
            ->whereBetween('date', [$sixMonthsAgo->format('Y-m-d'), $today->format('Y-m-d')])
            ->with([
                'attendance' => function ($query) {
                    $query->where('status', 1); // 修正前データ(0)を除外 申請中も除外
                }
            ])
            ->orderBy('date', 'asc')
            ->get();

        // --- 集計用変数の初期化 ---
        $totalMinutes = 0;
        $totalOvertimeMinutes = 0;
        $workingDays = 0;

        // 月次推移用配列の初期化
        $monthlyTrends = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthStr = Carbon::now()->subMonths($i)->format('Y-m');
            $monthlyTrends[$monthStr] = [
                'month' => $monthStr,
                'total_minutes' => 0,
                'overtime_minutes' => 0,
            ];
        }

        // 今月の異常検知用
        $currentMonthStr = Carbon::now()->format('Y-m');
        $lateCount = 0;
        $earlyLeaveCount = 0;
        $longWorkingDays = 0;

        // --- データ集計ロジック ---
        foreach ($attendanceData as $record) {
            $recordDate = Carbon::parse($record->date);
            $monthKey = $recordDate->format('Y-m');

            $attendances = $record->attendance;
            if ($attendances->isEmpty()) {
                continue;
            }

            // 1. 出勤データから拘束時間を計算
            $workRecords = $attendances->where('category', '出勤');
            $dailyWorkMinutes = 0;
            $firstCheckIn = null;
            $lastCheckOut = null;

            foreach ($workRecords as $work) {
                if ($work->start_time && $work->end_time) {
                    $start = Carbon::parse($work->start_time);
                    $end = Carbon::parse($work->end_time);
                    $dailyWorkMinutes += $start->diffInMinutes($end);

                    // 異常検知用に、その日の「最初の出勤」と「最後の退勤」を保持
                    if (is_null($firstCheckIn) || $start->lt($firstCheckIn)) {
                        $firstCheckIn = $start;
                    }
                    if (is_null($lastCheckOut) || $end->gt($lastCheckOut)) {
                        $lastCheckOut = $end;
                    }
                }
            }

            // 出勤データがない場合はスキップ
            if ($dailyWorkMinutes === 0) {
                continue;
            }
            $workingDays++;

            // 2. 休憩データから総休憩時間を計算して差し引く
            $restRecords = $attendances->where('category', '休憩');
            $dailyRestMinutes = 0;

            foreach ($restRecords as $rest) {
                if ($rest->start_time && $rest->end_time) {
                    $dailyRestMinutes += Carbon::parse($rest->start_time)->diffInMinutes(Carbon::parse($rest->end_time));
                }
            }

            // 実労働時間 = 拘束時間 - 休憩時間
            $dailyNetWorkingMinutes = max(0, $dailyWorkMinutes - $dailyRestMinutes);

            // 残業時間の計算 (1日8時間 = 480分 を超えた分)
            $dailyOvertimeMinutes = max(0, $dailyNetWorkingMinutes - 480);

            // 全体サマリーへの加算
            $totalMinutes += $dailyNetWorkingMinutes;
            $totalOvertimeMinutes += $dailyOvertimeMinutes;

            // 月次推移への加算
            if (isset($monthlyTrends[$monthKey])) {
                $monthlyTrends[$monthKey]['total_minutes'] += $dailyNetWorkingMinutes;
                $monthlyTrends[$monthKey]['overtime_minutes'] += $dailyOvertimeMinutes;
            }

            // 3. 今月の異常検知ロジック (基準: 始業 09:00 / 終業 18:00 / 長時間 10時間)
            if ($monthKey === $currentMonthStr) {
                // 遅刻判定 (最初の出勤が09:00:59より後ろ)
                if ($firstCheckIn && $firstCheckIn->format('H:i:s') > '09:00:59') {
                    $lateCount++;
                }
                // 早退判定 (最後の退勤が18:00:00より前)
                if ($lastCheckOut && $lastCheckOut->format('H:i:s') < '18:00:00') {
                    $earlyLeaveCount++;
                }
                // 長時間労働判定 (実労働時間が10時間 = 600分超え)
                if ($dailyNetWorkingMinutes > 600) {
                    $longWorkingDays++;
                }
            }
        }

        // --- 画面表示用に「Xh Ym」形式へフォーマット変換 ---
        $summary = [
            'total_work' => $this->formatMinutesToHours($totalMinutes),
            'total_overtime' => $this->formatMinutesToHours($totalOvertimeMinutes),
            'average_work' => $workingDays > 0 ? $this->formatMinutesToHours($totalMinutes / $workingDays) : '0h 0m',
        ];

        foreach ($monthlyTrends as $key => $trend) {
            $monthlyTrends[$key]['total_hours'] = $this->formatMinutesToHours($trend['total_minutes']);
            $monthlyTrends[$key]['overtime_hours'] = $this->formatMinutesToHours($trend['overtime_minutes']);
        }

        $anomalies = [
            'late' => $lateCount,
            'early' => $earlyLeaveCount,
            'long_work' => $longWorkingDays,
        ];

        return view('myreport', compact('summary', 'monthlyTrends', 'anomalies'));
    }

    /**
     * 分を「Xh Ym」の文字列に変換
     */
    private function formatMinutesToHours($minutes)
    {
        $hours = floor($minutes / 60);
        $remainingMinutes = round($minutes % 60);
        return "{$hours}h {$remainingMinutes}m";
    }
}
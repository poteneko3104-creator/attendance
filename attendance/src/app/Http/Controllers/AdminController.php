<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Date;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ApplicationRequest;

class AdminController extends Controller
{
    public function attendanceList(Request $request)
    {
        $date = $request->query('date', Carbon::now()->format('Y-m-d'));
        $dateObject = Carbon::parse($date);
        $prevDate = $dateObject->copy()->subDay()->format('Y-m-d');
        $nextDate = $dateObject->copy()->addDay()->format('Y-m-d');
        $records = Date::where('date', $date)->with('user')->with('attendance')->get();
        $staffReports = [];
        foreach ($records as $record) {
            $id = $record->id;
            $name = $record->user->name;
            $attend_start = $record->attendance?->where('category', '出勤')->where('status', 1)->first()?->start_time;
            $clockIn = Carbon::parse($attend_start);
            $attend_end = $record->attendance?->where('category', '出勤')->where('status', 1)->first()?->end_time;
            $clockOut = $attend_end ? Carbon::parse($attend_end) : null;
            $rests = $record?->attendance?->where('category', '休憩')->where('status', 1)->all();
            $totalRestSeconds = 0;
            if (isset($rests)) {
                foreach ($rests as $rest) {
                    if ($rest->end_time) {
                        $totalRestSeconds += Carbon::parse($rest->start_time)->diffInSeconds(Carbon::parse($rest->end_time));
                    }
                }
            }
            $totalWorkSeconds = $clockOut ? $clockIn->diffInSeconds($clockOut) : 0;
            $actualWorkSeconds = max(0, $totalWorkSeconds - $totalRestSeconds);
            $staffReports[] = [
                'id' => $id,
                'name' => $name,
                'clock_in' => $clockIn->format('H:i'),
                'clock_out' => $clockOut?->format('H:i'),
                'rest_time' => $this->formatSecondsToHours($totalRestSeconds),
                'total_time' => $clockOut ? $this->formatSecondsToHours($actualWorkSeconds) : '-',
            ];
        }

        return view('admin.list', compact('dateObject', 'prevDate', 'nextDate', 'staffReports'));
    }
    private function formatSecondsToHours(int $seconds): string
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);

        return sprintf('%02d:%02d', $hours, $minutes);
    }
    public function login()
    {

        return view('admin.auth.login');
    }
    public function detail(Request $request)
    {
        $report = Date::where('id', $request->date_id)->with('user')->with('attendance')->first();
        $status = $report?->application;
        return view('admin.detail', compact('report', 'status'));
    }
    public function update(ApplicationRequest $request)
    {
        $date = Date::where('user_id', $request->user_id)->where('date', $request->date)->first();
        $date->update(['remarks' => $request->remarks]);
        Attendance::where('user_id', $request->user_id)->where('date_id', $date->id)->update(['status' => 0]);
        $ymd = Carbon::parse($date->date)->format('Y-m-d');
        foreach ($request->input('new_attendances') as $data) {
            $fullStartTime = Carbon::parse($ymd . ' ' . $data['start_time']);
            $fullEndTime = Carbon::parse($ymd . ' ' . $data['end_time']);

            if (isset($data['start_time']) && isset($data['end_time'])) {
                Attendance::create([
                    'user_id' => $request->user_id,
                    'date_id' => $date->id,
                    'category' => $data['category'],
                    'start_time' => $fullStartTime,
                    'end_time' => $fullEndTime,
                    'status' => 1
                ]);
            } elseif (isset($data['start_time']) && !isset($data['end_time'])) {
                Attendance::create([
                    'user_id' => $request->user_id,
                    'date_id' => $date->id,
                    'category' => $data['category'],
                    'start_time' => $fullStartTime,
                    'status' => 1
                ]);
            }

        }
        return redirect()->route('admin_attendance-detail', ['id' => $date->id])->with('success', '登録しました');
    }
    public function staffList()
    {
        $users = User::get();
        return view('admin.staff-list', compact('users'));
    }
    public function staffAttendance(Request $request)
    {
        $user = User::find($request->user_id)->first();
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

        $attendances = Date::where('user_id', $request->user_id)
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
                //$dayAttendances = $attendances[$dateString];
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
                    'date_id' => $dayRecord->id,
                    'date' => $date->copy(),
                    'day_of_week' => "({$dayOfWeek})",
                    'clock_in' => $clockIn->format('H:i'),
                    'clock_out' => $clockOut ? $clockOut->format('H:i') : '-',
                    'rest_time' => $this->formatSecondsToHours($totalRestSeconds),
                    'total_time' => $clockOut ? $this->formatSecondsToHours($actualWork_Seconds) : '-',
                ];
            } else {
                $dailyReports[] = [
                    'date_id' => '',
                    'date' => $date->copy(),
                    'day_of_week' => "({$dayOfWeek})",
                    'clock_in' => '-',
                    'clock_out' => '-',
                    'rest_time' => '-',
                    'total_time' => '-',
                ];

            }
        }

        return view('admin.attendance_list', [
            'user' => $user,
            'daysInMonth' => $daysInMonth,
            'currentMonth' => $currentMonth,
            'prevMonth' => $prevMonth,
            'nextMonth' => $nextMonth,
            'dailyReports' => $dailyReports,
        ]);
    }
    public function applicationList(Request $request)
    {
        $activeTab = $request->query('tab', 'pending');
        if ($activeTab == 'pending') {
            $application_items = Application::where('status', 2)->orderBy('application_date', 'asc')->with('date')->with('user')->get();
        } elseif ($activeTab = 'approved') {
            $application_items = Application::where('status', 1)->orderBy('approved_date', 'asc')->with('date')->with('user')->get();
        }
        return view('admin.application', compact('application_items'));
    }
    public function approval(Request $request)
    {
        $report = Date::where('id', $request->date_id)->with('attendance')->with('user')->first();
        return view('admin.approval', compact('report'));
    }
}

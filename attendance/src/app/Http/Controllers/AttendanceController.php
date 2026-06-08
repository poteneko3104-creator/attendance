<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Date;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Support\Carbon;

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
                    ->where('status',1)
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
                    ->where('status',1)
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
        ->whereBetween('date', [$startDate, $endDate]) // ※date_columnは実際の日付カラム名に変えてください
        ->with('attendance')
        ->get()
        ->groupBy(function($item) {
            return Carbon::parse($item->date)->format('Y-m-d');
        });

        $dailyReports=[];

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $daysInMonth[] = $date->copy();
            $dayOfWeek = $date->isoFormat('dd');
            $dateString = $date->format('Y-m-d');
            if($attendances->has($dateString)){
                $dayAttendances = $attendances[$dateString];
                $dayRecord = $attendances->get($dateString)?->first();
                $attend_start = $dayRecord?->attendance?->where('category', '出勤')->where('status',1)->first()?->start_time;
                $clockIn = Carbon::parse($attend_start);
                $attend_end = $dayRecord?->attendance?->where('category', '出勤')->where('status',1)->first()?->end_time;
                $clockOut = $attend_end ? Carbon::parse($attend_end):null;
                $rests = $dayRecord?->attendance?->where('category','休憩')->where('status',1)->all();
                $totalRestSeconds = 0;
                if(isset($rests)){
                foreach($rests as $rest){
                    if($rest->end_time){
                        $totalRestSeconds += Carbon::parse($rest->start_time)->diffInSeconds(Carbon::parse($rest->end_time));
                    }
                }
                }
                $totalWorkSeconds = $clockOut ? $clockIn->diffInSeconds($clockOut):0;
                $actualWork_Seconds = max(0,$totalWorkSeconds-$totalRestSeconds);

               $dailyReports[] = [
                    'date' => $date->format('m/d'),
                    'day_of_week' => "({$dayOfWeek})",
                    'clock_in' => $clockIn->format('H:i'),
                    'clock_out' => $clockOut ? $clockOut->format('H:i') : '-',
                    'rest_time' => $this->formatSecondsToHours($totalRestSeconds),
                    'total_time' => $clockOut ? $this->formatSecondsToHours($actualWork_Seconds) : '-',
                ];  
            }

            else{
                $dailyReports[] = [
                    'date' => $date->format('m/d'),
                    'day_of_week' => "({$dayOfWeek})",
                    'clock_in' => '-',
                    'clock_out' => '-',
                    'rest_time' => '-',
                    'total_time' => '-',
                 ];  
        
            }
        }

        return view('list', [
            'daysInMonth'  => $daysInMonth,
            'currentMonth' => $currentMonth,
            'prevMonth'    => $prevMonth,
            'nextMonth'    => $nextMonth,
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
     
        $formattedDate = Carbon::createFromFormat('m/d', $dateParam)->format('Y-m-d');
        $user = User::where('id',Auth::id())->first();
        $attendances = Date::where('user_id',Auth::id())->where('date',$formattedDate)->with('attendance')->first();
        return view('detail',compact('user','attendances'));
    }

}
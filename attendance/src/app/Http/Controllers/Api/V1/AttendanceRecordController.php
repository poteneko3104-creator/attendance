<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Date;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AttendanceRecordController extends Controller
{
    //
    public function index(Request $request)
    {
        return response()->json(Date::all(), 200);
    }
    public function show(Request $request, $id)
    {

        $dateRecord = Date::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();


        $attendanceRecord = Attendance::where('date_id', $dateRecord->id)
            ->where('category', '出勤')
            ->first();

        return response()->json([
            'date_info' => $dateRecord,
            'attendance_info' => $attendanceRecord
        ], 200);
    }
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'clock_in' => 'required|date_format:H:i:s',
            'clock_out' => 'nullable|date_format:H:i:s',
            'comment' => 'nullable|string|max:255',
        ], [
            'date.required' => '勤怠日は必須です。',
            'date.date' => '勤怠日は YYYY-MM-DD 形式で指定してください。',
            'clock_in.required' => '出勤時刻は必須です。',
            'clock_in.date_format' => '出勤時刻は HH:MM:SS 形式で指定してください。',
            'clock_out.date_format' => '退勤時刻は HH:MM:SS 形式で指定してください。',
            'comment.max' => '備考は 255 文字以内で入力してください。',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first()
            ], 422);
        }

        $validated = $validator->validated();
        $userId = $request->user()->id;

        $exists = Date::where('user_id', $userId)
            ->where('date', $validated['date'])
            ->exists();

        if ($exists) {
            return response()->json([
                'error' => '指定された日付の勤怠記録は既に存在します。'
            ], 422);
        }

        $record = DB::transaction(function () use ($validated, $userId) {

            $dateRecord = Date::create([
                'user_id' => $userId,
                'date' => $validated['date'],
                'application' => 1,
                'remarks' => $validated['comment'] ?? null,
                'status' => $validated['clock_out'] ? 3 : 1,
            ]);


            $startTime = $validated['date'] . ' ' . $validated['clock_in'];
            $endTime = $validated['clock_out'] ? ($validated['date'] . ' ' . $validated['clock_out']) : null;

            $attendanceRecord = Attendance::create([
                'user_id' => $userId,
                'date_id' => $dateRecord->id,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'category' => '出勤',
                'status' => 1,
            ]);


            return [
                'date_info' => $dateRecord,
                'attendance_info' => $attendanceRecord
            ];
        });

        return response()->json($record, 201);
    }
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'clock_in' => 'required|date_format:H:i:s',
            'clock_out' => 'nullable|date_format:H:i:s',
            'comment' => 'nullable|string|max:255',
        ], [
            'date.required' => '勤怠日は必須です。',
            'date.date' => '勤怠日は YYYY-MM-DD 形式で指定してください。',
            'clock_in.required' => '出勤時刻は必須です。',
            'clock_in.date_format' => '出勤時刻は HH:MM:SS 形式で指定してください。',
            'clock_out.date_format' => '退勤時刻は HH:MM:SS 形式で指定してください。',
            'comment.max' => '備考は 255 文字以内で入力してください。',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first()
            ], 422);
        }

        $validated = $validator->validated();
        $userId = $request->user()->id;


        $exists = Date::where('user_id', $userId)
            ->where('date', $validated['date'])
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return response()->json([
                'error' => '指定された日付の勤怠記録は既に存在します。'
            ], 422);
        }

        $record = DB::transaction(function () use ($validated, $id, $request) {

            $dateRecord = Date::where('id', $id)
                ->where('user_id', $request->user()->id)
                ->firstOrFail();


            $dateRecord->update([
                'date' => $validated['date'],
                'remarks' => $validated['comment'] ?? null,
                'status' => $validated['clock_out'] ? 3 : 1, // 退勤時間があれば3、なければ1
            ]);


            $attendanceRecord = Attendance::where('date_id', $dateRecord->id)
                ->where('category', '出勤')
                ->firstOrFail();

            $startTime = $validated['date'] . ' ' . $validated['clock_in'];
            $endTime = $validated['clock_out'] ? ($validated['date'] . ' ' . $validated['clock_out']) : null;

            $attendanceRecord->update([
                'start_time' => $startTime,
                'end_time' => $endTime,
            ]);

            return [
                'date_info' => $dateRecord,
                'attendance_info' => $attendanceRecord
            ];
        });


        return response()->json($record, 200);
    }

    public function destroy(Request $request, $id)
    {
        $dateRecord = Date::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();


        $dateRecord->delete();


        return response()->noContent();
    }
}



<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator; 
use Carbon\Carbon;

class ApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            //
            'new_attendances.*.start_time' => ['nullable','date_format:H:i',],
            'new_attendances.*.end_time' => ['nullable','date_format:H:i',],
            'remarks' => ['required']
        ];
    }
    public function masages(){
        return[
            'new_attendances.*.start_time.date_format' => '開始時間は「時:分（例 09:00）」の形式で入力してください。',
            'new_attendances.*.end_time.date_format'   => '終了時間は「時:分（例 18:30）」の形式で入力してください。',
            'remarks.required' => '備考を記入してください',
        ];
    }
    public function withValidator(Validator $validator): void{
        $validator->after(function($validator){
            $attendances = $this->input('new_attendances',[]);

            $parsedData = [];
            $workStart = null;
            $workEnd = null;
            foreach($attendances as $index => $data){

                if(empty($data['start_time'])||empty($data['end_time'])){
                    continue;
                }
                try{
                $start = Carbon::parse($data['start_time']);
                $end = Carbon::parse($data['end_time']);
                $parsedData[$index] = [
                    'category' => $data['category'] ?? '',
                    'start' => $start,
                    'end' => $end,
                ];
                if(($data['category']??'')==='出勤'){
                    $workStart = $start;
                    $workEnd = $end;
                }
                }catch (\Carbon\Exceptions\InvalidFormatException $e){
                    continue;
                }
            }
            foreach($parsedData as $index => $data){
                $start = $data['start'];
                $end = $data['end'];
                if($start->greaterThan($end)||$end->lessThan($start)){
                    $validator->errors()->add(
                        "new_attendances.{$index}.start_time",
                        "出勤時間もしくは退勤時間が不適切な値です"
                    );
                    continue;
                }
                if($data['category']==='休憩' && $workStart && $workEnd){
                    if($start->lessThan($workStart)||$end->greaterThan($workEnd)){
                        $validator->errors()->add(
                            "new_attendances.{$index}.start_time",
                            "休憩時間が勤務時間外です。"
                        );
                    }
                }
            }
        });
    }
}

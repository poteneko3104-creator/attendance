<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'remarks' => ['required']
        ];
    }
    public function masages(){
        return[
            'new_attendances.*.start_time' => 'nullable|date_format:H:i',
            'new_attendances.*.end_time' => 'nullable|date_format:H:i',
            'remarks.required' => '備考を記入してください',
        ];
    }
    public function withValidator(Validator $validator): void{
        $validator->after(function($validator){
            $attendances = $this->input('new_attendance',[]);

            foreach($attendances as $index => $data){
                if(empty($data['start_time'])||empty($data['end_time'])){
                    continue;
                }

                $start = Carbon::parse($data['start_time']);
                $end = Carbon::parse($data['end_time']);
                if($start->graterThanOrEqualTo($end)){
                    $validator->errors()->add(
                        "new_attendances.{$index}.start_time",
                        "出勤時間もしくは退勤時間が不適切な値です"
                    );
                }
                if($end->lessThanOrEqualTo($start)){
                    $validator->errors()->add(
                        "new_attendances.{$index}.end_time",
                        "出勤時間もしくは退勤時間が不適切な値です"
                    );
                }
            }
        });
    }
}

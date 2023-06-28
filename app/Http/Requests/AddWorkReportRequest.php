<?php

namespace App\Http\Requests;

use App\Rules\ExistTransactionToday;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;

class AddWorkReportRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'integer', 'exists:employees,id', new ExistTransactionToday],
            'hours' => ['required', 'integer', 'min:6', 'max:12'],
        ];
    }

    public function failedValidation(Validator $validator): JsonResponse
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Ошибка валидации',
            'data'      => $validator->errors()
        ], 400));
    }

    public function messages(): array
    {
        return [
            'employee_id.required' => 'employee_id обязательно для заполнения',
            'employee_id.integer' => 'employee_id должно быть целым числом',
            'employee_id.exists' => 'Сотрудник не найден',
            'hours.required' => 'Hours обязательно для заполнения',
            'hours.integer' => 'Hours должно быть целым числом',
            'hours.min' => 'Минимальное количество часов 6',
            'hours.max' => 'Максимальное количество часов 12',
        ];
    }
}

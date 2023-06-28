<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;

class AddEmployeeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'unique:users', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'max:10'],
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
            'email.required' => 'Email обязательно для заполнения',
            'email.unique' => 'Email занят',
            'email.email' => 'Email - не корректное значение',
            'email.max' => 'Максимальное количество символов 255',
            'email.string' => 'Email должен быть строкой',
            'password.required' => 'Password обязательно для заполнения',
            'password.string' => 'Password должен быть строкой',
            'password.min' => 'Минимальное количество символов 6',
            'password.max' => 'Максимальное количество символов 10',
        ];
    }
}
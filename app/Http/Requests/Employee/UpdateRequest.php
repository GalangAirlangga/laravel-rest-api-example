<?php

namespace App\Http\Requests\Employee;

use App\Traits\RespondsWithHttpStatus;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateRequest extends FormRequest
{
    use RespondsWithHttpStatus;

    public function rules(): array
    {
        return [
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required', 'email', 'max:254'],
            'phone_number' => ['required'],
            'hire_date' => ['required', 'date'],
            'salary' => ['required', 'numeric'],
            'department_id' => ['integer'],
            'position_id' => ['integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator): HttpResponseException
    {
        throw new HttpResponseException($this->failureValidation('Validation error', $validator->errors()));
    }
}

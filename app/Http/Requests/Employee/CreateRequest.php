<?php

namespace App\Http\Requests\Employee;

use App\Traits\RespondsWithHttpStatus;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateRequest extends FormRequest
{
    use RespondsWithHttpStatus;

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:254'],
            'phone_number' => ['required'],
            'hire_date' => ['required', 'date'],
            'salary' => ['required', 'numeric'],
            'department_id' => ['required', 'integer'],
            'position_id' => ['required', 'integer'],
            'start_date' => ['required', 'date', 'before:end_date'],
            'end_date' => ['required', 'date', 'after:start_date']
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

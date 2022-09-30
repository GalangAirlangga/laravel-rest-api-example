<?php

namespace App\Http\Requests\Department;

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
            'name' => 'string|max:255|required',
            'description' => 'string'
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

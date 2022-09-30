<?php

namespace App\Http\Requests\Auth;

use App\Traits\RespondsWithHttpStatus;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
{
    use RespondsWithHttpStatus;

    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required'
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

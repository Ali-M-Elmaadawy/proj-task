<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator; 
use Illuminate\Http\Exceptions\HttpResponseException;
class RegisterRequest extends FormRequest
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
    public function rules()
    {
        return [
            'name'          => 'required|string|min:3|max:255',
            'email'         => 'required|string|email|unique:users,email',
            'password'      => 'required|string|min:8|confirmed'
        ];
        
    }

    protected function failedValidation(Validator $validator)
    {
        $response = [
            'status' => false,
            'data' => $validator->errors()->first()
        ];
        throw new HttpResponseException(response()->json($response, 422));
    }

}

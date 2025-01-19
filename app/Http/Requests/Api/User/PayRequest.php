<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator; 
use Illuminate\Http\Exceptions\HttpResponseException;
class PayRequest extends FormRequest
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
            'payment_method_id'                         => 'required|exists:payment_methods,id',
            'card'                                      => 'required|integer|digits:16',
            'cvv'                                       => 'required|integer|digits:3',
            'exp'                                       => 'required'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = [
            'status' => false,
            'data' => $validator->errors()->first(),
            'message' => 'error'
        ];
        throw new HttpResponseException(response()->json($response, 422));
    }
    

}

<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator; 
use Illuminate\Http\Exceptions\HttpResponseException;
class OrderRequest extends FormRequest
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
            'requested_order'                        => 'array|required',
            'requested_order.*.qty'                  => 'required|integer|min:0|max:5000',
            'requested_order.*.product_id'           => 'required|exists:products,id'
            
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
